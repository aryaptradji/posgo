<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use App\Models\District;
use App\Models\SubDistrict;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class ProfileController extends Controller
{
    public function indexAccount()
    {
        return view('profile.account.index');
    }

    public function indexAddress(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $city = $user->address->neighborhood->subDistrict->district->city;
        $citySlug = $city->slug;
        $district = $user->address->neighborhood->subDistrict->district;
        $districtSlug = $district->slug;
        $subDistrictSlug = $user->address->neighborhood->subDistrict->slug;

        $cities = City::select('name', 'slug')->get();
        $districts = $city->districts()->select('name', 'slug')->get();
        $subDistricts = $district->subDistricts()->select('name', 'slug')->get();

        return view('profile.address.index', [
            'cities' => $cities,
            'districts' => $districts,
            'subDistricts' => $subDistricts,
            'citySlug' => $citySlug,
            'districtSlug' => $districtSlug,
            'subDistrictSlug' => $subDistrictSlug,
        ]);
    }

    public function updatePhoto(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $imageData = $request->input('profile');
        $storageFolder = 'customers';
        $maxFileSizeMB = 2;

        $oldPhotoPathInDb = $user->photo;
        $oldPhotoFullPath = null;

        if ($oldPhotoPathInDb) {
            if (Storage::disk('public')->exists($oldPhotoPathInDb)) {
                $oldPhotoFullPath = $oldPhotoPathInDb;
            }
        }

        if ($imageData && $imageData !== '') {
            $request->validate([
                'profile' => 'string',
            ]);

            $data = explode(';base64,', $imageData);
            if (count($data) < 2) {
                return back()->withErrors(['photo' => 'Format data gambar tidak valid.']);
            }

            $base64Image = $data[1];
            $imageType = explode('/', $data[0]);
            $type = strtolower(end($imageType));

            if (!in_array($type, ['jpeg', 'jpg', 'png'])) {
                return back()->with('error', 'Format foto harus JPG/JPEG dan PNG');
            }

            $decodedImage = base64_decode($base64Image);

            if ($decodedImage === false) {
                return back()->with('error', 'Gagal mendekode gambar base64');
            }

            $fileSizeBytes = strlen($decodedImage); // ukuran dalam bytes
            $maxSizeBytes = $maxFileSizeMB * 1024 * 1024;

            if ($fileSizeBytes > $maxSizeBytes) {
                return back()->with('error', 'Ukuran file foto maksimal ' . $maxFileSizeMB . ' mb');
            }

            // Buat nama file unik dengan folder yang baru
            $newFileName = $storageFolder . '/' . $user->id . '_' . Str::random(10) . '.' . $type;

            // Simpan gambar baru
            Storage::disk('public')->put($newFileName, $decodedImage);

            // Hapus foto lama jika berbeda dengan yang baru dan memang ada
            if ($oldPhotoFullPath && $oldPhotoFullPath !== $newFileName && Storage::disk('public')->exists($oldPhotoFullPath)) {
                Storage::disk('public')->delete($oldPhotoFullPath);
            }

            $user->update(['photo' => $newFileName]);
            $message = 'Foto profil berhasil diperbarui!';
        } else {
            if ($oldPhotoFullPath && Storage::disk('public')->exists($oldPhotoFullPath)) {
                Storage::disk('public')->delete($oldPhotoFullPath);
            }

            // Kosongkan kolom 'photo' di database
            $user->update(['photo' => null]);
            $message = 'Foto profil berhasil dihapus!';
        }

        return back()->with('success', $message);
    }

    public function updateName(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'customer') {
            $rules = ['required', 'string', 'regex:/^[a-zA-Z]+[a-zA-Z.\s]*$/', Rule::unique('users')->where(fn($q) => $q->where('role', 'customer')), 'max:50'];
        } else {
            $rules = ['required', 'string', 'regex:/^[a-zA-Z]+[a-zA-Z.\s]*$/', Rule::unique('users')->where(fn($q) => $q->where('role', 'cashier')), 'max:50'];
        }
        $validated = $request->validate(
            [
                'name' => $rules,
            ],
            [
                'name.required' => 'Nama wajib diisi',
                'name.regex' => 'Format nama masih salah',
                'name.unique' => 'Nama ini sudah terdaftar',
                'name.max' => 'Nama maksimal 50 huruf',
            ],
        );

        $user->name = $validated['name'];
        $user->save();

        return back()->with('success', 'Nama akun berhasil diperbarui!');
    }

    public function updatePhone(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate(
            [
                'phone' => 'required|string|regex:/^08[0-9]{8,13}$/',
            ],
            [
                'phone.required' => 'Nomor telepon wajib diisi',
                'phone.regex' => 'Format nomor telepon tidak valid',
            ],
        );

        $user->phone_number = $validated['phone'];
        $user->save();

        return back()->with('success', 'Nomor telepon akun berhasil diperbarui!');
    }

    public function updateEmail(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->role === 'customer') {
            $rules = ['required', 'email', 'regex:/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', Rule::unique('users')->where(fn($q) => $q->where('role', 'customer'))];
        } else {
            $rules = ['required', 'email', 'regex:/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', Rule::unique('users')->where(fn($q) => $q->where('role', 'cashier'))];
        }

        $validated = $request->validate(
            [
                'email' => $rules,
            ],
            [
                'email.required' => 'Email wajib diisi',
                'email.regex' => 'Format email tidak valid',
                'email.unique' => 'Email ini sudah terdaftar',
            ],
        );

        $user->email = $validated['email'];
        $user->save();

        return back()->with('success', 'Email akun berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate(
            [
                'old_password' => 'required',
                'new_password' => 'required|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*._]).{8,}$/',
                'confirm_password' => 'required',
            ],
            [
                'old_password.required' => 'Password lama wajib diisi',
                'new_password.required' => 'Password baru wajib diisi',
                'new_password.regex' => 'Format password tidak valid',
                'confirm_password.required' => 'Konfirmasi password wajib diisi',
            ],
        );

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $oldPassword = $validated['old_password'];
        $newPassword = $validated['new_password'];
        $confirmPassword = $validated['confirm_password'];

        // Cek password lama
        if (!Hash::check($oldPassword, $user->password)) {
            return back()
                ->withErrors(['old_password' => 'Password lama tidak cocok'])
                ->withInput();
        }

        // Konfirmasi password
        if ($confirmPassword !== $newPassword) {
            return back()
                ->withErrors(['confirm_password' => 'Isi konfirmasi password harus sama'])
                ->withInput();
        }

        // Konfirmasi password baru harus beda
        if (Hash::check($newPassword, $user->password)) {
            return back()
                ->withErrors(['new_password' => 'Gunakan password baru yang belum pernah dipakai'])
                ->withInput();
        }

        // Update password user
        $user->update(['password' => Hash::make($newPassword)]);

        // Logout
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', ['Password berhasil diperbarui!', 'Silahkan login dengan password baru!']);
    }

    public function editAddress(Request $request, User $user)
    {
        $user = $user->load('address.neighborhood.subDistrict.district.city');
        $citySlug = $request->query('city') ?? $user->address->neighborhood->subDistrict->district->city->slug;
        $districtSlug = $request->query('city') ? $request->query('district') : $request->query('district') ?? $user->address->neighborhood->subDistrict->district->slug;

        $subDistrictSlug = $request->query('city') ? $request->query('sub_district') : $request->query('sub_district') ?? $user->address->neighborhood->subDistrict->slug;

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

        return view('profile.address.edit', [
            'cities' => $cities,
            'districts' => $districts,
            'subDistricts' => $subDistricts,
            'citySlug' => $citySlug,
            'districtSlug' => $districtSlug,
            'subDistrictSlug' => $subDistrictSlug,
            'user' => $user,
        ]);
    }

    public function updateAddress(Request $request, User $user)
    {
        $validated = $request->validate(
            [
                'address' => 'required|string|max:75',
                'rt' => 'required|string|regex:/^[0-9]{3}$/',
                'rw' => 'required|string|regex:/^[0-9]{3}$/',
                'postal_code' => 'required|string|regex:/^[0-9]{5}$/',
                'city' => 'required|exists:cities,slug',
                'district' => 'required|exists:districts,slug',
                'sub_district' => 'required|exists:sub_districts,slug',
            ],
            [
                'address.required' => 'Alamat wajib diisi',
                'address.max' => 'Alamat maksimal 75 huruf',
                'rt.required' => 'Nomor RT wajib diisi',
                'rt.regex' => 'Format nomor RT tidak valid',
                'rw.required' => 'Nomor RW wajib diisi',
                'rw.regex' => 'Format nomor RW tidak valid',
                'postal_code.required' => 'Kode pos wajib diisi',
                'postal_code.regex' => 'Format kode pos tidak valid',
                'city.required' => 'Kota wajib diisi',
                'district.required' => 'Kecamatan wajib diisi',
                'sub_district.required' => 'Kelurahan wajib diisi',
            ],
        );

        $user = $user->load('address.neighborhood.subDistrict.district.city');

        // Update alamat
        $user->address()->update([
            'street' => $validated['address'],
        ]);

        // Cari ID sub_district berdasarkan slug
        $subDistrict = SubDistrict::where('slug', $validated['sub_district'])->first();

        // Update neighborhood
        $user->address->neighborhood()->update([
            'rt' => $validated['rt'],
            'rw' => $validated['rw'],
            'postal_code' => $validated['postal_code'],
            'sub_district_id' => $subDistrict->id,
        ]);

        return redirect()->route('profile.address')->with('success', 'Alamat akun berhasil diperbarui!');
    }
}
