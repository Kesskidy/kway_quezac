<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'first_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'size' => ['nullable', 'in:XS,S,M,L,XL,XXL'],
        ];
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'Choisis un produit.',
            'product_id.exists' => 'Ce produit n’existe pas.',
            'first_name.required' => 'Indique ton prénom.',
            'first_name.max' => 'Ton prénom doit faire 50 caractères maximum.',
            'email.required' => 'Indique ton adresse e-mail.',
            'email.email' => 'Cette adresse e-mail est invalide.',
            'email.max' => 'Cette adresse e-mail est trop longue.',
            'size.in' => 'Choisis une taille entre XS et XXL.',
        ];
    }
}
