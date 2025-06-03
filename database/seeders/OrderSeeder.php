<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereIn('role', ['cashier', 'customer'])->get();
        $products = Product::all();

        $jumlahOrder = 20;

        for ($i = 1; $i <= $jumlahOrder; $i++) {
            $user = $users->random();
            $category = $user->role === 'cashier' ? 'offline' : 'online';
            $time = fake()->dateTimeBetween('-30 days', 'now');

            $order = Order::create([
                'user_id' => $user->id,
                'code' => 'ORD' . $time->format('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'time' => $time,
                'category' => $category,
                'payment_status' => fake()->randomElement(['belum dibayar', 'dibayar', 'batal']),
                'shipping_status' => fake()->randomElement(['belum dikirim', 'dalam perjalanan', 'selesai']),
                'item' => 0,
                'total' => 0,
            ]);

            $selectedProducts = $products->shuffle()->take(rand(1, 4));
            $totalQty = 0;
            $totalPrice = 0;

            foreach ($selectedProducts as $product) {
                $qty = rand(1, 5);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'price' => $product->price,
                ]);

                $totalQty += $qty;
                $totalPrice += $qty * $product->price;
            }

            $order->update([
                'item' => $totalQty,
                'total' => $totalPrice,
            ]);
        }
    }
}
