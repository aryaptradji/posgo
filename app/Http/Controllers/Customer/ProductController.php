<?php

namespace App\Http\Controllers\Customer;

use Midtrans\Snap;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = $this->getProductList($request);

        return view('customer.product.index', compact('products'));
    }

    public function checkout(Request $request)
    {
        $cart = json_decode($request->input('cart', '{}'), true);

        if (empty($cart) || count($cart) === 0) {
            return redirect()->back()->with('error', 'Keranjang kosong.');
        }

        // Ambil produk yang ada di cart
        $productIds = array_keys($cart);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        $items = [];
        $totalPrice = 0;

        foreach ($cart as $productId => $item) {
            $product = $products[$productId] ?? null;
            $qty = $item['qty'] ?? 0;

            if (!$product || $qty < 1) continue;

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

        // $latestId = Order::max('id') + 1;
        // $orderId = 'ORD' . now()->format('Ymd') . str_pad($latestId, 4, '0', STR_PAD_LEFT);
        $orderId = 'ORD' . now()->format('YmdHis') . rand(100, 999);


        $user = User::whereIn('role', ['customer', 'cashier'])->inRandomOrder()->first();

        $payload = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone_number
            ],
        ];

        // Buat Snap Token Midtrans
        $snapToken = Snap::getSnapToken($payload);
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
