@extends('layouts.app')

@section('title', ($settings['cafe_name'] ?? 'TERRA Cafe') . ' - Ruang Nyaman untuk Setiap Ceritamu')

@section('content')

<!-- ================= 1. SECTION BERANDA / HERO ================= -->
<section id="beranda" class="py-16 md:py-24 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="md:w-1/2">
            <span class="text-xs uppercase tracking-widest bg-gray-200/60 px-3.5 py-1.5 rounded-full font-semibold text-gray-700">
                🕒 BUKA {{ $settings['cafe_hours'] ?? '09.00 - 22.00 WIB' }}
            </span>
            <h1 class="text-4xl md:text-6xl font-extrabold text-[#2C2623] mt-6 leading-[1.15]">
                Ruang nyaman untuk setiap ceritamu.
            </h1>
            <p class="text-gray-600 mt-6 text-base md:text-lg leading-relaxed">
                Lebih dari sekadar tempat ngopi, {{ $settings['cafe_name'] ?? 'TERRA Cafe' }} hadir untuk menemani waktu produktif dan momen santaimu.
            </p>
            <div class="mt-8">
                <a href="#menu" class="inline-flex items-center px-7 py-3.5 bg-[#2C2623] text-white rounded-full font-medium hover:bg-black transition shadow-sm">
                    Lihat Menu & Pesan &rarr;
                </a>
            </div>
        </div>

        <!-- Hero Card Image -->
        <div class="md:w-1/2 flex justify-center">
            <div class="relative w-full max-w-md bg-white p-3 rounded-3xl shadow-xl border border-gray-100">
                <div class="w-full h-80 md:h-96 bg-gray-200 rounded-2xl overflow-hidden flex items-center justify-center text-gray-400">
                    <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=800&q=80')"></div>
                </div>
                <!-- Rating Badge -->
                <div class="absolute bottom-6 left-6 bg-white/90 backdrop-blur-md px-4 py-2.5 rounded-2xl shadow-md flex items-center space-x-2 border border-gray-100">
                    <span class="text-yellow-500">⭐</span>
                    <div>
                        <p class="text-xs font-bold text-gray-900">Rating 4.9 / 5</p>
                        <p class="text-[10px] text-gray-500">Berdasarkan 500+ ulasan pelanggan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ================= 2. SECTION TENTANG KAMI (BLOK HIJAU SAGE) ================= -->
<section id="tentang" class="bg-[#414E43] text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center gap-12">
        <div class="md:w-1/2 flex space-x-4">
            <div class="w-1/2 h-64 bg-gray-700 rounded-2xl overflow-hidden shadow-sm">
                <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=500&q=80')"></div>
            </div>
            <div class="w-1/2 h-64 bg-gray-700 rounded-2xl overflow-hidden shadow-sm mt-8">
                <div class="w-full h-full bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=500&q=80')"></div>
            </div>
        </div>
        <div class="md:w-1/2">
            <span class="text-xs font-bold uppercase tracking-widest text-[#A3B1A6]">DISEDUH DENGAN HATI</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-white mt-2">Diseduh dengan Hati.</h2>
            <p class="text-gray-200 mt-4 text-sm md:text-base leading-relaxed">
                {{ $settings['about_text'] ?? 'TERRA diambil dari elemen bumi, merepresentasikan komitmen kami menggunakan bahan-bahan alami terbaik. Setiap biji kopi dipilih langsung dari petani lokal, dipanggang dengan presisi, dan diseduh dengan passion.' }}
            </p>
            <div class="grid grid-cols-2 gap-6 mt-6 pt-6 border-t border-gray-600">
                <div>
                    <h4 class="font-bold text-white text-base">100% Biji Lokal</h4>
                    <p class="text-xs text-gray-300 mt-1">Mendukung petani kopi nusantara dengan "Fair Trade".</p>
                </div>
                <div>
                    <h4 class="font-bold text-white text-base">Cozy Space</h4>
                    <p class="text-xs text-gray-300 mt-1">WiFi cepat, disokong di setiap meja, cocok untuk WFC.</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ================= 3. SECTION MENU ANDALAN ================= -->
