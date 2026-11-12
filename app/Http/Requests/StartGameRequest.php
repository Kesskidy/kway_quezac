<?php

namespace App\Http\Requests;

use App\Models\PromoCode;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

/**
 * regle.md, section 6. Whether the code exists is checked in the transaction
 * (GameSessionController::store), under lockForUpdate().
 */
class StartGameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('code'))) {
            $this->merge(['code' => PromoCode::normalize($this->input('code'))]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:32'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'code.required' => 'Saisis ton code pour jouer.',
            'code.string' => 'Ce code est invalide.',
            'code.max' => 'Ce code est invalide.',
        ];
    }
}
