@extends('public.layouts.app')

@section('title', 'TalentMapping - Discover Your Potential')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/public/css/pages/home.css') }}">
@endsection

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        Talent Competency
                    </h1>
                    <p class="hero-subtitle">
                        Temukan potensi terbaik dalam dirimu dan kenali kecenderunganmu lebih
                        mendalam. Jelajahi kekuatan dan bakat yang ada dalam diri untuk
                        mengoptimalkan langkahmu ke depan.
                    </p>
                    @auth
                        <a href="{{ route('test.form') }}" class="btn btn-cta">
                            Ayo Cari Tahu!
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-cta">
                            Ayo Cari Tahu!
                        </a>
                    @endauth
                </div>
                <div class="hero-image">
                    <img src="{{ asset('assets/public/images/img-home1.png') }}" alt="Talent Assessment Illustration">
                </div>
            </div>
        </div>
    </section>

    <!-- Discover Your Potential Section -->
    <section class="discover-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">
                    <span class="highlight">Discover Your Potential</span>
                </h2>
                <p class="section-description">
                    Self Management, Thinking Skills, Leadership, Problem Solving, Career Attitude,
                    Communication, Interpersonal Ability, General Hardskills, Professional Ethics,
                    Work With Others, and Self Esteem
                </p>
            </div>

            <div class="discover-content">
                <div class="discover-image">
                    <img src="{{ asset('assets/public/images/img-home2.png') }}" alt="People discussing potential">
                </div>

                <div class="talent-competency-card">
                    <div class="card-header">
                        <h3>Apa itu <br><span class="highlight">Talent Competency</span></h3>
                    </div>
                    <div class="card-content">
                        <p>
                            Talent Competency menggunakan metodologi yang
                            dikembangkan untuk mengekstraksi kompetensi yang
                            dimiliki seseorang. Instrumen ini dikembangkan
                            berdasarkan jawaban yang anda berikan. Laporan ini
                            mengajukan gambaran kompetensi mengapai profil
                            kompetensi Anda.
                        </p>
                    </div>
                    <div class="card-arrow">
                        <div class="arrow-icon"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Profil Kompetensi Section -->
    <section class="profile-section">
        <div class="container">
            <div class="profile-header">
                <h2 class="section-title">
                    <span class="highlight">Profil Kompetensi</span>
                </h2>
                <p class="section-subtitle">
                    Laporan ini memberikan gambaran komprehensif mengenai profil kompetensi
                    Anda, menunjuk:
                </p>
            </div>

            <div class="competency-grid">
                <div class="competency-card primary">
                    <div class="card-content">
                        <p>
                            <strong>3 Kompetensi Tertinggi:</strong> Ketiga kompetensi yang
                            paling menonjol pada diri Anda, serta berapa
                            kelebihan Anda dalam memainkan berbagai peran
                            dan tanggung jawab.
                        </p>
                        <a href="#" class="learn-more">
                            Learn more <span class="arrow">→</span>
                        </a>
                    </div>
                </div>

                <div class="competency-card secondary">
                    <div class="card-content">
                        <p>
                            <strong>3 Kompetensi yang Perlu Dikembangkan:</strong> Ketiga
                            kompetensi yang perlu Anda asah lebih lanjut untuk
                            mencapai keunggulan yang Anda inginkan.
                        </p>
                        <a href="#" class="learn-more">
                            Learn more <span class="arrow">→</span>
                        </a>
                    </div>
                </div>

                <div class="competency-card tertiary">
                    <div class="card-content">
                        <p>
                            <strong>Rekomendasi Aktivitas Pengembangan:</strong>
                            Rekomendasi aktivitas atau pelatihan yang
                            direkomendasikan untuk mengoptimalkan
                            kompetensi yang perlu dikembangkan.
                            Rekomendasi ini disesuaikan dengan profil
                            kompetensi Anda secara keseluruhan.
                        </p>
                        <a href="#" class="learn-more">
                            Learn more <span class="arrow">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pencari Potensi Section -->
    <section class="search-potential-section">
        <div class="container">
            <div class="search-content">
                <div class="search-card">
                    <h2>Pencari Potensi</h2>
                    <p>
                        Sudah banyak individu yang mengiikuti Talent Mapping
                        untuk mengenali dan mengembangkan kompetensi diri
                        mereka.
                    </p>
                    <div class="stats">
                        <div class="stat-number">300+</div>
                        <div class="stat-label">
                            Peserta Talent<br>
                            Competency
                        </div>
                    </div>
                    @auth
                        <a href="{{ route('test.form') }}" class="btn btn-cta-secondary">
                            Ayo Cari Tahu Potensimu
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-cta-secondary">
                            Ayo Cari Tahu Potensimu
                        </a>
                    @endauth
                </div>

                <div class="search-illustration">
                    <img src="{{ asset('assets/public/images/img-home3.png') }}" alt="Search potential illustration">
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        // Homepage specific JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Smooth scroll for CTA buttons
            const ctaButtons = document.querySelectorAll('.btn-cta, .btn-cta-secondary');
            ctaButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Add navigation to test registration or login
                    console.log('Navigate to test registration');
                });
            });

            // Animation on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-in');
                    }
                });
            }, observerOptions);

            // Observe sections for animation
            document.querySelectorAll('section').forEach(section => {
                observer.observe(section);
            });
        });
    </script>
@endsection
