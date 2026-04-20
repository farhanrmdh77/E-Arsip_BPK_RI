<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ArsipImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Arsip;
use App\Models\User; 
use App\Models\Folder; 
use App\Models\ActivityLog; 
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ArsipController extends Controller
{
    public function dashboard()
    {
        $totalBerkas = Folder::count(); 
        $totalArsip = Arsip::count(); 
        $totalUsers = User::count();
        $berkasHariIni = Arsip::whereDate('created_at', Carbon::today())->count();

        $statistikKP = Arsip::select(DB::raw("SUBSTRING_INDEX(kode_arsip, '.', 2) as kode_arsip"), DB::raw('count(*) as total'))
                            ->groupBy(DB::raw("SUBSTRING_INDEX(kode_arsip, '.', 2)"))
                            ->orderBy('kode_arsip', 'asc')
                            ->get();

        $recentActivities = ActivityLog::with('user')->latest()->limit(5)->get();
        $lastUpdateGlobal = ActivityLog::latest()->first()?->created_at?->diffForHumans() ?? 'Belum ada aktivitas';

        // =====================================================================
        // 🔥 FITUR 1: MENGHITUNG KAPASITAS HARDISK SERVER ASLI 🔥
        // =====================================================================
        $path = base_path(); 
        
        $totalBytes = disk_total_space($path);
        $freeBytes = disk_free_space($path);
        $usedBytes = $totalBytes - $freeBytes;

        $diskTotal = round($totalBytes / 1073741824, 2);
        $diskUsed = round($usedBytes / 1073741824, 2);
        $diskPercentage = ($totalBytes > 0) ? round(($usedBytes / $totalBytes) * 100) : 0;

        // =====================================================================
        // 🛡️ FITUR 2: SENSOR KESEHATAN SISTEM (SYSTEM HEALTH) 🛡️
        // =====================================================================
        $isEncrypted = !empty(config('app.key')) ? 'AKTIF' : 'RENTAN';
        $encDot = $isEncrypted == 'AKTIF' ? 'dot-green' : 'dot-red';

        $start_time = microtime(true);
        try {
            DB::select('SELECT 1'); 
            $end_time = microtime(true);
            $dbLatency = round(($end_time - $start_time) * 1000); 
            $dbStatus = $dbLatency < 50 ? 'STABIL' : 'LAMBAT';
            $dbDot = $dbLatency < 50 ? 'dot-green' : 'dot-amber';
        } catch (\Exception $e) {
            $dbLatency = 0;
            $dbStatus = 'TERPUTUS';
            $dbDot = 'dot-red';
        }

        if (!Storage::disk('local')->exists('backups')) {
            Storage::disk('local')->makeDirectory('backups');
        }
        
        $backupFiles = Storage::disk('local')->files('backups');
        if (count($backupFiles) > 0) {
            $latestTime = 0;
            foreach ($backupFiles as $file) {
                $time = Storage::disk('local')->lastModified($file);
                if ($time > $latestTime) { $latestTime = $time; }
            }
            $lastBackupTime = strtoupper(Carbon::createFromTimestamp($latestTime)->diffForHumans());
            $backupDot = 'dot-green';
        } else {
            $lastBackupTime = 'BELUM ADA BACKUP';
            $backupDot = 'dot-amber';
        }

        $docPath = storage_path('app/public/dokumen_arsip');
        if (!File::exists($docPath)) {
            File::makeDirectory($docPath, 0755, true); 
        }
        
        if (File::exists($docPath) && is_writable($docPath)) {
            $storageHealth = 'AMAN';
            $storageDot = 'dot-green';
        } else {
            $storageHealth = 'TERKUNCI / ERROR';
            $storageDot = 'dot-red';
        }

        return view('arsip.dashboard', compact(
            'totalBerkas', 'berkasHariIni', 'totalArsip', 'totalUsers', 
            'statistikKP', 'recentActivities', 'lastUpdateGlobal',
            'diskTotal', 'diskUsed', 'diskPercentage',
            'isEncrypted', 'encDot', 'dbLatency', 'dbStatus', 'dbDot', 
            'lastBackupTime', 'backupDot', 'storageHealth', 'storageDot'
        ));
    }

    public function folders()
    {
        $dbFolders = Folder::orderBy('kode_folder', 'asc')->get();
        $folders = [];

        foreach ($dbFolders as $f) {
            $kode = strtoupper(trim($f->kode_folder));
            
            $totalArsip = Arsip::where('kode_arsip', 'LIKE', $kode . '%')->count();
            $lastUpdateDate = Arsip::where('kode_arsip', 'LIKE', $kode . '%')->max('created_at');

            $urlToScan = route('arsip.folder.isi', $kode);
            $qrCodeUrl = "https://quickchart.io/qr?size=150&text=" . urlencode($urlToScan);
            
            $updateTime = $lastUpdateDate 
                          ? Carbon::parse($lastUpdateDate)->diffForHumans() 
                          : 'Belum ada aktivitas';

            $folders[] = [
                'id'     => $f->id,
                'kode'   => $kode,
                'nama'   => $f->nama_folder,
                'qr_url' => $qrCodeUrl,
                'update' => $updateTime,
                'total'  => $totalArsip
            ];
        }
        
        return view('arsip.folders', compact('folders'));
    }

    public function storeFolder(Request $request)
    {
        $request->validate([
            'kode_folder' => ['required', 'regex:/^[a-zA-Z0-9.\-_ ]+$/', 'unique:folders,kode_folder', 'max:50'],
            'nama_folder' => 'nullable|string|max:255'
        ], [
            'kode_folder.regex' => 'Kode klasifikasi hanya boleh berisi huruf, angka, titik (.), strip (-), garis bawah (_), dan SPASI.'
        ]);

        $kodeBaru = strtoupper(trim($request->kode_folder));

        Folder::create([
            'kode_folder' => $kodeBaru,
            'nama_folder' => $request->nama_folder ?? 'Folder Tanpa Nama'
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Tambah Folder',
            'description' => 'Membuat folder kategori baru: ' . $kodeBaru,
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Folder <strong>' . $kodeBaru . '</strong> berhasil dibuat!');
    }

    public function updateFolder(Request $request, $id)
    {
        $request->validate([
            'kode_folder' => ['required', 'regex:/^[a-zA-Z0-9.\-_ ]+$/', 'unique:folders,kode_folder,' . $id, 'max:50'],
            'nama_folder' => 'nullable|string|max:255'
        ], [
            'kode_folder.regex' => 'Kode klasifikasi hanya boleh berisi huruf, angka, titik (.), strip (-), garis bawah (_), dan SPASI.'
        ]);

        $folder = Folder::findOrFail($id);
        $oldKode = $folder->kode_folder;
        $kodeBaru = strtoupper(trim($request->kode_folder));

        $folder->update([
            'kode_folder' => $kodeBaru,
            'nama_folder' => $request->nama_folder
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Edit Folder',
            'description' => "Mengubah folder $oldKode menjadi " . $kodeBaru,
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', 'Data folder berhasil diperbarui!');
    }

    public function destroyFolder(Request $request, $id)
    {
        $folder = Folder::findOrFail($id);
        $kode = $folder->kode_folder;

        Arsip::where('kode_arsip', 'LIKE', $kode . '%')->delete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Hapus Folder',
            'description' => 'Memindahkan folder beserta isinya ke tong sampah: ' . $kode,
            'ip_address' => $request->ip()
        ]);

        $folder->delete();

        return back()->with('success', 'Folder <strong>' . $kode . '</strong> beserta seluruh isinya berhasil dipindahkan ke Tong Sampah!');
    }

    public function trash()
    {
        $arsips = Arsip::onlyTrashed()->latest()->get();
        $folders = Folder::onlyTrashed()->latest()->get(); 

        return view('arsip.trash', compact('arsips', 'folders'));
    }

    public function restoreFolder($id)
    {
        $folder = Folder::onlyTrashed()->findOrFail($id);
        $kode = $folder->kode_folder;

        Arsip::onlyTrashed()->where('kode_arsip', 'LIKE', $kode . '%')->restore();

        $folder->restore();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Pulihkan Folder',
            'description' => "Memulihkan kategori folder beserta isinya: $kode",
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', "Folder <strong>$kode</strong> beserta isinya berhasil dipulihkan dari kelola sampah!");
    }

    public function forceDeleteFolder($id)
    {
        $folder = Folder::onlyTrashed()->findOrFail($id);
        $kode = $folder->kode_folder;

        $arsips = Arsip::withTrashed()->where('kode_arsip', 'LIKE', $kode . '%')->get();

        foreach ($arsips as $arsip) {
            if ($arsip->file_dokumen) {
                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($arsip->file_dokumen)) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($arsip->file_dokumen);
                } else if (file_exists(public_path('storage/' . $arsip->file_dokumen))) {
                    unlink(public_path('storage/' . $arsip->file_dokumen));
                }
            }
            $arsip->forceDelete();
        }

        $folder->forceDelete();

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Hapus Permanen Folder',
            'description' => "Memusnahkan folder $kode beserta " . $arsips->count() . " arsip fisik di dalamnya.",
            'ip_address' => request()->ip()
        ]);

        return back()->with('success', "Folder <strong>$kode</strong> beserta seluruh dokumen di dalamnya berhasil dimusnahkan secara permanen!");
    }
    
    public function folderIsi(Request $request, $kode)
    {
        $kodeSearch = strtoupper(trim($kode));
        $folder = Folder::where(DB::raw('UPPER(TRIM(kode_folder))'), $kodeSearch)->firstOrFail();

        $query = Arsip::where('kode_arsip', 'LIKE', $kodeSearch . '%');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_berkas', 'LIKE', '%' . $search . '%')
                  ->orWhere('deskripsi_berkas', 'LIKE', '%' . $search . '%')
                  ->orWhere('tahun_berkas', 'LIKE', '%' . $search . '%');
            });
        }
        
        // 🔥 LOGIKA MATEMATIKA JRA (CERDAS & DINAMIS) 🔥
        if ($request->has('filter_retensi') && $request->filter_retensi != 'semua') {
            $currentYear = (int)date('Y');
            
            if ($request->filter_retensi == 'aktif') {
                $query->whereRaw('(COALESCE(tahun_sistem, ?) + COALESCE(retensi_aktif, 0)) >= ?', [$currentYear, $currentYear]);
            } elseif ($request->filter_retensi == 'inaktif') {
                $query->whereRaw('(COALESCE(tahun_sistem, ?) + COALESCE(retensi_aktif, 0)) < ?', [$currentYear, $currentYear]);
            }
        }

        // 🔥 LOGIKA FILTER STATUS FILE 🔥
        if ($request->filled('filter_file') && $request->filter_file != 'semua') {
            if ($request->filter_file == 'ada') {
                $query->whereNotNull('file_dokumen');
            } elseif ($request->filter_file == 'kosong') {
                $query->whereNull('file_dokumen');
            }
        }

        // 🔥 LOGIKA FILTER LOKASI FISIK 🔥
        if ($request->filled('filter_lokasi') && $request->filter_lokasi != 'semua') {
            if ($request->filter_lokasi == 'Internal') {
                // Biasanya data lama yang belum diisi statusnya kita anggap masih di Internal
                $query->where(function($q) {
                    $q->where('status_lokasi', 'Internal')
                      ->orWhereNull('status_lokasi'); 
                });
            } elseif ($request->filter_lokasi == 'Bagian Umum') {
                $query->where('status_lokasi', 'Bagian Umum');
            }
        }
        
        $perPage = $request->get('per_page', 10); 
        $arsips = $query->latest()->paginate($perPage)->withQueryString();
        
        return view('arsip.folder_isi', compact('arsips', 'kode', 'folder', 'perPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_berkas' => 'required', 
            'kode_arsip' => 'required', 
            'tahun_berkas' => 'required', 
            'file_dokumen' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);
        
        $data = $request->all();
        $data['user_id'] = auth()->id(); 
        $data['kode_arsip'] = strtoupper(trim($request->kode_arsip)); 

        preg_match('/\d{4}/', $request->tahun_berkas, $matches);
        $data['tahun_sistem'] = !empty($matches) ? (int)$matches[0] : (int)date('Y');
        
        if ($request->hasFile('file_dokumen')) {
            $file = $request->file('file_dokumen');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('dokumen_arsip', $fileName, 'public');
            $data['file_dokumen'] = $path;
        }
        
        Arsip::create($data);
        
        ActivityLog::create([
            'user_id' => auth()->id(), 
            'activity' => 'Tambah Berkas', 
            'description' => 'Menambah berkas manual: ' . $request->nama_berkas, 
            'ip_address' => $request->ip()
        ]);
        
        // 🔥 PERBAIKAN LOGIKA FOLDER INDUK ANTI-BUG (.00) 🔥
        $kodeArsip = $data['kode_arsip'];
        if (strpos($kodeArsip, '.') !== false) {
            $folderIndukArr = explode('.', $kodeArsip);
            $kodeInduk = $folderIndukArr[0] . '.' . ($folderIndukArr[1] ?? '00');
        } else {
            $kodeInduk = $kodeArsip;
        }

        return redirect()->route('arsip.folder.isi', $kodeInduk)->with('success', 'Berkas detail baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_berkas' => 'required|string|max:255', 
            'kode_arsip' => 'required', 
            'tahun_berkas' => 'required|string|max:255', 
            'file_dokumen' => 'nullable|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);
        
        $arsip = Arsip::findOrFail($id);
        $data = $request->all();
        $data['kode_arsip'] = strtoupper(trim($request->kode_arsip));

        preg_match('/\d{4}/', $request->tahun_berkas, $matches);
        $data['tahun_sistem'] = !empty($matches) ? (int)$matches[0] : (int)date('Y');

        if ($request->hasFile('file_dokumen')) {
            if ($arsip->file_dokumen && Storage::disk('public')->exists($arsip->file_dokumen)) {
                Storage::disk('public')->delete($arsip->file_dokumen);
            }
            
            $file = $request->file('file_dokumen');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('dokumen_arsip', $fileName, 'public');
            $data['file_dokumen'] = $path; 
        }
        
        $arsip->update($data);
        
        ActivityLog::create([
            'user_id' => auth()->id(), 
            'activity' => 'Edit Berkas', 
            'description' => 'Memperbarui berkas: ' . $arsip->nama_berkas, 
            'ip_address' => $request->ip()
        ]);
        
        // 🔥 PERBAIKAN LOGIKA FOLDER INDUK ANTI-BUG (.00) 🔥
        $kodeArsip = $data['kode_arsip'];
        if (strpos($kodeArsip, '.') !== false) {
            $folderIndukArr = explode('.', $kodeArsip);
            $kodeInduk = $folderIndukArr[0] . '.' . ($folderIndukArr[1] ?? '00');
        } else {
            $kodeInduk = $kodeArsip;
        }
        
        return redirect()->route('arsip.folder.isi', $kodeInduk)->with('success', 'Data arsip <strong>' . $arsip->nama_berkas . '</strong> berhasil diperbarui!');
    }

    public function import(Request $request) 
    {
        // 🔥 PERBAIKAN: TANGKAP ERROR VALIDASI FILE SECARA EKSPLISIT AGAR TIDAK SILENT FAIL 🔥
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:20480'
        ]);

        if ($validator->fails()) {
            return back()->with('error', 'Gagal Upload: Pastikan format file adalah Excel (.xlsx / .csv) dan pastikan ukurannya tidak terlalu besar.');
        }

        try {
            Excel::import(new ArsipImport, $request->file('file'));
            
            ActivityLog::create([
                'user_id' => auth()->id(), 
                'activity' => 'Import Excel', 
                'description' => 'Melakukan import data rekapitulasi E-Arsip.', 
                'ip_address' => $request->ip()
            ]);
            
            return redirect()->route('arsip.folders')->with('success', 'Data Excel berhasil diimport dan disinkronkan ke folder!');
            
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
             // Jika error berasal dari aturan format internal library Excel
             $failures = $e->failures();
             $msg = "Terjadi kesalahan di baris " . $failures[0]->row() . ".";
             return back()->with('error', 'Gagal memproses: ' . $msg);
        } catch (\Exception $e) {
            // Jika error berasal dari satpam ArsipImport (Exception custom kita)
            return back()->with('error', 'Gagal memproses file Excel: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $arsip = Arsip::findOrFail($id);
        return view('arsip.edit', compact('arsip'));
    }

    public function destroy(Request $request, $id)
    {
        $arsip = Arsip::findOrFail($id);
        $namaBerkas = $arsip->nama_berkas; 
        
        $arsip->delete();
        
        ActivityLog::create([
            'user_id' => auth()->id(), 
            'activity' => 'Buang Berkas', 
            'description' => 'Membuang berkas: ' . $namaBerkas, 
            'ip_address' => $request->ip()
        ]);
        
        return back()->with('success', 'Berkas <strong>' . $namaBerkas . '</strong> dipindahkan ke Tong Sampah!');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        
        if (!$ids || count($ids) == 0) {
            return back()->with('error', 'Tidak ada dokumen yang dipilih untuk dihapus.');
        }

        $arsips = Arsip::whereIn('id', $ids)->get();
        $count = $arsips->count();

        foreach ($arsips as $arsip) {
            $arsip->delete();
        }

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Hapus Massal',
            'description' => "Memindahkan $count dokumen ke tong sampah secara bersamaan.",
            'ip_address' => $request->ip()
        ]);

        return back()->with('success', "Luar biasa! <strong>$count</strong> berkas berhasil dipindahkan ke Tong Sampah.");
    }

    public function hapusFile(Request $request, $id)
    {
        $arsip = Arsip::findOrFail($id);
        
        // 🔥 PERBAIKAN LOGIKA FOLDER INDUK ANTI-BUG (.00) 🔥
        $kodeArsip = $arsip->kode_arsip;
        if (strpos($kodeArsip, '.') !== false) {
            $folderIndukArr = explode('.', $kodeArsip);
            $kodeInduk = $folderIndukArr[0] . '.' . ($folderIndukArr[1] ?? '00');
        } else {
            $kodeInduk = $kodeArsip;
        }
        
        if ($arsip->file_dokumen) {
            $isDeleted = false;

            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($arsip->file_dokumen)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($arsip->file_dokumen);
                $isDeleted = true;
            } 
            else if (file_exists(public_path('storage/' . $arsip->file_dokumen))) {
                unlink(public_path('storage/' . $arsip->file_dokumen));
                $isDeleted = true;
            }

            if ($isDeleted || !$isDeleted) {
                $arsip->update(['file_dokumen' => null]);
                
                ActivityLog::create([
                    'user_id' => auth()->id(), 
                    'activity' => 'Hapus File Fisik', 
                    'description' => 'Menghapus dokumen fisik: ' . $arsip->nama_berkas, 
                    'ip_address' => $request->ip()
                ]);
                
                return redirect()->route('arsip.folder.isi', $kodeInduk)->with('success', 'File fisik berhasil dihapus sepenuhnya!');
            }
        }
        
        return redirect()->route('arsip.folder.isi', $kodeInduk)->with('error', 'Sistem tidak menemukan file fisik untuk dihapus.');
    }

    public function create($kode)
    {
        $kodeSearch = strtoupper(trim($kode));
        return view('arsip.create', ['kode' => $kodeSearch]);
    }

    public function cetakPDF(Request $request, $kode)
    {
        $kodeSearch = strtoupper(trim($kode));
        $arsips = Arsip::where('kode_arsip', 'LIKE', $kodeSearch . '%')->latest()->get();
        
        ActivityLog::create([
            'user_id' => auth()->id(), 
            'activity' => 'Cetak PDF', 
            'description' => "Cetak PDF isi folder $kodeSearch beserta sub-nya", 
            'ip_address' => $request->ip()
        ]);
        
        $pdf = Pdf::loadView('arsip.cetak_pdf', compact('arsips', 'kode'));
        return $pdf->stream('Laporan_Isi_Folder_' . $kodeSearch . '.pdf');
    }

    public function globalSearch(Request $request)
    {
        $keyword = $request->search;
        $arsips = Arsip::where('nama_berkas', 'LIKE', "%{$keyword}%")
                       ->orWhere('deskripsi_berkas', 'LIKE', "%{$keyword}%")
                       ->orWhere('tahun_berkas', 'LIKE', "%{$keyword}%")
                       ->orWhere('kode_arsip', 'LIKE', "%{$keyword}%")
                       ->latest()->get();
                       
        if ($keyword) {
            ActivityLog::create([
                'user_id' => auth()->id(), 
                'activity' => 'Cari Berkas', 
                'description' => "Mencari kata kunci: $keyword", 
                'ip_address' => $request->ip()
            ]);
        }
        
        return view('arsip.search_results', compact('arsips', 'keyword'));
    }

    public function export(Request $request) 
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $desc = 'Mengunduh rekap data seluruh arsip.';
        $fileName = 'Laporan_E-Arsip_FULL_' . date('d-m-Y') . '.xlsx';

        if ($startDate && $endDate) {
            $desc = "Mengunduh rekap arsip dari " . date('d/m/Y', strtotime($startDate)) . " s/d " . date('d/m/Y', strtotime($endDate));
            $fileName = 'Laporan_E-Arsip_' . $startDate . '_sampai_' . $endDate . '.xlsx';
        }

        ActivityLog::create([
            'user_id' => auth()->id(), 
            'activity' => 'Export Excel', 
            'description' => $desc, 
            'ip_address' => $request->ip()
        ]);
        
        return Excel::download(new \App\Exports\ArsipExport($startDate, $endDate), $fileName);
    }

    public function restore(Request $request, $id)
    {
        $arsip = Arsip::onlyTrashed()->findOrFail($id);
        $arsip->restore();
        
        ActivityLog::create([
            'user_id' => auth()->id(), 
            'activity' => 'Restore Berkas', 
            'description' => "Memulihkan berkas $arsip->nama_berkas", 
            'ip_address' => $request->ip()
        ]);
        
        return back()->with('success', 'Data arsip berhasil dipulihkan dari kelola sampah!');
    }

    public function forceDelete(Request $request, $id)
    {
        $arsip = Arsip::onlyTrashed()->findOrFail($id);
        
        if ($arsip->file_dokumen) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($arsip->file_dokumen)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($arsip->file_dokumen);
            } else if (file_exists(public_path('storage/' . $arsip->file_dokumen))) {
                unlink(public_path('storage/' . $arsip->file_dokumen));
            }
        }
        
        $arsip->forceDelete();
        
        ActivityLog::create([
            'user_id' => auth()->id(), 
            'activity' => 'Hapus Permanen', 
            'description' => "Memusnahkan berkas $arsip->nama_berkas", 
            'ip_address' => $request->ip()
        ]);
        
        return back()->with('success', 'Data arsip berhasil dimusnahkan secara permanen!');
    }

    public function downloadTemplate()
    {
        $filePath = public_path('files/template_arsip.xlsx');

        if (file_exists($filePath)) {
            $headers = [
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ];
            return response()->download($filePath, 'Template_Import_Arsip_BPK.xlsx', $headers);
        } else {
            return back()->with('error', 'Maaf, file template excel belum diunggah ke server (folder public/files/template_arsip.xlsx).');
        }
    }
}