<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('orders')
            ->where('shipping_cost', 0)
            ->orWhereNull('shipping_cost')
            ->update(['shipping_cost' => 10000]);
    }

    public function down(): void {}
};
