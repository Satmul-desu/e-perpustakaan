<?php
// app/Services/OrderService.php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected MidtransService $midtransService;
    protected const SHIPPING_COST = 10000; // Ongkir tetap Rp 10.000

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Membuat Order baru dari Keranjang belanja.
     *
     * ALUR PROSES (TRANSACTION):
     * 1. Hitung total & Validasi Stok terakhir
     * 2. Buat Record Order (Header)
     * 3. Pindahkan Cart Items ke Order Items (Detail)
     * 4. Kurangi Stok Produk (Atomic Decrement)
     * 5. Generate Snap Token untuk pembayaran
     * 6. Hapus Keranjang
     */
    public function createOrder(User $user, array $shippingData): Order
    {
        // 1. Ambil Keranjang User
        $cart = $user->cart;

        if (! $cart || $cart->items->isEmpty()) {
            throw new \Exception("Keranjang belanja kosong.");
        }

        // ==================== DATABASE TRANSACTION START ====================
        return DB::transaction(function () use ($user, $cart, $shippingData) {

            // A. VALIDASI STOK & HITUNG TOTAL
            $totalAmount = 0;
            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception("Stok produk {$item->product->name} tidak mencukupi.");
                }
                $totalAmount += $item->product->price * $item->quantity;
            }

            // B. BUAT HEADER ORDER
            $order = Order::create([
                'user_id'          => $user->id,
                'order_number'     => $this->generateOrderNumber(),
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
                'shipping_name'    => $shippingData['name'],
                'shipping_address' => $shippingData['address'],
                'shipping_phone'   => $shippingData['phone'],
                'total_amount'     => $totalAmount + self::SHIPPING_COST,
                'shipping_cost'    => self::SHIPPING_COST,
            ]);

            // C. PINDAHKAN ITEMS
            foreach ($cart->items as $item) {
                $order->orderItems()->create([
                    'product_id'   => $item->product_id,
                    'product_name' => $item->product->name,
                    'price'        => $item->product->price,
                    'quantity'     => $item->quantity,
                    'subtotal'     => $item->product->price * $item->quantity,
                ]);

                $item->product->decrement('stock', $item->quantity);
            }

            // D. SNAP TOKEN DIHANDLE OLEH show.blade.php (on-the-fly)
            // Token dibuat saat user klik tombol Bayar, bukan saat checkout
            // Ini memastikan token selalu fresh dan valid

            // E. BERSIHKAN KERANJANG
            $cart->items()->delete();

            return $order;
        });
    }

    /**
     * Generate unique order number in format ORD-YYYYMMDD-XXXXXXXX
     * Uses timestamp + random suffix for guaranteed uniqueness
     */
    private function generateOrderNumber(): string
    {
        // Format: ORD-YYYYMMDD-HHMMSS-XXXX (XXXX = random 4 chars)
        $date = now()->format('Ymd');
        $time = now()->format('His');
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
        $orderNumber = "ORD-{$date}-{$time}-{$random}";

        // Double-check uniqueness (extremely unlikely to collide)
        if (Order::where('order_number', $orderNumber)->exists()) {
            $orderNumber = "ORD-{$date}-{$time}-" . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));
        }

        return $orderNumber;
    }
}
