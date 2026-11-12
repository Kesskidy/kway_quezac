<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    /** Erreurs propres à ce formulaire : la page en contient plusieurs avec les mêmes noms de champs. */
    protected $errorBag = 'order';

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255'],
            'size' => ['required', Rule::in(Order::SIZES)],
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
            'size.required' => 'Choisis une taille.',
            'size.in' => 'Choisis une taille entre XS et XXL.',
        ];
    }

    /** Sans JS, en cas d'erreur : retour sur le formulaire (#product) et non en haut de la page. */
    protected function getRedirectUrl(): string
    {
        return url('/').'#product';
    }
}
