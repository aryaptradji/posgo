<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Courier;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereIn('role', ['cashier', 'customer'])->get();
        $products = Product::all();
        $couriers = Courier::all();
        $photos = ['sample-foto-paket-1.jpg', 'sample-foto-paket-2.jpeg', 'sample-foto-paket-3.jpg'];

        foreach ($photos as $photo) {
            Storage::disk('public')->put('deliveries/' . $photo, file_get_contents(storage_path('app/public/deliveries/' . $photo)));
        }

        $jumlahOrder = 100;

        for ($i = 1; $i <= $jumlahOrder; $i++) {
            $user = $users->random();
            $category = $user->role === 'cashier' ? 'offline' : 'online';
            $time = fake()->dateTimeBetween('-30 days', 'now');

            // 70% chance 'dibayar'
            $payment_status = fake()->randomElement([
                ...array_fill(0, 7, 'dibayar'), // ~70%
                'belum dibayar',
                'dibatalkan',
                'kadaluwarsa',
                'ditolak',
            ]);

            $courier_id = null;
            $shipping_status = 'belum dikirim';
            $shipped_at = null;

            if ($payment_status === 'dibayar') {
                if (fake()->boolean(70)) {
                    // 70% dari 'dibayar' → udah punya kurir & shipping_status jalan
                    $courier = $couriers->random();
                    $courier_id = $courier->id;
                    $shipping_status = fake()->randomElement(['dikirim', 'selesai']);

                    // shipped_at harus diisi > time
                    $shipped_at = Carbon::parse($time)->addHours(rand(1, 72));
                } else {
                    // Dibayar tapi belum dikirim
                    $courier_id = null;
                    $shipping_status = 'belum dikirim';
                    $shipped_at = null;
                }
            }

            $photo = null;
            $arrived_at = null;

            if ($shipping_status === 'selesai' && !empty($photos)) {
                $photo = 'deliveries/' . fake()->randomElement($photos);

                if ($shipped_at) {
                    $arrived_at = Carbon::parse($shipped_at)->addHours(rand(1, 72));
                }
            }

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'courier_id' => $courier_id,
                'code' => 'ORD' . $time->format('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'time' => $time,
                'category' => $category,
                'payment_status' => $payment_status,
                'payment_method' => fake()->randomElement(['QRIS ShopeePay', 'QRIS GoPay', 'QRIS', 'Bank Mandiri', 'Bank BCA', 'Bank BNI']),
                'shipping_status' => $shipping_status,
                'item' => 0, // updated setelah insert item
                'total' => 0, // updated setelah insert item
                'snap_token' => null,
                'snap_expires_at' => null,
                'snap_order_id' => null,
                'shipped_at' => $shipped_at,
                'arrived_at' => $arrived_at,
                'photo' => $photo,
            ]);

            // Insert Order Items
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

            // Update order item & total
            $order->update([
                'item' => $selectedProducts->count(),
                'total' => $totalPrice,
            ]);
        }
    }
}
