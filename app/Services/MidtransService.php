<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    protected function generateMidtransOrderId(Order $order): string
    {
        return $order->order_number.'-'.uniqid('', true);
    }

    public function createSnapToken(Order $order): string
    {
        if (empty($order->midtrans_order_id)) {
            $order->midtrans_order_id = $this->generateMidtransOrderId($order);
            $order->save(['midtrans_order_id' => true]);
        }

        return $this->createSnapTokenWithRetry($order);
    }

    protected function createSnapTokenWithRetry(Order $order, int $maxRetries = 2): string
    {
        $attempt = 0;
        while ($attempt < $maxRetries) {
            $attempt++;
            try {
                return $this->createSnapTokenOnce($order);
            } catch (\Exception $e) {
                if ($this->isDuplicateOrderIdError($e)) {
                    $order->midtrans_order_id = $this->generateMidtransOrderId($order);
                    $order->save(['midtrans_order_id' => true]);

                    continue;
                }
                throw $e;
            }
        }
        throw new \Exception('Failed to create Snap token after '.$maxRetries.' attempts');
    }

    protected function isDuplicateOrderIdError(\Exception $e): bool
    {
        $message = $e->getMessage();

        return str_contains($message, 'order_id has already been taken') ||
               str_contains($message, 'order_id sudah digunakan') ||
               str_contains($message, 'duplicate') ||
               str_contains($message, 'HTTP status code: 400');
    }

    protected function createSnapTokenOnce(Order $order): string
    {
        $user = $order->user ?? $order->userRelation ?? null;
        $userName = $user ? $user->name : $order->shipping_name;
        $userEmail = $user ? $user->email : 'customer@example.com';
        $params = [
            'transaction_details' => [
                'order_id' => $order->midtrans_order_id,
                'gross_amount' => (int) $order->total_amount,
            ],
            'customer_details' => [
                'first_name' => $userName,
                'email' => $userEmail,
                'phone' => $order->shipping_phone,
                'shipping_address' => [
                    'address' => $order->shipping_address,
                    'city' => 'Jakarta',
                    'postal_code' => '12345',
                    'country' => 'Indonesia',
                ],
            ],
            'item_details' => $order->orderItems->map(function ($item) {
                return [
                    'id' => $item->product_id ?? $item->id,
                    'price' => (int) $item->price,
                    'quantity' => (int) $item->quantity,
                    'name' => $item->product_name,
                ];
            })->toArray(),
        ];

        return Snap::getSnapToken($params);
    }
}
