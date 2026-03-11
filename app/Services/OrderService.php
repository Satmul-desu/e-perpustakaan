<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected MidtransService $midtransService;

    protected const SHIPPING_COST = 10000;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    public function createOrder(User $user, array $shippingData): Order
    {
        $cart = $user->cart;
        if (! $cart || $cart->items->isEmpty()) {
            throw new \Exception('Keranjang belanja kosong.');
        }

        return DB::transaction(function () use ($user, $cart, $shippingData) {
            $totalAmount = 0;
            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception("Stok produk {$item->product->name} tidak mencukupi.");
                }
                $totalAmount += $item->product->price * $item->quantity;
            }
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => $this->generateOrderNumber(),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'shipping_name' => $shippingData['name'],
                'shipping_address' => $shippingData['address'],
                'shipping_phone' => $shippingData['phone'],
                'total_amount' => $totalAmount + self::SHIPPING_COST,
                'shipping_cost' => self::SHIPPING_COST,
            ]);
            foreach ($cart->items as $item) {
                $order->orderItems()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->product->price * $item->quantity,
                ]);
                $item->product->decrement('stock', $item->quantity);
            }
            $cart->items()->delete();

            return $order;
        });
    }

    private function generateOrderNumber(): string
    {
        $date = now()->format('Ymd');
        $time = now()->format('His');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        $orderNumber = "ORD-{$date}-{$time}-{$random}";
        if (Order::where('order_number', $orderNumber)->exists()) {
            $orderNumber = "ORD-{$date}-{$time}-".strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        }

        return $orderNumber;
    }
}
