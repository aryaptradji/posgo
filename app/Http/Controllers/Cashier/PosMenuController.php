<?php

namespace App\Http\Controllers\Cashier;

use Exception;
use Midtrans\Snap;
use App\Models\City;
use App\Models\User;
use Midtrans\Config;
use App\Models\Order;
use App\Models\Address;
use App\Models\Product;
use App\Models\District;
use App\Models\OrderItem;
use App\Models\SubDistrict;
use Illuminate\Support\Str;
use App\Models\Neighborhood;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PosMenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12)->withQueryString();

        return view('cashier.pos-menu.index', compact('products'));
    }

    public function checkout(Request $request)
    {
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

        $orderIdMidtrans = null;

        $order = DB::transaction(function () use (&$orderIdMidtrans, $items, $totalPrice) {
            $datePrefix = now()->format('Ymd');
            $countToday = Order::where('code', 'like', "ORD{$datePrefix}%")->count() + 1;
            $orderId = 'ORD' . $datePrefix . str_pad($countToday, 4, '0', STR_PAD_LEFT);
            $orderIdMidtrans = $orderId . '-' . now()->format('His') . '-' . now()->timestamp;

            $order = Order::create([
                'user_id' => null,
                'code' => $orderId,
                'time' => now(),
                'category' => 'offline',
                'payment_status' => 'belum dibayar',
                'shipping_status' => 'belum dikirim',
                'item' => count($items),
                'total' => $totalPrice,
                'snap_order_id' => $orderIdMidtrans,
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

        return redirect()->route('pos-menu.checkout.recipient', $order);
    }

    public function showCheckoutRecipient(Order $order)
    {
        $users = User::select('id', 'name')->where('role', 'customer')->get();

        return view('cashier.pos-menu.checkout-recipient', compact('order', 'users'));
    }

    public function storeCheckoutRecipient(Request $request, Order $order)
    {
        $validated = $request->validate(
            [
                'user_id' => 'required|exists:users,id',
            ],
            [
                'user_id.required' => 'Nama pemesan wajib diisi',
            ],
        );

        session()->put("checkout.{$order->id}.user_id", $validated['user_id']);

        return redirect()->route('pos-menu.checkout.address', compact('order'));
    }

    public function showCheckoutAddress(Order $order, Request $request)
    {
        $citySlug = $request->query('city');
        $districtSlug = $request->query('district');
        $subDistrictSlug = $request->query('sub_district');

        // Ambil daftar kota
        $cities = City::select('name', 'slug')->get();

        // Ambil user_id dari session jika ada
        $userId = session()->get("checkout.{$order->id}.user_id");

        // Ambil data user beserta relasi alamat jika tersedia
        $user = $userId ? User::with('address.neighborhood.subDistrict.district.city')->find($userId) : null;

        if ($user) {
            $user->loadMissing(['address.neighborhood.subDistrict.district.city']);
        }

        // Inisialisasi default
        $districts = collect();
        $subDistricts = collect();

        if ($user?->address?->neighborhood?->subDistrict?->district?->city) {
            $citySlug = $user->address->neighborhood->subDistrict->district->city->slug;
            $districtSlug = $user->address->neighborhood->subDistrict->district->slug;
            $subDistrictSlug = $user->address->neighborhood->subDistrict->slug;

            $districts = $user->address->neighborhood->subDistrict->district->city->districts()->select('name', 'slug')->get();
            $subDistricts = $user->address->neighborhood->subDistrict->district->subDistricts()->select('name', 'slug')->get();
        }

        // Ambil daftar kecamatan jika city slug dikirim
        if ($citySlug) {
            $city = City::where('slug', $citySlug)->first();
            if ($city) {
                $districts = $city->districts()->select('name', 'slug')->get();
            }
        }

        // Ambil daftar kelurahan jika district slug dikirim
        if ($districtSlug) {
            $district = District::where('slug', $districtSlug)->first();
            if ($district) {
                $subDistricts = $district->subDistricts()->select('name', 'slug')->get();
            }
        }

        // Kirim data ke view
        return view('cashier.pos-menu.checkout-address', compact('order', 'cities', 'districts', 'subDistricts', 'citySlug', 'districtSlug', 'subDistrictSlug', 'user'));
    }

    public function storeCheckoutAddress(Request $request, Order $order)
    {
        // Validasi input
        $validated = $request->validate(
            [
                'phone' => 'required|string|regex:/^08[0-9]{8,13}$/',
                'city' => 'required|exists:cities,slug',
                'district' => 'required|exists:districts,slug',
                'sub_district' => 'required|exists:sub_districts,slug',
                'address' => 'required|string|max:75',
                'rt' => 'required|string|regex:/^[0-9]{3}$/',
                'rw' => 'required|string|regex:/^[0-9]{3}$/',
                'postal_code' => 'required|string|regex:/^[0-9]{5}$/',
            ],
            [
                'phone.required' => 'Nomor telepon wajib diisi',
                'phone.regex' => 'Format nomor telepon tidak valid',
                'address.required' => 'Alamat wajib diisi',
                'address.max' => 'Alamat maksimal 75 huruf',
                'city.required' => 'Kota wajib diisi',
                'district.required' => 'Kecamatan wajib diisi',
                'sub_district.required' => 'Kelurahan wajib diisi',
                'rt.required' => 'Nomor RT wajib diisi',
                'rt.regex' => 'Format nomor RT tidak valid',
                'rw.required' => 'Nomor RW wajib diisi',
                'rw.regex' => 'Format nomor RW tidak valid',
                'postal_code.required' => 'Kode pos wajib diisi',
                'postal_code.regex' => 'Format kode pos tidak valid',
            ],
        );

        // Ambil user_id dari session
        $userId = session()->get("checkout.{$order->id}.user_id");
        if (!$userId) {
            return back()->with('error', 'User belum dipilih.');
        }

        // Load user & relasi alamatnya
        $user = User::with('address.neighborhood')->findOrFail($userId);
        $subDistrict = SubDistrict::where('slug', $validated['sub_district'])->firstOrFail();

        // Update phone_number user
        $user->update(['phone_number' => $validated['phone']]);

        // Cek apakah user sudah punya address
        if (!$user->address) {
            // Buat neighborhood baru
            $neighborhood = Neighborhood::create([
                'sub_district_id' => $subDistrict->id,
                'rt' => $validated['rt'],
                'rw' => $validated['rw'],
                'postal_code' => $validated['postal_code'],
            ]);

            // Buat address baru
            $address = Address::create([
                'neighborhood_id' => $neighborhood->id,
                'street' => $validated['address'],
            ]);

            $user->update(['address_id' => $address->id]);
        } else {
            // Jika address sudah ada, tinggal update
            $user->address->update([
                'street' => $validated['address'],
            ]);
            $user->address->neighborhood->update([
                'sub_district_id' => $subDistrict->id,
                'rt' => $validated['rt'],
                'rw' => $validated['rw'],
                'postal_code' => $validated['postal_code'],
            ]);
        }

        // Assign user ke order
        $order->update(['user_id' => $userId]);

        // Cek jenis pembayaran
        if ($request->payment_method === 'tunai') {
            $order->update(['payment_method' => 'tunai']);

            return redirect()->route('pos-menu.pay-cash', $order);
        }

        // Non-tunai - Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Load ulang relasi ter-update buat payload Midtrans
        $order->load('items.product', 'user.address.neighborhood.subDistrict.district.city');
        $user = $order->user;

        $items = $order->items
            ->map(function ($item) {
                return [
                    'id' => $item->product_id,
                    'price' => $item->price,
                    'quantity' => $item->qty,
                    'name' => $item->product->name,
                ];
            })
            ->toArray();

        $payload = [
            'transaction_details' => [
                'order_id' => $order->snap_order_id,
                'gross_amount' => $order->total,
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit' => 'minute',
                'duration' => 5,
            ],
            'item_details' => $items,
            'customer_details' => [
                'first_name' => $user->name,
                'phone' => $user->phone_number ?? '-',
                'shipping_address' => [
                    'first_name' => $user->name,
                    'phone' => $user->phone_number,
                    'address' => $user->address->street,
                    'city' => $user->address->neighborhood->subDistrict->district->city->name,
                    'postal_code' => $user->address->neighborhood->postal_code,
                ],
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($payload);
            $order->update([
                'snap_token' => $snapToken,
                'snap_expires_at' => now()->addMinutes(5),
            ]);
        } catch (Exception $e) {
            return back()->with('error', 'Gagal membuat token pembayaran: ' . $e->getMessage());
        }

        return redirect()->route('pos-menu.pay', $order);
    }

    public function pay(Order $order)
    {
        return view('cashier.pos-menu.pay', [
            'order' => $order,
            'snapToken' => $order->snap_token,
        ]);
    }

    public function payCash(Order $order, Request $request)
    {
        // Pastikan order punya user yang valid
        if (!$order->user_id) {
            return redirect()->route('pos-menu.checkout.recipient', $order)->with('error', 'User belum dipilih untuk transaksi ini.');
        }

        // Load relasi user & alamatnya
        $order->load('user.address.neighborhood.subDistrict.district.city', 'items.product');

        return view('cashier.pos-menu.pay-cash', compact('order'));
    }

    public function storePayCash(Request $request, Order $order)
    {
        // Validasi input cash
        $validated = $request->validate(
            [
                'cash' => 'required|numeric|min:' . $order->total,
            ],
            [
                'cash.required' => 'Jumlah uang wajib diisi',
                'cash.numeric' => 'Jumlah uang harus berupa angka',
                'cash.min' => 'Jumlah uang kurang dari total pembayaran',
            ],
        );

        // Hitung kembalian
        $change = $validated['cash'] - $order->total;

        // Update order
        $order->update([
            'payment_status' => 'dibayar',
            'shipping_status' => 'selesai',
            'payment_method' => 'tunai',
            'paid' => $validated['cash'],
            'change' => $change,
        ]);

        return redirect()
            ->route('pos-menu.pay-cash', $order)
            ->with('success', 'Pembayaran tunai berhasil!<br>Kembalian: Rp ' . number_format($change, 0, ',', '.'))
            ->with('showPrintModal', true);
    }

    public function createUser(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'regex:/^[a-zA-Z]+[a-zA-Z.\s]*$/', Rule::unique('users')->where(fn($q) => $q->where('role', 'customer')), 'max:50'],
            ],
            [
                'name.required' => 'Nama wajib diisi',
                'name.regex' => 'Format nama masih salah',
                'name.unique' => 'Nama ini sudah terdaftar',
                'name.max' => 'Nama maksimal 50 huruf',
            ],
        );

        $user = User::create([
            'created' => now(),
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'role' => 'customer',
        ]);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
        ]);
    }
}
