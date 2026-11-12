<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartGameRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge(['code' => strtoupper(trim((string) $this->input('code')))]);
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:32'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'Saisis ton code pour jouer.',
            'code.max' => 'Ce code est invalide.',
        ];
    }
}
