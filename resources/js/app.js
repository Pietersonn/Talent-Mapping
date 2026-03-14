import './bootstrap';

// 1. Import Alpine & Plugins
import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'; // Plugin untuk animasi dropdown sidebar halus

// 2. Import Notiflix (Loading Spinner)
import Notiflix from 'notiflix';
import 'notiflix/dist/notiflix-3.2.6.min.css';

// -----------------------------------------------------------------------------
// KONFIGURASI NOTIFLIX & TM LOADING
// -----------------------------------------------------------------------------

Notiflix.Loading.init({
    className: 'tm-loading',
    zindex: 9999, // Pastikan di atas segalanya (sidebar z-50)
    backgroundColor: 'rgba(255,255,255,0.8)', // Lebih putih (glass effect)
    svgColor: '#22c55e', // Warna Hijau TalentMapping (Tailwind green-500)
    messageColor: '#1f2937',
    clickToClose: false,
    cssAnimation: true,
    cssAnimationDuration: 300,
});

let __tmLoadingShownAt = 0;
let __tmLoadingTimer = null;

// Durasi minimal loading tampil (biar gak kedip cepat banget)
const TM_MIN_VISIBLE_MS = 500;

window.TM = {
    showLoading(msg = 'Memproses...') {
        if (__tmLoadingShownAt !== 0) {
            Notiflix.Loading.change(msg);
            return;
        }
        // Pakai tipe 'circle' atau 'pulse' biar lebih modern daripada hourglass
        Notiflix.Loading.circle(msg);
        __tmLoadingShownAt = Date.now();
    },

    hideLoading() {
        const elapsed = Date.now() - __tmLoadingShownAt;
        const remain = Math.max(0, TM_MIN_VISIBLE_MS - elapsed);

        if (__tmLoadingTimer) clearTimeout(__tmLoadingTimer);

        __tmLoadingTimer = setTimeout(() => {
            Notiflix.Loading.remove();
            __tmLoadingShownAt = 0;
            __tmLoadingTimer = null;
        }, remain);
    },

    async withLoading(promiseOrFn, msg = 'Memproses...') {
        try {
            this.showLoading(msg);
            const p = typeof promiseOrFn === 'function' ? promiseOrFn() : promiseOrFn;
            const result = await p;
            return result;
        } finally {
            this.hideLoading();
        }
    }
};

// -----------------------------------------------------------------------------
// GLOBAL EVENT LISTENERS
// -----------------------------------------------------------------------------

document.addEventListener('DOMContentLoaded', () => {
    // 1. Auto Loading untuk FORM dengan class .js-loading-form
    document.querySelectorAll('form.js-loading-form').forEach(form => {
        form.addEventListener('submit', (e) => {
            if (form.dataset.submitting === 'true') {
                e.preventDefault();
                return;
            }
            // Validasi HTML5 standar (biar gak loading kalau form invalid)
            if (!form.checkValidity()) return;

            form.dataset.submitting = 'true';
            TM.showLoading('Menyimpan data...');
        });
    });

    // 2. Auto Loading untuk LINK/BUTTON navigasi (.js-loading-link)
    document.querySelectorAll('a.js-loading-link, button.js-loading-link').forEach(el => {
        el.addEventListener('click', (e) => {
            // Cek kalau link hanya anchor (#) atau membuka tab baru, jangan loading
            const href = el.getAttribute('href');
            const target = el.getAttribute('target');

            if (href && href !== '#' && target !== '_blank' && !e.ctrlKey && !e.metaKey) {
                TM.showLoading('Memuat halaman...');
            }
        });
    });

    // Bersihkan loading jika halaman selesai dimuat (jaga-jaga spinner nyangkut)
    TM.hideLoading();
});

// -----------------------------------------------------------------------------
// INISIALISASI ALPINE
// -----------------------------------------------------------------------------

window.Alpine = Alpine;
Alpine.plugin(collapse); // Wajib didaftarkan sebelum start
Alpine.start();
