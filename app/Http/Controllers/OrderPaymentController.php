<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class OrderPaymentController extends Controller
{
    public function snapToken(string $orderNumber, MidtransService $midtrans): JsonResponse
    {
        try {
            $order = Order::where('order_number', $orderNumber)
                ->with(['orderItems', 'user'])
                ->firstOrFail();

            // Kalau sudah punya snap token → pakai ulang
            if ($order->snap_token) {
                return response()->json([
                    'snap_token' => $order->snap_token,
                ]);
            }

            $snapToken = $midtrans->createSnapToken($order);

            $order->update([
                'snap_token' => $snapToken,
            ]);

            return response()->json([
                'snap_token' => $snapToken,
            ]);

        } catch (\Throwable $e) {

            Log::error('MIDTRANS SNAP ERROR', [
                'order' => $orderNumber,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Gagal membuat snap token',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
