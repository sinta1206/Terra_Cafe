@extends('layouts.app')

@section('title', 'Dashboard Pelanggan - TERRA Cafe')

@section('content')

<style>
    html {
        scroll-behavior: smooth;
    }

    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-none {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .product-card {
        animation: fadeUp .45s ease both;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .hero-banner {
        background:
            linear-gradient(105deg, #2C2623 0%, rgba(44,38,35,0.92) 50%, rgba(44,38,35,0.4) 100%),
            url('https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=1200&q=85');
        background-size: cover;
        background-position: center;
    }
</style>

<!-- Kontainer Utama dengan spasi yang rapat dan rapi -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-8">

    <!-- HERO BANNER -->
    <div class="hero-banner rounded-[28px] min-h-[320px] md:min-h-[360px] p-6 md:p-10 relative overflow-hidden flex flex-col justify-between shadow-lg border border-[#3E3531]">

        <div class="relative z-10 max-w-xl space-y-3">

            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 backdrop-blur-md text-[#E8DCCB] text-[10px] font-bold tracking-[2px] uppercase px-3.5 py-1.5 rounded-full">
                <i class="fa-solid fa-mug-hot text-[#D9C1A5]"></i>
                Welcome to TERRA Cafe
            </span>

            <h1 class="text-3xl md:text-5xl font-extrabold text-white leading-[1.12] tracking-tight">
                Satu teguk.<br>
                <span class="text-[#D9C1A5]">Sejuta cerita.</span>
            </h1>

            <p class="text-xs md:text-sm text-gray-200 leading-relaxed max-w-md font-medium">
                Kopi yang diracik dengan hati, untuk setiap momen yang berarti. Temukan rasa favoritmu dan nikmati hari bersama {{ $settings['cafe_name'] ?? 'TERRA' }}.
            </p>

            <a href="#menu-section"
               class="inline-flex items-center gap-2.5 bg-[#D9C1A5] hover:bg-white text-[#2C2623] px-6 py-3 rounded-xl text-xs font-extrabold transition duration-300 shadow-md">
                Jelajahi Menu
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <div class="relative z-10 flex items-center gap-6 pt-4 text-white/80 text-[10px] font-bold tracking-widest border-t border-white/10">
            <span><i class="fa-solid fa-coffee text-[#D9C1A5] mr-1"></i> FRESH COFFEE</span>
            <span><i class="fa-solid fa-heart text-[#D9C1A5] mr-1"></i> MADE WITH LOVE</span>
        </div>

        <div class="absolute -right-12 -bottom-20 w-64 h-64 rounded-full border border-white/10 pointer-events-none"></div>
    </div>


    <!-- PENCARIAN DAN KATEGORI -->
    <div id="menu-section" class="space-y-4">

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 md:p-6 rounded-[24px] border border-[#E5E0D8] shadow-2xs">

            <div>
                <span class="text-[9px] font-extrabold tracking-[2px] text-[#8C7A6B] uppercase">
                    EXPLORE OUR MENU
                </span>
                <h2 class="text-xl md:text-2xl font-extrabold text-[#2C2623] mt-0.5">
                    Mau pesan apa hari ini?
                </h2>
            </div>

            <form action="{{ route('public.menu') }}" method="GET" class="w-full md:w-72">
                <div class="relative group">
                    <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text"
                           name="search"
                           placeholder="Cari kopi, makanan, camilan..."
                           class="w-full pl-10 pr-4 py-2.5 bg-[#FAF8F5] border border-[#E5E0D8] rounded-xl text-xs focus:outline-none focus:border-[#2C2623] focus:bg-white focus:ring-2 focus:ring-[#2C2623]/10 transition">
                </div>
            </form>

        </div>

        <!-- KATEGORI PIL -->
        <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">

            <button type="button"
                    onclick="filterCategory('all', this)"
                    class="category-btn px-4 py-2.5 bg-[#2C2623] text-white rounded-xl text-xs font-bold flex-shrink-0 shadow-xs transition cursor-pointer">
                <i class="fa-solid fa-border-all mr-1 text-[#D9C1A5]"></i>
                Semua Menu
            </button>

            @foreach($categories as $category)
                <button type="button"
                        onclick="filterCategory('{{ $category->slug }}', this)"
                        class="category-btn px-4 py-2.5 bg-white border border-[#E5E0D8] text-gray-700 rounded-xl text-xs font-bold flex-shrink-0 hover:bg-[#F5F0EA] hover:border-[#D9C1A5] transition cursor-pointer shadow-2xs">
                    {{ $category->name }}
                </button>
            @endforeach

        </div>

    </div>


    <!-- KATALOG PRODUK -->
    <div class="space-y-5">

        <div class="flex items-end justify-between px-1">
            <div>
                <span class="text-[9px] font-extrabold tracking-[2px] text-[#8C7A6B] uppercase">
                    HANDPICKED FOR YOU
                </span>
                <h3 class="text-xl font-extrabold text-[#2C2623] mt-0.5">
                    Menu Pilihan
                </h3>
            </div>

            <span class="hidden sm:inline-flex items-center gap-1.5 bg-[#EFECE6] text-[#2C2623] px-3.5 py-1.5 rounded-xl text-[10px] font-extrabold tracking-wider border border-[#E5E0D8]">
                <i class="fa-solid fa-star text-[#C49A6C]"></i>
                BEST SELLER
            </span>
        </div>

        @if($products->count() > 0)

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">

                @foreach($products as $product)

                    <div class="product-card bg-white border border-[#EAE5DF] rounded-[24px] p-4 shadow-[0_4px_15px_rgba(44,38,35,0.03)] hover:shadow-[0_8px_25px_rgba(44,38,35,0.07)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between"
                         data-category="{{ optional($product->category)->slug }}">

                        <div>
                            <!-- FOTO PRODUK (Tanpa tulisan favorit, hanya tombol love di pojok kanan atas) -->
                            <div class="h-44 rounded-[16px] overflow-hidden relative group bg-[#F5F2EE] border border-[#F0ECE7]">

                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <i class="fa-solid fa-image text-2xl"></i>
                                    </div>
                                @endif

                                <form action="{{ route('customer.favorites.toggle', $product->id) }}"
                                    method="POST"
                                    class="absolute top-2.5 right-2.5">
                                    @csrf
                                    @php
                                        // Cek apakah produk ini ada di koleksi favorites yang sudah dimuat
                                        $isFavorite = $product->favorites->isNotEmpty();
                                    @endphp
                                    <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-white/95 shadow-md flex items-center justify-center transition hover:scale-110 cursor-pointer" title="Kelola Favorit">
                                        <i class="fa-solid fa-heart text-[10px] {{ $isFavorite ? 'text-red-500' : 'text-gray-400' }}"></i>
                                    </button>
                                </form>

                            </div>

                            <!-- INFORMASI PRODUK -->
                            <div class="pt-3 px-1 space-y-1">

                                <span class="text-[9px] font-extrabold text-[#8C7A6B] uppercase tracking-widest block">
                                    {{ $product->category->name ?? 'Menu' }}
                                </span>

                                <h4 class="font-extrabold text-[#2C2623] text-sm leading-snug line-clamp-1">
                                    {{ $product->name }}
                                </h4>

                                <p class="text-[11px] text-gray-500 leading-relaxed line-clamp-2 min-h-[30px]">
                                    {{ $product->description }}
                                </p>

                            </div>

                        </div>

                        <!-- HARGA DAN DETAIL -->
                        <div class="mt-4 pt-3 border-t border-[#F0ECE7] flex items-center justify-between gap-3">

                            <div>
                                <span class="text-[9px] text-gray-400 block font-medium uppercase">Harga</span>
                                <span class="font-extrabold text-[#2C2623] text-sm">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </span>
                            </div>

                            <a href="{{ route('customer.products.show', $product->id) }}"
                               class="inline-flex items-center gap-1 bg-[#FAF8F5] hover:bg-[#2C2623] hover:text-white text-[#2C2623] px-3.5 py-2 rounded-xl text-[11px] font-extrabold transition duration-300 border border-[#E5E0D8] shadow-2xs">
                                Detail
                                <i class="fa-solid fa-arrow-right text-[8px]"></i>
                            </a>

                        </div>

                    </div>

                @endforeach

            </div>

        @else

            <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-10 text-center shadow-sm">
                <i class="fa-solid fa-mug-hot text-3xl text-[#C9B49B] mb-3"></i>
                <h4 class="font-extrabold text-[#2C2623] text-xs">
                    Menu belum tersedia
                </h4>
                <p class="text-[11px] text-gray-400 mt-1">
                    Silakan pilih kategori lainnya.
                </p>
            </div>

        @endif

        <!-- LIHAT SEMUA MENU -->
        <div class="text-center pt-4">
            <a href="{{ route('public.menu') }}"
               class="inline-flex items-center gap-2.5 bg-white border border-[#D5CEC5] hover:bg-[#2C2623] hover:text-white hover:border-[#2C2623] text-[#2C2623] px-7 py-3 rounded-xl text-xs font-extrabold transition duration-300 shadow-xs">
                Lihat Semua Menu
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

    </div>

</div>


<!-- FILTER KATEGORI SCRIPT -->
<script>
    function filterCategory(categorySlug, buttonElement) {
        const buttons = document.querySelectorAll('.category-btn');

        buttons.forEach(btn => {
            btn.classList.remove(
                'bg-[#2C2623]',
                'text-white',
                'font-bold',
                'shadow-xs'
            );
            btn.classList.add(
                'bg-white',
                'border',
                'border-[#E5E0D8]',
                'text-gray-700',
                'font-bold',
                'shadow-2xs'
            );
        });

        buttonElement.classList.remove(
            'bg-white',
            'border',
            'border-[#E5E0D8]',
            'text-gray-700',
            'font-bold',
            'shadow-2xs'
        );
        buttonElement.classList.add(
            'bg-[#2C2623]',
            'text-white',
            'font-bold',
            'shadow-xs'
        );

        const cards = document.querySelectorAll('.product-card');

        cards.forEach(card => {
            const cardCategory = card.getAttribute('data-category');
            if (categorySlug === 'all' || cardCategory === categorySlug) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>

@endsection
