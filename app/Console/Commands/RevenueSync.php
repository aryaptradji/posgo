<?php

namespace App\Console\Commands;

use App\Models\Revenue;
use App\Models\OrderItem;
use Illuminate\Console\Command;

class RevenueSync extends Command
{
    protected $signature = 'revenue:sync';
    protected $description = 'Sinkronisasi revenue berdasarkan semua data order lama';

    public function handle()
    {
        $this->info('Proses sync revenue dimulai...');

        Revenue::truncate(); // Kosongkan dulu supaya clean

        $items = OrderItem::with(
            [
                'order' => function ($query) {
                    $query->where('shipping_status', 'selesai');
                },
            ],
            'product',
        )->get();

        foreach ($items as $item) {
            if (!$item->order) {
                continue;
            }

            $date = $item->order->time->format('Y-m-d');

            Revenue::updateOrCreate(
                [
                    'product_id' => $item->product_id,
                    'date' => $date,
                ],
                [
                    'source' => $item->product->name,
                    'category' => 'Produk',
                    'total' => Revenue::where('product_id', $item->product_id)->where('date', $date)->value('total') + $item->qty * $item->price,
                ],
            );
        }

        $this->info('Revenue berhasil disinkronkan!');
    }
}
