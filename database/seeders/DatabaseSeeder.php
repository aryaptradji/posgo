<?php

namespace Database\Seeders;


use App\Models\Expense;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            TangerangSeeder::class,
            NeighborhoodSeeder::class,
            CashierSeeder::class,
            CustomerSeeder::class,
            ProductSeeder::class,
            SupplierSeeder::class,
            CourierSeeder::class,
            OrderSeeder::class,
            AdminSeeder::class,
            PurchaseOrderSeeder::class
        ]);
        Expense::factory(10)->create();
    }
}
