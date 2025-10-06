{{-- resources/views/public/thank-you.blade.php --}}
@extends('public.layouts.app')

@section('title', 'Terima Kasih - TalentMapping')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/public/css/pages/thank-you.css') }}">
@endsection

@section('content')
<div class="thank-you-container">
    <div class="thank-you-main">
        <div class="container">
            <div class="row align-items-start min-vh-100">

                <!-- Left: Text -->
                <div class="col-lg-6 col-md-12 d-flex align-items-center">
                    <div class="thank-you-content w-100">
                        <h1 class="thank-you-title">Terima Kasih Telah Mengikuti</h1>
                        <h2 class="thank-you-subtitle">
                            <span class="highlight">Talent Competency!</span> 🎉🎉
                        </h2>

                        <p class="thank-you-description">
                            Kami sangat menghargai waktu dan kejujuran Anda dalam mengisi tes ini.
                            Hasil analisis akan kami proses untuk memberikan gambaran singkat mengenai
                            kompetensi dan potensi Anda, dan hasilnya akan dikirim langsung ke email Anda.
                        </p>

                        <div class="thank-you-actions d-flex flex-wrap">
                            {{-- Back to home using url('/') to avoid missing route name --}}
                            <a href="{{ url('/') }}" class="btn btn-secondary btn-md mr-3 mb-2 back-home-btn" aria-label="Kembali ke beranda">
                                Kembali ke beranda
                            </a>

                            {{-- Learn more button - adjust URL to your real page --}}
                            <a href="{{ url('/learn-more') }}" class="btn btn-md mb-2 learn-more-btn" aria-label="Cari Lebih Tau">
                                Cari Lebih Tau
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right: Illustration + Animations -->
                <div class="col-lg-6 col-md-12">
                    <div class="thank-you-image" aria-hidden="true">
                        <img
                            src="{{ asset('assets/public/images/img-thanks.png') }}"
                            alt="Thank You Illustration"
                            class="illustration"
                        >

                        <div class="floating-elements" aria-hidden="true">
                            <div class="orbit-lines">
                                <div class="orbit-line"></div>
                                <div class="orbit-line"></div>
                                <div class="orbit-line"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> <!-- /row -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // subtle variety for floating elements (already present in CSS animations)
  document.querySelectorAll('.floating-icon, .floating-shape').forEach((el, i) => {
    el.style.animationDelay = (i * 0.35) + 's';
    el.style.animationDuration = (3 + Math.random() * 2) + 's';
  });

  // JS fallback: ensure clicks navigate even if something intercepts events.
  // This should be redundant when pointer-events:none is set on illustration,
  // but it's a safe fallback while caches update.
  document.querySelectorAll('.back-home-btn, .learn-more-btn').forEach(btn => {
    btn.addEventListener('click', function(e){
      const href = this.getAttribute('href');
      if (!href) return;
      // allow native navigation first; in case it's prevented by overlay, force after tiny delay
      setTimeout(() => {
        if (window.location.href !== href) {
          window.location.href = href;
        }
      }, 120);
    });
  });
});
</script>
@endsection
