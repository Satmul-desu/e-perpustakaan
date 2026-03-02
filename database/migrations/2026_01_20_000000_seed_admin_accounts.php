<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Hapus admin lama jika ada
        DB::table('users')->whereIn('email', [
            'AdminTB@TokoBuku.com',
            'adminTb@TokoBuku.com'
        ])->delete();

        // Insert Admin 1
        DB::table('users')->insert([
            'name' => 'Admin TB',
            'email' => 'AdminTB@TokoBuku.com',
            'password' => Hash::make('AdminTb1'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Insert Admin 2
        DB::table('users')->insert([
            'name' => 'Admin Toko Buku',
            'email' => 'adminTb@TokoBuku.com',
            'password' => Hash::make('Admintb2'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')->whereIn('email', [
            'AdminTB@TokoBuku.com',
            'adminTb@TokoBuku.com'
        ])->delete();
    }
};

