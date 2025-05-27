<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\CustomerExport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with(['address.neighborhood.subDistrict.district.city'])->where('role', 'customer');

        // Pencarian
        if ($request->filled('search')) {
            $query
                ->join('addresses', 'addresses.id', '=', 'users.address_id')
                ->join('neighborhoods', 'neighborhoods.id', '=', 'addresses.neighborhood_id')
                ->join('sub_districts', 'sub_districts.id', '=', 'neighborhoods.sub_district_id')
                ->join('districts', 'districts.id', '=', 'sub_districts.district_id')
                ->join('cities', 'cities.id', '=', 'districts.city_id')
                ->where(function ($q) use ($request) {
                    $search = '%' . $request->search . '%';
                    $q->where('users.name', 'like', $search)->orWhere('users.email', 'like', $search)->orWhere('users.phone_number', 'like', $search)->orWhere('addresses.street', 'like', $search)->orWhere('neighborhoods.rt', 'like', $search)->orWhere('neighborhoods.rw', 'like', $search)->orWhere('sub_districts.name', 'like', $search)->orWhere('districts.name', 'like', $search)->orWhere('cities.name', 'like', $search);
                })
                ->select('users.*');
        }

        // Sorting
        if ($request->filled('sort') && $request->sort === 'address') {
            $query
                ->join('addresses', 'addresses.id', '=', 'users.address_id')
                ->orderBy('addresses.street', $request->boolean('desc') ? 'desc' : 'asc')
                ->select('users.*');
        } else {
            $query->orderBy($request->sort ?? 'created', $request->boolean('desc') ? 'desc' : 'asc');
        }

        // Pagination
        $perPage = $request->input('per_page', 5);
        $customers = $query->paginate($perPage)->withQueryString();

        return view('admin.customer.index', compact('customers'));
    }

    public function print()
    {
        $customers = User::with(['address.neighborhood.subDistrict.district.city'])
            ->where('role', 'customer')
            ->orderBy('created', 'desc')
            ->get();

        return view('admin.customer.print', compact('customers'));
    }

    public function export()
    {
        return Excel::download(new CustomerExport(), 'daftar_customer.xlsx');
    }
}
