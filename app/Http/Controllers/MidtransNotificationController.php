<?php

namespace App\Http\Controllers;

use App\Events\OrderPaidEvent;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransNotificationController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        Log::info('Midtrans Notification Received', [
            'order_id' => $payload['order_id'] ?? null,
            'status' => $payload['transaction_status'] ?? null,
        ]);
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;
        if (! $orderId || ! $transactionStatus || ! $signatureKey) {
            Log::warning('Midtrans Notification: Missing required fields', $payload);

            return response()->json(['message' => 'Invalid payload'], 400);
        }
        $serverKey = config('midtrans.server_key');
        $expectedSignature = hash(
            'sha512',
            $orderId.$statusCode.$grossAmount.$serverKey
        );
        if ($signatureKey !== $expectedSignature) {
            Log::warning('Midtrans Notification: Invalid signature', [
                'order_id' => $orderId,
            ]);

            return response()->json(['message' => 'Invalid signature'], 403);
        }
        $order = Order::where('order_number', $orderId)->first();
        if (! $order) {
            Log::warning('Midtrans Notification: Order not found', ['order_id' => $orderId]);

            return response()->json(['message' => 'Order not found'], 404);
        }
        if (in_array($order->status, ['processing', 'shipped', 'delivered', 'cancelled'])) {
            Log::info('Midtrans Notification: Order already processed', ['order_id' => $orderId]);

            return response()->json(['message' => 'Order already processed'], 200);
        }
        $payment = Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'midtrans_transaction_id' => $transactionId,
                'payment_type' => $paymentType,
                'gross_amount' => $grossAmount,
                'raw_response' => json_encode($payload),
            ]
        );
        switch ($transactionStatus) {
            case 'capture':
                if ($fraudStatus === 'challenge') {
                    $this->handlePending($order, $payment, 'Menunggu review fraud');
                } else {
                    $this->handleSuccess($order, $payment);
                }
                break;
            case 'settlement':
                $this->handleSuccess($order, $payment);
                break;
            case 'pending':
                $this->handlePending($order, $payment, 'Menunggu pembayaran');
                break;
            case 'deny':
                $this->handleFailed($order, $payment, 'Pembayaran ditolak');
                break;
            case 'expire':
            case 'cancel':
                if ($order->status !== 'cancelled') {
                    $this->handleFailed($order, $payment, 'Pembayaran expired/dibatalkan');
                }
                break;
            case 'refund':
            case 'partial_refund':
                $this->handleRefund($order, $payment);
                break;
            default:
                Log::info('Midtrans Notification: Unknown status', [
                    'order_id' => $orderId,
                    'status' => $transactionStatus,
                ]);
        }

        return response()->json(['message' => 'Notification processed'], 200);
    }

    protected function handleSuccess(Order $order, ?Payment $payment): void
    {
        Log::info("Payment SUCCESS for Order: {$order->order_number}");
        $order->update([
            'status' => 'processing',
        ]);
        if ($payment) {
            $payment->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);
        }
        event(new OrderPaidEvent($order));
    }

    protected function handlePending(Order $order, ?Payment $payment, string $message = ''): void
    {
        Log::info("Payment PENDING for Order: {$order->order_number}", ['message' => $message]);
        if ($payment) {
            $payment->update(['status' => 'pending']);
        }
    }

    protected function handleFailed(Order $order, ?Payment $payment, string $reason = ''): void
    {
        Log::info("Payment FAILED for Order: {$order->order_number}", ['reason' => $reason]);
        $order->update([
            'status' => 'cancelled',
        ]);
        if ($payment) {
            $payment->update(['status' => 'failed']);
        }
        foreach ($order->orderItems as $item) {
            $item->product?->increment('stock', $item->quantity);
        }
    }

    protected function handleRefund(Order $order, ?Payment $payment): void
    {
        Log::info("Payment REFUNDED for Order: {$order->order_number}");
        if ($payment) {
            $payment->update(['status' => 'refunded']);
        }
    }
}
