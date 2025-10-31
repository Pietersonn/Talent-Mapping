@extends('public.layouts.app')

@section('title', 'Terima Kasih - TalentMapping')

@section('styles')
{{-- Pastikan path CSS benar --}}
<link rel="stylesheet" href="{{ asset('assets/public/css/pages/thank-you.css') }}">
@endsection

@section('content')
<div class="thank-you-container">
    <div class="thank-you-main">
        <div class="container">
            <div class="row align-items-center" style="min-height: calc(100vh - var(--footer-height));"> {{-- Gunakan style agar full height --}}

                <div class="col-lg-6 col-md-12 d-flex align-items-center order-lg-1 order-2"> {{-- Urutan diubah di mobile --}}
                    <div class="thank-you-content w-100">
                        <h1 class="thank-you-title">Terima Kasih Telah Mengikuti</h1>
                        <h2 class="thank-you-subtitle">
                            <span class="highlight">Talent Competency Assessment!</span> 🎉
                        </h2>

                        <p class="thank-you-description">
                            Kami sangat menghargai waktu dan kejujuran Anda dalam mengisi tes ini.
                            Hasil analisis akan kami proses untuk memberikan gambaran singkat mengenai
                            kompetensi dan potensi Anda, dan <strong>hasilnya akan dikirim langsung ke email Anda.</strong>
                        </p>

                        <div class="thank-you-actions">
                            {{-- Back to home --}}
                            <a href="{{ route('home') }}" class="btn btn-secondary btn-md back-home-btn" aria-label="Kembali ke beranda">
                                Kembali ke beranda
                            </a>

                            {{-- Learn more button --}}
                            <a href="https://drive.google.com/file/d/1SVASrys3hk2kqBt4xLomPeozX8kZLbZw/view?usp=sharing" target="_blank" rel="noopener noreferrer" class="btn btn-md learn-more-btn" aria-label="Cari Lebih Tau">
                                Cari Lebih Tau
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 order-lg-2 order-1"> {{-- Urutan diubah di mobile --}}
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
            </div>
         </div>
    </div>
</div>
@endsection


