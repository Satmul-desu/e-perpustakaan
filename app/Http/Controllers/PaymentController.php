<?php
namespace App\Http\Controllers;
use App\Events\OrderPaidEvent;
use App\Models\Order;
use App\Models\Payment;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
class PaymentController extends Controller
{
    public function getSnapToken(string $orderNumber, MidtransService $midtrans): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('user')
            ->firstOrFail();
        if ($order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if ($order->payment_status === 'paid') {
            return response()->json(['error' => 'Sudah dibayar'], 400);
        }
        $snapToken = $midtrans->createSnapToken($order);
        $order->update(['snap_token' => $snapToken]);
        return response()->json(['token' => $snapToken]);
    }
    public function success(Order $order): View
    {
        $this->updatePaymentStatus($order, 'success');
        return view('orders.success', compact('order'));
    }
    public function pending(Order $order): View
    {
        $this->updatePaymentStatus($order, 'pending');
        return view('orders.pending', compact('order'));
    }
    public function result(Order $order, string $status = 'unknown'): View
    {
        if ($status === 'success') {
            $this->updatePaymentStatus($order, 'success');
        } elseif ($status === 'pending') {
            $this->updatePaymentStatus($order, 'pending');
        } elseif ($status === 'failed') {
            $this->updatePaymentStatus($order, 'failed');
        }
        return view('orders.result', compact('order', 'status'));
    }
    protected function updatePaymentStatus(Order $order, string $paymentStatus): void
    {
        if ($order->payment_status === 'paid' && $paymentStatus !== 'failed') {
            return;
        }
        Log::info("Updating payment status for order: {$order->order_number}", [
            'payment_status' => $paymentStatus,
            'user_id' => auth()->id(),
        ]);
        if ($paymentStatus === 'success') {
            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid',
            ]);
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'status' => 'success',
                    'paid_at' => now(),
                    'gross_amount' => $order->total_amount,
                ]
            );
            event(new OrderPaidEvent($order));
        } elseif ($paymentStatus === 'pending') {
            $order->update(['payment_status' => 'pending']);
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                ['status' => 'pending']
            );
        } elseif ($paymentStatus === 'failed') {
            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'unpaid',
            ]);
            Payment::updateOrCreate(
                ['order_id' => $order->id],
                ['status' => 'failed']
            );
            foreach ($order->orderItems as $item) {
                $item->product?->increment('stock', $item->quantity);
            }
        }
    }
    public function checkStatus(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        return response()->json([
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
        ]);
    }
}