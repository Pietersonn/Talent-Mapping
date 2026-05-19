<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'PIC Dashboard') | TalentMapping</title>
    <link rel="icon" href="{{ asset('assets/public/images/tm-logo.png') }}" type="image/png">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Styles & Scripts (Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #22c55e;
            --primary-soft: #dcfce7;
            --primary-hover: #16a34a;
            --secondary: #64748b;
            --dark: #0f172a;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--bg-body);
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
            color: var(--dark);
        }

        /* Scrollbar Halus */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        .fade-in-up { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>

<body class="antialiased h-screen overflow-hidden" x-data="{ sidebarOpen: false }">

    <div class="flex h-full w-full">
        {{-- Include Sidebar khusus PIC --}}
        @include('pic.layouts.partials.sidebar')

        <div class="flex-1 flex flex-col h-full relative overflow-hidden transition-all duration-300">
            {{-- Include Navbar khusus PIC --}}
            @include('pic.layouts.partials.navbar')

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8 pb-24 scroll-smooth">
                <div class="max-w-7xl mx-auto fade-in-up">
                    @if (session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-green-700 flex items-center gap-3">
                            <i class="fas fa-check-circle"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 text-red-700 flex items-center gap-3">
                            <i class="fas fa-exclamation-circle"></i>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                    @endif

                    @hasSection('header')
                        @yield('header')
                    @endif

                    @yield('content')
                </div>

                {{-- Include Footer khusus PIC --}}
                @include('pic.layouts.partials.footer')
            </main>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    @stack('scripts')
</body>
</html>
