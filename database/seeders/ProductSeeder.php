<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $images = ['teh-botol.png', 'panther.png', 'milku.png', 'floridina.png', 'teh-kotak.png'];

        foreach ($images as $image) {
            Storage::disk('public')->put('products/' . $image, file_get_contents(storage_path('app/public/products/' . $image)));
        }

        Product::create([
            'name' => 'Teh Botol Sosro',
            'slug' => 'teh-botol-sosro',
            'image' => 'products/teh-botol.png',
            'stock' => 0,
            'pcs' => 50,
            'price' => 60000,
        ]);

        Product::create([
            'name' => 'Panther',
            'slug' => 'panther',
            'image' => 'products/panther.png',
            'stock' => 0,
            'pcs' => 30,
            'price' => 70000,
        ]);

        Product::create([
            'name' => 'Milku',
            'slug' => 'milku',
            'image' => 'products/milku.png',
            'stock' => 1,
            'pcs' => 45,
            'price' => 20000,
        ]);

        Product::create([
            'name' => 'Floridina',
            'slug' => 'floridina',
            'image' => 'products/floridina.png',
            'stock' => 4,
            'pcs' => 25,
            'price' => 72000,
        ]);

        Product::create([
            'name' => 'Teh Kotak',
            'slug' => 'teh-kotak',
            'image' => 'products/teh-kotak.png',
            'stock' => 46,
            'pcs' => 20,
            'price' => 25000,
        ]);
    }
}
