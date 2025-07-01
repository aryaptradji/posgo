<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // ===============================
        // 1. Resolve tanggal dan range
        // ===============================
        $range = $request->input('range');
        $dariTanggal = $request->input('dari_tanggal') ? Carbon::parse($request->input('dari_tanggal')) : null;
        $sampaiTanggal = $request->input('sampai_tanggal') ? Carbon::parse($request->input('sampai_tanggal')) : null;

        if ($range !== null) {
            [$dariTanggal, $sampaiTanggal] = $this->resolveRangeDates((int) $range);
        } else {
            if (!$dariTanggal || !$sampaiTanggal) {
                // default hari ini
                $dariTanggal = Carbon::today()->startOfDay();
                $sampaiTanggal = Carbon::today()->endOfDay();
            } else {
                $dariTanggal = $dariTanggal->startOfDay();
                $sampaiTanggal = $sampaiTanggal->endOfDay();
            }

            if ($dariTanggal->greaterThan($sampaiTanggal)) {
                [$dariTanggal, $sampaiTanggal] = [$sampaiTanggal, $dariTanggal];
            }
        }

        // ===============================
        // 2. Buat label periode untuk tampilan
        // ===============================
        if ($dariTanggal->isToday() && $sampaiTanggal->isToday()) {
            $labelPeriode = 'Hari ini';
        } elseif ($dariTanggal->isYesterday() && $sampaiTanggal->isYesterday()) {
            $labelPeriode = 'Kemarin';
        } else {
            $labelPeriode = $dariTanggal->translatedFormat('d M Y') . ' - ' . $sampaiTanggal->translatedFormat('d M Y');
        }

        // ===============================
        // 3. Data Ringkasan
        // ===============================
        $jumlahPesanan = Order::whereBetween('time', [$dariTanggal, $sampaiTanggal])->count();
        $pemasukan = Order::whereBetween('time', [$dariTanggal, $sampaiTanggal])
            ->where('shipping_status', 'selesai')
            ->sum('total');
        $pengeluaran = Expense::whereBetween('date', [$dariTanggal, $sampaiTanggal])->sum('total');
        $labaBersih = $pemasukan - $pengeluaran;

        // ===============================
        // 4. Produk Terlaris
        // ===============================
        $queryProdukTerlaris = Product::withCount([
            'items as sold' => function ($query) use ($dariTanggal, $sampaiTanggal) {
                $query->whereHas('order', function ($q) use ($dariTanggal, $sampaiTanggal) {
                    $q->whereBetween('time', [$dariTanggal, $sampaiTanggal])->where('shipping_status', 'selesai');
                });
            },
        ]);

        if ($request->filled('produk_search')) {
            $queryProdukTerlaris->where('name', 'like', '%' . $request->input('produk_search') . '%');
        }
        if ($request->filled('produk_sort')) {
            $queryProdukTerlaris->orderBy($request->input('produk_sort'), $request->boolean('produk_desc') ? 'desc' : 'asc');
        } else {
            $queryProdukTerlaris->orderByDesc('sold');
        }

        $produkTerlaris = $queryProdukTerlaris->paginate($request->input('produk_per_page', 5), ['*'], 'produk_page')->withQueryString();

        // ===============================
        // 5. Chart Pemasukan
        // ===============================
        [$pemasukanPerHari, $chartCategoriesPemasukan] = $this->getDailySums('orders', 'time', $dariTanggal, $sampaiTanggal, fn($query) => $query->where('shipping_status', 'selesai'));

        // ===============================
        // 6. Chart Pengeluaran
        // ===============================
        [$pengeluaranPerHari, $chartCategoriesPengeluaran] = $this->getDailySums('expenses', 'date', $dariTanggal, $sampaiTanggal);

        // ===============================
        // 7. Stok Produk
        // ===============================
        $queryStokProduk = Product::query();
        if ($request->filled('stok_search')) {
            $queryStokProduk->where('name', 'like', '%' . $request->input('stok_search') . '%');
        }
        if ($request->filled('stok_sort')) {
            $queryStokProduk->orderBy($request->input('stok_sort'), $request->boolean('stok_desc') ? 'desc' : 'asc');
        } else {
            $queryStokProduk->latest();
        }

        $products = $queryStokProduk->paginate($request->input('stok_per_page', 5))->withQueryString();

        return view('admin.dashboard.index', compact('products', 'jumlahPesanan', 'pemasukan', 'pengeluaran', 'labaBersih', 'produkTerlaris', 'pemasukanPerHari', 'pengeluaranPerHari', 'chartCategoriesPemasukan', 'chartCategoriesPengeluaran', 'dariTanggal', 'sampaiTanggal', 'labelPeriode'));
    }

    /**
     * Resolve Range ke Tanggal
     */
    private function resolveRangeDates(int $range): array
    {
        if ($range === 0) {
            $start = Carbon::today()->startOfDay();
            $end = Carbon::today()->endOfDay();
        } elseif ($range === 1) {
            $start = Carbon::yesterday()->startOfDay();
            $end = Carbon::yesterday()->endOfDay();
        } elseif ($range === 7) {
            $start = Carbon::today()->subDays(6)->startOfDay();
            $end = Carbon::today()->endOfDay();
        } elseif ($range === 30) {
            $start = Carbon::today()->subMonth()->startOfDay();
            $end = Carbon::today()->endOfDay();
        } elseif ($range === 90) {
            $start = Carbon::today()->subMonths(3)->startOfDay();
            $end = Carbon::today()->endOfDay();
        } else {
            $start = Carbon::today()->startOfDay();
            $end = Carbon::today()->endOfDay();
        }

        return [$start, $end];
    }

    /**
     * Daily Sums
     */
    private function getDailySums(string $table, string $dateColumn, Carbon $startDate, Carbon $endDate, $extraQuery = null): array
    {
        $sums = [];
        $labels = [];

        $cursor = $startDate->copy();
        while ($cursor->lte($endDate)) {
            $startOfDay = $cursor->copy()->startOfDay();
            $endOfDay = $cursor->copy()->endOfDay();

            $query = DB::table($table);
            if ($extraQuery) {
                $extraQuery($query);
            }
            $query->whereBetween($dateColumn, [$startOfDay, $endOfDay]);

            $sum = (int) $query->sum('total');

            $sums[] = $sum;
            $labels[] = $cursor->translatedFormat('d M');

            $cursor->addDay();
        }

        return [$sums, $labels];
    }
}
