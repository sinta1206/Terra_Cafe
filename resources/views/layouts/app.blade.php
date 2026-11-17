<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TERRA Cafe - Ruang Nyaman & Kopi Pilihan')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <!-- Logo & Brand -->
                <div class="flex items-center">
                    <a href="{{ auth()->check() && auth()->user()->role === 'customer' ? route('customer.dashboard') : route('public.index') }}" class="text-xl font-bold tracking-wider text-gray-900">
                        ☕ TERRA <span class="font-light text-gray-500">CAFE</span>
                    </a>
                </div>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center space-x-8">
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <a href="{{ route('customer.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-black">Beranda</a>
                            <a href="{{ route('public.menu') }}" class="text-sm font-medium text-gray-700 hover:text-black">Menu</a>
                            <a href="{{ route('customer.orders.index') }}" class="text-sm font-medium text-gray-700 hover:text-black">Pesanan Saya</a>
                            <a href="{{ route('customer.favorites') }}" class="text-sm font-medium text-gray-700 hover:text-black">Favorit</a>
                        @endif
                    @else
                        <a href="{{ route('public.index') }}" class="text-sm font-medium text-gray-700 hover:text-black">Beranda</a>
                        <a href="{{ route('public.menu') }}" class="text-sm font-medium text-gray-700 hover:text-black">Menu</a>
                        <a href="{{ route('public.about') }}" class="text-sm font-medium text-gray-700 hover:text-black">Tentang Kami</a>
                        <a href="{{ route('public.location') }}" class="text-sm font-medium text-gray-700 hover:text-black">Lokasi</a>
                    @endauth
                </div>

                <!-- Right Actions (Cart & Profile/Auth) -->
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->role === 'customer')
                            <!-- Keranjang -->
                            <a href="{{ route('customer.cart') }}" class="relative text-gray-700 hover:text-black p-2">
                                <i class="fa-solid fa-cart-shopping text-lg"></i>
                            </a>
                        @endif

                        <!-- Profil Dropdown / Menu User -->
                        <div class="relative flex items-center space-x-3 border-l pl-4 border-gray-200">
                            <span class="text-sm font-medium text-gray-700">Halo, {{ auth()->user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">Keluar</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-black px-4 py-2">Masuk</a>
                        <a href="{{ route('register') }}" class="text-sm font-medium bg-gray-900 text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- NOTIFIKASI / FLASH MESSAGE -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center justify-between">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-green-500 hover:text-green-700">&times;</button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4 w-full">
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm flex items-center justify-between">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700">&times;</button>
            </div>
        </div>
    @endif

    <!-- MAIN CONTENT -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-gray-100 mt-16 py-8 text-center text-sm text-gray-500">
        <p>&copy; 2026 TERRA Cafe. Seluruh hak cipta dilindungi.</p>
    </footer>

</body>
</html>
