@extends('layouts.app')

@section('title', 'Hasil Pencarian: ' . $keyword . ' - ' . \App\Models\Setting::getAppName())
@section('header_title', 'Hasil Pencarian Global')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    .custom-page { font-family: 'Poppins', sans-serif; color: #334155; padding-top: 10px; padding-bottom: 40px; }
    
    .ea-header { margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
    .ea-title { font-size: 24px; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 12px; margin: 0; letter-spacing: -0.5px; }
    
    /* Highlight Kata Kunci Pencarian */
    .keyword-highlight { 
        color: #C8A35A; 
        background: rgba(200, 163, 90, 0.1); 
        padding: 4px 12px; 
        border-radius: 8px; 
        font-weight: 700; 
        border: 1px dashed rgba(200, 163, 90, 0.3);
    }
    
    .btn-back { 
        background: #ffffff; color: #475569; padding: 10px 20px; border-radius: 12px; 
        font-weight: 600; font-size: 14px; text-decoration: none; display: inline-flex; 
        align-items: center; gap: 8px; border: 1px solid #e2e8f0; transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
    }
    .btn-back:hover { transform: translateY(-2px); border-color: #C8A35A; color: #C8A35A; box-shadow: 0 6px 15px rgba(200, 163, 90, 0.1); }

    /* Kartu Utama Tabel */
    .ea-section { 
        background: #ffffff; border-radius: 20px; padding: 0; 
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04); border: 1px solid rgba(226, 232, 240, 0.8); 
        overflow: hidden;
    }
    
    /* Header Kartu Pencarian */
    .section-header {
        padding: 20px 30px;
        border-bottom: 1px solid #f1f5f9;
        background: linear-gradient(to right, rgba(248, 250, 252, 0.8), #ffffff);
        display: flex; justify-content: space-between; align-items: center;
    }
    .section-header h5 { margin: 0; font-weight: 700; font-size: 16px; color: #1e293b; }

    /* Desain Tabel Premium */
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; padding: 15px; }
    .ea-table { width: 100%; border-collapse: separate; border-spacing: 0 8px; text-align: left; margin: 0; }
    .ea-table th { 
        padding: 15px 20px; color: #94a3b8; font-size: 12px; font-weight: 700; 
        text-transform: uppercase; letter-spacing: 0.5px; border-bottom: none; 
        white-space: nowrap;
    }
    .ea-table td { padding: 15px 20px; font-size: 13px; color: #334155; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .ea-table td:first-child { border-left: 1px solid #f1f5f9; border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
    .ea-table td:last-child { border-right: 1px solid #f1f5f9; border-top-right-radius: 12px; border-bottom-right-radius: 12px; }
    
    .ea-table tbody tr { background-color: #ffffff; box-shadow: 0 2px 10px rgba(0,0,0,0.01); border-radius: 12px; transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s; opacity: 0; animation: fadeUpRow 0.5s ease forwards; }
    @keyframes fadeUpRow { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
    .ea-table tbody tr:hover { transform: translateY(-2px) scale(1.005); box-shadow: 0 10px 25px rgba(0,0,0,0.04); z-index: 2; position: relative; }
    
    .col-no { width: 45px; text-align: center !important; } 
    .col-aksi { width: 1%; white-space: nowrap; text-align: center !important; padding-right: 15px !important; padding-left: 15px !important; } 

    .file-name { font-weight: 700; color: #0f172a; font-size: 14px; margin-bottom: 4px; display: block; }
    .file-desc { color: #64748b; font-size: 12px; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; max-width: 100%; }
    
    /* Desain Kotak KP */
    .badge-kp { background: #f8fafc; color: #0f172a; padding: 6px 10px; border-radius: 8px; font-weight: 700; font-size: 11px; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 4px; transition: 0.3s; }
    .badge-kp i { color: #C8A35A; }
    
    /* Badge Retensi JRA */
    .ea-badge-aktif { background: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; margin-top: 5px; }
    .ea-badge-inaktif { background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; margin-top: 5px; }

    /* Tombol Aksi */
    .action-group-table { display: flex; gap: 6px; justify-content: center; align-items: center; flex-wrap: nowrap; }
    .btn-t-view { background: #f0f9ff; color: #0284c7; padding: 8px 12px; border-radius: 8px; font-weight: 600; font-size: 12px; text-decoration: none; transition: all 0.3s ease; display: inline-flex; justify-content: center; align-items: center; gap: 5px; border: 1px solid #e0f2fe; }
    .btn-t-view:hover { background: #0284c7; color: #ffffff; border-color: #0284c7; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(2, 132, 199, 0.2); }
    .btn-t-folder { background: #fffbeb; color: #d97706; padding: 8px 12px; border-radius: 8px; font-weight: 600; font-size: 12px; text-decoration: none; transition: all 0.3s ease; display: inline-flex; justify-content: center; align-items: center; gap: 5px; border: 1px solid #fde68a; }
    .btn-t-folder:hover { background: #d97706; color: #ffffff; border-color: #d97706; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(217, 119, 6, 0.2); }

    /* Area Jika Tidak Ada Hasil */
    .empty-state { text-align: center; padding: 80px 20px; color: #94a3b8; }
    .empty-icon { font-size: 70px; color: #cbd5e1; margin-bottom: 20px; opacity: 0.7; }
    .empty-state h4 { color: #1e293b; font-weight: 700; margin-bottom: 10px; }

    /* 📱 RESPONSIVE MOBILE */
    @media (max-width: 768px) {
        .ea-header { flex-direction: column; align-items: flex-start; }
        .ea-title { font-size: 20px; }
        .ea-table th, .ea-table td { padding: 15px; }
        .section-header { padding: 15px 20px; }
    }

    /* ========================================================= */
    /* 🌟 INTEGRASI MODE GELAP (DARK MODE) UNTUK SEARCH 🌟       */
    /* ========================================================= */
    body.dark-mode .custom-page { color: #cbd5e1; }
    body.dark-mode .ea-title { color: #ffffff; }
    body.dark-mode .text-muted { color: #94a3b8 !important; }
    
    body.dark-mode .btn-back { background: #1e293b; color: #cbd5e1; border-color: #334155; box-shadow: none; }
    body.dark-mode .btn-back:hover { border-color: #C8A35A; color: #fde68a; background: #0f172a; }
    
    body.dark-mode .keyword-highlight { background: rgba(200, 163, 90, 0.15); color: #fde68a; border-color: rgba(200, 163, 90, 0.4); }

    body.dark-mode .ea-section { background: #0f172a; border-color: #1e293b; box-shadow: 0 10px 40px rgba(0,0,0,0.5); }
    body.dark-mode .section-header { background: #1e293b; border-bottom-color: #334155; }
    body.dark-mode .section-header h5 { color: #ffffff; }

    body.dark-mode .ea-table tbody tr { background-color: #1e293b; box-shadow: none; }
    body.dark-mode .ea-table tbody tr:hover { background-color: #1e293b; border-color: #C8A35A; box-shadow: 0 5px 15px rgba(200, 163, 90, 0.1); }
    body.dark-mode .ea-table td { border-color: #334155; color: #f8fafc; }
    body.dark-mode .file-name { color: #ffffff; }
    body.dark-mode .file-desc { color: #94a3b8; }
    
    body.dark-mode .badge-kp { background: rgba(200, 163, 90, 0.15); color: #fde68a; border-color: rgba(200, 163, 90, 0.3); }
    body.dark-mode .badge-kp i { color: #fde68a; }

    body.dark-mode .ea-badge-aktif { background: rgba(16, 185, 129, 0.1) !important; color: #34d399 !important; border-color: rgba(16, 185, 129, 0.2) !important; }
    body.dark-mode .ea-badge-inaktif { background: rgba(239, 68, 68, 0.1) !important; color: #fb7185 !important; border-color: rgba(239, 68, 68, 0.2) !important; }

    body.dark-mode .btn-t-view { background: rgba(2, 132, 199, 0.1) !important; color: #38bdf8 !important; border-color: rgba(2, 132, 199, 0.2) !important; }
    body.dark-mode .btn-t-view:hover { background: #0284c7 !important; color: #ffffff !important; border-color: #0284c7 !important; box-shadow: 0 4px 10px rgba(2, 132, 199, 0.2) !important; }
    body.dark-mode .btn-t-folder { background: rgba(217, 119, 6, 0.1) !important; color: #fbbf24 !important; border-color: rgba(217, 119, 6, 0.2) !important; }
    body.dark-mode .btn-t-folder:hover { background: #d97706 !important; color: #ffffff !important; border-color: #d97706 !important; box-shadow: 0 4px 10px rgba(217, 119, 6, 0.2) !important; }

    body.dark-mode .empty-state h4 { color: #ffffff; }
    body.dark-mode .empty-icon { color: #334155; }
</style>
@endpush

@section('content')
<div class="custom-page container-fluid px-0 px-md-4">
    
    <div class="ea-header px-3 px-md-0">
        <div>
            <h1 class="ea-title">
                <i class="fa-solid fa-magnifying-glass" style="color: #C8A35A;"></i> Temuan Pencarian
            </h1>
            <p class="text-muted mt-2 mb-0" style="font-size: 14px;">Menampilkan hasil arsip untuk kata kunci: <span class="keyword-highlight">"{{ $keyword }}"</span></p>
        </div>
        <a href="{{ route('arsip.dashboard') }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="ea-section mx-3 mx-md-0">
        <div class="section-header">
            <h5><i class="fa-solid fa-list-check me-2" style="color: #C8A35A;"></i> Daftar Dokumen Ditemukan</h5>
            <span class="badge" style="background: rgba(200, 163, 90, 0.1); color: #C8A35A; border: 1px solid rgba(200, 163, 90, 0.3); font-size: 13px; padding: 6px 12px; border-radius: 8px;">
                {{ count($arsips) }} Berkas Tersortir
            </span>
        </div>

        <div class="table-responsive">
            <table class="ea-table">
                <thead>
                    <tr>
                        <th class="col-no">NO</th>
                        <th>INFORMASI DOKUMEN</th>
                        <th>TAHUN & RETENSI</th>
                        <th>KODE KP</th>
                        <th class="col-aksi text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arsips as $index => $item)
                    
                    {{-- 🔥 LOGIKA CERDAS JRA & FOLDER INDUK 🔥 --}}
                    @php
                        preg_match('/\d{4}/', $item->tahun_berkas, $matches);
                        $tahunInt = !empty($matches) ? (int)$matches[0] : (int)date('Y');
                        $isAktif = $tahunInt >= 2021;

                        // PERBAIKAN BUG: Cek apakah kodenya LAINNYA (atau teks tanpa titik)
                        if (strpos($item->kode_arsip, '.') === false) {
                            $kodeFolderAsli = $item->kode_arsip; // Biarkan "LAINNYA" tetap "LAINNYA"
                        } else {
                            // Pecah kode (KP.05.12 menjadi KP.05) untuk menghindari Error 404
                            $folderIndukArr = explode('.', $item->kode_arsip);
                            $kodeFolderAsli = $folderIndukArr[0] . '.' . ($folderIndukArr[1] ?? '00');
                        }
                    @endphp

                    <tr class="row-item" style="animation-delay: {{ $index * 0.08 }}s;">
                        <td class="col-no text-muted fw-bold" style="font-size: 15px;">
                            {{ $index + 1 }}
                        </td>
                        
                        <td>
                            <div style="min-width: 0;">
                                <span class="file-name">{{ $item->nama_berkas }}</span>
                                <span class="file-desc">{{ $item->deskripsi_berkas ?: 'Tidak ada deskripsi tambahan.' }}</span>
                            </div>
                        </td>
                        
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 14px; margin-bottom: 2px;">{{ $item->tahun_berkas }}</div>
                            @if($isAktif)
                                <span class="ea-badge-aktif"><i class="fa-solid fa-shield-check"></i> Arsip Aktif</span>
                            @else
                                <span class="ea-badge-inaktif"><i class="fa-solid fa-fire"></i> Inaktif (Musnahkan)</span>
                            @endif
                        </td>
                        
                        <td>
                            <span class="badge-kp"><i class="fa-solid fa-tag"></i> {{ $item->kode_arsip }}</span>
                        </td>
                        
                        <td class="col-aksi">
                            <div class="action-group-table">
                                @if($item->file_dokumen)
                                    <a href="{{ asset('storage/' . $item->file_dokumen) }}" target="_blank" class="btn-t-view" title="Buka File PDF Terlampir">
                                        <i class="fa-solid fa-file-pdf"></i> File
                                    </a>
                                @endif
                                
                                {{-- Tombol ini mengarahkan ke halaman Folder Isi Induk, dan mem-filter otomatis berdasarkan judulnya! --}}
                                <a href="{{ route('arsip.folder.isi', $kodeFolderAsli) }}?search={{ urlencode($item->nama_berkas) }}" class="btn-t-folder" title="Lompat ke Gudang Folder Arsip">
                                    <i class="fa-solid fa-folder-open"></i> Buka Folder
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="fa-solid fa-magnifying-glass-minus empty-icon"></i>
                                <h4>Arsip Tidak Ditemukan</h4>
                                <p style="font-size: 14px;">Kami tidak menemukan arsip fisik maupun digital yang cocok dengan kata kunci <strong>"{{ $keyword }}"</strong>.</p>
                                <a href="{{ route('arsip.dashboard') }}" class="btn-back mt-3" style="background: #C8A35A; color: white; border-color: #C8A35A;">
                                    Kembali ke Dashboard
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection