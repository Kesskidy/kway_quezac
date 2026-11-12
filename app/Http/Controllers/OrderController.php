<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * POST /orders — fictitious pre-order, no payment (regle.md, sections 3 and 8).
     */
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $order = Order::create($request->validated());

        return redirect()->to(url('/').'#product')->with(
            'status',
            "Merci {$order->first_name} ! Ta précommande de la {$order->product->name} est enregistrée. C’est une précommande fictive : aucun paiement n’est demandé.",
        );
    }
}
