<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ResetPasswordMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    public function emailForm()
    {
        return view('auth.password.email');
    }

    public function sendEmail(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|regex:/^[a-zA-Z0-9._]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|exists:users,email',
            ],
            [
                'email.required' => 'Email wajib diisi',
                'email.regex' => 'Format email tidak valid',
                'email.exists' => 'Email belum terdaftar',
            ],
        );

        $token = Str::random(64);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        Mail::to($request->email)->send(new ResetPasswordMail($token, $request->email));

        return back()->with('success', $request->email);
    }

    public function resetForm($token, Request $request)
    {
        $email = $request->query('email');

        // Validasi email di URL
        if (!$email) {
            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Link reset tidak ada email valid',
                ]);
        }

        // Cek apakah ada di tabel password_reset_tokens
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record) {
            return redirect()->route('password.email')->with('error', 'Link reset tidak valid atau sudah digunakan');
        }

        // Cek expired (60 menit)
        $expiresAt = Carbon::parse($record->created_at)->addMinutes(60);

        if (now()->greaterThan($expiresAt)) {
            return redirect()->route('password.email')->with('error', 'Link reset sudah kadaluwarsa');
        }

        // Cek token cocok
        if (!Hash::check($token, $record->token)) {
            return redirect()->route('password.email')->with('error', 'Link reset tidak valid');
        }

        return view('auth.password.reset', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate(
            [
                'new_password' => 'required|regex:/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*._]).{8,}$/',
                'token' => 'required',
                'email' => 'required|email|exists:users,email',
            ],
            [
                'new_password.required' => 'Password wajib diisi',
                'new_password.regex' => 'Format password tidak valid',
                'email.required' => 'Email wajib diisi',
                'email.exists' => 'Email belum terdaftar',
            ],
        );

        $email = $request->email;
        $token = $request->token;
        $newPassword = $request->new_password;
        $confirmPassword = $request->confirm_password;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.email')->with('error', 'User tidak ditemukan');
        }

        // Konfirmasi password
        if ($confirmPassword !== $newPassword) {
            return back()->withErrors(['confirm_password' => 'Isi konfirmasi password harus sama'])->withInput();
        }

        // Konfirmasi password baru harus beda
        if (Hash::check($newPassword, $user->password)) {
            return back()->withErrors(['new_password' => 'Gunakan password baru yang belum pernah dipakai'])->withInput();
        }

        // Ambil record token
        $record = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!$record) {
            return redirect()->route('password.email')->with('error', 'Halaman ubah password tidak valid atau sudah digunakan');
        }

        // Expired check
        $expiresAt = Carbon::parse($record->created_at)->addMinutes(60);
        if (now()->greaterThan($expiresAt)) {
            return redirect()->route('password.email')->with('error', 'Halaman ubah password sudah kadaluwarsa');
        }

        // Token check
        if (!Hash::check($token, $record->token)) {
            return redirect()->route('password.email')->with('error', 'Halaman ubah password tidak valid.');
        }

        // Update password user
        $user->update(['password' => Hash::make($newPassword)]);

        // Hapus token
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Redirect sukses
        return redirect()->route('login')->with('success', "Password berhasil diubah!\nSilakan login dengan password baru");
    }
}
