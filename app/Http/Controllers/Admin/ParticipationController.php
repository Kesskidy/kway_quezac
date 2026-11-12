<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participation;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ParticipationController extends Controller
{
    public function index(): View
    {
        return view('admin.participations', [
            'participations' => Participation::with('gameSession.promoCode')->latest()->get(),
        ]);
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // UTF-8 marker, so Excel shows the accents correctly

            fputcsv($file, ['Prénom', 'E-mail', 'Code', 'Date', 'Lot'], ';');

            foreach (Participation::with('gameSession.promoCode')->get() as $participation) {
                fputcsv($file, [
                    $participation->first_name,
                    $participation->email,
                    $participation->gameSession->promoCode->code,
                    $participation->created_at,
                    $participation->prize_rank,
                ], ';');
            }

            fclose($file);
        }, 'participations.csv');
    }
}
