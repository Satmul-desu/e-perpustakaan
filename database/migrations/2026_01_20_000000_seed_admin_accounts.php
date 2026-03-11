<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->whereIn('email', [
            'AdminTB@TokoBuku.com',
            'adminTb@TokoBuku.com',
        ])->delete();
        DB::table('users')->insert([
            'name' => 'Admin TB',
            'email' => 'AdminTB@TokoBuku.com',
            'password' => Hash::make('AdminTb1'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([
            'name' => 'Admin Toko Buku',
            'email' => 'adminTb@TokoBuku.com',
            'password' => Hash::make('Admintb2'),
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('users')->whereIn('email', [
            'AdminTB@TokoBuku.com',
            'adminTb@TokoBuku.com',
        ])->delete();
    }
};
