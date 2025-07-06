<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function indexAccount()
    {
        return view('profile.account.index');
    }

    public function indexAddress()
    {
        return view('profile.address.index');
    }

    public function updatePhoto(Request $request, User $user)
    {
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
}
