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
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }
        $order->load(['orderItems.product', 'user']);
        $snapToken = null;
        if (in_array($order->payment_status, ['pending', 'unpaid'])) {
            try {
                $midtransService = app(\App\Services\MidtransService::class);
                $snapToken = $midtransService->createSnapToken($order);
                $order->update(['snap_token' => $snapToken]);
            } catch (\Exception $e) 
                logger()->error('Failed to generate snap token', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        return view('orders.show', compact('order', 'snapToken'));
    }
}