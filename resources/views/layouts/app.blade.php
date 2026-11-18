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
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body class="bg-[#FAF8F5] text-gray-800 font-sans antialiased flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <!-- Logo & Brand -->
                <div class="flex items-center">
                    <a href="{{ request()->routeIs('public.index') ? '#beranda' : route('public.index') }}" class="text-xl font-bold tracking-wider text-[#2C2623] flex items-center space-x-2">
                        <span class="w-8 h-8 rounded-full border border-[#2C2623] flex items-center justify-center text-xs">☕</span>
                        <span>TERRA</span>
                    </a>
                </div>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center space-x-8">
                    @if(request()->routeIs('public.*'))
                        <a href="{{ route('public.index') }}#beranda" class="text-sm font-medium text-gray-700 hover:text-black transition">Beranda</a>
                        <a href="{{ route('public.index') }}#tentang" class="text-sm font-medium text-gray-700 hover:text-black transition">Tentang Kami</a>
                        <a href="{{ route('public.menu') }}" class="text-sm font-medium text-gray-700 hover:text-black transition">Menu Andalan</a>
                        <a href="{{ route('public.index') }}#lokasi" class="text-sm font-medium text-gray-700 hover:text-black transition">Lokasi</a>
                    @else
                        @auth
                            @if(auth()->user()->role === 'customer')
                                <a href="{{ route('customer.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-black">Beranda</a>
                                <a href="{{ route('public.menu') }}" class="text-sm font-medium text-gray-700 hover:text-black">Menu</a>
                                <a href="{{ route('customer.orders.index') }}" class="text-sm font-medium text-gray-700 hover:text-black">Pesanan Saya</a>
                                <a href="{{ route('customer.favorites') }}" class="text-sm font-medium text-gray-700 hover:text-black">Favorit</a>
                            @endif
                        @endauth
                    @endif
                </div>

                <!-- Right Actions -->
                <div class="flex items-center space-x-4">
                    @auth
                        @if(auth()->user()->role === 'customer')

                            <a href="{{ route('customer.cart') }}" class="relative text-gray-700 hover:text-black p-2">
                                <i class="fa-solid fa-cart-shopping text-lg"></i>
                            </a>
                        @endif
                        
                        <div class="relative flex items-center space-x-3 border-l pl-4 border-gray-200">
                            <span class="text-sm font-medium text-gray-700">Halo, {{ auth()->user()->name }}</span>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">Keluar</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-2.5 bg-[#2C2623] text-white rounded-full text-sm font-medium hover:bg-black transition shadow-sm">
                            LOGIN
                        </a>
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

    <!-- FOOTER GLOBAL (Tampil di Publik & Customer) - Dipercantik -->
    <footer class="bg-[#181615] text-white pt-20 pb-10 border-t border-gray-800/80 mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 pb-16 border-b border-gray-800/80">
                
                <!-- Kolom 1: Brand & Tentang (Span 5) -->
                <div class="md:col-span-5 space-y-4">
                    <div class="flex items-center space-x-2.5">
                        <span class="w-9 h-9 rounded-xl bg-white/10 border border-gray-700 flex items-center justify-center text-sm">☕</span>
                        <span class="text-lg font-bold tracking-widest text-white uppercase">{{ $settings['cafe_name'] ?? 'TERRA' }}</span>
                    </div>
                    <p class="text-xs text-gray-400 leading-relaxed max-w-sm">
                        {{ $settings['about_text'] ?? ($settings['cafe_name'] ?? 'TERRA Cafe') . ' adalah ruang di mana kopi berkualitas, hidangan pilihan, dan cerita hangat berpadu menjadi satu.' }}
                    </p>

                    <!-- Sosial Media Ikon Modern -->
                    <div class="pt-2 flex items-center space-x-3">
                        @if(!empty($settings['instagram_url']))
                            <a href="{{ $settings['instagram_url'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-[#2C2623] border border-gray-800 hover:border-gray-600 flex items-center justify-center text-gray-300 hover:text-white transition duration-300 text-xs shadow-sm" title="Instagram">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        @endif
                        @if(!empty($settings['tiktok_url']))
                            <a href="{{ $settings['tiktok_url'] }}" target="_blank" class="w-9 h-9 rounded-xl bg-white/5 hover:bg-[#2C2623] border border-gray-800 hover:border-gray-600 flex items-center justify-center text-gray-300 hover:text-white transition duration-300 text-xs shadow-sm" title="TikTok">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Kolom 2: Pintasan (Span 3) -->
                <div class="md:col-span-3 space-y-4">
                    <h4 class="text-[11px] font-bold uppercase tracking-widest text-gray-300">Pintasan Navigasi</h4>
                    <ul class="space-y-2.5 text-xs text-gray-400">
                        <li><a href="{{ route('public.index') }}" class="hover:text-white transition flex items-center space-x-1.5"><span class="text-gray-600">&rsaquo;</span> <span>Beranda Utama</span></a></li>
                        <li><a href="{{ route('public.menu') }}" class="hover:text-white transition flex items-center space-x-1.5"><span class="text-gray-600">&rsaquo;</span> <span>Katalog Menu Kami</span></a></li>
                        <li><a href="{{ route('public.index') }}#tentang" class="hover:text-white transition flex items-center space-x-1.5"><span class="text-gray-600">&rsaquo;</span> <span>Cerita & Nilai TERRA</span></a></li>
                        <li><a href="{{ route('public.index') }}#lokasi" class="hover:text-white transition flex items-center space-x-1.5"><span class="text-gray-600">&rsaquo;</span> <span>Lokasi & Kontak</span></a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Kontak & Jam Operasional (Span 4) -->
                <div class="md:col-span-4 space-y-4">
                    <h4 class="text-[11px] font-bold uppercase tracking-widest text-gray-300">Informasi & Kontak</h4>
                    <div class="space-y-3 text-xs text-gray-400">
                        <div class="flex items-start space-x-2.5">
                            <i class="fa-solid fa-location-dot text-gray-500 mt-0.5"></i>
                            <p class="leading-relaxed"><span class="text-gray-300 font-medium">Alamat:</span><br>{{ $settings['cafe_address'] ?? 'Belum diatur' }}</p>
                        </div>
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-phone text-gray-500"></i>
                            <p><span class="text-gray-300 font-medium">Telepon / WA:</span> {{ $settings['cafe_phone'] ?? '-' }}</p>
                        </div>
                        <div class="flex items-center space-x-2.5">
                            <i class="fa-solid fa-clock text-gray-500"></i>
                            <p><span class="text-gray-300 font-medium">Jam Buka:</span> {{ $settings['cafe_hours'] ?? '09.00 - 22.00 WIB' }}</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Copyright & Baris Bawah -->
            <div class="pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-gray-500 space-y-4 sm:space-y-0">
                <p>&copy; 2026 <span class="text-gray-400 font-medium">{{ $settings['cafe_name'] ?? 'TERRA Cafe' }}</span>. Seluruh hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

</body>
</html>
