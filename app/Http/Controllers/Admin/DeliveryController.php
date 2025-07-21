<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Courier;
use Illuminate\Support\Str;
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
        Order::where('shipping_status', 'belum dibayar')
            ->where('shipped_at', '<=', now())
            ->update(['shipping_status' => 'dikirim']);

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
            ->orderBy('shipped_at', 'asc')
            ->get();

        return view('admin.delivery.print', compact('deliveries'));
    }

    public function export()
    {
        return Excel::download(new DeliveryExport(), 'daftar_pengiriman_pesanan.xlsx');
    }

    public function deliveryNote(Order $delivery)
    {
        return view('admin.delivery.delivery_note', compact('delivery'));
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

        $status = now()->gte($validated['shipped_at']) ? 'dikirim' : 'belum dikirim';

        $delivery->update([
            'courier_id' => $validated['courier_id'],
            'shipping_status' => $status,
            'shipped_at' => $validated['shipped_at'],
        ]);

        return redirect()
            ->route('delivery.index', ['filter' => $status, 'sort' => 'shipped_at', 'desc' => 1])
            ->with('success', 'Pesanan berhasil dikirim');
    }

    public function upload(Request $request, Order $delivery)
    {
        // Validasi awal: pastikan ada file dikirim minimal 1
        $request->validate(
            [
                'photo' => 'required|array|min:1',
            ],
            [
                'photo.required' => 'Gambar wajib diisi',
                'photo.array' => 'Data gambar tidak valid',
                'photo.min' => 'Minimal unggah 1 gambar',
            ],
        );

        // Loop validasi per file
        foreach ($request->file('photo') as $index => $file) {
            $request->validate(
                [
                    "photo.$index" => 'required|image|mimes:jpg,jpeg|max:3048',
                ],
                [
                    "photo.$index.required" => 'Gambar wajib diisi',
                    "photo.$index.image" => 'File harus berbentuk gambar',
                    "photo.$index.mimes" => 'Format gambar harus .jpg/.jpeg',
                    "photo.$index.max" => 'Ukuran maksimal 3 MB',
                ],
            );

            $imagePath = $file->store('deliveries', 'public');

            $delivery->update([
                'photo' => $imagePath,
                'shipping_status' => 'selesai',
                'arrived_at' => now(),
            ]);

            break; // hanya proses file pertama
        }

        return redirect()
            ->route('delivery.index', ['filter' => 'selesai', 'sort' => 'arrived_at', 'desc' => 1])
            ->with('success', 'Bukti pengiriman pesanan berhasil diupload');
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
