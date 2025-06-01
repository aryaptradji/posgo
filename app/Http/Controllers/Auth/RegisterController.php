<?php

namespace App\Http\Controllers\Auth;

use App\Models\City;
use App\Models\District;
use App\Models\SubDistrict;
use App\Models\Neighborhood;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function index(Request $request)
    {
        $citySlug = $request->query('city');
        $districtSlug = $request->query('district');
        $subDistrictSlug = $request->query('sub_district');

        $cities = City::select('name', 'slug')->get();

        $districts = collect();
        $subDistricts = collect();

        if ($citySlug) {
            $city = City::where('slug', $citySlug)->first();
            if ($city) {
                $districts = $city->districts()->select('name', 'slug')->get();
            }
        }

        if ($districtSlug) {
            $district = District::where('slug', $districtSlug)->first();
            if ($district) {
                $subDistricts = $district->subDistricts()->select('name', 'slug')->get();
            }
        }

        return view('register', [
            'cities' => $cities,
            'districts' => $districts,
            'subDistricts' => $subDistricts,
            'citySlug' => $citySlug,
            'districtSlug' => $districtSlug,
            'subDistrictSlug' => $subDistrictSlug,
        ]);
    }
}
