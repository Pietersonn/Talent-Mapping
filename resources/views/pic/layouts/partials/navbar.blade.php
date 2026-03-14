<header class="sticky top-0 z-30 w-full bg-white/80 backdrop-blur-md border-b border-gray-100 h-20 px-6 sm:px-8 flex items-center justify-between transition-all duration-300">

    <div class="flex items-center gap-4 lg:gap-6">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 text-gray-500 hover:text-green-600 hover:bg-green-50 rounded-xl transition-all">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <div class="flex flex-col">
            <span class="text-xs text-gray-400 font-medium mt-1">
                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
            </span>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-5">
        {{-- Search (Opsional) --}}
        <div class="hidden md:block relative group">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-green-500 transition-colors">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" placeholder="Cari data..."
                   class="bg-gray-50 border-none rounded-full py-2.5 pl-11 pr-4 w-56 focus:w-72 text-sm text-gray-700 focus:ring-2 focus:ring-green-500/20 focus:bg-white placeholder-gray-400 transition-all duration-300">
        </div>

        <div class="h-8 w-px bg-gray-100 hidden sm:block"></div>

        <button onclick="confirmLogout()"
           class="p-2.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all duration-200 group"
           title="Logout">
            <i class="fas fa-sign-out-alt text-xl group-hover:scale-110 transition-transform"></i>
        </button>

    </div>
</header>
