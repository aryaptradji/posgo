<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Expense;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Database\Factories\ExpenseFactory;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ProductSeeder::class);
        Expense::factory(10)->create();
    }
}
