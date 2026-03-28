{{--
    Pastikan file AppServiceProvider sudah diupdate.
    Variabel $user dikirim otomatis dari sana.
--}}
<aside
    class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100 transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col"
    :class="sidebarOpen ? 'translate-x-0 shadow-[4px_0_24px_rgba(0,0,0,0.02)]' : '-translate-x-full'"
>
    {{-- LOGO HEADER --}}
    <div class="h-24 flex items-center px-8 border-b border-gray-50/50 bg-white">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group transition-all">
            <img src="{{ asset('assets/public/images/tm-logo.png') }}" alt="TalentMapping" class="h-9 w-auto group-hover:scale-105 transition-transform duration-300">
            <div class="flex flex-col">
                <span class="font-bold text-gray-800 text-lg leading-tight tracking-tight">Talent<span class="text-green-500">Mapping</span></span>
                <span class="text-[10px] text-gray-400 font-medium tracking-widest uppercase">System</span>
            </div>
        </a>
    </div>

    {{-- MENU LIST --}}
    <nav class="flex-1 overflow-y-auto custom-scrollbar py-6 px-4 space-y-1 scroll-smooth">

        {{-- DASHBOARD --}}
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-green-50 text-green-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-th-large {{ request()->routeIs('admin.dashboard') ? 'text-green-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            </div>
            Dashboard
        </a>

        {{-- SECTION: BANK SOAL --}}
        <div class="pt-6 pb-2 px-4">
            <p class="text-[10px] font-extrabold text-gray-300 uppercase tracking-[0.2em]">Bank Soal & Data</p>
        </div>

        {{-- 1. All Versions (General) --}}
        <a href="{{ route('admin.questions.index') }}"
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.questions.index') || request()->routeIs('admin.questions.show') || request()->routeIs('admin.questions.create') || request()->routeIs('admin.questions.edit') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-layer-group {{ request()->routeIs('admin.questions.index') || request()->routeIs('admin.questions.show') || request()->routeIs('admin.questions.create') || request()->routeIs('admin.questions.edit') ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-500' }}"></i>
            </div>
            Versi Soal
        </a>

        {{-- 2. ST-30 Questions --}}
        <a href="{{ route('admin.questions.st30.index') }}"
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.questions.st30.*') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-list-ol {{ request()->routeIs('admin.questions.st30.*') ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-500' }}"></i>
            </div>
            ST-30
        </a>

        {{-- 3. SJT Questions --}}
        <a href="{{ route('admin.questions.sjt.index') }}"
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.questions.sjt.*') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-tasks {{ request()->routeIs('admin.questions.sjt.*') ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-500' }}"></i>
            </div>
            Talent Kompetensi
        </a>

        {{-- 4. Competencies --}}
        <a href="{{ route('admin.questions.competencies.index') }}"
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.questions.competencies.*') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-award {{ request()->routeIs('admin.questions.competencies.*') ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-500' }}"></i>
            </div>
            Kompetensi
        </a>

        {{-- 5. Typologies --}}
        <a href="{{ route('admin.questions.typologies.index') }}"
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.questions.typologies.*') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-fingerprint {{ request()->routeIs('admin.questions.typologies.*') ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-500' }}"></i>
            </div>
            Tipologi
        </a>

        {{-- SECTION: OPERASIONAL --}}
        <div class="pt-6 pb-2 px-4">
            <p class="text-[10px] font-extrabold text-gray-300 uppercase tracking-[0.2em]">Operasional</p>
        </div>

        @if (isset($user) && $user->role === 'admin')
        <a href="{{ route('admin.users.index') }}"
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.users.*') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-users-cog {{ request()->routeIs('admin.users.*') ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-500' }}"></i>
            </div>
            Manajemen Pengguna
        </a>
        @endif

        <a href="{{ route('admin.programs.index') }}"
           class="flex items-center justify-between px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.programs.*') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="flex items-center">
                <div class="w-6 flex justify-center mr-3">
                    <i class="fas fa-calendar-check {{ request()->routeIs('admin.programs.*') ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-500' }}"></i>
                </div>
                Manajemen program
            </div>
            @if (isset($user) && $user->role === 'staff')
                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
            @endif
        </a>

        {{-- SECTION: LAPORAN & HASIL --}}
        <div class="pt-6 pb-2 px-4">
            <p class="text-[10px] font-extrabold text-gray-300 uppercase tracking-[0.2em]">Laporan & Hasil</p>
        </div>

        <a href="{{ route('admin.results.index') }}"
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.results.*') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-poll {{ request()->routeIs('admin.results.*') ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-500' }}"></i>
            </div>
            Peserta
        </a>

        {{-- FIX: Logika routeIs diganti ke admin.score.* agar highlight aktif --}}
        <a href="{{ route('admin.score.index') }}"
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.score.*') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-file-invoice {{ request()->routeIs('admin.score.*') ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-500' }}"></i>
            </div>
            Kompetensi Peserta
        </a>

        <a href="{{ route('admin.resend.index') }}"
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('admin.resend.*') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-paper-plane {{ request()->routeIs('admin.resend.*') ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-500' }}"></i>
            </div>
            Permintaan
        </a>

    </nav>

    {{-- FOOTER: PROFILE & LOGOUT --}}
    <div class="p-4 bg-white border-t border-gray-50">
        <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50/80 hover:bg-green-50 transition-colors border border-gray-100 group">
            <a href="{{ route('admin.profile.edit') }}" class="relative shrink-0">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-green-200">
                    {{ isset($user) ? substr($user->name, 0, 1) : 'U' }}
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-white rounded-full flex items-center justify-center">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                </div>
            </a>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-800 truncate">{{ isset($user) ? $user->name : 'Guest' }}</p>
                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide truncate">{{ isset($user) ? ucfirst($user->role) : 'Guest' }}</p>
            </div>
            <button onclick="confirmLogout()" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Logout">
                <i class="fas fa-power-off"></i>
            </button>
        </div>
    </div>
</aside>

{{-- SCRIPT: LOGOUT CONFIRMATION --}}
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Logout?',
            text: 'Yakin mau menyudahi sesi ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#f3f4f6',
            confirmButtonText: '<span style="font-weight:600">Yes, Logout</span>',
            cancelButtonText: '<span style="color:#6b7280; font-weight:500">Batal</span>',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-[20px] border border-gray-100 shadow-xl',
                confirmButton: 'rounded-xl px-6 py-2.5 shadow-lg shadow-green-100',
                cancelButton: 'rounded-xl px-6 py-2.5'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('logout') }}';
                form.innerHTML = '@csrf';
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
