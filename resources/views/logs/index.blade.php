@extends('layouts.app')

@section('title', 'Riwayat Aktivitas - ' . \App\Models\Setting::getAppName())
@section('header_title', 'Riwayat Aktivitas')

@push('styles')
<style>
    .custom-page { font-family: 'Poppins', sans-serif; color: #334155; padding-top: 10px; padding-bottom: 50px; }
    
    /* 🌟 1. AUDIT-LOG HEADER 🌟 */
    .header-banner {
        background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
        border-radius: 20px;
        padding: 35px 40px;
        margin-bottom: 30px; 
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(200, 163, 90, 0.15);
    }

    .header-banner::after {
        content: '\f1da'; /* Ikon History */
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: 0;
        top: -20px;
        font-size: 180px;
        color: #C8A35A;
        opacity: 0.05;
        transform: rotate(-10deg);
        pointer-events: none;
    }

    .banner-title { font-size: 28px; font-weight: 800; color: #ffffff; margin-bottom: 8px; display: flex; align-items: center; gap: 15px; }
    .banner-title i { color: #C8A35A; }
    .banner-desc { color: #cbd5e1; font-size: 14px; font-weight: 400; margin: 0; max-width: 650px; line-height: 1.6; }

    /* 🌟 2. TIMELINE TABLE CARD 🌟 */
    .log-card { 
        background: #ffffff; 
        border-radius: 20px; 
        padding: 25px; 
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); 
        border: 1px solid #e2e8f0; 
    }
    
    .ea-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .ea-table th { 
        padding: 15px 20px; 
        color: #94a3b8; 
        font-size: 11px; 
        font-weight: 700; 
        text-transform: uppercase; 
        background: #f8fafc;
        border-bottom: 2px solid #f1f5f9;
        letter-spacing: 1px;
    }
    .ea-table td { padding: 20px; font-size: 13px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

    /* USER INFO STYLING */
    .user-box { display: flex; align-items: center; gap: 12px; }
    .user-avatar { 
        width: 38px; height: 38px; border-radius: 10px; 
        background: #f1f5f9; border: 1px solid #e2e8f0;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; color: #1e293b;
    }
    
    /* 🌟 PERBAIKAN: Kunci Warna Nama User (Mode Terang) 🌟 */
    .user-name-log { font-weight: 700; color: #0f172a !important; display: block; }
    
    .ip-tag { 
        font-size: 10px; color: #94a3b8; background: #f8fafc; 
        padding: 2px 8px; border-radius: 4px; border: 1px solid #f1f5f9;
        margin-top: 4px; display: inline-block;
    }

    /* ACTIVITY BADGE DYNAMICS */
    .activity-pill {
        padding: 6px 14px; border-radius: 50px; font-size: 11px; font-weight: 700;
        text-transform: uppercase; letter-spacing: 0.5px; display: inline-block;
    }
    .pill-add { background: #dcfce7; color: #16a34a; }
    .pill-edit { background: #fffbeb; color: #d97706; }
    .pill-delete { background: #fef2f2; color: #ef4444; }
    .pill-auth { background: #e0f2fe; color: #0284c7; }
    .pill-default { background: #f1f5f9; color: #475569; }

    .desc-text { color: #64748b; font-size: 13px; line-height: 1.5; font-weight: 500; }
    .time-text { font-weight: 700; color: #1e293b; }
    .time-ago { font-size: 11px; color: #94a3b8; }

    /* Row Hover */
    .ea-table tbody tr { transition: 0.2s; }
    .ea-table tbody tr:hover { background: #fafbfc; }

    /* ======================================================= */
    /* 🌟 DARK MODE KHUSUS HALAMAN RIWAYAT 🌟                  */
    /* ======================================================= */
    
    /* Latar Belakang Kotak & Tabel */
    body.dark-mode .log-card { 
        background-color: #0f172a !important; 
        border-color: #1e293b !important; 
    }
    body.dark-mode .ea-table th { 
        background-color: #1e293b !important; 
        border-bottom-color: #334155 !important; 
        color: #cbd5e1 !important; 
    }
    body.dark-mode .ea-table td { 
        border-bottom-color: #1e293b !important; 
    }
    body.dark-mode .ea-table tbody tr:hover { 
        background-color: #1e293b !important; 
    }

    /* 🌟 PERBAIKAN: Kunci Warna Nama User (Mode Gelap) 🌟 */
    body.dark-mode .user-name-log,
    body.dark-mode .desc-text,
    body.dark-mode .time-text { 
        color: #ffffff !important; 
    }

    /* Teks Tambahan (IP & Waktu Lalu) Menjadi Abu Terang */
    body.dark-mode .ip-tag { 
        background-color: #1e293b !important; 
        border-color: #334155 !important; 
        color: #cbd5e1 !important; 
    }
    body.dark-mode .time-ago { 
        color: #cbd5e1 !important; 
    }
    body.dark-mode .user-avatar { 
        background-color: #1e293b !important; 
        border-color: #334155 !important; 
        color: #f8fafc !important; 
    }

    /* Warna Badge Aktivitas Khusus Dark Mode (Terang & Kontras) */
    body.dark-mode .pill-add { background: rgba(22, 163, 74, 0.2) !important; color: #4ade80 !important; border: 1px solid #16a34a !important; }
    body.dark-mode .pill-edit { background: rgba(217, 119, 6, 0.2) !important; color: #fbbf24 !important; border: 1px solid #d97706 !important; }
    body.dark-mode .pill-delete { background: rgba(239, 68, 68, 0.2) !important; color: #f87171 !important; border: 1px solid #ef4444 !important; }
    body.dark-mode .pill-auth { background: rgba(2, 132, 199, 0.2) !important; color: #38bdf8 !important; border: 1px solid #0284c7 !important; }
    body.dark-mode .pill-default { background: rgba(71, 85, 105, 0.2) !important; color: #94a3b8 !important; border: 1px solid #475569 !important; }

</style>
@endpush

@section('content')
<div class="custom-page container-fluid">

    {{-- 🌟 1. AUDIT HEADER 🌟 --}}
    <div class="header-banner">
        <h1 class="banner-title"><i class="fa-solid fa-clock-rotate-left"></i> Jejak Aktivitas Sistem</h1>
        <p class="banner-desc">Memantau setiap perubahan data, akses pengguna, dan riwayat pengelolaan arsip untuk menjaga integritas dan transparansi informasi di lingkungan BPK RI Jambi.</p>
    </div>

    {{-- 🌟 2. TABEL LOG 🌟 --}}
    <div class="log-card table-responsive">
        <table class="ea-table">
            <thead>
                <tr>
                    <th width="20%">Pengguna</th>
                    <th width="15%">Aktivitas</th>
                    <th width="45%">Deskripsi Detail</th>
                    <th width="20%">Waktu Kejadian</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>
                        <div class="user-box">
                            <div class="user-avatar">
                                {{ substr($log->user->name ?? 'S', 0, 1) }}
                            </div>
                            <div>
                                {{-- 🌟 PERBAIKAN: Menggunakan class khusus user-name-log 🌟 --}}
                                <span class="user-name-log">{{ $log->user->name ?? 'System' }}</span>
                                <span class="ip-tag"><i class="fa-solid fa-network-wired me-1"></i> {{ $log->ip_address }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        @php
                            $act = strtolower($log->activity);
                            $class = 'pill-default';
                            if(str_contains($act, 'tambah')) $class = 'pill-add';
                            elseif(str_contains($act, 'edit') || str_contains($act, 'update')) $class = 'pill-edit';
                            elseif(str_contains($act, 'hapus') || str_contains($act, 'buang') || str_contains($act, 'permanen')) $class = 'pill-delete';
                            elseif(str_contains($act, 'login') || str_contains($act, 'logout')) $class = 'pill-auth';
                        @endphp
                        <span class="activity-pill {{ $class }}">
                            {{ $log->activity }}
                        </span>
                    </td>
                    <td>
                        <div class="desc-text">{{ $log->description }}</div>
                    </td>
                    <td>
                        <div class="time-text">{{ $log->created_at->format('d M Y, H:i') }}</div>
                        <div class="time-ago"><i class="fa-regular fa-clock me-1"></i> {{ $log->created_at->diffForHumans() }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <i class="fa-solid fa-calendar-xmark fs-1 text-light mb-3"></i>
                        <h6 class="text-muted">Belum ada rekaman riwayat aktivitas.</h6>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        {{-- Jika ada pagination, tambahkan di sini --}}
        @if(method_exists($logs, 'links'))
            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
</div>
@endsection