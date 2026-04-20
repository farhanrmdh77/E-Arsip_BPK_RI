@extends('layouts.app')

@section('title', 'Manajemen Pengguna - ' . \App\Models\Setting::getAppName())
@section('header_title', 'Manajemen Pengguna')

@push('styles')
<style>
    .custom-page { font-family: 'Poppins', sans-serif; color: #334155; padding-top: 10px; padding-bottom: 50px; }
    
    /* 🌟 1. HEADER BANNER RAMPING 🌟 */
    .header-banner {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 15px; padding: 25px 35px; margin-bottom: 35px; 
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.1); position: relative; overflow: hidden;
        border: 1px solid rgba(200, 163, 90, 0.2); display: flex; justify-content: space-between;
        align-items: center; gap: 20px;
    }
    .header-banner::after {
        content: '\f3ed'; font-family: 'Font Awesome 6 Free'; font-weight: 900;
        position: absolute; right: 5%; top: -20px; font-size: 140px; color: #C8A35A; opacity: 0.04; transform: rotate(-10deg); pointer-events: none;
    }
    .banner-title { font-size: 24px; font-weight: 800; color: #ffffff; margin-bottom: 5px; display: flex; align-items: center; gap: 12px; }
    .banner-title i { color: #C8A35A; }
    .banner-desc { color: #94a3b8; font-size: 13px; margin: 0; max-width: 500px; }

    .btn-add-user {
        background: #C8A35A; color: #ffffff; border: none; padding: 12px 22px; border-radius: 10px;
        font-weight: 700; font-size: 13px; display: inline-flex; align-items: center; gap: 8px;
        transition: 0.3s; position: relative; z-index: 2; box-shadow: 0 5px 15px rgba(200, 163, 90, 0.2);
    }
    .btn-add-user:hover { background: #ae8b49; transform: translateY(-2px); color: white; box-shadow: 0 8px 20px rgba(200, 163, 90, 0.3); }

    /* 🌟 2. USER GRID & COMPACT CARD 🌟 */
    .user-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px; }
    .user-card {
        background: #ffffff; border-radius: 16px; padding: 25px 20px; border: 1px solid #e2e8f0;
        text-align: center; transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1); position: relative;
        opacity: 0; animation: fadeUpCard 0.6s ease forwards;
    }
    @keyframes fadeUpCard { from { opacity: 0; transform: translateY(25px); } to { opacity: 1; transform: translateY(0); } }
    .user-card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(0,0,0,0.06); border-color: #C8A35A; }

    /* Avatar */
    .u-avatar-wrapper { position: relative; width: 75px; height: 75px; margin: 0 auto 15px; }
    .u-avatar { width: 100%; height: 100%; border-radius: 18px; object-fit: cover; border: 3px solid #f8fafc; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
    .u-name { font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 3px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .u-email { font-size: 12px; color: #64748b; margin-bottom: 12px; display: block; }
    
    .u-role-badge { display: inline-block; padding: 5px 14px; border-radius: 50px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px; }
    .role-admin { background: #fffbeb; color: #C8A35A; border: 1px solid rgba(200, 163, 90, 0.2); }
    .role-pegawai { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    /* 🌟 TOMBOL AKSI 🌟 */
    .u-actions { display: flex; gap: 8px; border-top: 1px solid #f1f5f9; padding-top: 20px; }
    .btn-u-action {
        flex: 1; padding: 10px 5px; border-radius: 8px; font-size: 12px; font-weight: 700;
        display: flex; align-items: center; justify-content: center; gap: 5px;
        transition: 0.3s; border: 1px solid transparent; cursor: pointer; text-decoration: none;
    }
    .btn-u-edit { background: #fffbeb; color: #d97706; border-color: #fde68a; }
    .btn-u-edit:hover { background: #d97706; color: #ffffff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(217, 119, 6, 0.2); }
    .btn-u-reset { background: #f0f9ff; color: #0284c7; border-color: #e0f2fe; }
    .btn-u-reset:hover { background: #0284c7; color: #ffffff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(2, 132, 199, 0.2); }
    .btn-u-delete { background: #fff1f2; color: #e11d48; border-color: #ffe4e6; }
    .btn-u-delete:hover { background: #e11d48; color: #ffffff; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(225, 29, 72, 0.2); }

    /* 🌟 PERBAIKAN: KELAS INPUT COMPACT & BUTTON ANIMASI (UNTUK MODAL) 🌟 */
    .ea-input {
        border-radius: 10px; border: 1px solid #cbd5e1; padding: 10px 15px 10px 40px;
        background: #f8fafc; font-size: 13px; color: #334155; width: 100%; transition: 0.3s; box-shadow: none;
    }
    .ea-input:focus { border-color: #C8A35A; outline: none; background: #ffffff; box-shadow: 0 0 0 3px rgba(200, 163, 90, 0.15); }
    .ea-icon-input { position: absolute; top: 50%; left: 14px; transform: translateY(-50%); color: #94a3b8; font-size: 14px; z-index: 5; }

    .btn-submit-emas {
        background: #C8A35A; color: white; border-radius: 10px; padding: 10px 24px; 
        border: 2px solid #C8A35A; font-weight: 700; font-size: 14px; margin: 0;
        display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s ease;
    }
    .btn-submit-emas:hover {
        background: #ae8b49; border-color: #ae8b49; transform: translateY(-2px); 
        box-shadow: 0 6px 15px rgba(200, 163, 90, 0.3); color: white;
    }

    /* ======================================================= */
    /* 🌟 DARK MODE OVERRIDES 🌟                               */
    /* ======================================================= */
    body.dark-mode .user-card { background: #0f172a; border-color: #1e293b; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
    body.dark-mode .user-card:hover { border-color: #C8A35A; }
    body.dark-mode .u-avatar { border-color: #1e293b; }
    body.dark-mode .u-name { color: #ffffff; }
    body.dark-mode .u-email { color: #cbd5e1; }
    body.dark-mode .role-pegawai { background: rgba(71, 85, 105, 0.2); color: #cbd5e1; border-color: #334155; }
    body.dark-mode .role-admin { background: rgba(200, 163, 90, 0.1); color: #fde68a; border-color: rgba(200, 163, 90, 0.3); }
    body.dark-mode .u-actions { border-top-color: #1e293b; }
    
    body.dark-mode .btn-u-edit { background: rgba(217, 119, 6, 0.1); color: #fbbf24; border-color: rgba(217, 119, 6, 0.2); }
    body.dark-mode .btn-u-edit:hover { background: #d97706; color: #ffffff; }
    body.dark-mode .btn-u-reset { background: rgba(2, 132, 199, 0.1); color: #38bdf8; border-color: rgba(2, 132, 199, 0.2); }
    body.dark-mode .btn-u-reset:hover { background: #0284c7; color: #ffffff; }
    body.dark-mode .btn-u-delete { background: rgba(225, 29, 72, 0.1); color: #fb7185; border-color: rgba(225, 29, 72, 0.2); }
    body.dark-mode .btn-u-delete:hover { background: #e11d48; color: #ffffff; }

    /* 🌟 PERBAIKAN DARK MODE UNTUK INPUT DAN MODAL 🌟 */
    body.dark-mode .modal-content { background: #0f172a !important; border-color: #1e293b !important; }
    body.dark-mode .alert-info { background: rgba(2, 132, 199, 0.1) !important; border-color: rgba(2, 132, 199, 0.2) !important; color: #38bdf8 !important; }
    
    body.dark-mode .ea-input { background: #1e293b !important; border-color: #334155 !important; color: #f8fafc !important; }
    body.dark-mode .ea-input::placeholder { color: #64748b !important; }
    body.dark-mode .ea-input:focus { border-color: #C8A35A !important; background: #0b1120 !important; }
    body.dark-mode .ea-icon-input { color: #64748b !important; }
    body.dark-mode label.text-muted { color: #cbd5e1 !important; }

    /* ======================================================= */
    /* 🌟 RESPONSIVE LAYOUT UNTUK MOBILE (HP) 🌟               */
    /* ======================================================= */
    @media (max-width: 768px) {
        .header-banner {
            flex-direction: column; 
            align-items: flex-start; 
            padding: 20px 20px; 
            gap: 15px;
        }
        
        .banner-title {
            font-size: 20px; 
        }

        .btn-add-user {
            width: 100%; 
            justify-content: center; 
        }
    }
</style>
@endpush

@section('content')
<div class="custom-page container-fluid">
    @include('partials.alerts')

    {{-- 🌟 1. HEADER BANNER 🌟 --}}
    <div class="header-banner">
        <div class="banner-content">
            <h1 class="banner-title"><i class="fa-solid fa-user-shield"></i> Otoritas Pengguna</h1>
            <p class="banner-desc">Manajemen akses personil BPK RI secara terpusat.</p>
        </div>
        <button class="btn-add-user" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            <i class="fa-solid fa-user-plus"></i> Tambah User Baru
        </button>
    </div>

    {{-- 🌟 2. USER GRID 🌟 --}}
    <div class="user-grid">
        @foreach($users as $index => $user)
        <div class="user-card" style="animation-delay: {{ $index * 0.05 }}s;">
            <div class="u-avatar-wrapper">
                @if($user->avatar)
                    <img src="{{ asset($user->avatar) }}" class="u-avatar" alt="{{ $user->name }}">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=C8A35A&color=fff&size=128" class="u-avatar" alt="Default">
                @endif
            </div>

            <h3 class="u-name" title="{{ $user->name }}">{{ $user->name }}</h3>
            <span class="u-email">{{ $user->email }}</span>

            <span class="u-role-badge {{ $user->role == 'admin' ? 'role-admin' : 'role-pegawai' }}">
                <i class="fa-solid {{ $user->role == 'admin' ? 'fa-crown' : 'fa-user-tie' }} me-1"></i>
                {{ strtoupper($user->role ?? 'Pegawai') }}
            </span>

            <div class="u-actions">
                {{-- TOMBOL EDIT PROFIL --}}
                <button type="button" class="btn-u-action btn-u-edit" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $user->id }}">
                    <i class="fa-solid fa-user-pen"></i> Edit
                </button>

                {{-- TOMBOL RESET PASSWORD --}}
                <button type="button" class="btn-u-action btn-u-reset" data-bs-toggle="modal" data-bs-target="#modalResetPassword{{ $user->id }}">
                    <i class="fa-solid fa-key"></i> Reset
                </button>
                
                @if(auth()->id() !== $user->id)
                {{-- TOMBOL HAPUS DENGAN SWEETALERT2 --}}
                <form id="form-delete-user-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="flex: 1; display: flex;">
                    @csrf @method('DELETE')
                    <button type="button" class="btn-u-action btn-u-delete w-100" onclick="confirmDeleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')">
                        <i class="fa-solid fa-trash-can"></i> Hapus
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- 🌟 MODAL EDIT USER (SUDAH COMPACT & ANTI SCROLL) 🌟 --}}
        <div class="modal fade" id="modalEditUser{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header border-0 px-4 py-3" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; border-bottom: 3px solid #C8A35A !important;">
                        <h5 class="modal-title fw-bold" style="margin: 0; display: flex; align-items: center; gap: 10px; font-size: 16px;">
                            <i class="fa-solid fa-user-pen" style="color: #C8A35A;"></i> Edit Data User
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('users.update', $user->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body px-4 py-3 text-start">
                            <div class="mb-2">
                                <label class="small fw-bold text-muted mb-1 text-uppercase letter-spacing-1">Nama Lengkap <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <i class="fa-regular fa-id-badge ea-icon-input"></i>
                                    <input type="text" name="name" value="{{ $user->name }}" class="ea-input" required>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label class="small fw-bold text-muted mb-1 text-uppercase letter-spacing-1">Email Institusi <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <i class="fa-regular fa-envelope ea-icon-input"></i>
                                    <input type="email" name="email" value="{{ $user->email }}" class="ea-input" required>
                                </div>
                            </div>
                            <div class="mb-1">
                                <label class="small fw-bold text-muted mb-1 text-uppercase letter-spacing-1">Level Otoritas</label>
                                <div class="position-relative">
                                    <i class="fa-solid fa-layer-group ea-icon-input"></i>
                                    <select name="role" class="ea-input" style="cursor: pointer;">
                                        <option value="pegawai" {{ $user->role == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 px-4 pb-3 pt-0">
                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal" style="padding: 10px 20px; border-radius: 10px;">Batal</button>
                            <button type="submit" class="btn-submit-emas">
                                <i class="fa-solid fa-save"></i> Perbarui Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- 🌟 MODAL RESET PASSWORD (SUDAH COMPACT & ANTI SCROLL) 🌟 --}}
        <div class="modal fade" id="modalResetPassword{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="modal-header border-0 px-4 py-3" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; border-bottom: 3px solid #C8A35A !important;">
                        <h5 class="modal-title fw-bold" style="margin: 0; display: flex; align-items: center; gap: 10px; font-size: 16px;">
                            <i class="fa-solid fa-unlock-keyhole" style="color: #C8A35A;"></i> Atur Ulang Sandi
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('users.reset', $user->id) }}" method="POST">
                        @csrf @method('PUT')
                        <div class="modal-body px-4 py-3 text-start">
                            <div class="alert alert-info d-flex align-items-center mb-3" style="border-radius: 10px; font-size: 13px; padding: 12px 15px;">
                                <i class="fa-solid fa-shield-halved fs-5 me-3"></i> 
                                <div>Masukkan kata sandi baru untuk akun <strong>{{ $user->name }}</strong>.</div>
                            </div>
                            
                            <div class="mb-1">
                                <label class="small fw-bold text-muted mb-1 text-uppercase letter-spacing-1">Kata Sandi Baru <span class="text-danger">*</span></label>
                                <div class="position-relative">
                                    <i class="fa-solid fa-key ea-icon-input"></i>
                                    <input type="password" name="new_password" class="ea-input" placeholder="Minimal 6 karakter" required minlength="6">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 px-4 pb-3 pt-0">
                            <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal" style="padding: 10px 20px; border-radius: 10px;">Batal</button>
                            <button type="submit" class="btn-submit-emas">
                                <i class="fa-solid fa-save"></i> Simpan Sandi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- 🌟 MODAL TAMBAH USER (SUDAH COMPACT & ANTI SCROLL) 🌟 --}}
<div class="modal fade" id="modalTambahUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header border-0 px-4 py-3" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; border-bottom: 3px solid #C8A35A !important;">
                <h5 class="modal-title fw-bold" style="margin: 0; display: flex; align-items: center; gap: 10px; font-size: 16px;">
                    <i class="fa-solid fa-user-plus" style="color: #C8A35A;"></i> Daftarkan User Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4 py-3 text-start">
                    <div class="mb-2">
                        <label class="small fw-bold text-muted mb-1 text-uppercase letter-spacing-1">Nama Lengkap <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <i class="fa-regular fa-id-badge ea-icon-input"></i>
                            <input type="text" name="name" class="ea-input" placeholder="Contoh: Budi Santoso" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold text-muted mb-1 text-uppercase letter-spacing-1">Email Institusi <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <i class="fa-regular fa-envelope ea-icon-input"></i>
                            <input type="email" name="email" class="ea-input" placeholder="budi@bpk.go.id" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <label class="small fw-bold text-muted mb-1 text-uppercase letter-spacing-1">Password Awal <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <i class="fa-solid fa-lock ea-icon-input"></i>
                            <input type="password" name="password" class="ea-input" placeholder="Minimal 6 karakter" required minlength="6">
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="small fw-bold text-muted mb-1 text-uppercase letter-spacing-1">Level Otoritas</label>
                        <div class="position-relative">
                            <i class="fa-solid fa-layer-group ea-icon-input"></i>
                            <select name="role" class="ea-input" style="cursor: pointer;">
                                <option value="pegawai">Pegawai</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 px-4 pb-3 pt-0">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal" style="padding: 10px 20px; border-radius: 10px;">Batal</button>
                    {{-- 🌟 PERBAIKAN: Tombol Submit Animasi Emas 🌟 --}}
                    <button type="submit" class="btn-submit-emas">
                        <i class="fa-solid fa-user-check"></i> Daftarkan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{{-- PANGGIL SWEETALERT2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // 🌟 FUNGSI SWEETALERT2 UNTUK HAPUS PENGGUNA 🌟
    function confirmDeleteUser(id, name) {
        Swal.fire({
            title: 'Hapus ' + name + '?',
            text: "Akun ini akan dihapus secara permanen dari sistem dan tidak dapat dipulihkan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444', 
            cancelButtonColor: '#475569',  
            confirmButtonText: '<i class="fa-solid fa-user-xmark me-1"></i> Ya, Hapus Akun!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            backdrop: `rgba(15, 23, 42, 0.4)`,
            customClass: { 
                popup: 'border border-light shadow-lg', 
                title: 'fs-4 fw-bold' 
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form hapus yang sesuai dengan ID user
                document.getElementById('form-delete-user-' + id).submit();
            }
        });
    }
</script>
@endpush