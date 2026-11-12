<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class StoreParticipationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('gameSession')->won;
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException('Cette partie n’est pas gagnante.');
    }

    // Lower case, so the same person cannot win two prizes with "Camille@…" and "camille@…".
    protected function prepareForValidation(): void
    {
        $this->merge(['email' => strtolower(trim((string) $this->input('email')))]);
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
            'consent.accepted' => 'Tu dois accepter le règlement pour participer.',
        ];
    }
}
