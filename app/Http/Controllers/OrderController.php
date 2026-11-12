<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * Inscription à la précommande de la veste.
     * Envoyée en JS (fetch, JSON) : pas de rechargement. Sans JS : redirection vers #product.
     */
    public function store(StoreOrderRequest $request): JsonResponse|RedirectResponse
    {
        $order = Order::create($request->validated());

        $message = "Merci {$order->first_name} ! Ton inscription à la précommande de la veste RE·SOURCE K-Way × Quézac (taille {$order->size}) est enregistrée. Aucun paiement n’est demandé pour le moment.";

        if ($request->expectsJson()) {
            return response()->json(['data' => ['message' => $message]], 201);
        }

        return redirect(url('/').'#product')->with('status', $message);
    }
}
