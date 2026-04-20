<style>
    /* 🌟 BASE STYLING (MODE TERANG) 🌟 */
    .premium-alert {
        display: flex;
        align-items: center;
        background: #ffffff;
        border-radius: 16px;
        padding: 16px 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
        border: 1px solid #e2e8f0;
        margin-bottom: 25px;
        position: relative;
        overflow: hidden;
        animation: slideDownFadeIn 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        max-height: 200px; 
    }
    
    /* Animasi Masuk (Turun & Muncul) */
    @keyframes slideDownFadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* 🌟 ANIMASI KELUAR (Naik & Menghilang) 🌟 */
    @keyframes slideUpFadeOut {
        0% { opacity: 1; transform: translateY(0); max-height: 200px; padding: 16px 20px; margin-bottom: 25px; border-width: 1px; }
        100% { opacity: 0; transform: translateY(-20px); max-height: 0; padding: 0 20px; margin-bottom: 0; border-width: 0; }
    }
    
    /* Class penarik yang akan ditambahkan oleh JavaScript */
    .premium-alert.hide-alert {
        animation: slideUpFadeOut 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        pointer-events: none; 
    }

    .premium-alert-icon {
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 22px;
        flex-shrink: 0;
        margin-right: 18px;
        transition: 0.3s;
    }

    .premium-alert-body { flex-grow: 1; }
    .premium-alert-title { font-size: 16px; font-weight: 800; color: #1e293b; margin: 0 0 4px 0; letter-spacing: 0.3px; }
    .premium-alert-text { font-size: 13px; color: #64748b; margin: 0; line-height: 1.5; font-weight: 500; }

    .premium-alert-close {
        background: #f8fafc; border: 1px solid #e2e8f0; font-size: 16px; color: #94a3b8; 
        cursor: pointer; transition: all 0.2s ease; padding: 8px 12px; border-radius: 10px; margin-left: 15px;
    }
    .premium-alert-close:hover { color: #ef4444; background: #fee2e2; border-color: #fecaca; transform: scale(1.05); }

    /* Varian Alert: Success (Hijau) */
    .premium-alert.success { border-left: 5px solid #10b981; }
    .premium-alert.success .premium-alert-icon { background: #ecfdf5; color: #10b981; border: 1px solid #d1fae5; }

    /* Varian Alert: Danger/Error (Merah) */
    .premium-alert.danger { border-left: 5px solid #ef4444; }
    .premium-alert.danger .premium-alert-icon { background: #fef2f2; color: #ef4444; border: 1px solid #fee2e2; }

    /* Varian Alert: Warning (Emas/Kuning) */
    .premium-alert.warning { border-left: 5px solid #f59e0b; }
    .premium-alert.warning .premium-alert-icon { background: #fffbeb; color: #d97706; border: 1px solid #fef3c7; }

    /* ========================================================= */
    /* 🌟 DARK MODE OVERRIDES (TRANSLUCENT & NYAMAN DI MATA) 🌟  */
    /* ========================================================= */
    body.dark-mode .premium-alert { background: #1e293b; border-color: #334155; box-shadow: 0 15px 35px rgba(0,0,0,0.3); }
    body.dark-mode .premium-alert-title { color: #f8fafc; } 
    body.dark-mode .premium-alert-text { color: #cbd5e1; } 
    body.dark-mode .premium-alert-text strong { color: #f8fafc; } /* Tambahan agar text tebal lebih cerah di dark mode */
    
    body.dark-mode .premium-alert-close { background: #0f172a; border-color: #334155; color: #94a3b8; }
    body.dark-mode .premium-alert-close:hover { background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.3); color: #fb7185; }

    body.dark-mode .premium-alert.success .premium-alert-icon { background: rgba(16, 185, 129, 0.15); border-color: rgba(16, 185, 129, 0.2); color: #34d399; }
    body.dark-mode .premium-alert.danger .premium-alert-icon { background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.2); color: #fb7185; }
    body.dark-mode .premium-alert.warning .premium-alert-icon { background: rgba(245, 158, 11, 0.15); border-color: rgba(245, 158, 11, 0.2); color: #fbbf24; }
</style>

{{-- 🟢 ALERT SUCCESS (BERHASIL) --}}
@if (session('success'))
    <div class="premium-alert success" id="alert-box-success">
        <div class="premium-alert-icon">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div class="premium-alert-body">
            <h6 class="premium-alert-title">Berhasil Dikonfirmasi!</h6>
            {{-- 🔥 PERBAIKAN: Menggunakan tag {!! !!} agar tag <strong> terbaca sebagai HTML 🔥 --}}
            <p class="premium-alert-text">{!! session('success') !!}</p>
        </div>
        <button type="button" class="premium-alert-close" onclick="closePremiumAlert('alert-box-success')" title="Tutup Notifikasi">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
@endif

{{-- 🔴 ALERT ERROR (GAGAL) --}}
@if (session('error'))
    <div class="premium-alert danger" id="alert-box-error">
        <div class="premium-alert-icon">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div class="premium-alert-body">
            <h6 class="premium-alert-title">Peringatan Sistem!</h6>
            {{-- 🔥 PERBAIKAN: Menggunakan tag {!! !!} 🔥 --}}
            <p class="premium-alert-text">{!! session('error') !!}</p>
        </div>
        <button type="button" class="premium-alert-close" onclick="closePremiumAlert('alert-box-error')" title="Tutup Notifikasi">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
@endif

{{-- 🟡 ALERT VALIDASI ERROR (FORM) --}}
@if ($errors->any())
    <div class="premium-alert warning" id="alert-box-warning">
        <div class="premium-alert-icon">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>
        <div class="premium-alert-body">
            <h6 class="premium-alert-title">Cek Kembali Inputan Anda!</h6>
            <p class="premium-alert-text">
                @foreach ($errors->all() as $error)
                    {{-- 🔥 PERBAIKAN: Menggunakan tag {!! !!} 🔥 --}}
                    • {!! $error !!} <br>
                @endforeach
            </p>
        </div>
        <button type="button" class="premium-alert-close" onclick="closePremiumAlert('alert-box-warning')" title="Tutup Notifikasi">
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>
@endif

{{-- 🌟 SCRIPT AUTO-DISMISS 5 DETIK 🌟 --}}
<script>
    // Fungsi manual untuk menutup alert dengan animasi
    function closePremiumAlert(elementId) {
        const alertBox = document.getElementById(elementId);
        if(alertBox) {
            alertBox.classList.add('hide-alert');
            setTimeout(() => {
                alertBox.style.display = 'none';
                alertBox.remove();
            }, 500); 
        }
    }

    // Logika Auto-Dismiss setelah 5 detik
    document.addEventListener("DOMContentLoaded", function() {
        const alerts = document.querySelectorAll('.premium-alert');
        
        alerts.forEach(function(alertBox) {
            setTimeout(function() {
                if (document.body.contains(alertBox)) {
                    alertBox.classList.add('hide-alert');
                    setTimeout(() => {
                        alertBox.style.display = 'none';
                        alertBox.remove();
                    }, 500);
                }
            }, 5000); 
        });
    });
</script>