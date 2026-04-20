@extends('layouts.app')

@section('title', 'Tong Sampah - ' . \App\Models\Setting::getAppName())
@section('header_title', 'Kelola Sampah Arsip')

@push('styles')
<style>
    .custom-page { font-family: 'Poppins', sans-serif; color: #334155; padding-top: 10px; padding-bottom: 50px; }
    
    /* 🌟 STYLE ALERT PREMIUM 🌟 */
    .ea-alert-premium {
        border-radius: 12px; font-weight: 500; font-size: 14px; border-left: 5px solid; 
        display: flex; align-items: center; padding: 15px 20px;
    }
    .alert-success.ea-alert-premium { background-color: #ecfdf5; color: #065f46; border-color: #10b981; }
    .alert-danger.ea-alert-premium { background-color: #fef2f2; color: #991b1b; border-color: #ef4444; }

    /* 🌟 HEADER BANNER 🌟 */
    .header-banner {
        background: linear-gradient(135deg, #450a0a 0%, #0f172a 100%);
        border-radius: 20px; padding: 35px 40px; margin-bottom: 30px; 
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2); position: relative;
        overflow: hidden; border: 1px solid rgba(239, 68, 68, 0.2);
    }
    .header-banner::after {
        content: '\f2ed'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
        position: absolute; right: -10px; top: -30px; font-size: 180px; color: #ef4444;
        opacity: 0.05; transform: rotate(15deg); pointer-events: none;
    }
    .banner-title { font-size: 28px; font-weight: 800; color: #ffffff; margin-bottom: 8px; display: flex; align-items: center; gap: 15px; }
    .banner-desc { color: #fecaca; font-size: 14px; font-weight: 400; margin: 0; max-width: 600px; line-height: 1.6; }

    /* 🌟 TAB SYSTEM 🌟 */
    .nav-tabs-custom { border: none; margin-bottom: 25px; display: flex; gap: 10px; }
    .nav-tabs-custom .nav-link {
        background: #f1f5f9; color: #64748b; border: 1px solid #e2e8f0; border-radius: 10px;
        padding: 12px 25px; font-weight: 700; font-size: 14px; transition: 0.3s;
    }
    .nav-tabs-custom .nav-link.active {
        background: #1e293b; color: #ffffff; border-color: #1e293b;
        box-shadow: 0 8px 15px rgba(30, 41, 59, 0.2);
    }

    /* 🌟 TABLE STYLING 🌟 */
    .table-card { background: #ffffff; border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); border: 1px solid #e2e8f0; }
    .ea-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .ea-table th { padding: 15px 20px; color: #94a3b8; font-size: 12px; font-weight: 700; text-transform: uppercase; background: #f8fafc; border-bottom: 1px solid #e2e8f0; }
    .ea-table td { padding: 18px 20px; font-size: 13px; color: #334155; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }

    .doc-title { font-weight: 700; color: #0f172a; font-size: 14px; margin-bottom: 4px; }
    .doc-info { font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 600; }

    /* 🌟 BUTTON ACTIONS 🌟 */
    .btn-restore {
        background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0;
        padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 12px;
        display: inline-flex; align-items: center; gap: 6px; transition: 0.3s; text-decoration: none; cursor: pointer;
    }
    .btn-restore:hover { background: #16a34a; color: white; border-color: #16a34a; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(22, 163, 74, 0.2); }

    .btn-permanent-del {
        background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3;
        padding: 8px 12px; border-radius: 8px; transition: 0.3s; cursor: pointer; font-size: 12px;
    }
    .btn-permanent-del:hover { background: #e11d48; color: white; border-color: #e11d48; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(225, 29, 72, 0.2); }

    .empty-state { text-align: center; padding: 60px 20px; }
    .empty-state i { font-size: 60px; color: #e2e8f0; margin-bottom: 20px; }

    /* 🔥 BADGE HITUNGAN MUNDUR (30 HARI) 🔥 */
    .countdown-badge { display: inline-flex; align-items: center; gap: 5px; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; margin-top: 4px; }
    .cd-safe { background: #f0f9ff; color: #0284c7; border: 1px solid #bae6fd; }
    .cd-warning { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .cd-danger { background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; }

    /* ======================================================= */
    /* 🌟 DARK MODE COMPATIBILITY 🌟                           */
    /* ======================================================= */
    body.dark-mode .table-card { background: #0f172a; border-color: #1e293b; }
    body.dark-mode .ea-table th { background: #1e293b; color: #94a3b8; border-color: #334155; }
    body.dark-mode .ea-table td { color: #cbd5e1; border-color: #1e293b; }
    body.dark-mode .doc-title { color: #ffffff; }

    body.dark-mode .btn-restore { background: rgba(16, 185, 129, 0.1) !important; color: #34d399 !important; border: 1px solid rgba(16, 185, 129, 0.2) !important; }
    body.dark-mode .btn-restore:hover { background: #10b981 !important; color: #ffffff !important; }
    body.dark-mode .btn-permanent-del { background: rgba(239, 68, 68, 0.1) !important; color: #fb7185 !important; border: 1px solid rgba(239, 68, 68, 0.2) !important; }
    body.dark-mode .btn-permanent-del:hover { background: #e11d48 !important; color: #ffffff !important; }

    /* Dark Mode Countdown */
    body.dark-mode .cd-safe { background: rgba(2, 132, 199, 0.1); color: #38bdf8; border-color: rgba(2, 132, 199, 0.2); }
    body.dark-mode .cd-warning { background: rgba(217, 119, 6, 0.1); color: #fbbf24; border-color: rgba(217, 119, 6, 0.2); }
    body.dark-mode .cd-danger { background: rgba(239, 68, 68, 0.1); color: #fb7185; border-color: rgba(239, 68, 68, 0.2); }
</style>
@endpush

@section('content')
<div class="custom-page container-fluid">
    @include('partials.alerts')

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm ea-alert-premium mb-4" role="alert">
            <i class="fa-solid fa-circle-xmark me-3 fs-5"></i> 
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- 🌟 2. HEADER BANNER 🌟 --}}
    <div class="header-banner">
        <h1 class="banner-title"><i class="fa-solid fa-dumpster-fire"></i> Pusat Pemulihan Data</h1>
        <p class="banner-desc">Dokumen di sini dapat dipulihkan jika terjadi kesalahan penghapusan. <strong>Perhatian:</strong> Sistem akan otomatis memusnahkan dokumen yang telah berada di tong sampah selama <strong>30 hari</strong> secara permanen.</p>
    </div>

    {{-- 🌟 3. TAB NAVIGASI 🌟 --}}
    <ul class="nav nav-tabs nav-tabs-custom" id="trashTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="arsip-tab" data-bs-toggle="tab" data-bs-target="#arsip-content" type="button" role="tab">
                <i class="fa-solid fa-file-lines me-2"></i> Arsip Terhapus ({{ count($arsips) }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="folder-tab" data-bs-toggle="tab" data-bs-target="#folder-content" type="button" role="tab">
                <i class="fa-solid fa-folder-closed me-2"></i> Folder Terhapus ({{ count($folders) }})
            </button>
        </li>
    </ul>

    <div class="tab-content" id="trashTabContent">
        {{-- TAB 1: ARSIP --}}
        <div class="tab-pane fade show active" id="arsip-content" role="tabpanel">
            <div class="table-card table-responsive">
                <table class="ea-table">
                    <thead>
                        <tr>
                            <th width="40%">INFORMASI BERKAS</th>
                            <th width="15%">KODE ARSIP</th>
                            <th width="20%">WAKTU DIHAPUS</th>
                            <th width="25%" class="text-end">AKSI PEMULIHAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($arsips as $arsip)
                        {{-- 🔥 LOGIKA PEMBULATAN HARI ARSIP 🔥 --}}
                        @php
                            $hariSinggah = \Carbon\Carbon::parse($arsip->deleted_at)->startOfDay()->diffInDays(now()->startOfDay());
                            $sisaHari = 30 - (int)$hariSinggah;
                            $badgeClass = $sisaHari > 15 ? 'cd-safe' : ($sisaHari > 5 ? 'cd-warning' : 'cd-danger');
                        @endphp
                        <tr>
                            <td>
                                <div class="doc-title">{{ $arsip->nama_berkas }}</div>
                                <div class="doc-info">Tahun: {{ $arsip->tahun_berkas }}</div>
                            </td>
                            <td><span class="badge bg-light text-dark border">{{ $arsip->kode_arsip }}</span></td>
                            <td>
                                <div class="fw-bold text-muted">{{ $arsip->deleted_at->format('d M Y') }}</div>
                                <div class="countdown-badge {{ $badgeClass }}">
                                    <i class="fa-regular fa-clock"></i> Sisa {{ $sisaHari }} Hari
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <form id="form-restore-arsip-{{ $arsip->id }}" action="{{ route('arsip.restore', $arsip->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="button" onclick="confirmRestore('form-restore-arsip-{{ $arsip->id }}', 'Dokumen', '{{ addslashes($arsip->nama_berkas) }}')" class="btn-restore">
                                            <i class="fa-solid fa-rotate-left"></i> Pulihkan
                                        </button>
                                    </form>
                                    <form id="form-delete-arsip-{{ $arsip->id }}" action="{{ route('arsip.force_delete', $arsip->id) }}" method="POST" style="margin:0;">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmForceDelete('form-delete-arsip-{{ $arsip->id }}', 'Dokumen', '{{ addslashes($arsip->nama_berkas) }}')" class="btn-permanent-del" title="Hapus Permanen">
                                            <i class="fa-solid fa-skull-crossbones"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4"><div class="empty-state"><i class="fa-solid fa-box-open"></i><h6 class="fw-bold">Kosong</h6></div></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TAB 2: FOLDER --}}
        <div class="tab-pane fade" id="folder-content" role="tabpanel">
            <div class="table-card table-responsive">
                <table class="ea-table">
                    <thead>
                        <tr>
                            <th width="40%">NAMA FOLDER KLASIFIKASI</th>
                            <th width="15%">KODE FOLDER</th>
                            <th width="20%">WAKTU DIHAPUS</th>
                            <th width="25%" class="text-end">AKSI PEMULIHAN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($folders as $folder)
                        {{-- 🔥 LOGIKA PEMBULATAN HARI FOLDER 🔥 --}}
                        @php
                            $hariSinggah = \Carbon\Carbon::parse($folder->deleted_at)->startOfDay()->diffInDays(now()->startOfDay());
                            $sisaHari = 30 - (int)$hariSinggah;
                            $badgeClass = $sisaHari > 15 ? 'cd-safe' : ($sisaHari > 5 ? 'cd-warning' : 'cd-danger');
                        @endphp
                        <tr>
                            <td>
                                <div class="doc-title">{{ $folder->nama_folder }}</div>
                                <div class="doc-info text-danger">Seluruh isi arsip di dalamnya juga ikut tersembunyi</div>
                            </td>
                            <td><span class="badge bg-dark text-white">{{ $folder->kode_folder }}</span></td>
                            <td>
                                <div class="fw-bold text-muted">{{ $folder->deleted_at->format('d M Y') }}</div>
                                <div class="countdown-badge {{ $badgeClass }}">
                                    <i class="fa-regular fa-clock"></i> Sisa {{ $sisaHari }} Hari
                                </div>
                            </td>
                            <td class="text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <form id="form-restore-folder-{{ $folder->id }}" action="{{ route('folders.restore', $folder->id) }}" method="POST" style="margin:0;">
                                        @csrf
                                        <button type="button" onclick="confirmRestore('form-restore-folder-{{ $folder->id }}', 'Folder', '{{ addslashes($folder->kode_folder) }}')" class="btn-restore">
                                            <i class="fa-solid fa-folder-plus"></i> Pulihkan
                                        </button>
                                    </form>
                                    <form id="form-delete-folder-{{ $folder->id }}" action="{{ route('folders.force_delete', $folder->id) }}" method="POST" style="margin:0;">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmForceDelete('form-delete-folder-{{ $folder->id }}', 'Folder', '{{ addslashes($folder->kode_folder) }}')" class="btn-permanent-del" title="Hapus Permanen">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4"><div class="empty-state"><i class="fa-solid fa-folder-open"></i><h6 class="fw-bold">Kosong</h6></div></td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmRestore(formId, type, name) {
        Swal.fire({
            title: 'Pulihkan ' + type + '?',
            html: "<strong>" + name + "</strong> akan dikembalikan utuh ke tempat asalnya.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981', 
            cancelButtonColor: '#475569',
            confirmButtonText: '<i class="fa-solid fa-rotate-left me-1"></i> Ya, Pulihkan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            backdrop: `rgba(16, 185, 129, 0.15)`, 
            customClass: { popup: 'border border-success shadow-lg', title: 'fs-4 fw-bold' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }

    function confirmForceDelete(formId, type, name) {
        Swal.fire({
            title: 'Musnahkan Permanen?',
            html: "AWAS! <strong>" + name + "</strong> beserta file fisiknya akan dihapus selamanya. Aksi ini tidak bisa dibatalkan!",
            icon: 'error', 
            showCancelButton: true,
            confirmButtonColor: '#b91c1c', 
            cancelButtonColor: '#475569',
            confirmButtonText: '<i class="fa-solid fa-skull-crossbones me-1"></i> Ya, Musnahkan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            backdrop: `rgba(220, 38, 38, 0.25)`, 
            customClass: { popup: 'border border-danger shadow-lg', title: 'fs-4 fw-bold text-danger' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>
@endpush