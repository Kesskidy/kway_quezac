<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpeditionRegistrationRequest extends FormRequest
{
    /** Erreurs propres à ce formulaire : la page en contient plusieurs avec les mêmes noms de champs. */
    protected $errorBag = 'expedition';

    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => trim((string) $this->input('first_name')),
            'email' => mb_strtolower(trim((string) $this->input('email'))),
        ]);
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'consent' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Indique ton prénom.',
            'first_name.max' => 'Ton prénom doit faire 50 caractères maximum.',
            'email.required' => 'Indique ton adresse e-mail.',
            'email.email' => 'Cette adresse e-mail est invalide.',
            'email.max' => 'Cette adresse e-mail est trop longue.',
            'consent.accepted' => 'Coche la case pour que l’on puisse te contacter au sujet de l’expédition.',
        ];
    }

    /** Sans JS, en cas d'erreur : retour sur le formulaire (#expedition) et non en haut de la page. */
    protected function getRedirectUrl(): string
    {
        return url('/').'#expedition';
    }
}
