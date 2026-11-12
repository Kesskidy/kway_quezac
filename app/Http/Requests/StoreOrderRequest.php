<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Fictitious pre-order form (regle.md, section 8).
 */
class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->input('email'))) {
            $this->merge(['email' => mb_strtolower(trim($this->input('email')))]);
        }
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'first_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'size' => ['nullable', Rule::in(Order::SIZES)],
        ];
    }

    /**
     * On error, go back to the product section with the errors and the typed values.
     */
    protected function getRedirectUrl(): string
    {
        return url('/').'#product';
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'product_id.required' => 'Choisis un produit.',
            'product_id.exists' => 'Ce produit n’existe pas.',
            'first_name.required' => 'Indique ton prénom.',
            'first_name.string' => 'Ton prénom est invalide.',
            'first_name.max' => 'Ton prénom doit faire 50 caractères maximum.',
            'email.required' => 'Indique ton adresse e-mail.',
            'email.email' => 'Cette adresse e-mail est invalide.',
            'email.max' => 'Cette adresse e-mail est trop longue.',
            'size.in' => 'Choisis une taille entre XS et XXL.',
        ];
    }
}
