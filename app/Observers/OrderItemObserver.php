<?php

namespace App\Observers;

use App\Models\Revenue;
use App\Models\OrderItem;

class OrderItemObserver
{
    /**
     * Handle the OrderItem "created" event.
     */
    public function created(OrderItem $orderItem)
    {
        $order = $orderItem->order;
        if (!$order) {
            return;
        }

        // Simpan revenue berdasarkan tanggal Order
        Revenue::updateOrCreate(
            [
                'product_id' => $orderItem->product_id,
                'date' => $order->time->format('Y-m-d'),
            ],
            [
                'source' => $orderItem->product->name,
                'category' => 'Produk',
                // Kalau sudah ada, tambahkan total
                'total' =>
                    Revenue::where('product_id', $orderItem->product_id)
                        ->where('date', $order->time->format('Y-m-d'))
                        ->value('total') +
                    $orderItem->qty * $orderItem->price,
            ],
        );
    }

    /**
     * Handle the OrderItem "restored" event.
     */
    public function restored(OrderItem $orderItem): void
    {
        //
    }

    /**
     * Handle the OrderItem "force deleted" event.
     */
    public function forceDeleted(OrderItem $orderItem): void
    {
        //
    }
}