<section id="menu" class="py-20 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-xs font-bold uppercase tracking-widest text-gray-500">FAVORIT PELANGGAN</span>
        <h2 class="text-3xl md:text-4xl font-extrabold text-[#2C2623] mt-2">Menu Andalan {{ $settings['cafe_name'] ?? 'TERRA' }}</h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mt-12 text-left">

            @if(isset($featuredProducts) && count($featuredProducts) > 0)
                @foreach($featuredProducts as $product)
                    <!-- Card Menu Dinamis dari Database -->
                    <div class="bg-white border border-gray-100 rounded-3xl p-5 shadow-sm flex flex-col justify-between">
                        <div>
                            <div class="h-56 bg-gray-100 rounded-2xl mb-4 overflow-hidden relative">
                                <span class="absolute top-3 left-3 bg-black/70 text-white text-[10px] px-2.5 py-1 rounded-full uppercase tracking-wider">Favorit</span>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400 text-xs">No Image</div>
                                @endif
                            </div>
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ $product->category->name ?? 'Menu' }}</span>
                            <h3 class="font-bold text-[#2C2623] text-lg mt-0.5">{{ $product->name }}</h3>
                            <p class="text-xs text-gray-500 mt-1.5 leading-relaxed line-clamp-2">{{ $product->description }}</p>
                        </div>

                        <!-- Tombol Lihat Detail -->
                        <div class="mt-6 pt-4 border-t border-gray-50">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-extrabold text-[#2C2623] text-base">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('public.product.detail', $product->id) }}" class="block w-full py-2.5 bg-[#FAF8F5] text-[#2C2623] text-center rounded-xl text-xs font-bold hover:bg-[#f0ebe1] transition border border-gray-200/60">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <!-- Fallback jika data kosong -->
                <div class="col-span-3 text-center text-gray-500 py-8">Belum ada menu andalan yang ditampilkan.</div>
            @endif

        </div>

        <div class="mt-12">
            <a href="{{ route('public.menu') }}" class="text-sm font-bold text-[#2C2623] underline hover:text-gray-600 transition">
                Lihat Menu Lengkapnya &rarr;
            </a>
        </div>
    </div>
</section>


<!-- ================= 4. SECTION LOKASI ================= -->
<section id="lokasi" class="py-20 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="md:w-1/2">
            <h2 class="text-3xl md:text-4xl font-extrabold text-[#2C2623]">Mampir ke {{ $settings['cafe_name'] ?? 'TERRA' }}.</h2>
            <p class="text-gray-600 mt-3 text-sm leading-relaxed">
                Terletak strategis di pusat kota. Tempat parkir luas, dan suasana yang tenang siap menyambutmu.
            </p>

            <div class="mt-6 space-y-4">
                <div class="flex items-start space-x-3">
                    <span class="text-lg">📍</span>
                    <div>
                        <h4 class="font-bold text-sm text-[#2C2623]">Alamat Kami</h4>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $settings['cafe_address'] ?? 'Jl. Soekarno Hatta No. 38, Banda Aceh' }}</p>
                    </div>
                </div>
                <div class="flex items-start space-x-3">
                    <span class="text-lg">🕒</span>
                    <div>
                        <h4 class="font-bold text-sm text-[#2C2623]">Jam Operasional</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Senin - Minggu<br>{{ $settings['cafe_hours'] ?? '09.00 - 22.00 WIB' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Google Maps Mockup -->
        <div class="md:w-1/2 w-full">
            <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100">
                <div class="w-full h-72 bg-gray-200 rounded-2xl overflow-hidden relative flex items-center justify-center">
                    <div class="absolute inset-0 bg-cover bg-center opacity-80" style="background-image: url('https://images.unsplash.com/photo-1526778548025-fa2f459cd5c1?auto=format&fit=crop&w=600&q=80')"></div>
                    <div class="relative bg-white/90 backdrop-blur-sm px-5 py-2.5 rounded-xl shadow-md text-center font-bold text-xs text-[#2C2623] border border-gray-200">
                        📍 {{ $settings['cafe_name'] ?? 'TERRA Cafe' }}<br><span class="text-[10px] font-normal text-gray-500">Buka di Google Maps</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
