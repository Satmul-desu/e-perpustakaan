<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\Product;
use App\Models\OrderItem;
$productsToDelete = [
    'Kisah Teladan Anak',
    'Laskar Pelangi',
    'The Hobbit',
    'Sang Penulis',
    'Sherlock Holmes',
    'Naruto Volume 1'
];
echo "=== MENGHAPUS PRODUK LAMA ===\n\n";
foreach ($productsToDelete as $name) {
    $product = Product::where('name', $name)->first();
    if ($product) {
        echo "Menghapus: $name (ID: $product->id)\n";
        OrderItem::where('product_id', $product->id)->delete();
        $product->images()->delete();
        $product->delete();
    } else {
        echo "Tidak ditemukan: $name\n";
    }
}
echo "\nSelesai!\n";