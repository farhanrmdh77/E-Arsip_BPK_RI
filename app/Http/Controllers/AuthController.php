<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan form login
    public function showLogin()
    {
        // Jika sudah login, langsung lempar ke dashboard
        if (Auth::check()) {
            return redirect()->route('arsip.dashboard');
        }
        return view('auth.login');
    }

    // Memproses data login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Jika login berhasil
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('arsip.dashboard'); // Arahkan ke rute dashboard yang benar
        }

        // Jika login GAGAL (Email atau Password salah)
        // Menggunakan with('error') agar ditangkap oleh kotak merah di tampilan login
        return back()
            ->with('error', 'Akses Ditolak! Email atau Kata Sandi tidak cocok.')
            ->onlyInput('email'); // Email yang tadi diketik tetap tertahan di form
    }

    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}