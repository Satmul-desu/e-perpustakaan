<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->integer('warning_level')->default(0)->after('status')->comment('0: aman, 1: peringatan 1, 2: peringatan terakhir, 3: siap denda');
            $table->boolean('is_fine_active')->default(false)->after('fine_status')->comment('apakah admin sudah approve denda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['warning_level', 'is_fine_active']);
        });
    }
};
