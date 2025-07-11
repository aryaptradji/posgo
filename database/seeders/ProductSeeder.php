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
        $files = Storage::disk('local')->files('public/products');

        foreach ($files as $file) {
            $filename = basename($file);
            Storage::disk('public')->put("products/$filename", file_get_contents(storage_path("app/public/products/$filename")));
        }

        Product::create([
            'name' => 'Aqua 220 ml',
            'slug' => 'aqua-220-ml',
            'image' => 'products/aqua-220ml.png',
            'stock' => 12,
            'pcs' => 48,
            'price' => 35000,
        ]);

        Product::create([
            'name' => 'Aqua 330 ml',
            'slug' => 'aqua-330-ml',
            'image' => 'products/aqua-330ml.png',
            'stock' => 8,
            'pcs' => 24,
            'price' => 41000,
        ]);

        Product::create([
            'name' => 'Aqua 600 ml',
            'slug' => 'aqua-600-ml',
            'image' => 'products/aqua-600ml.png',
            'stock' => 10,
            'pcs' => 24,
            'price' => 49000,
        ]);

        Product::create([
            'name' => 'Aqua 1500 ml',
            'slug' => 'aqua-1500-ml',
            'image' => 'products/aqua-1500ml.png',
            'stock' => 20,
            'pcs' => 12,
            'price' => 55000,
        ]);

        Product::create([
            'name' => 'Le Minerale 330 ml',
            'slug' => 'le-minerale-330-ml',
            'image' => 'products/le-minerale-330ml.png',
            'stock' => 0,
            'pcs' => 24,
            'price' => 40000,
        ]);

        Product::create([
            'name' => 'Le Minerale 600 ml',
            'slug' => 'le-minerale-600-ml',
            'image' => 'products/le-minerale-600ml.png',
            'stock' => 5,
            'pcs' => 24,
            'price' => 49000,
        ]);

        Product::create([
            'name' => 'Le Minerale 1500 ml',
            'slug' => 'le-minerale-1500-ml',
            'image' => 'products/le-minerale-1500ml.png',
            'stock' => 6,
            'pcs' => 12,
            'price' => 53000,
        ]);

        Product::create([
            'name' => 'Vit 220 ml',
            'slug' => 'vit-220-ml',
            'image' => 'products/vit-220ml.png',
            'stock' => 5,
            'pcs' => 48,
            'price' => 20000,
        ]);

        Product::create([
            'name' => 'Vit 330 ml',
            'slug' => 'vit-330-ml',
            'image' => 'products/vit-330ml.png',
            'stock' => 3,
            'pcs' => 24,
            'price' => 31000,
        ]);

        Product::create([
            'name' => 'Vit 600 ml',
            'slug' => 'vit-600-ml',
            'image' => 'products/vit-600ml.png',
            'stock' => 7,
            'pcs' => 24,
            'price' => 32000,
        ]);

        Product::create([
            'name' => 'Vit 1500 ml',
            'slug' => 'vit-1500-ml',
            'image' => 'products/vit-1500ml.png',
            'stock' => 8,
            'pcs' => 12,
            'price' => 33500,
        ]);

        Product::create([
            'name' => 'Ale-ale',
            'slug' => 'ale-ale',
            'image' => 'products/ale-ale.png',
            'stock' => 5,
            'pcs' => 24,
            'price' => 20000,
        ]);

        Product::create([
            'name' => 'Teh Bandulan',
            'slug' => 'teh-bandulan',
            'image' => 'products/teh-bandulan.png',
            'stock' => 14,
            'pcs' => 24,
            'price' => 36000,
        ]);

        Product::create([
            'name' => 'Okky Jelly BIG',
            'slug' => 'okky-jelly-big',
            'image' => 'products/okky-jelly-big.png',
            'stock' => 6,
            'pcs' => 24,
            'price' => 35500,
        ]);

        Product::create([
            'name' => 'Cocona',
            'slug' => 'cocona',
            'image' => 'products/cocona.png',
            'stock' => 11,
            'pcs' => 24,
            'price' => 19500,
        ]);

        Product::create([
            'name' => 'Olala Jelly',
            'slug' => 'olala-jelly',
            'image' => 'products/olala-jelly.png',
            'stock' => 11,
            'pcs' => 24,
            'price' => 20000,
        ]);

        Product::create([
            'name' => 'Olala Milky',
            'slug' => 'olala-milky',
            'image' => 'products/olala-milky.png',
            'stock' => 10,
            'pcs' => 24,
            'price' => 35000,
        ]);

        Product::create([
            'name' => 'Mizone Cranberry 500 ml',
            'slug' => 'mizone-cranberry-500-ml',
            'image' => 'products/mizone-cranberry-500ml.png',
            'stock' => 7,
            'pcs' => 12,
            'price' => 48000,
        ]);

        Product::create([
            'name' => 'Teh Pucuk 350 ml',
            'slug' => 'teh-pucuk-350-ml',
            'image' => 'products/teh-pucuk-350ml.png',
            'stock' => 10,
            'pcs' => 24,
            'price' => 60000,
        ]);

        Product::create([
            'name' => 'Alaina 220 ml',
            'slug' => 'alaina-220-ml',
            'image' => 'products/alaina.png',
            'stock' => 10,
            'pcs' => 48,
            'price' => 15000,
        ]);

        Product::create([
            'name' => 'Alaina 600 ml',
            'slug' => 'alaina-600-ml',
            'image' => 'products/alaina.png',
            'stock' => 12,
            'pcs' => 24,
            'price' => 25000,
        ]);

        Product::create([
            'name' => 'Gunung 220 ml',
            'slug' => 'gunung-220-ml',
            'image' => 'products/gunung-220ml.png',
            'stock' => 14,
            'pcs' => 48,
            'price' => 17000,
        ]);

        Product::create([
            'name' => 'Gunung 600 ml',
            'slug' => 'gunung-600-ml',
            'image' => 'products/gunung-600ml.png',
            'stock' => 20,
            'pcs' => 24,
            'price' => 30000,
        ]);

        Product::create([
            'name' => 'Teh Rio 180 ml',
            'slug' => 'teh-rio-180-ml',
            'image' => 'products/teh-rio.png',
            'stock' => 25,
            'pcs' => 40,
            'price' => 20000,
        ]);

        Product::create([
            'name' => 'Teh Gelas 170 ml',
            'slug' => 'teh-gelas-170-ml',
            'image' => 'products/teh-gelas-170ml.png',
            'stock' => 36,
            'pcs' => 24,
            'price' => 20000,
        ]);

        Product::create([
            'name' => 'Teh Gelas BIG 300 ml',
            'slug' => 'teh-gelas-big-300-ml',
            'image' => 'products/teh-gelas-big-300ml.png',
            'stock' => 32,
            'pcs' => 24,
            'price' => 31000,
        ]);

        Product::create([
            'name' => 'Floridina 350 ml',
            'slug' => 'floridina-350-ml',
            'image' => 'products/floridina-350ml.png',
            'stock' => 40,
            'pcs' => 12,
            'price' => 31000,
        ]);

        Product::create([
            'name' => 'Golda Coffee 200 ml',
            'slug' => 'golda-coffee-200-ml',
            'image' => 'products/golda-coffee-200ml.png',
            'stock' => 37,
            'pcs' => 12,
            'price' => 36000,
        ]);

        Product::create([
            'name' => 'Milku Coklat 200 ml',
            'slug' => 'milku-coklat-200-ml',
            'image' => 'products/milku-coklat-200ml.png',
            'stock' => 26,
            'pcs' => 12,
            'price' => 35000,
        ]);

        Product::create([
            'name' => 'Milku Strawberry 200 ml',
            'slug' => 'milku-strawberry-200-ml',
            'image' => 'products/milku-strawberry-200ml.png',
            'stock' => 26,
            'pcs' => 12,
            'price' => 35000,
        ]);

        Product::create([
            'name' => 'Granita',
            'slug' => 'granita',
            'image' => 'products/granita.png',
            'stock' => 10,
            'pcs' => 24,
            'price' => 35000,
        ]);

        Product::create([
            'name' => 'Fanta 390 ml',
            'slug' => 'fanta-390-ml',
            'image' => 'products/fanta-390ml.png',
            'stock' => 22,
            'pcs' => 12,
            'price' => 47000,
        ]);

        Product::create([
            'name' => 'Sprite 250 ml',
            'slug' => 'sprite-250-ml',
            'image' => 'products/sprite-250ml.png',
            'stock' => 20,
            'pcs' => 12,
            'price' => 47000,
        ]);

        Product::create([
            'name' => 'Panther 170 ml',
            'slug' => 'panther-170-ml',
            'image' => 'products/panther-170ml.png',
            'stock' => 44,
            'pcs' => 24,
            'price' => 20000,
        ]);

        Product::create([
            'name' => 'S-Tee 390 ml',
            'slug' => 's-tee-390-ml',
            'image' => 'products/s-tee-390ml.png',
            'stock' => 34,
            'pcs' => 12,
            'price' => 30000,
        ]);

        Product::create([
            'name' => 'Pulpy Orange 300 ml',
            'slug' => 'pulpy-orange-300-ml',
            'image' => 'products/pulpy-orange-300ml.png',
            'stock' => 27,
            'pcs' => 12,
            'price' => 50000,
        ]);

        Product::create([
            'name' => 'Frestea 350 ml',
            'slug' => 'frestea-350-ml',
            'image' => 'products/frestea-350ml.png',
            'stock' => 26,
            'pcs' => 12,
            'price' => 40000,
        ]);

        Product::create([
            'name' => 'Teh Botol Sosro 350 ml',
            'slug' => 'teh-botol-sosro-350-ml',
            'image' => 'products/teh-botol-sosro-350ml.png',
            'stock' => 33,
            'pcs' => 12,
            'price' => 42000,
        ]);

        Product::create([
            'name' => 'Kopi Nongkrong 150 ml',
            'slug' => 'kopi-nongkrong-150-ml',
            'image' => 'products/kopi-nongkrong.png',
            'stock' => 28,
            'pcs' => 24,
            'price' => 20000,
        ]);

        Product::create([
            'name' => 'Good Day',
            'slug' => 'good-day',
            'image' => 'products/good-day.png',
            'stock' => 27,
            'pcs' => 24,
            'price' => 125000,
        ]);

        Product::create([
            'name' => 'Tebs',
            'slug' => 'tebs',
            'image' => 'products/tebs.png',
            'stock' => 17,
            'pcs' => 20,
            'price' => 91000,
        ]);

        Product::create([
            'name' => 'Fruit Tea Apple 200 ml',
            'slug' => 'fruit-tea-apple-200-ml',
            'image' => 'products/fruit-tea-apple-200ml.png',
            'stock' => 20,
            'pcs' => 24,
            'price' => 45000,
        ]);

        Product::create([
            'name' => 'Larutan Badak 350 ml',
            'slug' => 'larutan-badak-350-ml',
            'image' => 'products/larutan-badak-350ml.png',
            'stock' => 22,
            'pcs' => 24,
            'price' => 128000,
        ]);

        Product::create([
            'name' => 'Pocari Sweat 330 ml',
            'slug' => 'pocari-sweat-330-ml',
            'image' => 'products/pocari-sweat-330ml.png',
            'stock' => 44,
            'pcs' => 24,
            'price' => 122000,
        ]);

        Product::create([
            'name' => 'Pocari Sweat 350 ml',
            'slug' => 'pocari-sweat-350-ml',
            'image' => 'products/pocari-sweat-350ml.png',
            'stock' => 40,
            'pcs' => 24,
            'price' => 130000,
        ]);

        Product::create([
            'name' => 'Pocari Sweat 500 ml',
            'slug' => 'pocari-sweat-500-ml',
            'image' => 'products/pocari-sweat-500ml.png',
            'stock' => 34,
            'pcs' => 24,
            'price' => 155000,
        ]);

        Product::create([
            'name' => 'You C1000 140 ml',
            'slug' => 'you-c1000-140-ml',
            'image' => 'products/you-c1000-140ml.png',
            'stock' => 20,
            'pcs' => 30,
            'price' => 170000,
        ]);

        Product::create([
            'name' => 'Kratingdaeng',
            'slug' => 'kratingdaeng',
            'image' => 'products/kratingdaeng.png',
            'stock' => 10,
            'pcs' => 8,
            'price' => 50000,
        ]);

        Product::create([
            'name' => 'Nu Greentea Madu 450 ml',
            'slug' => 'nu-greentea-madu-450-ml',
            'image' => 'products/nu-greentea-madu-450ml.png',
            'stock' => 24,
            'pcs' => 24,
            'price' => 115000,
        ]);

        Product::create([
            'name' => 'Adem Sari 320 ml',
            'slug' => 'adem-sari-320-ml',
            'image' => 'products/adem-sari-320ml.png',
            'stock' => 36,
            'pcs' => 24,
            'price' => 145000,
        ]);

        Product::create([
            'name' => 'You C Orange Water 500 ml',
            'slug' => 'you-c-orange-water-500-ml',
            'image' => 'products/you-c-orange-water-500ml.png',
            'stock' => 20,
            'pcs' => 24,
            'price' => 145000,
        ]);

        Product::create([
            'name' => 'You C Lemon Water 500 ml',
            'slug' => 'you-c-lemon-water-500-ml',
            'image' => 'products/you-c-lemon-water-500ml.png',
            'stock' => 20,
            'pcs' => 24,
            'price' => 145000,
        ]);

        Product::create([
            'name' => 'Tujuh Kurma 200 ml',
            'slug' => 'tujuh-kurma-200-ml',
            'image' => 'products/tujuh-kurma-200ml.png',
            'stock' => 22,
            'pcs' => 12,
            'price' => 105000,
        ]);

        Product::create([
            'name' => 'Bear Brand 189 ml',
            'slug' => 'bear-brand-189-ml',
            'image' => 'products/bear-brand-189ml.png',
            'stock' => 20,
            'pcs' => 30,
            'price' => 260000,
        ]);
    }
}
