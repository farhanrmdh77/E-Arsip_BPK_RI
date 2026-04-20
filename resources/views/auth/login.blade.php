<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Arsip Digital BPK RI Jambi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
        
        body {
            background-color: #0f172a; 
            background-image: url("data:image/svg+xml,%3Csvg width='52' height='26' viewBox='0 0 52 26' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%231e293b' fill-opacity='0.4'%3E%3Cpath d='M10 10c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6h2c0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6 0 2.21 1.79 4 4 4 3.314 0 6 2.686 6 6h-2c0-2.21-1.79-4-4-4-3.314 0-6-2.686-6-6zm25.464-1.95l8.486 8.486-1.414 1.414-8.486-8.486 1.414-1.414z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            padding: 20px; 
        }

        .login-wrapper {
            display: flex;
            /* 🔥 UKURAN DESKTOP DIKECILKAN AGAR LEBIH COMPACT 🔥 */
            width: 800px; /* Sebelumnya 980px */
            min-height: 480px; /* Sebelumnya 600px */
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 40px 100px rgba(0,0,0,0.6);
            overflow: hidden;
            border: 1px solid rgba(226, 232, 240, 0.1);
            position: relative;
            z-index: 10;
        }

        /* AREA KIRI: Latar Belakang & Efek Cahaya */
        .login-left {
            width: 50%;
            background-color: #1a2233; 
            background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43 0c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm29 37c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM13 29c3.314 0 6-2.686 6-6 0-3.314-2.686-6-6-6-3.314 0-6 2.686-6 6 0 3.314 2.686 6 6 6zm19 68c3.314 0 6-2.686 6-6 0-3.314-2.686-6-6-6-3.314 0-6 2.686-6 6 0 3.314 2.686 6 6 6zm40-15c3.314 0 6-2.686 6-6 0-3.314-2.686-6-6-6-3.314 0-6 2.686-6 6 0 3.314 2.686 6 6 6zM36 39c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7z' fill='%23C8A35A' fill-opacity='0.08'/%3E%3C/svg%3E");
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px; /* Dipersempit dari 50px */
            border-right: 1px solid rgba(200, 163, 90, 0.1);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .glow-orb-1, .glow-orb-2 {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(200, 163, 90, 0.4) 0%, rgba(200, 163, 90, 0) 70%);
            z-index: 1;
            animation: pulseGlow 6s infinite alternate ease-in-out;
        }
        .glow-orb-1 { width: 350px; height: 350px; top: -50px; left: -50px; }
        .glow-orb-2 { width: 450px; height: 450px; bottom: -100px; right: -100px; animation-delay: -3s; }

        @keyframes pulseGlow {
            0% { transform: scale(0.8); opacity: 0.3; }
            100% { transform: scale(1.2); opacity: 0.7; }
        }

        .logo-container {
            position: relative;
            z-index: 2;
            animation: floatLogo 4s ease-in-out infinite;
        }

        /* Logo Desktop Dikecilkan */
        .login-left img {
            width: 100%;
            max-width: 180px; /* Sebelumnya 250px */
            object-fit: contain;
            margin-bottom: 20px;
        }

        @keyframes floatLogo {
            0% { transform: translateY(0px); filter: drop-shadow(0 5px 15px rgba(0,0,0,0.5)); }
            50% { transform: translateY(-12px); filter: drop-shadow(0 20px 30px rgba(200, 163, 90, 0.6)); }
            100% { transform: translateY(0px); filter: drop-shadow(0 5px 15px rgba(0,0,0,0.5)); }
        }

        .left-text {
            color: #ffffff;
            font-weight: 700;
            font-size: 18px; /* Sebelumnya 20px */
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            filter: drop-shadow(0 2px 2px rgba(0,0,0,0.3));
            position: relative;
            z-index: 2;
        }
        .left-text-sub {
            color: #94a3b8;
            font-weight: 500;
            font-size: 13px; /* Sebelumnya 14px */
            margin: 5px 0 0;
            position: relative;
            z-index: 2;
        }

        /* AREA KANAN: Form Desktop Dipersempit */
        .login-right {
            width: 50%;
            padding: 40px 50px; /* Sebelumnya 60px 80px */
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            background: #ffffff;
        }

        .dynamic-logo-box { display: flex; justify-content: center; margin-bottom: 10px; }
        .dynamic-logo-img { width: 60px; height: 60px; object-fit: contain; border-radius: 12px; margin-bottom: 10px; border: 2px solid #e2e8f0; padding: 5px; }
        .login-icon-fallback {
            width: 60px; height: 60px; /* Sebelumnya 80px */
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #ffffff; border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; margin-bottom: 15px;
            border: 2px solid #C8A35A;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .login-title {
            text-align: center; font-size: 17px; font-weight: 700; /* Sebelumnya 19px */
            color: #0f172a; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 0.5px;
        }

        /* Kolom Input Desktop Dibuat Lebih Ramping */
        .ea-input-group { position: relative; margin-bottom: 18px; } /* Sebelumnya 22px */
        .ea-icon { position: absolute; left: 16px; top: 16px; color: #94a3b8; font-size: 14px; transition: 0.3s; }

        .form-control {
            border-radius: 8px; padding: 14px 20px 14px 45px; /* Lebih tipis */
            background-color: #f8fafc; border: 1.5px solid #e2e8f0;
            font-size: 13px; color: #0f172a; transition: all 0.3s ease;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }
        .form-control::placeholder { color: #cbd5e1; font-weight: 500; }
        .form-control:focus { box-shadow: none; border-color: #C8A35A; background-color: #ffffff; outline: none; }
        .form-control:focus + .ea-icon { color: #C8A35A; }

        .forgot-password-link {
            display: block; text-align: right; font-size: 12px; font-weight: 600; /* Dikecilkan ke 12px */
            color: #C8A35A; text-decoration: none; margin-bottom: 20px;
            background: none; border: none; padding: 0; transition: 0.2s; cursor: pointer;
        }
        .forgot-password-link:hover { color: #ae8b49; text-decoration: underline; }

        /* Tombol Login Desktop Dipersempit */
        .btn-login {
            width: 100%; border-radius: 8px; background-color: #C8A35A; color: white;
            padding: 14px; font-weight: 700; font-size: 14px; border: none; /* Lebih tipis */
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            margin-top: 5px; cursor: pointer; letter-spacing: 0.5px; text-transform: uppercase;
            box-shadow: 0 4px 10px rgba(200, 163, 90, 0.3);
            position: relative; overflow: hidden;
        }

        .btn-login::after {
            content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-20deg); animation: shineButton 4s infinite;
        }

        .btn-login:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 8px 20px rgba(200, 163, 90, 0.5);
            background-color: #ae8b49;
        }

        @keyframes shineButton {
            0% { left: -100%; }
            20% { left: 200%; }
            100% { left: 200%; }
        }

        /* Copyright Desktop Dinaikkan Sedikit */
        .copyright {
            text-align: center; font-size: 11px; color: #94a3b8; font-weight: 500;
            position: absolute; bottom: 20px; left: 0; right: 0;
        }
        .copyright span { color: #C8A35A; font-weight: 600; } 

        /* MODAL KHUSUS (PREMIUM STYLING) */
        .modal-content {
            border-radius: 16px; border: none; overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }
        
        .modal-header-custom {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 20px 25px; border-bottom: 3px solid #C8A35A;
            display: flex; justify-content: space-between; align-items: center;
        }
        
        .warning-box {
            background: linear-gradient(145deg, #ffffff, #f8fafc);
            border-radius: 12px; border: 1px solid #e2e8f0;
            box-shadow: 0 10px 25px rgba(200, 163, 90, 0.08);
            position: relative; overflow: hidden; padding: 20px;
        }
        
        .warning-box::before {
            content: ''; position: absolute; left: 0; top: 0;
            width: 4px; height: 100%; background-color: #C8A35A;
        }

        .btn-mengerti {
            background: linear-gradient(135deg, #C8A35A 0%, #ae8b49 100%);
            color: white; border: none; font-weight: 700; padding: 12px;
            border-radius: 8px; width: 100%; transition: 0.3s;
            position: relative; overflow: hidden;
        }
        
        .btn-mengerti::after {
            content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-20deg); animation: shineButton 3s infinite; animation-delay: 1s;
        }
        
        .btn-mengerti:hover { transform: scale(1.03); box-shadow: 0 8px 20px rgba(200, 163, 90, 0.4); color: white; }

        /* ======================================================== */
        /* 🔥 PERBAIKAN KHUSUS MOBILE: LEBIH KECIL & COMPACT 🔥     */
        /* ======================================================== */
        @media (max-width: 991px) {
            body {
                align-items: flex-start; /* Dinaikkan sedikit ke atas di HP agar tak tertutup keyboard */
                padding-top: 30px;
                padding-bottom: 30px;
            }

            .login-wrapper { 
                flex-direction: column; 
                width: 100%; 
                max-width: 380px; 
                min-height: auto; 
            }

            .login-left { 
                width: 100%; 
                padding: 25px 15px; 
                border-right: none; 
                border-bottom: 1px solid rgba(200, 163, 90, 0.1); 
            }
            .login-left img { max-width: 90px; margin-bottom: 10px; }
            .left-text { font-size: 15px; }
            .left-text-sub { font-size: 11px; margin-top: 2px; }

            .login-right { 
                width: 100%; 
                padding: 25px 25px 65px 25px; 
            }

            .dynamic-logo-img { width: 50px; height: 50px; margin-bottom: 10px; }
            .login-icon-fallback { width: 50px; height: 50px; font-size: 24px; margin-bottom: 10px; border-radius: 12px; }
            
            .login-title { font-size: 16px; margin-bottom: 25px; }

            .ea-input-group { margin-bottom: 15px; }
            .form-control { padding: 12px 15px 12px 42px; font-size: 13px; }
            .ea-icon { top: 14px; left: 16px; font-size: 14px; }

            .forgot-password-link { margin-bottom: 20px; font-size: 12px; }

            .btn-login { padding: 12px; font-size: 13px; }

            .copyright { 
                position: absolute; 
                bottom: 15px; 
                left: 0; 
                right: 0;
                font-size: 11px;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-left">
        <div class="glow-orb-1"></div>
        <div class="glow-orb-2"></div>
        
        <div class="logo-container">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/d/d2/BPK_insignia.svg/330px-BPK_insignia.svg.png" alt="Logo BPK RI Resmi">
        </div>
        
        <h3 class="left-text">BPK RI</h3>
        <p class="left-text-sub">Perwakilan Provinsi Jambi</p>
    </div>

    <div class="login-right">
        
        <div class="dynamic-logo-box">
            @php
                $appLogo = \App\Models\Setting::getAppLogo();
            @endphp

            @if($appLogo)
                <img src="{{ $appLogo }}" alt="Logo Aplikasi" class="dynamic-logo-img">
            @else
                <div class="login-icon-fallback">
                    <i class="fa-solid fa-lock"></i>
                </div>
            @endif
        </div>
        
        <h2 class="login-title">E-Arsip Digital System</h2>

        {{-- KOTAK PEMBERITAHUAN JIKA LOGIN GAGAL --}}
        @if(session('error'))
            <div class="alert alert-danger text-center mb-4" style="border-radius: 8px; padding: 12px; font-size: 13px; font-weight: 600; background-color: #fef2f2; color: #991b1b; border: 1px solid #ef4444; box-shadow: 0 4px 6px rgba(239,68,68,0.1);">
                <i class="fa-solid fa-triangle-exclamation me-1"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger text-center mb-4" style="border-radius: 8px; padding: 12px; font-size: 13px; font-weight: 600; background-color: #fef2f2; color: #991b1b; border: 1px solid #ef4444; box-shadow: 0 4px 6px rgba(239,68,68,0.1);">
                <i class="fa-solid fa-shield-halved me-1"></i> Email atau Kata Sandi tidak cocok!
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            <div class="ea-input-group">
                <input type="email" name="email" class="form-control" placeholder="Masukan Email Institusi" required autofocus>
                <i class="fa-solid fa-user-check ea-icon"></i>
            </div>
            
            <div class="ea-input-group mb-2">
                <input type="password" name="password" class="form-control" placeholder="Masukan Kata Sandi" required>
                <i class="fa-solid fa-key ea-icon"></i>
            </div>
            
            <button type="button" class="forgot-password-link ms-auto" data-bs-toggle="modal" data-bs-target="#infoPasswordModal">
                Masalah Login? Lupa Password?
            </button>
            
            <button type="submit" class="btn-login">MASUK KE SISTEM</button>
        </form>

        <div class="copyright">
            Copyright © {{ date('Y') }} - <span>BPK RI Jambi</span>. All Rights Reserved.
        </div>
    </div>
</div>

<div class="modal fade" id="infoPasswordModal" tabindex="-1" aria-labelledby="infoPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header-custom">
                <h6 class="modal-title fw-bold text-white m-0" id="infoPasswordModalLabel">
                    <i class="fa-solid fa-shield-halved me-2" style="color: #C8A35A;"></i> Protokol Keamanan
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="font-size: 10px;"></button>
            </div>
            
            <div class="modal-body text-center" style="padding: 30px 25px; color: #475569; font-size: 13px;">
                <p class="mb-4 fw-500 text-dark">Pegawai tidak diperkenankan mengubah <br> kata sandi secara mandiri di sistem luar.</p>
                
                <div class="warning-box mb-3">
                    <i class="fa-solid fa-headset fs-3 mb-2" style="color: #C8A35A;"></i>
                    <p class="mb-0 fw-bold" style="color: #0f172a;">Hubungi Administrator <br><span class="fs-5" style="color: #C8A35A;">Subbag SDM</span></p>
                    <p class="small text-muted mt-2 mb-0 border-top pt-2">Untuk melakukan reset kredensial langsung melalui manajemen pengguna.</p>
                </div>
            </div>
            
            <div class="modal-footer" style="border-top: none; padding: 0 25px 25px; justify-content: center;">
                <button type="button" class="btn-mengerti" data-bs-dismiss="modal">
                    SAYA MENGERTI
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>