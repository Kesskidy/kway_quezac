<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participation;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Participations administration (regle.md, section 3). Protected by auth.basic in routes/web.php.
 */
class ParticipationController extends Controller
{
    private const PER_PAGE = 50;

    /**
     * GET /admin/participations
     */
    public function index(): View
    {
        return view('admin.participations', [
            'participations' => Participation::with('gameSession.promoCode')
                ->orderByRaw('prize_rank IS NULL') // winners first, by rank
                ->orderBy('prize_rank')
                ->latest('id')
                ->paginate(self::PER_PAGE),
            'total' => Participation::count(),
            'people' => Participation::distinct()->count('email'),
            'drawnAt' => Participation::max('drawn_at'),
            'prizes' => config('game.prizes'),
        ]);
    }

    /**
     * GET /admin/participations/export — CSV for Excel (semicolons, UTF-8 with BOM).
     */
    public function export(): StreamedResponse
    {
        $prizes = config('game.prizes');
        $filename = 'participations-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($prizes) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, ['ID', 'Prénom', 'E-mail', 'Code', 'Participation le', 'Consentement le', 'Rang du lot', 'Lot', 'Tiré le'], ';');

            // Exported by ID, in chunks, so a large table never loads at once.
            Participation::with('gameSession.promoCode')->lazyById(500)->each(function (Participation $p) use ($out, $prizes) {
                fputcsv($out, array_map($this->protectFromFormulas(...), [
                    $p->id,
                    $p->first_name,
                    $p->email,
                    $p->gameSession?->promoCode?->code,
                    $p->created_at?->format('d/m/Y H:i'),
                    $p->consented_at?->format('d/m/Y H:i'),
                    $p->prize_rank,
                    $p->prize_rank ? ($prizes[$p->prize_rank] ?? '') : '',
                    $p->drawn_at?->format('d/m/Y H:i'),
                ]), ';');
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * Player-typed values (first name) must not run as formulas in Excel (CSV injection).
     */
    private function protectFromFormulas(mixed $value): mixed
    {
        if (is_string($value) && $value !== '' && in_array($value[0], ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'".$value;
        }

        return $value;
    }
}
