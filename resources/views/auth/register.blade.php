<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar — TalentMapping</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        .text-tm-green { color: #10b981; }
        .bg-tm-green { background-color: #10b981; }
        .bg-tm-green:hover { background-color: #059669; }

        /* Mencegah highlight biru saat tap di mobile */
        * { -webkit-tap-highlight-color: transparent; }

        div:where(.swal2-container) button:where(.swal2-styled).swal2-confirm {
            background-color: #10b981 !important;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 sm:p-6 relative overflow-hidden">

    <div class="absolute top-0 left-0 -translate-x-1/4 -translate-y-1/4 w-64 h-64 sm:w-96 sm:h-96 bg-green-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 translate-x-1/4 translate-y-1/4 w-64 h-64 sm:w-96 sm:h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000 pointer-events-none"></div>

    <div class="w-full max-w-md z-10">

        <div class="bg-white py-8 px-6 sm:py-10 sm:px-10 shadow-lg sm:shadow-[0_8px_30px_rgb(0,0,0,0.04)] rounded-2xl border border-gray-100 my-4 sm:my-8">

            <div class="text-center mb-6 sm:mb-8">
                <img class="mx-auto h-12 w-auto sm:h-14 drop-shadow-sm hover:scale-105 transition-transform duration-300" src="{{ asset('assets/public/images/tm-logo.png') }}" alt="TalentMapping Logo">

                <h2 class="mt-4 text-2xl font-extrabold text-gray-900 tracking-tight">
                    Talent<span class="text-tm-green">Mapping</span>
                </h2>
                <p class="mt-2 text-sm text-gray-500 px-4 sm:px-0">
                    Buat akun baru untuk memulai perjalanan Anda.
                </p>
            </div>

            <form class="space-y-4 sm:space-y-5" action="{{ route('register') }}" method="POST">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input id="name" name="name" type="text" required value="{{ old('name') }}" autofocus
                            class="appearance-none block w-full pl-10 pr-3 py-3 sm:py-2.5 border border-gray-300 rounded-lg placeholder-gray-400 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                            placeholder="Nama Lengkap Anda">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Alamat Email</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                            class="appearance-none block w-full pl-10 pr-3 py-3 sm:py-2.5 border border-gray-300 rounded-lg placeholder-gray-400 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                            placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-semibold text-gray-700 mb-1">Nomor Telepon</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <input id="phone_number" name="phone_number" type="tel" required value="{{ old('phone_number') }}"
                            class="appearance-none block w-full pl-10 pr-3 py-3 sm:py-2.5 border border-gray-300 rounded-lg placeholder-gray-400 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                            placeholder="08xxxxxxxxxx">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Kata Sandi</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="appearance-none block w-full pl-10 pr-10 py-3 sm:py-2.5 border border-gray-300 rounded-lg placeholder-gray-400 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
                            placeholder="Minimal 8 karakter">

                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" onclick="togglePassword()" class="text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer p-2 rounded-full active:bg-gray-100 transition">
                                <svg id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg id="eye-slash-icon" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 011.577-3.23m3.29-3.29a9.969 9.969 0 013.675-1.12c4.478 0 8.268 2.943 9.542 7a10.05 10.05 0 01-1.577 3.23M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-start pt-1">
                    <div class="flex items-center h-5">
                        <input id="terms" name="terms" type="checkbox" required class="h-5 w-5 sm:h-4 sm:w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded cursor-pointer">
                    </div>
                    <div class="ml-2 text-sm">
                        <label for="terms" class="font-medium text-gray-700 cursor-pointer py-1 block">
                            Saya setuju dengan <span class="text-green-600">Syarat & Ketentuan</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="w-full flex justify-center py-3 sm:py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-tm-green hover:bg-green-700 active:scale-95 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                    Daftar Sekarang
                </button>
            </form>

            <div class="mt-8 relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-3 bg-white text-gray-500 font-medium">Atau daftar dengan</span>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('login.google.redirect') }}" class="w-full flex items-center justify-center px-4 py-3 sm:py-2.5 border-2 border-gray-200 rounded-lg shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 active:bg-gray-100 hover:border-gray-300 transition duration-200">
                    <svg class="h-5 w-5 mr-3" aria-hidden="true" viewBox="0 0 24 24">
                        <path d="M12.0003 20.45c4.6667 0 8.525-3.375 9.875-7.875H12.0003v-4.125h12.5667c.125.625.1833 1.2833.1833 2 0 7.375-5.2583 12-12.75 12-7.1833 0-13-5.8167-13-13s5.8167-13 13-13c3.5083 0 6.6333 1.2917 9.025 3.5583l-3.6583 3.6583c-1.425-1.3667-3.25-2.0917-5.3667-2.0917-4.4167 0-8.1583 3.1917-9.4583 7.35z" fill="#4285F4" fill-rule="evenodd" clip-rule="evenodd"/>
                        <path d="M2.54199 10.4503c.425-1.2667 1.0583-2.4333 1.8667-3.4667l3.6583 3.6583c-.45.8667-.7083 1.85-.7083 2.9084s.2583 2.0416.7083 2.9083l-3.6583 3.6584c-.8084-1.0334-1.4417-2.2-1.8667-3.4667-.425-1.3083-.6417-2.6916-.6417-4.1s.2167-2.7916.6417-4.1z" fill="#FBBC05" fill-rule="evenodd" clip-rule="evenodd"/>
                        <path d="M12.0003 24c3.2417 0 6.175-1.125 8.425-3.025l-3.95-3.325c-1.1.75-2.55 1.225-4.475 1.225-3.625 0-6.8583-2.3583-8.0333-5.6917L.291992 16.275C2.52533 20.8167 7.20866 24 12.0003 24z" fill="#34A853" fill-rule="evenodd" clip-rule="evenodd"/>
                        <path d="M12.0003 4.80001c2.1167 0 3.9417.72501 5.3667 2.09169l3.6583-3.65833C18.6336 1.29167 15.5086 0 12.0003 0 7.20866 0 2.52533 3.18334.291992 7.72501l3.675008 3.09169c1.175-3.33336 4.4083-5.69169 8.0333-5.69169z" fill="#EA4335" fill-rule="evenodd" clip-rule="evenodd"/>
                    </svg>
                    <span class="text-gray-700">Daftar dengan Google</span>
                </a>
            </div>

            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="font-bold text-green-600 hover:text-green-500 transition p-1 rounded-md active:bg-green-50">
                        Masuk disini
                    </a>
                </p>
            </div>
        </div>

        <div class="mt-8 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} TalentMapping. All rights reserved.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeSlashIcon = document.getElementById('eye-slash-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                let errorHtml = '<div class="text-left"><ul class="list-disc pl-5 mt-2 space-y-1">';
                @foreach ($errors->all() as $error)
                    errorHtml += '<li class="text-sm">{{ $error }}</li>';
                @endforeach
                errorHtml += '</ul></div>';

                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mendaftar',
                    html: errorHtml,
                    confirmButtonText: 'Perbaiki',
                    confirmButtonColor: '#d33',
                    customClass: {
                        popup: 'rounded-xl',
                        confirmButton: 'rounded-lg'
                    }
                });
            @endif
        });
    </script>
</body>
</html>
