@extends('public.layouts.app')

@section('title', 'TalentMapping - Discover Your Potential')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/public/css/pages/home.css') }}">
@endsection

@section('content')
    <section class="hero-wrapper">
        <div class="hero-box">
            <div class="hero-left">
                <h1> Kenali <span>Dirimu</span><br><span>dan kembangkan</span> Potensi Terbaikmu<br></h1>
                <p>Test ini akan membantumu melihat kekuatan dan ruang untuk tumbuh.</p>

                <p class="sub-text">Mau tau potensi yang kamu miliki sekarang</p>

                <div class="hero-buttons">

                    @auth
                        <a href="{{ route('test.form') }}" class="btn test">Explore Now</a>
                    @else
                        <a href="{{ route('login') }}" class="btn test">Explore Now</a>
                    @endauth

                    <a href="#" class="btn profile-bcti">BCTI Profile</a>
                </div>
            </div>

            <div class="hero-right">
                <img src="{{ asset('assets/public/images/img-home1.png') }}" alt="Personality Test Preview">
            </div>
        </div>
    </section>

    <section class="discover-section">
        <div class="container">
            <div class="discover-content">
                <div class="discover-image">
                    <img src="{{ asset('assets/public/images/img-home2.png') }}" alt="People discussing potential">
                </div>

                <div class="talent-competency-card">
                    <div class="card-header">
                        <h3>Apa itu <br><span class="highlight">Talent Competency?</span></h3>
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
                    <div class="hero-buttons">
                        @auth
                            <a href="{{ route('test.form') }}" class="btn test">Explore Now</a>
                        @else
                            <a href="{{ route('login') }}" class="btn test">Explore Now</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="feature-section">
        <div class="container">
            <div class="feature-wrapper">

                <div class="feature-item">
                    <div class="feature-image">
                        <img src="{{ asset('assets/public/images/icon1.png') }}" alt="ST-30 Illustration">
                    </div>
                    <div class="feature-content">
                        <h3>ST-30!</h3>
                        <p>membantu mengidentifikasi pola kekuatan dominan dalam diri Anda, mencakup kecenderungan berpikir,
                            berperilaku, dan berinteraksi,
                            sehingga Anda dapat memahami karakter unik serta potensi terbaik yang mendorong kinerja dan
                            pertumbuhan diri.</p>
                    </div>
                </div>

                <div class="feature-item feature-item--reverse">
                    <div class="feature-image">
                        <img src="{{ asset('assets/public/images/icon2.png') }}" alt="Talent Mapping Illustration">
                    </div>
                    <div class="feature-content">
                        <h3>Talent Mapping</h3>
                        <p>membantu mengidentifikasi kompetensi utama dan area pengembangan setiap individu,
                            sehingga Anda dapat memahami kekuatan diri serta merancang langkah pengembangan yang lebih
                            terarah.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="how-it-works">
        <div class="container">
            <h2>How it works</h2>
            <div class="steps">

                <div class="step">
                    <div class="circle">1</div>
                    <div class="step-content">
                        <h3>Prepare yourself</h3>
                        <p>Ensure you're in a relaxed setting conducive to concentration for the test.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="circle">2</div>
                    <div class="step-content">
                        <h3>Complete the test</h3>
                        <p>Respond to 100 questions designed to reveal aspects of your personality.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="circle">3</div>
                    <div class="step-content">
                        <h3>Receive your insights</h3>
                        <p>Access your report to explore the various personality types and find out where you belong!</p>
                    </div>
                </div>

                <div class="step">
                    <div class="circle">4</div>
                    <div class="step-content">
                        <h3>Explore & Grow</h3>
                        <p>Use your personalized results to enhance your self-awareness, improve relationships, and achieve
                            your goals.</p>
                    </div>
                </div>
            </div>

            <div class="button-wrapper">
                <a href="#" class="start-btn">Start Personality Test</a>
            </div>
        </div>
    </section>

    <section class="what-you-will-receive">
        <div class="container">
            <h2>What You Will Receive</h2>
            <div class="items">

                <div class="item">
                    <div class="icon">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div class="item-text-content">
                        <h3>Personality Report</h3>
                        <p>A highly detailed and straightforward report that reveals your strengths and weaknesses.</p>
                    </div>
                </div>

                <div class="item">
                    <div class="icon">
                        <i class="fa fa-book"></i>
                    </div>
                    <div class="item-text-content">
                        <h3>Test Library</h3>
                        <p>Access to over 20 assessment tests to help you evaluate your soft and hard skills.</p>
                    </div>
                </div>

                <div class="item">
                    <div class="icon">
                        <i class="fa fa-graduation-cap"></i>
                    </div>
                    <div class="item-text-content">
                        <h3>Course Library</h3>
                        <p>Based on your test results, you can enroll in specific courses aimed at enhancing your skills.
                        </p>
                    </div>
                </div>

                <div class="item">
                    <div class="icon">
                        <i class="fa fa-certificate"></i>
                    </div>
                    <div class="item-text-content">
                        <h3>Certificates</h3>
                        <p>Upon completing the courses and passing the tests, you will receive a downloadable certificate.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="search-potential-section">
        <div class="container">
            <div class="search-content">
                <div class="search-card">
                    <p>
                        Sudah banyak individu telah mempercayai Talent Mapping ini sebagai langkah awal yang efektif untuk
                        mengenali dan mengembangkan kompetensi terbaik mereka.
                    </p>
                    <div class="stats">
                        <div class="stat-number">300+</div>
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

            const ctaButtons = document.querySelectorAll('.btn-cta, .btn-cta-secondary');
            ctaButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
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
