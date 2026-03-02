<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update shipping_cost = 10000 untuk order yang belum memiliki ongkir
        DB::table('orders')
            ->where('shipping_cost', 0)
            ->orWhereNull('shipping_cost')
            ->update(['shipping_cost' => 10000]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak perlu rollback karena ini hanya update data
    }
};

