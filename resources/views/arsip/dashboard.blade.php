@extends('layouts.app')

@section('title', 'Dashboard - ' . \App\Models\Setting::getAppName())
@section('header_title', 'Dashboard Utama')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');

    .custom-dashboard { font-family: 'Poppins', sans-serif; color: #334155; padding-top: 10px; padding-bottom: 40px; }
    
    /* Header Area */
    .ea-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 15px; }
    .ea-title { font-size: 26px; font-weight: 700; color: #0f172a; margin: 0; }
    .ea-welcome-text { font-size: 15px; font-weight: 500; color: #475569; margin-top: 4px; margin-bottom: 0; }
    
    .ea-btn { background: #C8A35A; color: #ffffff; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: 0.3s ease; border: none; box-shadow: 0 4px 10px rgba(200, 163, 90, 0.2); }
    .ea-btn:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(200, 163, 90, 0.4); color: #ffffff; background: #ae8b49; }

    /* Banner Update */
    .ea-update-banner { background: #ffffff; border-left: 4px solid #C8A35A; padding: 12px 20px; border-radius: 8px; margin-bottom: 25px; font-size: 13px; color: #64748b; display: flex; align-items: center; gap: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
    
    /* 🔥 PERBAIKAN: KOTAK PENCARIAN RESPONSIVE 🔥 */
    .global-search-container {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 12px; padding: 35px 40px; margin-bottom: 35px;
        box-shadow: 0 15px 35px rgba(15, 23, 42, 0.2); color: #fff;
        position: relative; overflow: hidden;
        border: 1px solid rgba(200, 163, 90, 0.1);
    }
    .global-search-container::after {
        content: '\f002'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
        position: absolute; right: -20px; top: -20px; font-size: 150px; opacity: 0.05; transform: rotate(-15deg); color: #C8A35A;
    }
    .search-title { font-size: 22px; font-weight: 700; margin-bottom: 15px; color: #C8A35A; }
    
    /* Form Layout (Flexbox) */
    .global-search-form { display: flex; gap: 10px; position: relative; max-width: 800px; z-index: 2; align-items: stretch; }
    .global-input-wrapper { position: relative; flex-grow: 1; }
    .global-input { width: 100%; padding: 16px 20px 16px 50px; border-radius: 8px; border: 1px solid #334155; font-size: 14px; color: #f8fafc; background: rgba(255,255,255,0.05); height: 100%; transition: 0.3s; }
    .global-input::placeholder { color: #94a3b8; }
    .global-input:focus { outline: none; border-color: #C8A35A; background: rgba(255,255,255,0.1); }
    .global-icon { position: absolute; left: 20px; top: 50%; transform: translateY(-50%); color: #C8A35A; font-size: 18px; }
    
    /* Tombol Cari */
    .global-btn { background: #C8A35A; color: #fff; border: none; border-radius: 8px; padding: 0 30px; font-weight: 600; cursor: pointer; transition: 0.2s; white-space: nowrap; display: flex; align-items: center; justify-content: center; }
    .global-btn:hover { background: #ae8b49; }

    /* Statistik Cards */
    .ea-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
    
    .ea-card { background: #ffffff; border-radius: 12px; padding: 25px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03); border: 1px solid #e2e8f0; transition: 0.3s; }
    .ea-card:hover { border-color: #C8A35A; transform: translateY(-2px); }
    .ea-card-info h6 { font-size: 11px; color: #64748b; text-transform: uppercase; margin-bottom: 5px; font-weight: 700; letter-spacing: 0.5px; }
    .ea-card-info h2 { font-size: 28px; font-weight: 700; color: #0f172a; margin: 0; }
    .ea-icon-wrapper { width: 55px; height: 55px; border-radius: 10px; display: flex; justify-content: center; align-items: center; font-size: 22px; }
    
    .icon-blue { background: #f1f5f9; color: #334155; }
    .icon-amber { background: #fffbeb; color: #C8A35A; }
    .icon-sky { background: #f0f9ff; color: #0ea5e9; }
    .icon-green { background: #f0fdf4; color: #16a34a; }

    /* Sections Dasar */
    .ea-section { background: #ffffff; border-radius: 12px; padding: 25px; box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03); border: 1px solid #e2e8f0; display: flex; flex-direction: column; height: 100%; }
    .ea-section-header { font-size: 15px; font-weight: 700; color: #1e293b; margin-bottom: 18px; display: flex; justify-content: space-between; align-items: center; }

    /* WIDGET 1: STORAGE INDICATOR */
    .storage-wrapper { background: #f8fafc; border-radius: 10px; padding: 20px; border: 1px dashed #cbd5e1; }
    .storage-info { display: flex; justify-content: space-between; font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 10px; }
    .storage-bar-bg { width: 100%; height: 10px; background: #e2e8f0; border-radius: 10px; overflow: hidden; }
    .storage-bar-fill { height: 100%; border-radius: 10px; transition: width 1s ease-in-out; }
    
    /* WIDGET 2: QUICK SHORTCUTS */
    .shortcut-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 15px; }
    .shortcut-btn { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 15px; text-align: center; color: #475569; text-decoration: none; transition: 0.2s; box-shadow: 0 2px 5px rgba(0,0,0,0.02); display: flex; flex-direction: column; align-items: center; gap: 8px; font-size: 12px; font-weight: 600; cursor: pointer; }
    .shortcut-btn i { font-size: 20px; color: #C8A35A; transition: 0.2s; }
    .shortcut-btn:hover { border-color: #C8A35A; background: #f8fafc; color: #1e293b; transform: translateY(-2px); }
    .shortcut-btn:hover i { transform: scale(1.1); }

    /* WIDGET 3: RECENT ACCESS */
    .recent-list { display: flex; flex-direction: column; gap: 12px; }
    .recent-item { display: flex; align-items: center; gap: 15px; padding: 12px; background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 10px; text-decoration: none; transition: 0.2s; overflow: hidden; }
    .recent-item:hover { border-color: #C8A35A; background: #fff; transform: translateX(5px); }
    .recent-icon { width: 40px; height: 40px; background: #fffbeb; color: #C8A35A; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    
    .recent-text { flex: 1; min-width: 0; overflow: hidden; } 
    .recent-text h6 { font-size: 13px; font-weight: 700; color: #1e293b; margin: 0 0 2px 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%; }
    .recent-text p { font-size: 11px; color: #64748b; margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; width: 100%; }
    .recent-item .ms-auto { flex-shrink: 0; margin-left: auto; }

    /* WIDGET 4: SYSTEM HEALTH */
    .health-list { list-style: none; padding: 0; margin: 0; }
    .health-item { display: flex; align-items: center; gap: 12px; padding: 14px 0; border-bottom: 1px solid #f1f5f9; font-size: 13px; font-weight: 500; color: #475569; }
    .health-item:last-child { border-bottom: none; }
    .status-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; flex-shrink: 0; }
    .dot-green { background: #10b981; box-shadow: 0 0 8px rgba(16, 185, 129, 0.6); animation: pulseGreen 2s infinite; }
    .dot-amber { background: #f59e0b; box-shadow: 0 0 8px rgba(245, 158, 11, 0.6); }
    .dot-red { background: #ef4444; box-shadow: 0 0 8px rgba(239, 68, 68, 0.6); animation: pulseRed 0.8s infinite; }
    @keyframes pulseGreen { 0% { transform: scale(0.95); opacity: 0.8; } 50% { transform: scale(1.1); opacity: 1; } 100% { transform: scale(0.95); opacity: 0.8; } }
    @keyframes pulseRed { 0% { transform: scale(0.8); opacity: 0.8; } 50% { transform: scale(1.4); opacity: 1; } 100% { transform: scale(0.8); opacity: 0.8; } }

    /* CUSTOM SCROLLBAR ELEGAN */
    .custom-elegant-scroll { padding-right: 8px; }
    .custom-elegant-scroll::-webkit-scrollbar { width: 4px; height: 4px; } 
    .custom-elegant-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    .ea-activity-wrapper { height: 350px; overflow-y: auto; padding-right: 8px; margin-bottom: 15px; }
    
    .activity-link { text-decoration: none; display: block; border-radius: 8px; transition: 0.2s; }
    .activity-link:hover { background: #f8fafc; }
    .activity-item { padding: 12px 15px; border-bottom: 1px solid #f8fafc; }
    .activity-user { font-weight: 600; color: #1e293b; font-size: 13px; margin-bottom: 2px; }
    .activity-desc { font-size: 12px; color: #64748b; line-height: 1.4; }
    .activity-time { font-size: 10px; color: #94a3b8; display: block; margin-top: 4px; font-weight: 500; }
    .chart-container { height: 350px; position: relative; }

    /* Tables */
    .ea-table { width: 100%; border-collapse: collapse; text-align: left; }
    .ea-table th { padding: 15px; color: #475569; font-size: 12px; font-weight: 700; text-transform: uppercase; border-bottom: 2px solid #e2e8f0; background: #f8fafc; white-space: nowrap; }
    .ea-table td { padding: 15px; font-size: 13px; border-bottom: 1px solid #f1f5f9; color: #334155; }
    
    /* Badge Dokumen Fisik */
    .ea-badge { background: #fffbeb; color: #C8A35A; padding: 5px 12px; border-radius: 6px; font-size: 11px; font-weight: 600; border: 1px solid rgba(200, 163, 90, 0.3); display: inline-block; white-space: nowrap; }

    /* TOMBOL BIRU KONSISTEN */
    .btn-lihat-arsip, .btn-lihat-riwayat {
        background: #f0f9ff; border: 1px solid #e0f2fe; border-radius: 8px;
        color: #0284c7; font-weight: 600; font-size: 12px; padding: 8px 14px;
        transition: all 0.2s ease; text-decoration: none; display: inline-block;
    }
    .btn-lihat-riwayat { padding: 10px 14px; display: block; width: 100%; text-align: center; }

    .btn-lihat-arsip:hover, .btn-lihat-riwayat:hover {
        background: #0284c7; color: #ffffff; border-color: #0284c7;
        transform: translateY(-2px); box-shadow: 0 4px 10px rgba(2, 132, 199, 0.2);
    }

    /* Animasi */
    @keyframes fadeUp { from { opacity: 0; transform: translateY(25px); } to { opacity: 1; transform: translateY(0); } }
    .anim-fade-up { opacity: 0; animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    /* ======================================================= */
    /* 📱 RESPONSIVE MOBILE FIXES 📱                           */
    /* ======================================================= */
    @media (max-width: 1200px) { .ea-stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { 
        .ea-stats-grid { grid-template-columns: 1fr; } 
        .global-search-container { padding: 25px 20px; }
        .search-title { font-size: 18px; text-align: center; }
        
        /* 🔥 PADA LAYAR KECIL, FORM DISUSUN KE BAWAH 🔥 */
        .global-search-form { flex-direction: column; }
        .global-btn { padding: 14px; width: 100%; justify-content: center; }
    }

    /* ======================================================= */
    /* 🌟 DARK MODE OVERRIDES 🌟                               */
    /* ======================================================= */
    body.dark-mode .ea-title { color: #ffffff; }
    body.dark-mode .ea-welcome-text { color: #cbd5e1 !important; }
    
    body.dark-mode .custom-elegant-scroll::-webkit-scrollbar-thumb,
    body.dark-mode .ea-activity-wrapper::-webkit-scrollbar-thumb { background: #334155 !important; }
    
    body.dark-mode .ea-table td.fw-bold { color: #ffffff !important; }
    
    body.dark-mode .ea-badge { background: rgba(251, 191, 36, 0.1) !important; color: #fbbf24 !important; border-color: rgba(251, 191, 36, 0.3) !important; font-weight: 700 !important; text-shadow: 0 0 10px rgba(251, 191, 36, 0.2); }
    
    body.dark-mode .btn-lihat-arsip,
    body.dark-mode .btn-lihat-riwayat { background: rgba(2, 132, 199, 0.1) !important; color: #38bdf8 !important; border: 1px solid rgba(2, 132, 199, 0.2) !important; }
    body.dark-mode .btn-lihat-arsip:hover,
    body.dark-mode .btn-lihat-riwayat:hover { background: #0284c7 !important; color: #ffffff !important; }
    
    body.dark-mode .border-light { border-color: #1e293b !important; }
    body.dark-mode .activity-item { border-bottom-color: #1e293b; }
    body.dark-mode .activity-link:hover { background: rgba(255, 255, 255, 0.03); }
    
    body.dark-mode .storage-wrapper { background: #0b1120; border-color: #334155; }
    body.dark-mode .storage-info { color: #cbd5e1; }
    body.dark-mode .storage-bar-bg { background: #1e293b; }
    
    body.dark-mode .shortcut-btn { background: #1e293b; border-color: #334155; color: #cbd5e1; }
    body.dark-mode .shortcut-btn:hover { background: #0f172a; border-color: #C8A35A; color: #ffffff; }
    
    body.dark-mode .recent-item { background: #1e293b; border-color: #334155; }
    body.dark-mode .recent-item:hover { background: #0f172a; border-color: #C8A35A; color: #ffffff; }
    body.dark-mode .recent-text h6 { color: #ffffff; }
    body.dark-mode .recent-text p { color: #94a3b8; }
    body.dark-mode .recent-icon { background: rgba(200, 163, 90, 0.15); }
    
    body.dark-mode .health-item { border-bottom-color: #1e293b; color: #cbd5e1; }
</style>
@endpush

@section('content')
<div class="custom-dashboard container">
    @include('partials.alerts')
    
    <div class="ea-header">
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <button type="button" class="ea-btn" data-bs-toggle="modal" data-bs-target="#modalExportRentangWaktu">
                <i class="fa-solid fa-file-excel"></i> Export Laporan
            </button>
        </div>
    </div>

    <div class="ea-update-banner anim-fade-up" style="animation-delay: 0.1s;">
        <i class="fa-solid fa-clock-rotate-left" style="color: #C8A35A; font-size: 18px;"></i>
        <span>Pembaruan sistem terakhir: <strong>{{ $lastUpdateGlobal ?? now()->format('d M Y, H:i') }}</strong></span>
    </div>

    {{-- 🔥 BAGIAN KOTAK PENCARIAN YANG SUDAH DIRAPIKAN 🔥 --}}
    <div class="global-search-container anim-fade-up" style="animation-delay: 0.2s;">
        <div class="search-title">Pencarian Arsip Terpadu</div>
        <form action="{{ route('arsip.global_search') }}" method="GET" class="global-search-form">
            <div class="global-input-wrapper">
                <i class="fa-solid fa-magnifying-glass global-icon"></i>
                <input type="text" name="search" class="global-input" placeholder="Ketik nama dokumen, tahun, atau deskripsi arsip..." required>
            </div>
            <button type="submit" class="global-btn"><i class="fa-solid fa-search me-2 d-md-none"></i> Cari Berkas</button>
        </form>
    </div>

    {{-- 🔥 BARIS 1: 4 KARTU STATISTIK SEJAJAR 🔥 --}}
    <div class="ea-stats-grid">
        <div class="ea-card anim-fade-up" style="animation-delay: 0.3s;">
            <div class="ea-card-info"><h6>Kategori Arsip</h6><h2>{{ $totalBerkas ?? 0 }}</h2></div>
            <div class="ea-icon-wrapper icon-blue"><i class="fa-solid fa-folder-tree"></i></div>
        </div>
        <div class="ea-card anim-fade-up" style="animation-delay: 0.4s;">
            <div class="ea-card-info"><h6>Total Dokumen</h6><h2>{{ $totalArsip ?? 0 }}</h2></div>
            <div class="ea-icon-wrapper icon-amber"><i class="fa-solid fa-file-lines"></i></div>
        </div>
        <div class="ea-card anim-fade-up" style="animation-delay: 0.5s;">
            <div class="ea-card-info"><h6>Pengguna Aktif</h6><h2>{{ $totalUsers ?? 0 }}</h2></div>
            <div class="ea-icon-wrapper icon-sky"><i class="fa-solid fa-user-shield"></i></div>
        </div>
        <div class="ea-card anim-fade-up" style="animation-delay: 0.6s;">
            <div class="ea-card-info"><h6>Dokumen Hari Ini</h6><h2>{{ $berkasHariIni ?? 0 }}</h2></div>
            <div class="ea-icon-wrapper icon-green"><i class="fa-solid fa-cloud-arrow-up"></i></div>
        </div>
    </div>

    {{-- 🔥 BARIS 2: GRAFIK & AKSI CEPAT/STORAGE 🔥 --}}
    <div class="row mb-4">
        {{-- Kiri: Grafik Distribusi --}}
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="ea-section anim-fade-up" style="animation-delay: 0.7s;">
                <div class="ea-section-header">Distribusi Arsip Berdasarkan Klasifikasi (KP)</div>
                <div class="chart-container">
                    <canvas id="arsipChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Kanan: Storage & Quick Shortcuts --}}
        <div class="col-lg-4">
            <div class="ea-section anim-fade-up" style="animation-delay: 0.8s;">
                
                @if(auth()->user()->role == 'admin')
                <div class="ea-section-header border-0 mb-2">Kapasitas Server</div>
                <div class="storage-wrapper">
                    <div class="storage-info">
                        <span>Penyimpanan Fisik</span>
                        <span style="color: {{ ($diskPercentage ?? 0) > 85 ? '#ef4444' : '#C8A35A' }};">{{ $diskPercentage ?? 0 }}%</span>
                    </div>
                    <div class="storage-bar-bg mb-2">
                        <div class="storage-bar-fill" 
                             style="width: {{ $diskPercentage ?? 0 }}%; 
                                    background: {{ ($diskPercentage ?? 0) > 85 ? 'linear-gradient(90deg, #f59e0b, #ef4444)' : 'linear-gradient(90deg, #10b981, #C8A35A)' }};">
                        </div>
                    </div>
                    <div style="font-size: 11px; color: #94a3b8;">
                        Terpakai <strong style="color: {{ ($diskPercentage ?? 0) > 85 ? '#ef4444' : 'inherit' }}">{{ $diskUsed ?? 0 }} GB</strong> dari total {{ $diskTotal ?? 0 }} GB
                    </div>
                </div>
                @endif

                <div class="ea-section-header border-0 @if(auth()->user()->role == 'admin') mt-4 @endif mb-2">Aksi Cepat</div>
                <div class="shortcut-grid">
                    <a href="{{ route('arsip.folders') }}" class="shortcut-btn">
                        <i class="fa-solid fa-folder-plus"></i>
                        <span>Gudang Data</span>
                    </a>
                    <button type="button" class="shortcut-btn" data-bs-toggle="modal" data-bs-target="#modalScanQR">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>Pindai QR Map</span>
                    </button>
                    <button type="button" class="shortcut-btn" data-bs-toggle="modal" data-bs-target="#modalExportRentangWaktu">
                        <i class="fa-solid fa-file-export"></i>
                        <span>Cetak Excel</span>
                    </button>
                    @can('admin')
                    <a href="{{ route('arsip.trash') }}" class="shortcut-btn">
                        <i class="fa-solid fa-dumpster-fire text-danger"></i>
                        <span>Tong Sampah</span>
                    </a>
                    @else
                    <a href="#" class="shortcut-btn opacity-50" style="cursor: not-allowed;">
                        <i class="fa-solid fa-lock text-muted"></i>
                        <span>Terkunci</span>
                    </a>
                    @endcan
                </div>

            </div>
        </div>
    </div>

    {{-- 🔥 BARIS 3: TABEL VOLUME & LOG AKTIVITAS 🔥 --}}
    <div class="row mb-4">
        {{-- Kiri: Tabel Volume --}}
        <div class="@if(auth()->user()->role == 'admin') col-lg-7 @else col-lg-12 @endif mb-4 mb-lg-0">
            <div class="ea-section anim-fade-up" style="animation-delay: 0.9s;">
                <div class="ea-section-header">Volume Dokumen per Kategori Klasifikasi</div>
                
                <div class="table-responsive custom-elegant-scroll" style="max-height: 400px; overflow-y: auto;">
                    <table class="ea-table">
                        <thead style="position: sticky; top: 0; z-index: 5;">
                            <tr>
                                <th>Kode Klasifikasi</th>
                                <th>Kapasitas Tersimpan</th>
                                <th style="text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($statistikKP ?? [] as $stat)
                            <tr>
                                <td class="fw-bold">{{ $stat->kode_arsip }}</td>
                                <td><span class="ea-badge">{{ $stat->total }} Dokumen Fisik</span></td>
                                <td style="text-align: right;">
                                    <a href="{{ route('arsip.folder.isi', $stat->kode_arsip) }}" class="btn-lihat-arsip">Lihat Arsip</a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-4 text-muted">Basis data klasifikasi saat ini kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Kanan: Log Aktivitas (Hanya Admin) --}}
        @if(auth()->user()->role == 'admin')
        <div class="col-lg-5">
            <div class="ea-section anim-fade-up" style="animation-delay: 1.0s;">
                <div class="ea-section-header">Log Aktivitas Terbaru</div>
                
                <div class="ea-activity-wrapper custom-elegant-scroll">
                    @forelse($recentActivities ?? [] as $log)
                        @php
                            $targetUrl = '#';
                            $activity = strtolower($log->activity);
                            if (str_contains($activity, 'folder') && !str_contains($activity, 'hapus')) { $targetUrl = route('arsip.folders'); }
                            elseif (str_contains($activity, 'hapus') || str_contains($activity, 'buang') || str_contains($activity, 'massal')) { $targetUrl = route('arsip.trash'); } 
                            elseif (str_contains($activity, 'berkas') || str_contains($activity, 'arsip') || str_contains($activity, 'excel') || str_contains($activity, 'pulihkan')) { $targetUrl = route('arsip.folders'); }
                            if (str_contains($activity, 'permanen')) { $targetUrl = '#'; }
                        @endphp
                        
                        <a href="{{ $targetUrl }}" class="activity-link">
                            <div class="activity-item">
                                <div class="activity-user"><i class="fa-solid fa-circle-user text-muted me-1"></i> {{ $log->user->name ?? 'System' }}</div>
                                <div class="activity-desc"><strong>{{ $log->activity }}</strong>: {{ $log->description }}</div>
                                <span class="activity-time">{{ $log->created_at->diffForHumans() }}</span>
                            </div>
                        </a>
                    @empty
                    <div class="text-center py-5 text-muted small">Belum ada riwayat aktivitas yang tercatat.</div>
                    @endforelse
                </div>
                
                <div class="mt-auto pt-3 border-top border-light">
                    <a href="{{ route('logs.index') }}" class="btn-lihat-riwayat">
                        Lihat Seluruh Riwayat
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- 🔥 BARIS 4: WIDGET AKSES TERAKHIR & STATUS SISTEM 🔥 --}}
    <div class="row">
        <div class="@if(auth()->user()->role == 'admin') col-lg-7 @else col-lg-12 @endif mb-4 mb-lg-0">
            <div class="ea-section anim-fade-up" style="animation-delay: 1.1s;">
                <div class="ea-section-header">Akses Dokumen Terakhir (Jump Back In)</div>
                <div class="recent-list">
                    @php
                        // Menangani fallback view jika collection kosong
                        $recentDocs = $recentDocs ?? \App\Models\Arsip::latest('updated_at')->limit(3)->get();
                    @endphp

                    @forelse($recentDocs as $doc)
                        @php
                            $docInduk = explode('.', $doc->kode_arsip);
                            $linkInduk = $docInduk[0] . '.' . ($docInduk[1] ?? '00');
                        @endphp
                        <a href="{{ route('arsip.folder.isi', $linkInduk) }}" class="recent-item">
                            <div class="recent-icon">
                                @if($doc->file_dokumen && str_ends_with(strtolower($doc->file_dokumen), '.pdf'))
                                    <i class="fa-solid fa-file-pdf text-danger"></i>
                                @elseif($doc->file_dokumen)
                                    <i class="fa-solid fa-image text-primary"></i>
                                @else
                                    <i class="fa-solid fa-file-lines"></i>
                                @endif
                            </div>
                            <div class="recent-text">
                                <h6>{{ $doc->nama_berkas }}</h6>
                                <p><span class="fw-bold">{{ $doc->kode_arsip }}</span> • Diperbarui {{ $doc->updated_at->diffForHumans() }}</p>
                            </div>
                            <div class="ms-auto text-muted">
                                <i class="fa-solid fa-chevron-right" style="font-size: 12px;"></i>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-4 text-muted small">Belum ada dokumen yang diakses baru-hari ini.</div>
                    @endforelse
                </div>
            </div>
        </div>

        @if(auth()->user()->role == 'admin')
        <div class="col-lg-5">
            <div class="ea-section anim-fade-up" style="animation-delay: 1.2s;">
                <div class="ea-section-header">Status Kesehatan Server</div>
                <ul class="health-list">
                    <li class="health-item">
                        <span class="status-dot {{ $encDot ?? 'dot-green' }}"></span>
                        <div class="ms-2 flex-grow-1">Enkripsi Database ({{ config('hashing.driver') ?? 'Bcrypt' }})</div>
                        <span class="badge {{ ($isEncrypted ?? 'AKTIF') == 'AKTIF' ? 'bg-success' : 'bg-danger' }} text-white" style="font-size: 10px;">{{ $isEncrypted ?? 'AKTIF' }}</span>
                    </li>
                    
                    <li class="health-item">
                        <span id="dash-network-dot" class="status-dot {{ $dbDot ?? 'dot-green' }}"></span>
                        <div class="ms-2 flex-grow-1">Koneksi Jaringan Server</div>
                        <span id="dash-network-text" class="fw-bold" data-latency="{{ $dbLatency ?? 0 }}" style="font-size: 11px; color: {{ ($dbStatus ?? 'STABIL') == 'STABIL' ? '#10b981' : '#ef4444' }};">
                            {{ $dbStatus ?? 'STABIL' }} ({{ $dbLatency ?? 0 }}ms)
                        </span>
                    </li>
                    
                    <li class="health-item">
                        <span class="status-dot {{ $backupDot ?? 'dot-green' }}"></span>
                        <div class="ms-2 flex-grow-1">Pencadangan (Backup) Lokal</div>
                        <span class="fw-bold text-muted" style="font-size: 10px;">{{ $lastBackupTime ?? 'Baru saja' }}</span>
                    </li>
                    <li class="health-item">
                        <span class="status-dot {{ $storageDot ?? 'dot-green' }}"></span>
                        <div class="ms-2 flex-grow-1">Aksesibilitas Folder Dokumen</div>
                        <span class="badge {{ ($storageHealth ?? 'AMAN') == 'AMAN' ? 'bg-success' : 'bg-danger' }} text-white" style="font-size: 10px;">{{ $storageHealth ?? 'AMAN' }}</span>
                    </li>
                </ul>
            </div>
        </div>
        @endif
    </div>
    
    {{-- 🌟 MODAL EXPORT LAPORAN EXCEL 🌟 --}}
    <div class="modal fade" id="modalExportRentangWaktu" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 p-4" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; border-bottom: 3px solid #C8A35A !important;">
                    <h5 class="modal-title fw-bold" style="font-size: 16px; margin: 0; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-file-export" style="color: #C8A35A;"></i> Export Rekapitulasi Data
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('arsip.export') }}" method="GET">
                    <div class="modal-body p-4">
                        <div class="alert alert-info" style="background: #f0f9ff; border: 1px solid #bae6fd; color: #0369a1; border-radius: 8px; font-size: 13px;">
                            <i class="fa-solid fa-circle-info me-2"></i> Kosongkan tanggal jika ingin mengunduh seluruh data arsip.
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Dari Tanggal</label>
                                <input type="date" name="start_date" class="form-control" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 12px 15px; font-size: 14px; background: #f8fafc;">
                            </div>
                            <div class="col-md-6">
                                <label style="font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 8px;">Sampai Tanggal</label>
                                <input type="date" name="end_date" class="form-control" style="border-radius: 8px; border: 1px solid #cbd5e1; padding: 12px 15px; font-size: 14px; background: #f8fafc;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top-0 px-4 pb-4">
                        <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal" style="padding: 10px 20px; border-radius: 8px;">Batal</button>
                        <button type="submit" class="btn fw-bold shadow-sm" style="background: #C8A35A; color: white; border-radius: 8px; padding: 10px 24px; border: 1px solid #C8A35A; margin: 0; transition: 0.3s;" onmouseover="this.style.background='#ae8b49'" onmouseout="this.style.background='#C8A35A'">
                            <i class="fa-solid fa-download me-1"></i> Download Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const rawData = @json($statistikKP ?? []);
        const ctx = document.getElementById('arsipChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: rawData.map(item => item.kode_arsip),
                datasets: [{
                    label: 'Jumlah Dokumen',
                    data: rawData.map(item => item.total),
                    backgroundColor: '#C8A35A', 
                    borderWidth: 0, 
                    borderRadius: 4, 
                    maxBarThickness: 45
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { family: 'Poppins', size: 13 },
                        bodyFont: { family: 'Poppins', size: 14, weight: 'bold' },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false 
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { font: { family: 'Poppins', size: 11 }, color: '#64748b' } },
                    y: { beginAtZero: true, grid: { color: '#f1f5f9', drawBorder: false }, ticks: { stepSize: 1, font: { family: 'Poppins', size: 11 }, color: '#64748b' } }
                }
            }
        });

        // --- 🔥 FITUR REAL-TIME NETWORK LISTENER UNTUK DASHBOARD 🔥 ---
        function checkDashboardNetwork() {
            const dot = document.getElementById('dash-network-dot');
            const text = document.getElementById('dash-network-text');
            
            if(!dot || !text) return; 

            const latency = text.getAttribute('data-latency') || '~';

            if (navigator.onLine) {
                // STATUS: ONLINE (Kembali ke hijau)
                dot.className = 'status-dot dot-green';
                text.style.color = '#10b981';
                text.innerText = 'STABIL (' + latency + 'ms)';
            } else {
                // STATUS: OFFLINE (Berubah merah panik)
                dot.className = 'status-dot dot-red';
                text.style.color = '#ef4444';
                text.innerText = 'TERPUTUS (Offline)';
            }
        }

        window.addEventListener('online', checkDashboardNetwork);
        window.addEventListener('offline', checkDashboardNetwork);
        checkDashboardNetwork();
    });
</script>
@endpush