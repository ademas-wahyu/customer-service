<aside class="w-64 h-screen bg-gray-800 text-white flex flex-col fixed"
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="-translate-x-full"
    x-transition:enter-end="translate-x-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="translate-x-0"
    x-transition:leave-end="-translate-x-full"
    @click.away="open = false"
    x-cloak>

    <div class="h-16 flex items-center justify-center border-b border-gray-700">
        <h1 class="text-2xl font-bold">Customer Service</h1>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-2">
        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 rounded-md hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-900' : '' }}">
            <x-icons.dashboard />
            Dashboard
        </a>
        <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-gray-700">
            <x-icons.users />
            Pengguna
        </a>
        <a href="#" class="flex items-center px-4 py-2 rounded-md hover:bg-gray-700">
            <x-icons.settings />
            Pengaturan
        </a>
    </nav>

</aside>