<?php

namespace App\Http\Controllers\Customer;

use Midtrans\Snap;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use App\Enums\ShippingStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = $this->getProductList($request);

        return view('customer.product.index', compact('products'));
    }

    public function checkout(Request $request)
    {
        $request->validate(
            [
                'cart' => 'required|json',
            ],
            [
                'cart.required' => 'Maaf keranjang masih kosong',
            ],
        );

        $cart = json_decode($request->input('cart', '{}'), true);

        if (empty($cart) || count($cart) === 0) {
            return redirect()->back()->with('error', 'Maaf keranjang masih kosong');
        }

        // Ambil produk yang ada di cart
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

        $user = User::with('address.neighborhood.subDistrict.district.city')->find(Auth::id());
        $category = $user->role === 'cashier' ? 'Offline' : 'Online';

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $order = Order::create([
            'user_id' => $user->id,
            'code' => '',
            'time' => now(),
            'category' => $category,
            'payment_status' => PaymentStatus::BelumDibayar,
            'shipping_status' => ShippingStatus::BelumDikirim,
            'item' => array_sum(array_column($items, 'quantity')),
            'total' => $totalPrice,
        ]);

        $orderId = 'ORD' . now()->format('Ymd') . str_pad($order->id, 4, '0', STR_PAD_LEFT);
        $order->update(['code' => $orderId]);

        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'qty' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'minute',
                'duration' => 60,
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
                    'address' => $user->address->street . ', RT ' . $user->address->neighborhood->rt . '/RW ' . $user->address->neighborhood->rw . ', Kec. ' . $user->address->neighborhood->subDistrict->district->name . ', Kel. ' . $user->address->neighborhood->subDistrict->name . ', ',
                    'city' => $user->address->neighborhood->subDistrict->district->city->name,
                    'postal_code' => $user->address->neighborhood->postal_code,
                ],
            ],
        ];

        // Buat Snap Token Midtrans
        try {
            $snapToken = Snap::getSnapToken($payload);
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal membuat token pembayaran: ' . $e->getMessage());
        }
        // Kirim snap token ke halaman index + ulang produk
        $products = $this->getProductList($request);

        return view('customer.product.index', compact('products', 'snapToken', 'orderId'));
    }

    // Helper function biar DRY
    private function getProductList(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return $query->paginate(12)->withQueryString();
    }
}
