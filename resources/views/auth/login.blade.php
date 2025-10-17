<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang, Customer Service</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-200 w-full h-screen flex items-center justify-center">
    <div class="flex w-3/4 h-[500px] bg-white rounded-lg shadow-lg overflow-hidden">
        <!-- Left Column with Background Image -->
        <div class="w-[40%] bg-[url('/public/images/background-vodeco.webp')] bg-cover bg-center flex flex-col items-center justify-center py-8 relative">
            <svg width="50" viewBox="0 0 43 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="absolute top-8 left-8">
                <path d="M41.0612 0.299086C41.3107 0.302453 41.5548 0.373117 41.7672 0.504164C41.9796 0.635216 42.1523 0.821724 42.2672 1.04323C42.3821 1.26485 42.4348 1.51378 42.4196 1.76295C42.4043 2.01221 42.3225 2.25325 42.1813 2.45924L32.2506 17.0188L24.9713 27.6487L22.5709 31.0393C22.3839 31.3016 22.1366 31.5151 21.8502 31.6624C21.5637 31.8097 21.2459 31.8859 20.9244 31.885C20.6028 31.884 20.2858 31.8056 20.0016 31.6565C19.7176 31.5074 19.4735 31.2921 19.2907 31.0286L14.4108 23.9788C14.2982 23.8131 14.2368 23.6176 14.235 23.4182C14.2333 23.2189 14.2914 23.024 14.401 22.8587L22.6412 10.6184L29.941 0.188735L41.0612 0.299086ZM1.16077 0.209243C4.50077 -0.250757 9.63132 -0.0806792 12.4713 2.03932C18.1608 6.29939 17.5305 13.9689 12.7106 18.7688C12.5677 18.911 12.395 19.0196 12.2047 19.0862C12.0144 19.1528 11.8114 19.1756 11.611 19.1536C11.4107 19.1315 11.2177 19.0655 11.0465 18.9592C10.8753 18.8528 10.7293 18.7092 10.6207 18.5393C5.20783 10.12 1.81149 4.82643 0.431281 2.65944C0.197947 2.29944 0.0580254 1.96203 0.0113588 1.6487C-0.0114076 1.48313 8.0654e-05 1.31464 0.0455384 1.1526C0.0910227 0.990531 0.169227 0.838114 0.276007 0.70436C0.382842 0.570584 0.515794 0.457297 0.667609 0.372328C0.819407 0.287381 0.987232 0.2324 1.16077 0.209243Z" fill="white" />
            </svg>
            <h1 class="font-bold text-left text-6xl text-white drop-shadow-lg px-7">Selamat Datang</h1>
        </div>

        <!-- Right Column with Login Form -->
        <div class="w-1/2 p-6 flex flex-col justify-center">
            <h2 class="text-2xl font-bold mb-2 text-left">Login</h2>
            <p class="text-gray-500 text-[16px]">Selamat Datang di Dashboard Customer Service <br>Vodeco</p>
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-500 mb-2 text-[16px]">Email</label>
                    <input type="email" name="email" placeholder="Masukan Email" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-900">
                </div>
                <div>
                    <label class="block text-gray-500 mb-2 text-[16px]">Masukkan Password</label>
                    <input type="password" name="password" placeholder="Masukan Password" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" class="mr-2">
                        <label for="remember" class="text-gray-500 text-[16px]">Ingat Saya</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-blue-600 text-[16px]">Lupa password?</a>
                </div>
                <button type="submit" class="px-4 bg-navy-700 text-white py-2 rounded-lg hover:bg-navy-800 transition duration-200">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>

</html>