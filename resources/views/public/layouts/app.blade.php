<!DOCTYPE html>
<html lang="{{ str_replace('_','-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TalentMapping - Discover Your Potential')</title>

    {{-- BARIS YANG PERLU DITAMBAHKAN --}}
    <link rel="icon" href="{{ asset('assets/public/images/tm-logo.png') }}" type="image/png">

    <script src="{{ asset('assets/public/js/app.js') }}" defer></script>

    {{-- App base CSS (override Bootstrap bila perlu) --}}
    <link rel="stylesheet" href="{{ asset('assets/public/css/app.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/public/css/components/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/public/css/components/footer.css') }}">
    {{-- Page CSS by route --}}
    @if (request()->routeIs('home'))
        <link rel="stylesheet" href="{{ asset('assets/public/css/pages/home.css') }}">
    @endif

    @if (request()->routeIs('test.*'))
        <link rel="stylesheet" href="{{ asset('assets/public/css/pages/test.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/public/css/pages/st30-test.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/public/css/pages/sjt-test.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/public/css/pages/thanks.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/public/css/profile.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/public/css/components/stepper.css') }}">
    @endif

    @stack('styles')
    @yield('styles')
</head>

<body>
    @includeWhen(!isset($hideNavbar), 'public.layouts.navbar')

    <main class="main-content">
        @yield('content')
    </main>

    @includeWhen(!isset($hideFooter), 'public.layouts.footer')

    {{-- jQuery + Popper + Bootstrap JS --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- App JS lama (biarkan, tidak dihapus) --}}
    <script src="{{ asset('assets/public/js/public.js') }}"></script>

    @stack('scripts')
    @yield('scripts')
</body>

</html>
