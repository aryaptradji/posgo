<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use Illuminate\Database\Seeder;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\Storage;
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
        $photos = ['contoh-invoice-lunas-1.jpg', 'contoh-invoice-lunas-2.png', 'contoh-invoice-lunas-3.jpeg'];

        foreach ($photos as $photo) {
            Storage::disk('public')->put('purchase_orders/' . $photo, file_get_contents(storage_path('app/public/purchase_orders/' . $photo)));
        }

        for ($i = 1; $i <= 50; $i++) {

            $supplier = $suppliers->random();
            $created = now()->subDays(rand(0, 30));
            $status = fake()->randomElement(['perlu dikirim', 'perlu invoice', 'perlu dibayar', 'selesai']);

            $subtotalPO = 0;
            $ppnPercentage = 0;
            $photo = null;

            if ($status == 'perlu dibayar' || $status == 'selesai') {
                $ppnPercentage = fake()->randomElement([10, 11, 12]);
            }

            if ($status == 'selesai') {
                $photo = 'purchase_orders/' . fake()->randomElement($photos);
            }

            // buat dulu PO-nya
            $po = PurchaseOrder::create([
                'supplier_id' => $supplier->id,
                'code' => 'PO' . $created->format('Ymd') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'created' => $created,
                'status' => $status,
                'subtotal' => 0,
                'item' => 0,
                'total' => 0,
                'ppn_percentage' => $ppnPercentage,
                'photo' => $photo
            ]);

            $selectedProducts = $products->shuffle()->take(rand(1, 5));
            $totalItem = 0;
            $totalPrice = 0;

            foreach ($selectedProducts as $product) {
                $qty = rand(5, 20);
                $supplierPcs = rand(1, 20);

                // kondisi harga & total hanya berlaku jika statusnya 'siap'
                $supplierPrice = 0;
                if ($status == 'perlu dibayar' || $status == 'selesai') {
                    $supplierPrice = $product->price - rand(500, 3000);
                    if ($supplierPrice < 100) $supplierPrice = 100;
                }

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'pcs' => $supplierPcs,
                    'price' => $supplierPrice,
                ]);

                $subtotalPO += $qty * $supplierPrice;
                $totalItem++;
            }

            $totalPPNAmount = 0;
            if ($status == 'perlu dibayar' || $status == 'selesai') {
                $totalPPNAmount = $subtotalPO * ($ppnPercentage / 100);
            }

            $totalPrice = $subtotalPO + $totalPPNAmount;

            $po->update([
                'item' => $totalItem,
                'subtotal' => $subtotalPO,
                'total' => $totalPrice,
                'ppn_percentage' => $ppnPercentage
            ]);
        }
    }
}
