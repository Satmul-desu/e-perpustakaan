<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
   public function index()
{
    $orders = auth()->user()
        ->orders()
        ->with(['orderItems', 'payment'])
        ->latest()
        ->paginate(10);

    return view('orders.index', compact('orders'));
}
    public function show(Order $order): View
    {
        // Pastikan user hanya bisa lihat order miliknya sendiri
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load(['orderItems.product', 'user']);

        // Generate snap token jika order masih pending dan belum dibayar
        // SELALU generate token baru agar tidak expired
        $snapToken = null;
        if (in_array($order->payment_status, ['pending', 'unpaid'])) {
            try {
                $midtransService = app(\App\Services\MidtransService::class);
                // Generate fresh token setiap kali halaman dimuat
                $snapToken = $midtransService->createSnapToken($order);
                
                // Update token di database
                $order->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) {
                // Log error tapi jangan hentikan halaman
                logger()->error('Failed to generate snap token', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return view('orders.show', compact('order', 'snapToken'));
    }
}
