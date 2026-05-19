<aside
    class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-gray-100 transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col"
    :class="sidebarOpen ? 'translate-x-0 shadow-[4px_0_24px_rgba(0,0,0,0.02)]' : '-translate-x-full'"
>
    {{-- Logo Header --}}
    <div class="h-24 flex items-center px-8 border-b border-gray-50/50 bg-white">
        <a href="{{ route('pic.dashboard') }}" class="flex items-center gap-3 group transition-all">
            <img src="{{ asset('assets/public/images/tm-logo.png') }}" alt="TalentMapping" class="h-9 w-auto group-hover:scale-105 transition-transform duration-300">
            <div class="flex flex-col">
                <span class="font-bold text-gray-800 text-lg leading-tight tracking-tight">Tallent<span class="text-green-500">Mapping</span></span>
                <span class="text-[10px] text-gray-400 font-medium tracking-widest uppercase">PIC SITE</span>
            </div>
        </a>
    </div>

    {{-- Menu Items --}}
    <nav class="flex-1 overflow-y-auto custom-scrollbar py-6 px-4 space-y-1 scroll-smooth">

        {{-- DASHBOARD --}}
        <a href="{{ route('pic.dashboard') }}"
           class="flex items-center px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 group {{ request()->routeIs('pic.dashboard') ? 'bg-green-50 text-green-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-th-large {{ request()->routeIs('pic.dashboard') ? 'text-green-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            </div>
            Dashboard
        </a>

        <div class="pt-6 pb-2 px-4">
            <p class="text-[10px] font-extrabold text-gray-300 uppercase tracking-[0.2em]">Manajemen</p>
        </div>

        {{-- EVENT (Opsional, tapi penting untuk PIC) --}}
        <a href="{{ route('pic.events.index') }}"
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('pic.events.*') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-calendar-alt {{ request()->routeIs('pic.events.*') ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-500' }}"></i>
            </div>
            Event
        </a>

        {{-- PESERTA --}}
        <a href="{{ route('pic.participants.index') }}"
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('pic.participants.*') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-users {{ request()->routeIs('pic.participants.*') ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-500' }}"></i>
            </div>
            Peserta
        </a>

        <a href="{{ route('pic.score.index') }}"
           class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 group {{ request()->routeIs('pic.results.*') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <div class="w-6 flex justify-center mr-3">
                <i class="fas fa-chart-pie {{ request()->routeIs('pic.results.*') ? 'text-green-500' : 'text-gray-300 group-hover:text-gray-500' }}"></i>
            </div>
            Kompetensi Peserta
        </a>

    </nav>

    {{-- User Profile --}}
    <div class="p-4 bg-white border-t border-gray-50">
        <div class="flex items-center gap-3 p-3 rounded-2xl bg-gray-50/80 hover:bg-green-50 transition-colors border border-gray-100 group">
            <a href="#" class="relative shrink-0">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-blue-200">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-white rounded-full flex items-center justify-center">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                </div>
            </a>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-gray-400 font-medium uppercase tracking-wide truncate">PIC PANEL</p>
            </div>
            <button onclick="confirmLogout()" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Logout">
                <i class="fas fa-power-off"></i>
            </button>
        </div>
    </div>
</aside>

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
