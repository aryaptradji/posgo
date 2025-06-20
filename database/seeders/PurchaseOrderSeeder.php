<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Database\Seeder;
use App\Models\PurchaseOrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = Supplier::all();
        $products = Product::all();

        for ($i = 1; $i <= 50; $i++) {

            $supplier = $suppliers->random();
            $created = now()->subDays(rand(0, 30));
            $status = fake()->randomElement(['perlu dikirim', 'perlu invoice', 'siap']);

            // buat dulu PO-nya
            $po = PurchaseOrder::create([
                'supplier_id' => $supplier->id,
                'code' => 'PO' . $created->format('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'created' => $created,
                'status' => $status,
                'item' => 0,
                'total' => 0,
            ]);

            $selectedProducts = $products->shuffle()->take(rand(1, 5));
            $totalItem = 0;
            $totalPrice = 0;

            foreach ($selectedProducts as $product) {
                $qty = rand(5, 20);
                $supplierPcs = rand(1, 20);

                // kondisi harga & total hanya berlaku jika statusnya 'siap'
                $supplierPrice = 0;
                if ($status == 'siap') {
                    $supplierPrice = $product->price - rand(500, 3000);
                    $totalPrice += $qty * $supplierPrice;
                }

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'pcs' => $supplierPcs,
                    'price' => $supplierPrice,
                ]);

                $totalItem++;
            }

            $po->update([
                'item' => $totalItem,
                'total' => $totalPrice,
            ]);
        }
    }
}
