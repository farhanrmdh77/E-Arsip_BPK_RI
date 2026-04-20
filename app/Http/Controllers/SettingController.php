<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $appName = Setting::getAppName();
        $appLogo = Setting::getAppLogo();
        return view('settings.index', compact('appName', 'appLogo'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048', // Maks 2MB
        ]);

        // Simpan atau Update Nama Aplikasi
        Setting::updateOrCreate(
            ['key' => 'app_name'],
            ['value' => $request->app_name]
        );

        // Jika ada logo baru yang diunggah
        if ($request->hasFile('app_logo')) {
            $file = $request->file('app_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            
            // JURUS PAMUNGKAS: Pindahkan file langsung ke folder public/uploads/logo
            // Ini akan mengabaikan sistem storage:link yang sering bermasalah di lokal
            $file->move(public_path('uploads/logo'), $filename);
            
            // Update database dengan jalur yang langsung mengarah ke folder public
            Setting::updateOrCreate(
                ['key' => 'app_logo'],
                ['value' => 'uploads/logo/' . $filename]
            );
        }

        // Catat di Log
        ActivityLog::create([
            'user_id' => auth()->user()->id,
            'activity' => 'Ubah Pengaturan',
            'description' => 'Mengubah nama atau logo instansi menjadi: ' . $request->app_name,
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Profil instansi berhasil diperbarui!');
    }

    /**
     * FITUR LEVEL DEWA: Backup database menjadi file .sql
     */
    public function backupDatabase(Request $request)
    {
        // 1. Ambil kredensial database dari config (otomatis dari .env)
        $dbHost = config('database.connections.mysql.host');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // 2. Tentukan nama file backup yang unik
        $fileName = "Backup_" . $dbName . "_" . date('Y-m-d_H-i-s') . ".sql";
        
        // 3. Siapkan perintah mysqldump (Kompatibel dengan Laragon)
        // Jika password kosong (default Laragon), perintah tidak menggunakan flag -p
        $passwordCommand = $dbPass ? "-p" . $dbPass : "";
        $command = "mysqldump --user={$dbUser} {$passwordCommand} --host={$dbHost} {$dbName}";

        // 4. Eksekusi perintah
        $output = [];
        $returnVar = null;
        exec($command, $output, $returnVar);

        // 5. Cek keberhasilan dan kirim file ke browser
        if ($returnVar === 0) {
            $sqlContent = implode("\n", $output);

            // Catat ke Log Aktivitas bahwa Admin melakukan pengamanan data
            ActivityLog::create([
                'user_id' => auth()->user()->id,
                'activity' => 'Backup Database',
                'description' => 'Melakukan pengunduhan cadangan database sistem (.sql)',
                'ip_address' => $request->ip()
            ]);

            return response($sqlContent)
                ->header('Content-Type', 'application/sql')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        }

        return back()->with('error', 'Gagal melakukan backup. Pastikan layanan MySQL/Laragon aktif.');
    }
}