<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpeditionRegistration;
use App\Models\NewsletterSubscriber;
use App\Models\Participation;
use Illuminate\Contracts\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ParticipationController extends Controller
{
    public function index(): View
    {
        return view('admin.participations', [
            'participations' => Participation::with('gameSession.promoCode')->latest()->get(),
            'subscribers' => NewsletterSubscriber::latest()->get(),
            'expeditionRegistrations' => ExpeditionRegistration::latest()->get(),
        ]);
    }

    public function export(): StreamedResponse
    {
        return $this->csv('participations.csv', ['Prénom', 'E-mail', 'Code', 'Date', 'Lot'], function () {
            foreach (Participation::with('gameSession.promoCode')->get() as $participation) {
                yield [
                    $participation->first_name,
                    $participation->email,
                    $participation->gameSession->promoCode->code,
                    $participation->created_at,
                    $participation->prize_rank,
                ];
            }
        });
    }

    public function exportNewsletter(): StreamedResponse
    {
        return $this->csv('newsletter.csv', ['E-mail', 'Date d’inscription'], function () {
            foreach (NewsletterSubscriber::oldest()->get() as $subscriber) {
                yield [$subscriber->email, $subscriber->created_at];
            }
        });
    }

    public function exportExpedition(): StreamedResponse
    {
        return $this->csv('expedition.csv', ['Prénom', 'E-mail', 'Date d’inscription'], function () {
            foreach (ExpeditionRegistration::oldest()->get() as $registration) {
                yield [$registration->first_name, $registration->email, $registration->created_at];
            }
        });
    }

    /** Fichier CSV (séparateur « ; ») téléchargé directement, lisible dans Excel. */
    private function csv(string $filename, array $header, callable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($header, $rows) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF"); // UTF-8 marker, so Excel shows the accents correctly

            fputcsv($file, $header, ';');
            foreach ($rows() as $row) {
                fputcsv($file, $row, ';');
            }

            fclose($file);
        }, $filename);
    }
}
