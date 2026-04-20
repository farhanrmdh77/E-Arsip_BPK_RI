<?php

use App\Http\Controllers\ArsipController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TrashController; // 🌟 PANGGIL CONTROLLER BARU DI SINI
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem E-Arsip Digital
|--------------------------------------------------------------------------
*/

// --- RUTE LOGIN & LOGOUT (TIDAK DIKUNCI) ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// --- RUTE APLIKASI UTAMA (DIKUNCI OLEH MIDDLEWARE AUTH) ---
Route::middleware('auth')->group(function () {
    
    // ====================================================================
    // 🟢 RUTE UMUM (BISA DIAKSES ADMIN & PEGAWAI BIASA)
    // ====================================================================
    
    // 1. HALAMAN UTAMA (DASHBOARD)
    Route::get('/', [ArsipController::class, 'dashboard'])->name('arsip.dashboard');

    // 2. HALAMAN GUDANG FOLDER
    Route::get('/folders', [ArsipController::class, 'folders'])->name('arsip.folders');

    // 3. HALAMAN ISI FOLDER
    Route::get('/folder/{kode}', [ArsipController::class, 'folderIsi'])->name('arsip.folder.isi');

    // 4. DAFTAR SEMUA ARSIP
    Route::get('/arsip', [ArsipController::class, 'index'])->name('arsip.index');

    // 5. PENCARIAN GLOBAL
    Route::get('/pencarian-global', [ArsipController::class, 'globalSearch'])->name('arsip.global_search');

    // 6. INPUT MANUAL
    Route::get('/folder/{kode}/tambah', [ArsipController::class, 'create'])->name('arsip.create');
    Route::post('/arsip/store', [ArsipController::class, 'store'])->name('arsip.store');

    // 7. CETAK PDF & EXPORT EXCEL
    Route::get('/folder/{kode}/cetak-pdf', [ArsipController::class, 'cetakPDF'])->name('arsip.cetak_pdf');
    Route::get('/export-arsip', [ArsipController::class, 'export'])->name('arsip.export');

    // 8. UPDATE PROFIL (UBAH AVATAR)
    Route::put('/profile/update-avatar', [UserController::class, 'updateAvatar'])->name('profile.update_avatar');


    // ====================================================================
    // 🔴 RUTE SENSITIF (HANYA BISA DIAKSES ADMIN)
    // ====================================================================
    Route::middleware('can:admin')->group(function () {
        
        // 9. FITUR IMPORT EXCEL & UNDUH TEMPLATE
        Route::get('/arsip/download-template', [ArsipController::class, 'downloadTemplate'])->name('arsip.download_template');
        Route::get('/import-arsip', function () { return view('import'); })->name('arsip.import.form');
        Route::post('/import-arsip', [ArsipController::class, 'import'])->name('arsip.import');

        // 10. HAK EDIT & HAPUS (CRUD ARSIP)
        Route::delete('/arsip/bulk-delete', [ArsipController::class, 'bulkDelete'])->name('arsip.bulk_delete');
        
        Route::get('/arsip/{id}/edit', [ArsipController::class, 'edit'])->name('arsip.edit');
        Route::put('/arsip/{id}', [ArsipController::class, 'update'])->name('arsip.update');
        Route::delete('/arsip/{id}', [ArsipController::class, 'destroy'])->name('arsip.destroy');
        
        Route::delete('/arsip/{id}/hapus-file', [ArsipController::class, 'hapusFile'])->name('arsip.hapus_file');

        // 11. MANAJEMEN PENGGUNA
        Route::get('/pengguna', [UserController::class, 'index'])->name('users.index');
        Route::post('/pengguna', [UserController::class, 'store'])->name('users.store');
        Route::put('/pengguna/{id}', [UserController::class, 'update'])->name('users.update'); 
        Route::delete('/pengguna/{id}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::put('/pengguna/{id}/reset', [UserController::class, 'resetPassword'])->name('users.reset');

        // 12. LOG AKTIVITAS (AUDIT TRAIL)
        Route::get('/logs', function() { 
            $logs = \App\Models\ActivityLog::with('user')->latest()->get(); 
            return view('logs.index', compact('logs')); 
        })->name('logs.index');

        // =======================================================
        // 🌟 13. TONG SAMPAH (DIKELOLA OLEH TRASH CONTROLLER) 🌟
        // =======================================================
        Route::get('/tong-sampah', [TrashController::class, 'index'])->name('arsip.trash');
        
        // Aksi Arsip Terhapus
        Route::post('/tong-sampah/arsip/{id}/restore', [TrashController::class, 'restoreArsip'])->name('arsip.restore');
        Route::delete('/tong-sampah/arsip/{id}/force-delete', [TrashController::class, 'forceDeleteArsip'])->name('arsip.force_delete');

        // Aksi Folder Terhapus
        Route::post('/tong-sampah/folder/{id}/restore', [TrashController::class, 'restoreFolder'])->name('folders.restore');
        Route::delete('/tong-sampah/folder/{id}/force-delete', [TrashController::class, 'forceDeleteFolder'])->name('folders.force_delete');

        // 14. PENGATURAN APLIKASI (DYNAMIC BRANDING)
        Route::get('/pengaturan', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/pengaturan', [SettingController::class, 'update'])->name('settings.update');

        // 15. BACKUP DATABASE
        Route::get('/pengaturan/backup', [SettingController::class, 'backupDatabase'])->name('settings.backup');

        // 16. MANAJEMEN FOLDER DINAMIS (CRUD NORMAL)
        Route::post('/folders/store', [ArsipController::class, 'storeFolder'])->name('folders.store');
        Route::put('/folders/{id}', [ArsipController::class, 'updateFolder'])->name('folders.update');
        Route::delete('/folders/{id}', [ArsipController::class, 'destroyFolder'])->name('folders.destroy');
    });
});