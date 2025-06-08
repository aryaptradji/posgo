<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Courier;
use Illuminate\Http\Request;
use App\Exports\DeliveryExport;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('user', 'items')->where('payment_status', 'dibayar');

        // Filter kategori
        if ($request->filled('filter') && $request->filter !== 'semua') {
            $query->where('shipping_status', $request->filter);
        }

        // Pencarian
        if ($request->filled('search')) {
            $query
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->where(function ($order) use ($request) {
                    $search = '%' . $request->search . '%';
                    $order->where('users.name', 'like', $search)->orWhere('code', 'like', $search);
                })
                ->select('orders.*');
        }

        // Sorting
        if ($request->filled('sort') && $request->sort === 'name') {
            $query
                ->join('users', 'users.id', '=', 'orders.user_id')
                ->orderBy('users.name', $request->boolean('desc') ? 'desc' : 'asc')
                ->select('orders.*');
        } elseif ($request->filled('sort')) {
            $query->orderBy($request->sort, $request->boolean('desc') ? 'desc' : 'asc');
        } else {
            $query->orderBy('shipped_at', 'asc');
        }

        // Pagination
        $perPage = $request->input('per_page', 5);
        $deliveries = $query->paginate($perPage)->withQueryString();
        $couriers = Courier::pluck('name', 'id')->toArray();

        return view('admin.delivery.index', compact('deliveries', 'couriers'));
    }

    public function print()
    {
        $deliveries = Order::with(['user', 'items'])
            ->orderBy('time', 'desc')
            ->get();

        return view('admin.delivery.print', compact('deliveries'));
    }

    public function export()
    {
        return Excel::download(new DeliveryExport(), 'daftar_pengiriman_pesanan.xlsx');
    }

    public function kirim(Request $request, Order $delivery)
    {
        $validated = $request->validate(
            [
                'courier_id' => 'required|exists:couriers,id',
                'shipped_at' => 'required',
            ],
            [
                'courier_id.required' => 'Data kurir wajib diisi',
                'shipped_at.required' => 'Waktu pengiriman wajib diisi',
                'courier_id.exists' => 'Data kurir wajib diisi',
            ],
        );

        $delivery->update([
            'courier_id' => $validated['courier_id'],
            'shipping_status' => 'dalam perjalanan',
            'shipped_at' => $validated['shipped_at'],
        ]);

        return redirect()->route('delivery.index')->with('success', 'Pesanan berhasil dikirim');
    }

    public function upload(Request $request, Order $delivery)
    {
        $request->validate(
            [
                'photo' => 'required|image|mimes:jpg,jpeg|max:3048',
            ],
            [
                'photo.required' => 'Gambar wajib diisi',
                'photo.image' => 'File harus berbentuk gambar',
                'photo.mimes' => 'Format gambar harus .jpg/.jpeg',
                'photo.max' => 'Size gambar maksimal 3 mb',
            ],
        );

        $imagePath = $request->file('photo')->store('deliveries', 'public');

        $delivery->update([
            'photo' => $imagePath,
            'shipping_status' => 'selesai'
        ]);

        return redirect()->route('delivery.index')->with('success', 'Bukti pengiriman pesanan berhasil diupload');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
