<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         // 50% kemungkinan dari produk
        $isProduct = $this->faker->boolean();
        $product = Product::inRandomOrder()->first();

        // Daftar sumber pengeluaran non-produk
        $otherSources = [
            'Listrik Bulanan',
            'Gaji Karyawan',
            'Beli Tisu & Pembersih',
            'Sewa Tempat',
            'Transportasi',
            'Perlengkapan Tulis',
            'Internet & WiFi',
            'Biaya Keamanan',
        ];

        return [
            'date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'source' => $isProduct && $product ? $product->name : $this->faker->randomElement($otherSources),
            'category' => $isProduct ? 'operasional' : 'luar operasional',
            'total' => $this->faker->numberBetween(10000, 300000),
            'product_id' => $isProduct && $product ? $product->id : null,
        ];
    }
}
