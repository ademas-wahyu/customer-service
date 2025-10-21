<aside class="w-64 h-screen bg-white text-gray-700 flex flex-col fixed"
    x-show="sidebarOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    @click.away="sidebarOpen = false"
    x-cloak>

    <div class="h-16 flex flex-col items-center justify-center border-b border-navy-700">
        <h1 class="text-sm font-bold">Customer Service</h1>
        <img src="{{ asset('images/customer-support.png') }}" alt="Logo" class="h-10 ml-2">
    </div>

    <nav class="flex-1 px-4 py-4 space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-navy-700 hover:text-white {{ request()->routeIs('dashboard') ? 'bg-navy-700 text-white' : '' }}">
            <x-icons.dashboard />
            Dashboard
        </a>
        <a href="{{ route('akun-cs.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-navy-700 hover:text-white {{ request()->routeIs('akun-cs.index') ? 'bg-navy-700 text-white' : '' }}">
            <x-icons.users />
            Akun CS
        </a>
        <a href="{{ route('rekapan.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-navy-700 hover:text-white {{ request()->routeIs('rekapan.index') ? 'bg-navy-700 text-white' : '' }}">
            <svg class="w-5 h-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75c0-.231-.035-.454-.1-.664M6.75 7.5H18v2.25H6.75V7.5Z" />
            </svg>
            Rekapan
        </a>

    </nav>
    <div class="px-4 py-4">
        <a href="{{ route('pengaturan.index') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-navy-700 hover:text-white {{ request()->routeIs('pengaturan.index') ? 'bg-navy-700 text-white' : '' }}">
            <x-icons.settings />
            Pengaturan
        </a>
    </div>

</aside>