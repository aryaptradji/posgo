<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Teh Botol Sosro',
            'slug' => 'teh-botol-sosro',
            'image' => 'teh-botol.png',
            'stock' => 0,
            'pcs' => 50,
            'price' => 60000
        ]);

        Product::create([
            'name' => 'Panther',
            'slug' => 'panther',
            'image' => 'panther.png',
            'stock' => 0,
            'pcs' => 30,
            'price' => 70000
        ]);

        Product::create([
            'name' => 'Milku',
            'slug' => 'milku',
            'image' => 'milku.png',
            'stock' => 1,
            'pcs' => 45,
            'price' => 20000
        ]);

        Product::create([
            'name' => 'Floridina',
            'slug' => 'floridina',
            'image' => 'floridina.png',
            'stock' => 4,
            'pcs' => 25,
            'price' => 72000
        ]);

        Product::create([
            'name' => 'Teh Kotak',
            'slug' => 'teh-kotak',
            'image' => 'teh-kotak.png',
            'stock' => 46,
            'pcs' => 20,
            'price' => 25000
        ]);
    }
}
