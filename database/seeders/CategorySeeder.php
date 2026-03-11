<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Romance',
                'slug' => 'romance',
                'description' => 'Novel roman picisan yang penuh kisah cinta dan emosi',
                'image' => 'galih.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Drama',
                'slug' => 'drama',
                'description' => 'Kisah dramatis tentang kehidupan dan konflik manusia',
                'image' => 'jarot.png',
                'is_active' => true,
            ],
            [
                'name' => 'Fiksi Remaja',
                'slug' => 'fiksi-remaja',
                'description' => 'Novel fiksi yang ditulis untuk dan tentang remaja',
                'image' => 'galih.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Fantasi',
                'slug' => 'fantasi',
                'description' => 'Dunia magis dan petualangan penuh imajinasi',
                'image' => 'jarot.png',
                'is_active' => true,
            ],
            [
                'name' => 'Horor',
                'slug' => 'horor',
                'description' => 'Cerita menegangkan penuh kengerian dan suspense',
                'image' => 'galih.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Politik',
                'slug' => 'politik',
                'description' => 'Buku tentang dinamika politik dan kekuasaan',
                'image' => 'jarot.png',
                'is_active' => true,
            ],
            [
                'name' => 'Agama',
                'slug' => 'agama',
                'description' => 'Buku tentang keimanan dan kehidupan spiritual',
                'image' => 'galih.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Inspiratif',
                'slug' => 'inspiratif',
                'description' => 'Kisah nyata yang menginspirasi dan memotivasi',
                'image' => 'jarot.png',
                'is_active' => true,
            ],
        ];
        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }
        $this->command->info('Berhasil membuat '.count($categories).' kategori sample.');
    }
}
