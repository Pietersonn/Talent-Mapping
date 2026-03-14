<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') | TalentMapping</title>
    <link rel="icon" href="{{ asset('assets/public/images/tm-logo.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* --- 1. PALET WARNA MODERN --- */
        :root {
            --primary: #22c55e;
            --primary-soft: #dcfce7;
            --primary-hover: #16a34a;
            --secondary: #64748b;
            --dark: #0f172a;
            --bg-body: #f8fafc; /* Slate-50 */
            --bg-card: #ffffff;
            --border: #e2e8f0;
            --radius: 16px; /* Lebih rounded */
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 2px 4px -2px rgb(0 0 0 / 0.05);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.05), 0 4px 6px -4px rgb(0 0 0 / 0.05);
        }

        /* --- 2. GLOBAL STYLES --- */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--bg-body);
            /* Pattern Background Halus */
            background-image: radial-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
            color: var(--dark);
        }

        /* Scrollbar Halus */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        /* --- 3. KOMPONEN UI --- */

        /* Header Wrapper */
        .header-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.02em;
        }

        /* Icon Judul dengan Efek Glass */
        .page-title i {
            color: var(--primary);
            background: linear-gradient(135deg, #dcfce7 0%, #ffffff 100%);
            border: 1px solid #bbf7d0;
            padding: 10px;
            border-radius: 12px;
            font-size: 1.1rem;
            box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.15);
        }

        .page-subtitle {
            font-size: 0.9rem;
            color: var(--secondary);
            margin-top: 4px;
            margin-left: 54px; /* Sejajar dengan teks judul */
        }

        /* Buttons */
        .btn-base {
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.875rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            cursor: pointer;
        }

        .btn-add {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(34, 197, 94, 0.3);
        }
        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.4);
        }

        .btn-cancel, .btn-print {
            background: white;
            color: var(--secondary);
            border: 1px solid var(--border);
        }
        .btn-cancel:hover, .btn-print:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
            color: var(--dark);
            transform: translateY(-1px);
        }

        /* --- 4. ANIMASI --- */
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
        @include('admin.layouts.partials.sidebar')

        <div class="flex-1 flex flex-col h-full relative overflow-hidden transition-all duration-300">
            @include('admin.layouts.partials.navbar')

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 md:p-8 pb-24 scroll-smooth">
                <div class="max-w-7xl mx-auto fade-in-up">

                    @hasSection('header')
                        @yield('header')
                    @endif

                    @yield('content')

                </div>
                @include('admin.layouts.partials.footer')
            </main>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('admin.layouts.alerts')
    @stack('scripts')
</body>
</html>
