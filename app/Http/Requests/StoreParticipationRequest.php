<?php

namespace App\Http\Requests;

use App\Models\GameSession;
use App\Models\Participation;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * regle.md, sections 4.2 and 6.
 */
class StoreParticipationRequest extends FormRequest
{
    /**
     * Only a won game can register a participation (403 otherwise, before field validation).
     */
    public function authorize(): bool
    {
        $gameSession = $this->route('gameSession');

        return $gameSession instanceof GameSession && $gameSession->won;
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException('Cette partie n’est pas gagnante.');
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('email'))) {
            $this->merge(['email' => Participation::normalizeEmail($this->input('email'))]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'consent' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'Indique ton prénom.',
            'first_name.string' => 'Ton prénom est invalide.',
            'first_name.max' => 'Ton prénom doit faire 50 caractères maximum.',
            'email.required' => 'Indique ton adresse e-mail.',
            'email.email' => 'Cette adresse e-mail est invalide.',
            'email.max' => 'Cette adresse e-mail est trop longue.',
            'consent.accepted' => 'Tu dois accepter le règlement pour participer.',
        ];
    }
}
