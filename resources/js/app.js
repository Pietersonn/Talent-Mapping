import './bootstrap';

import Alpine from 'alpinejs';
// resources/js/app.js
import Notiflix from 'notiflix';
import 'notiflix/dist/notiflix-3.2.6.min.css';


Notiflix.Loading.init({
  className: 'tm-loading',
  zindex: 4000,
  backgroundColor: 'rgba(255,255,255,0.6)',
  svgColor: '#f6c400',
  messageColor: '#111827',
  clickToClose: false,
  cssAnimation: true,
  cssAnimationDuration: 400,
});

let __tmLoadingShownAt = 0;
let __tmLoadingTimer = null;

// Ubah angka ini sesuai rasa:
// contoh: 900ms = ~1 detik; 1200ms = 1.2 detik
const TM_MIN_VISIBLE_MS = 900;

window.TM = {
  showLoading(msg = 'Memproses...') {
    // kalau sudah tampil, cukup update pesan saja (hindari flicker)
    if (__tmLoadingShownAt !== 0) {
      Notiflix.Loading.change(msg);
      return;
    }
    Notiflix.Loading.hourglass(msg);
    __tmLoadingShownAt = Date.now();
  },

  hideLoading() {
    // pastikan spinner minimal tampil TM_MIN_VISIBLE_MS
    const elapsed = Date.now() - __tmLoadingShownAt;
    const remain = Math.max(0, TM_MIN_VISIBLE_MS - elapsed);

    if (__tmLoadingTimer) clearTimeout(__tmLoadingTimer);

    __tmLoadingTimer = setTimeout(() => {
      Notiflix.Loading.remove();
      __tmLoadingShownAt = 0;
      __tmLoadingTimer = null;
    }, remain);
  },

  // QoL helper: bungkus promise agar otomatis show/hide + minimal durasi
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


// Otomatis kasih loading untuk semua form dengan class .js-loading-form
document.addEventListener('DOMContentLoaded', () => {
  // FORM submit
  document.querySelectorAll('form.js-loading-form').forEach(form => {
    form.addEventListener('submit', (e) => {
      // Cegah double submit
      if (form.dataset.submitting === 'true') {
        e.preventDefault();
        return;
      }
      form.dataset.submitting = 'true';
      // Tampilkan loading
      TM.showLoading('Menyimpan...');
    });
  });

  // LINK atau BUTTON navigasi yang ingin kasih loading saat pindah halaman
  document.querySelectorAll('a.js-loading-link, button.js-loading-link').forEach(el => {
    el.addEventListener('click', () => {
      TM.showLoading('Memuat halaman...');
    });
  });

  // Jika halaman sudah selesai load, pastikan loading bersih (jaga-jaga)
  TM.hideLoading();
});


window.Alpine = Alpine;

Alpine.start();
