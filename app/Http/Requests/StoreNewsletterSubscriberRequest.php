<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreNewsletterSubscriberRequest extends FormRequest
{
    /** Erreurs propres à ce formulaire : la page en contient plusieurs avec les mêmes noms de champs. */
    protected $errorBag = 'newsletter';

    protected function prepareForValidation(): void
    {
        $this->merge(['email' => mb_strtolower(trim((string) $this->input('email')))]);
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255'],
            'consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Indique ton adresse e-mail.',
            'email.email' => 'Cette adresse e-mail est invalide.',
            'email.max' => 'Cette adresse e-mail est trop longue.',
            'consent.accepted' => 'Coche la case pour accepter de recevoir la newsletter.',
        ];
    }

    /** Sans JS, en cas d'erreur : retour sur le formulaire (#newsletter) et non en haut de la page. */
    protected function getRedirectUrl(): string
    {
        return url('/').'#newsletter';
    }
}
