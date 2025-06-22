<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier', 'items')->latest('created');

        // Filter kategori
        if ($request->filled('filter') && $request->filter !== 'semua') {
            $query->where('status', $request->filter);
        }

        // Pencarian
        if ($request->filled('search')) {
            $query
                ->join('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
                ->where(function ($q) use ($request) {
                    $search = '%' . $request->search . '%';
                    $q->where('suppliers.name', 'like', $search)->orWhere('purchase_orders.code', 'like', $search);
                })
                ->select('purchase_orders.*');
        }

        // Sorting
        if ($request->filled('sort') && $request->sort === 'supplier') {
            $query
                ->join('suppliers', 'suppliers.id', '=', 'purchase_orders.supplier_id')
                ->orderBy('suppliers.name', $request->boolean('desc') ? 'desc' : 'asc')
                ->select('purchase_orders.*');
        } else {
            $query->orderBy($request->sort ?? 'created', $request->boolean('desc') ? 'desc' : 'asc');
        }

        // Pagination
        $perPage = $request->input('per_page', 5);
        $purchase_orders = $query->paginate($perPage)->withQueryString();

        return view('admin.purchase-order.index', compact('purchase_orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::select('slug', 'name')->get();
        $products = Product::select('slug', 'name')->get();

        return view('admin.purchase-order.create', compact('suppliers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = json_decode($request->input('purchase'), true);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak valid!');
        }

        $validator = Validator::make(
            $data,
            [
                'supplier' => 'required|exists:suppliers,slug',
                'items' => 'required|array|min:1',
                'items.*.product' => 'required|exists:products,slug',
                'items.*.pcs' => 'required|integer|min:1',
                'items.*.qty' => 'required|integer|min:1',
            ],
            [
                'supplier.required' => 'Supplier wajib diisi',
                'items.required' => 'Harus ada minimal 1 produk',
                'items.*.product.required' => 'Nama produk harus dipilih',
                'items.*.pcs.required' => 'Jumlah pcs wajib diisi',
                'items.*.pcs.integer' => 'Jumlah pcs harus berupa angka',
                'items.*.pcs.min' => 'Jumlah pcs minimal 1',
                'items.*.qty.required' => 'Jumlah qty wajib diisi',
                'items.*.qty.integer' => 'Jumlah qty harus berupa angka',
                'items.*.qty.min' => 'Jumlah qty minimal 1',
            ],
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $supplier = Supplier::where('slug', $data['supplier'])->firstOrFail();

        $datePrefix = now()->format('Ymd');
        $countToday = PurchaseOrder::where('code', 'like', "PO{$datePrefix}%")->count() + 1;
        $code = 'PO' . $datePrefix . str_pad($countToday, 4, '0', STR_PAD_LEFT);

        try {
            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $supplier->id,
                'code' => $code,
                'created' => now(),
                'status' => 'perlu dikirim',
                'item' => count($data['items']),
                'total' => 0, // total tetap 0
            ]);

            foreach ($data['items'] as $itemData) {
                $product = Product::where('slug', $itemData['product'])->firstOrFail();

                $purchaseOrder->items()->create([
                    'product_id' => $product->id,
                    'qty' => $itemData['qty'],
                    'pcs' => $itemData['pcs'],
                    'price' => 0, // <== price juga 0 saat create
                ]);
            }

            return redirect()->route('purchase-order.index')->with('success', 'Purchase Order berhasil disimpan');
        } catch (Exception $e) {
            Log::error('Error storing Purchase Order: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan Purchase Order. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $po = $purchaseOrder->load('items.product', 'supplier');

        return view('admin.purchase-order.show', compact('po'));
    }

    public function printInvoice(PurchaseOrder $purchaseOrder) {
        $po = $purchaseOrder->load('items.product', 'supplier');

        return view('admin.purchase-order.print-invoice', compact('po'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder = $purchaseOrder->fresh('items.product');
        // dd($purchaseOrder->items);

        $data = [
            'supplier' => $purchaseOrder->supplier->slug,
            'items' => $purchaseOrder->items
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product' => $item->product->slug,
                        'pcs' => $item->pcs,
                        'qty' => $item->qty,
                        // Pastikan price juga ada jika diperlukan di frontend
                        // 'price' => $item->price,
                    ];
                })
                ->toArray(),
        ];

        return view('admin.purchase-order.edit', [
            'purchaseOrder' => $purchaseOrder,
            'purchaseData' => json_encode($data),
            'suppliers' => Supplier::all(),
            'products' => Product::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        try {
            $purchaseData = json_decode($request->input('purchase'), true);

            if (!$purchaseData) {
                throw ValidationException::withMessages(['purchase' => 'Data purchase order tidak valid.']);
            }

            // Konversi tipe data untuk ID, PCS, QTY, dan PRICE
            foreach ($purchaseData['items'] as &$itemData) {
                if (isset($itemData['id'])) {
                    // Hanya cast jika ada ID. Jika ID datang sebagai float dari JS, ini akan mengubahnya.
                    $itemData['id'] = (int) $itemData['id'];
                } else {
                    // Jika 'id' tidak diset sama sekali di frontend, pastikan itu null.
                    $itemData['id'] = null;
                }

                $itemData['pcs'] = (int) $itemData['pcs'];
                $itemData['qty'] = (int) $itemData['qty'];
                $itemData['price'] = (int) ($itemData['price'] ?? 0);
            }
            unset($itemData);

            // Validasi data
            $validator = Validator::make(
                $purchaseData,
                [
                    'supplier' => 'required|string|exists:suppliers,slug',
                    'items' => 'required|array|min:1',
                    'items.*.id' => 'nullable|integer', // Hanya nullable dan integer, bukan exists
                    'items.*.product' => 'required|string|exists:products,slug',
                    'items.*.pcs' => 'required|integer|min:1',
                    'items.*.qty' => 'required|integer|min:1',
                    'items.*.price' => 'nullable|integer|min:0', // Validasi price
                ],
                [
                    'supplier.required' => 'Supplier wajib diisi',
                    'supplier.exists' => 'Supplier yang dipilih tidak valid',
                    'items.required' => 'Harus ada minimal 1 produk',
                    'items.min' => 'Harus ada minimal 1 produk',
                    // Hapus pesan error untuk items.*.id.exists
                    'items.*.product.required' => 'Produk di setiap baris harus dipilih',
                    'items.*.product.exists' => 'Produk di setiap baris tidak valid',
                    'items.*.pcs.required' => 'Jumlah pcs wajib diisi',
                    'items.*.pcs.integer' => 'Jumlah pcs harus berupa angka',
                    'items.*.pcs.min' => 'Jumlah pcs minimal 1',
                    'items.*.qty.required' => 'Kuantitas wajib diisi',
                    'items.*.qty.integer' => 'Kuantitas harus berupa angka',
                    'items.*.qty.min' => 'Kuantitas minimal 1',
                    'items.*.price.integer' => 'Harga harus berupa angka.',
                    'items.*.price.min' => 'Harga tidak boleh kurang dari 0.',
                ],
            );

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            // Proses update
            $supplier = Supplier::where('slug', $purchaseData['supplier'])->firstOrFail();
            $purchaseOrder->supplier_id = $supplier->id;
            $purchaseOrder->item = count($purchaseData['items']);
            // $purchaseOrder->total = /* hitung total jika ada harga di produk */;
            $purchaseOrder->save();

            $existingItemIds = $purchaseOrder->items->pluck('id')->toArray();
            $updatedItemIds = [];

            foreach ($purchaseData['items'] as $itemData) {
                $product = Product::where('slug', $itemData['product'])->firstOrFail();

                if (isset($itemData['id']) && $itemData['id'] !== null) {
                    $item = $purchaseOrder->items()->find($itemData['id']);
                    if ($item) {
                        $item->product_id = $product->id;
                        $item->pcs = $itemData['pcs'];
                        $item->qty = $itemData['qty'];
                        $item->price = $itemData['price']; // Sekarang price sudah di-cast
                        $item->save();
                        $updatedItemIds[] = $item->id;
                    }
                } else {
                    $newItem = $purchaseOrder->items()->create([
                        'product_id' => $product->id,
                        'qty' => $itemData['qty'],
                        'pcs' => $itemData['pcs'],
                        'price' => $itemData['price'], // Sekarang price sudah di-cast
                    ]);
                    $updatedItemIds[] = $newItem->id;
                }
            }

            $itemsToDelete = array_diff($existingItemIds, $updatedItemIds);
            if (!empty($itemsToDelete)) {
                PurchaseOrderItem::whereIn('id', $itemsToDelete)->delete();
            }

            return redirect()->route('purchase-order.index')->with('success', 'Purchase Order berhasil diperbarui');
        } catch (ValidationException $e) {
            return redirect()->back()->withInput()->withErrors($e->errors());
        } catch (Exception $e) {
            Log::error('Error updating Purchase Order: ' . $e->getMessage() . ' on line ' . $e->getLine() . ' in ' . $e->getFile());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui Purchase Order. Silakan coba lagi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
