{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="en">

<head>
    {{-- ... head content ... --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @livewireStyles
</head>

<body class="font-sans antialiased">

    {{-- 1. Inisialisasi Alpine.js di pembungkus paling luar --}}
    <div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: true }">

        {{-- 2. Panggil sidebar yang sudah Anda buat --}}
        @include('layouts.sidebar')

        {{-- 3. Buat wrapper untuk konten utama dengan margin dinamis --}}
        <div class="flex-1 flex flex-col transition-all duration-300"
            :class="{ 'md:ml-64': sidebarOpen, 'md:ml-0': !sidebarOpen }">

            {{-- 4. Panggil navigasi atas --}}

            @include('layouts.navigation')

            @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
            @endif

            <main>
                {{ $slot }}
            </main>

        </div>
    </div>

    @livewireScripts
</body>

</html>