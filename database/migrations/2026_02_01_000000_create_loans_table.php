<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tabel untuk peminjaman buku di perpustakaan
     */
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained('products')->onDelete('cascade');
            $table->date('loan_date');
            $table->date('due_date'); // Tanggal jatuh tempo
            $table->date('return_date')->nullable(); // Tanggal pengembalian aktual
            $table->enum('status', ['pending', 'approved', 'borrowed', 'returned', 'overdue', 'cancelled'])
                  ->default('pending');
            $table->text('notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('returned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Index untuk query yang sering digunakan
            $table->index(['status', 'due_date']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};

