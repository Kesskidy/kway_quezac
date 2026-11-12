<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNewsletterSubscriberRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class NewsletterSubscriberController extends Controller
{
    /**
     * Inscription à la newsletter. Une adresse déjà inscrite n'est pas une erreur : on le lui dit simplement.
     * Envoyée en JS (fetch, JSON) : pas de rechargement. Sans JS : redirection vers #newsletter.
     */
    public function store(StoreNewsletterSubscriberRequest $request): JsonResponse|RedirectResponse
    {
        $subscriber = NewsletterSubscriber::firstOrCreate(
            ['email' => $request->validated('email')],
            ['consented_at' => now()],
        );

        $message = $subscriber->wasRecentlyCreated
            ? 'C’est noté ! Tu recevras les nouvelles de RE·SOURCE sur '.$subscriber->email.'.'
            : 'Cette adresse est déjà inscrite à la newsletter : rien à faire de plus.';

        if ($request->expectsJson()) {
            return response()->json(['data' => ['message' => $message]], $subscriber->wasRecentlyCreated ? 201 : 200);
        }

        return redirect(url('/').'#newsletter')->with('newsletter_status', $message);
    }
}
