<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpeditionRegistrationRequest;
use App\Models\ExpeditionRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ExpeditionRegistrationController extends Controller
{
    /**
     * Inscription à l'Expédition RE·SOURCE. Une adresse déjà inscrite n'est pas une erreur : on le lui dit simplement.
     * Envoyée en JS (fetch, JSON) : pas de rechargement. Sans JS : redirection vers #expedition.
     */
    public function store(StoreExpeditionRegistrationRequest $request): JsonResponse|RedirectResponse
    {
        $registration = ExpeditionRegistration::firstOrCreate(
            ['email' => $request->validated('email')],
            ['first_name' => $request->validated('first_name'), 'consented_at' => now()],
        );

        $message = $registration->wasRecentlyCreated
            ? "Bienvenue dans l’expédition, {$registration->first_name} ! Le programme des étapes t’arrivera sur {$registration->email}."
            : 'Cette adresse est déjà inscrite à l’expédition : on se retrouve sur le GR70.';

        if ($request->expectsJson()) {
            return response()->json(['data' => ['message' => $message]], $registration->wasRecentlyCreated ? 201 : 200);
        }

        return redirect(url('/').'#expedition')->with('expedition_status', $message);
    }
}
