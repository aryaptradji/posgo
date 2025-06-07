<?php

namespace App\Http\Controllers\Customer;

use Exception;
use Midtrans\Snap;
use App\Models\User;
use Midtrans\Config;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12)->withQueryString();

        return view('customer.product.index', compact('products'));
    }

    public function checkout(Request $request)
    {
        $user = User::with('address.neighborhood.subDistrict.district.city')->find(Auth::id());

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $cart = json_decode($request->input('cart', '{}'), true);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Maaf keranjang masih kosong');
        }

        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $item) {
            $product = $products[$productId] ?? null;
            $qty = $item['qty'] ?? 0;

            if (!$product || $qty < 1) {
                continue;
            }

            $price = $product->price;
            $total = $price * $qty;

            $items[] = [
                'id' => $product->id,
                'price' => $price,
                'quantity' => $qty,
                'name' => $product->name,
            ];

            $totalPrice += $total;
        }

        if (empty($items)) {
            return redirect()->back()->with('error', 'Produk tidak valid');
        }

        $category = $user->role === 'cashier' ? 'offline' : 'online';

        $orderIdMidtrans = null;

        $order = DB::transaction(function () use (&$orderIdMidtrans, $user, $category, $items, $totalPrice) {
            $datePrefix = now()->format('Ymd');
            $countToday = Order::where('code', 'like', "ORD{$datePrefix}%")->count() + 1;
            $orderId = 'ORD' . $datePrefix . str_pad($countToday, 4, '0', STR_PAD_LEFT);
            $orderIdMidtrans = $orderId . '-' . now()->format('His') . '-' . now()->timestamp;

            $order = Order::create([
                'user_id' => $user->id,
                'code' => $orderId,
                'time' => now(),
                'category' => $category,
                'payment_status' => 'belum dibayar',
                'shipping_status' => 'belum dikirim',
                'item' => array_sum(array_column($items, 'quantity')),
                'total' => $totalPrice,
                'snap_order_id' => $orderIdMidtrans
            ]);

            // Simpan detail item
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'qty' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return $order;
        });

        // Payload Snap Midtrans
        $payload = [
            'transaction_details' => [
                'order_id' => $orderIdMidtrans,
                'gross_amount' => $totalPrice,
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'minute',
                'duration' => 5,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone_number,
                'shipping_address' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone_number,
                    'address' => $user->address->street . ', RT ' . $user->address->neighborhood->rt . '/RW ' . $user->address->neighborhood->rw . ', Kec. ' . $user->address->neighborhood->subDistrict->district->name . ', Kel. ' . $user->address->neighborhood->subDistrict->name,
                    'city' => $user->address->neighborhood->subDistrict->district->city->name,
                    'postal_code' => $user->address->neighborhood->postal_code,
                ],
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($payload);

            $order->update([
                'snap_token' => $snapToken,
                'snap_expires_at' => now()->addMinutes(5)
            ]);
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal membuat token pembayaran: ' . $e->getMessage());
        }

        return view('customer.product.checkout', compact('snapToken', 'order'));
    }
}
