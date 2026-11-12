<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $order = Order::create($request->validated());

        return redirect(url('/').'#product')->with(
            'status',
            "Merci {$order->first_name} ! Ta précommande de la {$order->product->name} est enregistrée. C’est une précommande fictive : aucun paiement n’est demandé.",
        );
    }
}
