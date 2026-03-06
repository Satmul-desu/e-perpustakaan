<?php
namespace App\Console\Commands;
use App\Models\Product;
use Illuminate\Console\Command;
class DeleteProductsWithoutImages extends Command
{
    protected $signature = 'products:delete-without-images';
    protected $description = 'Hapus produk yang tidak memiliki gambar';
    public function handle(): int
    {
        $productsWithoutImages = Product::whereDoesntHave('images', function ($query) {
            $query->where('is_primary', true);
        })->get();
        if ($productsWithoutImages->isEmpty()) {
            $this->info('Tidak ada produk tanpa gambar.');
            return Command::SUCCESS;
        }
        $this->warn('Produk yang akan dihapus (tanpa gambar):');
        foreach ($productsWithoutImages as $product) {
            $this->line("- ID: {$product->id} | {$product->name}");
        }
        $count = $productsWithoutImages->count();
        if ($this->confirm("Apakah Anda yakin ingin menghapus {$count} produk ini?")) {
            $deleted = Product::whereDoesntHave('images', function ($query) {
                $query->where('is_primary', true);
            })->delete();
            $this->info("Berhasil menghapus {$deleted} produk tanpa gambar.");
            return Command::SUCCESS;
        }
        $this->info('Operasi dibatalkan.');
        return Command::SUCCESS;
    }
}