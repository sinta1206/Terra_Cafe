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

    <!-- FOOTER GLOBAL (Tampil di Publik & Customer) -->
    <footer class="bg-[#1C1C1E] text-white pt-16 pb-8 border-t border-gray-800 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pb-12 border-b border-gray-800">
                <!-- Kolom 1: Tentang Cafe & Sosmed Bulat -->
                <div>
                    <div class="flex items-center space-x-2 text-lg font-bold tracking-wider">
                        <span>☕</span>
                        <span>{{ $settings['cafe_name'] ?? 'TERRA' }}</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-4 leading-relaxed max-w-sm">
                        {{ $settings['about_text'] ?? ($settings['cafe_name'] ?? 'TERRA Cafe') . ' adalah ruang di mana kopi, makanan enak, dan percakapan hangat berpadu menjadi satu.' }}
                    </p>

                    <!-- Sosial Media Ikon Bulat -->
                    <div class="flex items-center space-x-3 mt-4">
                        @if(!empty($settings['instagram_url']))
                            <a href="{{ $settings['instagram_url'] }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-800 hover:bg-gray-700 flex items-center justify-center text-gray-300 hover:text-white transition text-xs" title="Instagram">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        @endif
                        @if(!empty($settings['tiktok_url']))
                            <a href="{{ $settings['tiktok_url'] }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-800 hover:bg-gray-700 flex items-center justify-center text-gray-300 hover:text-white transition text-xs" title="TikTok">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Kolom 2: Pintasan -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Pintasan</h4>
                    <ul class="space-y-2 text-xs text-gray-300">
                        <li><a href="{{ route('public.index') }}" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="{{ route('public.menu') }}" class="hover:text-white transition">Menu Kami</a></li>
                        <li><a href="{{ route('public.index') }}#tentang" class="hover:text-white transition">Cerita Kami</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Kontak & Tautan Ikon Sosmed Teks -->
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Kontak</h4>
                    <p class="text-xs text-gray-300 mb-2">Telepon / WhatsApp: {{ $settings['cafe_phone'] ?? '-' }}</p>
                    <p class="text-xs text-gray-400 mb-4">Alamat: {{ $settings['cafe_address'] ?? '-' }}</p>

                    <!-- Tambahan Ikon Sosmed dengan Label Teks di Kolom Kontak -->
                    <div class="space-y-2 text-xs text-gray-300 pt-2 border-t border-gray-800">
                        @if(!empty($settings['instagram_url']))
                            <div class="flex items-center space-x-2">
                                <i class="fa-brands fa-instagram text-gray-400 w-4"></i>
                                <a href="{{ $settings['instagram_url'] }}" target="_blank" class="hover:text-white transition">Instagram Resmi</a>
                            </div>
                        @endif
                        @if(!empty($settings['tiktok_url']))
                            <div class="flex items-center space-x-2">
                                <i class="fa-brands fa-tiktok text-gray-400 w-4"></i>
                                <a href="{{ $settings['tiktok_url'] }}" target="_blank" class="hover:text-white transition">TikTok Resmi</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="pt-8 text-center text-xs text-gray-500">
                <p>&copy; 2026 {{ $settings['cafe_name'] ?? 'TERRA Cafe' }}. Seluruh hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

</body>
</html>
