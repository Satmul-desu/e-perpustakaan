<?php
use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
return new class extends Migration
{
    public function up(): void
    {
        Order::chunkById(100, function ($orders) {
            foreach ($orders as $order) {
                $itemsSubtotal = $order->orderItems->sum(function ($item) {
                    return $item->price * $item->quantity;
                });
                $newTotal = $itemsSubtotal + $order->shipping_cost;
                if ($order->total_amount != $newTotal) {
                    $order->update(['total_amount' => $newTotal]);
                }
            }
        });
    }
    public function down(): void
    {
    }
};