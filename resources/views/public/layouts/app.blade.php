<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TalentMapping - Discover Your Potential')</title>

    <link rel="stylesheet" href="{{ asset('assets/public/css/app.css') }}">

    @if (request()->routeIs('home'))
        <link rel="stylesheet" href="{{ asset('assets/public/css/pages/home.css') }}">
    @endif

    @if (request()->routeIs('test.*'))
        <link rel="stylesheet" href="{{ asset('assets/public/css/pages/test.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/public/css/pages/st30-test.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/public/css/pages/sjt-test.css') }}">
    @endif

    @stack('styles')  <!-- TAMBAHKAN INI -->
    @yield('styles')
</head>

<body>
    @includeWhen(!isset($hideNavbar), 'public.layouts.navbar')

    <main class="main-content">
        @yield('content')
    </main>

    @includeWhen(!isset($hideFooter), 'public.layouts.footer')

    <script src="{{ asset('assets/public/js/public.js') }}"></script>
    @stack('scripts')  <!-- TAMBAHKAN INI -->
    @yield('scripts')
</body>
</html>
