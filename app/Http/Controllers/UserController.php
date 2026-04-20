<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth; // Tambahkan ini untuk mengecek siapa yang sedang login

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        // Ambil semua data pengguna dari database (diurutkan dari yang terbaru)
        $users = User::latest()->get();
        return view('users.index', compact('users'));
    }

    /**
     * Menyimpan pengguna baru ke database.
     */
    public function store(Request $request)
    {
        // 🌟 PERBAIKAN: Menambahkan validasi untuk field Role
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,pegawai' 
        ], [
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'password.min' => 'Password minimal harus 6 karakter.'
        ]);

        // Buat akun baru lengkap dengan jabatannya
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => $request->role, // 🌟 FIELD ROLE SEKARANG DISIMPAN
        ]);

        return back()->with('success', 'Akun pengguna baru berhasil didaftarkan ke dalam sistem!');
    }

    /**
     * 🌟 FITUR BARU: Memperbarui data profil pengguna (Nama, Email, Role).
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi inputan form Edit
        // Perhatikan bagian email: unique:users,email,' . $id (artinya email boleh sama jika itu milik user ini sendiri)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id, 
            'role' => 'required|in:admin,pegawai'
        ], [
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
        ]);

        try {
            $user = User::findOrFail($id);

            // 🛡️ KEAMANAN LAPIS 3: Mencegah admin mengubah rolenya sendiri menjadi pegawai jika dia satu-satunya admin
            if ($user->role == 'admin' && $request->role == 'pegawai' && User::where('role', 'admin')->count() <= 1) {
                return back()->with('error', 'Gagal! Anda adalah satu-satunya Administrator. Tidak dapat mengubah level otoritas menjadi pegawai.');
            }

            // 2. Eksekusi pembaruan data
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]);

            return back()->with('success', 'Data profil <strong>' . $user->name . '</strong> berhasil diperbarui!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat memperbarui data pengguna.');
        }
    }

    /**
     * Menghapus akun pengguna.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // 🛡️ KEAMANAN LAPIS 1: Mencegah admin menghapus dirinya sendiri
        if (Auth::id() == $id) {
            return back()->with('error', 'Dilarang! Anda tidak dapat menghapus akun Anda sendiri saat sedang login.');
        }

        // 🛡️ KEAMANAN LAPIS 2: Mencegah sistem kehilangan seluruh akses Admin
        if (User::where('role', 'admin')->count() <= 1 && $user->role == 'admin') {
            return back()->with('error', 'Gagal! Sistem membutuhkan setidaknya 1 Administrator yang tersisa.');
        }

        $user->delete();

        return back()->with('success', 'Akun pegawai berhasil dihapus permanen.');
    }
    
    /**
     * Mereset password pengguna secara manual menggunakan form Modal.
     */
    public function resetPassword(Request $request, $id)
    {
        // Validasi inputan dari Modal Card Reset
        $request->validate([
            'new_password' => 'required|min:6'
        ], [
            'new_password.required' => 'Kata sandi baru tidak boleh kosong.',
            'new_password.min' => 'Kata sandi baru minimal harus 6 karakter.'
        ]);

        try {
            $user = User::findOrFail($id);
            
            // Eksekusi perubahan password dengan enkripsi yang aman
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return back()->with('success', 'Kata sandi untuk <strong>' . $user->name . '</strong> berhasil diubah!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem saat mereset password.');
        }
    }
}