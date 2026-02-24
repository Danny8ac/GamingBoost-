<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Crear pedido
    public function store(Request $request)
    {
        $request->validate([
            'provider' => 'required|in:stripe,mercadopago,paypal',
            'items' => 'required|array|min:1',
            'items.*.boost_id' => 'required|integer',
            'items.*.qty' => 'required|integer|min:1|max:100',
        ]);

        return DB::transaction(function () use ($request) {

            $order = Order::create([
                'user_id' => $request->user()->id,
                'provider' => $request->provider,
                'status' => 'pending_payment',
                'currency' => 'MXN',
                'total_amount' => 0,
                'provider_ref' => null,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                // Precio simulado (centavos)
                $unitPrice = 1000; // $10.00 MXN
                $subtotal = $unitPrice * (int)$item['qty'];
                $total += $subtotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'boost_id' => (int)$item['boost_id'],
                    'qty' => (int)$item['qty'],
                    'unit_price' => $unitPrice,
                ]);
            }

            $order->update(['total_amount' => $total]);

            return response()->json([
                'order_id' => $order->id,
                'status' => $order->status,
                'provider' => $order->provider,
                'total_amount' => $order->total_amount,
            ], 201);
        });
    }

    // Mis pedidos
    public function index(Request $request)
    {
        return $request->user()
            ->orders()
            ->with('items')
            ->latest()
            ->get();
    }

    // Ver pedido
    public function show(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        return $order->load('items');
    }

    // Genera checkout_url simulado
    public function pay(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(403);
        }

        if ($order->status !== 'pending_payment') {
            return response()->json(['error' => 'Order not pending'], 400);
        }

        // from se agrega en la web (catalog/orders) como ?from=web
        $checkoutUrl = url("/checkout/{$order->id}?provider={$order->provider}");

        return response()->json([
            'checkout_url' => $checkoutUrl
        ]);
    }

    // Vista HTML simulada (pasarela)
    public function checkout(Request $request, Order $order)
    {
        $from = $request->query('from', null); // "web" o null
        return view('checkout', compact('order', 'from'));
    }

    // Confirmar pago (simulado)
    public function confirm(Request $request, Order $order)
    {
        if ($order->status === 'pending_payment') {

            DB::transaction(function () use ($order) {

                $order->load('items');
                $order->update(['status' => 'paid']);

                foreach ($order->items as $item) {

                    $purchase = Purchase::firstOrNew([
                        'user_id' => $order->user_id,
                        'boost_id' => $item->boost_id
                    ]);

                    $purchase->qty = ($purchase->qty ?? 0) + $item->qty;
                    $purchase->save();
                }
            });
        }

        $deepLink = "gamingboost://payment-result?order_id={$order->id}&status=paid";
        $from = $request->query('from', null);

        return view('payment_result', [
            'orderId' => $order->id,
            'status' => 'paid',
            'deepLink' => $deepLink,
            'from' => $from,
        ]);
    }

    // Cancelar pago (simulado)
    public function cancel(Request $request, Order $order)
    {
        if ($order->status === 'pending_payment') {
            $order->update(['status' => 'cancelled']);
        }

        $deepLink = "gamingboost://payment-result?order_id={$order->id}&status=cancelled";
        $from = $request->query('from', null);

        return view('payment_result', [
            'orderId' => $order->id,
            'status' => 'cancelled',
            'deepLink' => $deepLink,
            'from' => $from,
        ]);
    }
}