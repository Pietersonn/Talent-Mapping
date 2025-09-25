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
                <div class="col-lg-6 col-md-12">
                    <div class="thank-you-content">
                        <h1 class="thank-you-title">Terima Kasih Telah Mengikuti</h1>
                        <h2 class="thank-you-subtitle">
                            <span class="highlight">Talent Competency!</span> 🎉🎉
                        </h2>

                        <p class="thank-you-description">
                            Kami sangat menghargai waktu dan kejujuran Anda dalam mengisi tes ini.
                            Hasil analisis akan kami proses untuk memberikan gambaran singkat mengenai
                            kompetensi dan potensi Anda, dan hasilnya akan dikirim langsung ke email Anda.
                        </p>

                        <div class="thank-you-actions">
                            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                                Kembali ke beranda
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Right: Illustration + Animations -->
                <div class="col-lg-6 col-md-12">
                    <div class="thank-you-image">
                        <img
                            src="{{ asset('assets/public/images/img-thanks.png') }}"
                            alt="Thank You Illustration"
                            class="illustration"
                        >

                        <!-- Floating & Orbit Elements -->
                        <div class="floating-elements">
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
  // randomize delay/duration for subtle variety
  document.querySelectorAll('.floating-icon, .floating-shape').forEach((el, i) => {
    el.style.animationDelay = (i * 0.35) + 's';
    el.style.animationDuration = (3 + Math.random() * 2) + 's';
  });
});
</script>
@endsection
