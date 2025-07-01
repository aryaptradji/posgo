<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Revenue>
 */
class RevenueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $otherSources = [
            'Penjualan Langsung',
            'Komisi',
            'Bonus Supplier',
            'Pendapatan Lain',
            'Sewa Tempat',
            'Penjualan Aset Lama',
        ];

        return [
            'date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'source' => $this->faker->randomElement($otherSources),
            'category' => 'Luar Produk',
            'total' => $this->faker->numberBetween(50000, 500000),
            'product_id' => null,
        ];
    }
}
