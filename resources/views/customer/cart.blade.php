@extends('layouts.app')

@section('title', 'Keranjang Saya - TERRA Cafe')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">

    <!-- Header Judul -->
    <div class="space-y-1">
        <span class="text-[10px] font-extrabold tracking-[3px] text-[#8C7A6B] uppercase">PESANAN ANDA</span>
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#2C2623]">
            Keranjang Saya
        </h1>
        <p class="text-xs text-gray-500 font-medium">Pastikan pesananmu sudah benar sebelum lanjut ke pembayaran.</p>
    </div>

    @if($cart && $cart->items->count() > 0)
        <!-- Layout Grid Dua Kolom -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- KOLOM KIRI: Pilihan Metode & Daftar Pesanan -->
            <div class="lg:col-span-7 space-y-6">
                
                <!-- 1. Pilihan Metode (Makan di mana?) -->
                <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-5 shadow-[0_4px_15px_rgba(44,38,35,0.03)] space-y-3">
                    <span class="text-xs font-extrabold text-[#2C2623] block">Makan di mana?</span>
                    <div class="grid grid-cols-3 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="order_type" value="delivery" checked class="peer hidden order-type-radio" onchange="updateShipping()">
                            <div class="py-2.5 px-3 rounded-xl border border-[#E5E0D8] text-center text-xs font-bold text-[#2C2623] bg-[#FAF8F5] peer-checked:bg-[#2C2623] peer-checked:text-white peer-checked:border-[#2C2623] transition">
                                <i class="fa-solid fa-bicycle mr-1"></i> Delivery
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="order_type" value="take_away" class="peer hidden order-type-radio" onchange="updateShipping()">
                            <div class="py-2.5 px-3 rounded-xl border border-[#E5E0D8] text-center text-xs font-bold text-[#2C2623] bg-[#FAF8F5] peer-checked:bg-[#2C2623] peer-checked:text-white peer-checked:border-[#2C2623] transition">
                                <i class="fa-solid fa-bag-shopping mr-1"></i> Take Away
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="order_type" value="dine_in" class="peer hidden order-type-radio" onchange="updateShipping()">
                            <div class="py-2.5 px-3 rounded-xl border border-[#E5E0D8] text-center text-xs font-bold text-[#2C2623] bg-[#FAF8F5] peer-checked:bg-[#2C2623] peer-checked:text-white peer-checked:border-[#2C2623] transition">
                                <i class="fa-solid fa-utensils mr-1"></i> Dine In
                            </div>
                        </label>
                    </div>
                </div>

                <!-- 2. Daftar Pesanan -->
                <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-6 shadow-[0_4px_15px_rgba(44,38,35,0.03)] space-y-4">
                    <div class="flex items-center justify-between border-b border-[#F0ECE7] pb-3">
                        <span class="text-xs font-extrabold text-[#2C2623] uppercase tracking-wider flex items-center gap-2">
                            <i class="fa-solid fa-bag-shopping text-[#C49A6C]"></i> Daftar Pesanan
                        </span>
                        <span class="text-xs font-bold text-[#8C7A6B]">{{ $cart->items->sum('quantity') }} Item</span>
                    </div>

                    <div class="divide-y divide-[#F0ECE7]">
                        @foreach($cart->items as $item)
                            @php
                                $isLarge = str_contains($item->note, 'Large') || str_contains($item->note, 'Besar');
                                $itemPrice = $item->price > 0 ? $item->price : ($item->product->price + ($isLarge ? 3000 : 0));
                            @endphp
                            <div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                
                                <div class="flex items-center space-x-4">
                                    <!-- Foto Produk -->
                                    <div class="w-16 h-16 rounded-[16px] bg-[#F5F2EE] border border-[#F0ECE7] overflow-hidden flex-shrink-0 flex items-center justify-center">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-solid fa-mug-hot text-gray-400 text-sm"></i>
                                        @endif
                                    </div>

                                    <!-- Nama & Harga Produk -->
                                    <div class="space-y-0.5">
                                        <h3 class="font-extrabold text-[#2C2623] text-sm sm:text-base">{{ $item->product->name }}</h3>
                                        <p class="text-xs font-bold text-[#8C7A6B]">Rp {{ number_format($itemPrice, 0, ',', '.') }}</p>
                                        @if($item->note)
                                            <span class="inline-block px-2.5 py-0.5 bg-[#FAF8F5] border border-[#E5E0D8] text-[10px] text-gray-500 rounded-lg mt-1 font-medium">
                                                {{ $item->note }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Tombol Kuantitas & Hapus -->
                                <div class="flex items-center justify-between w-full sm:w-auto gap-4">
                                    <form action="{{ route('customer.cart.update', $item->id) }}" method="POST" class="flex items-center space-x-1.5 bg-[#FAF8F5] border border-[#E5E0D8] rounded-xl p-1">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" name="quantity" value="{{ max(0, $item->quantity - 1) }}" class="w-6 h-6 rounded-lg bg-white shadow-2xs flex items-center justify-center text-xs font-bold hover:bg-gray-100 transition">-</button>
                                        <span class="w-8 text-center text-xs font-bold text-[#2C2623]">{{ $item->quantity }}</span>
                                        <button type="submit" name="quantity" value="{{ $item->quantity + 1 }}" class="w-6 h-6 rounded-lg bg-white shadow-2xs flex items-center justify-center text-xs font-bold hover:bg-gray-100 transition">+</button>
                                    </form>

                                    <form action="{{ route('customer.cart.remove', $item->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-100 flex items-center justify-center transition cursor-pointer" title="Hapus Item">
                                            <i class="fa-solid fa-trash-can text-xs"></i>
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>

            </div>

            <!-- KOLOM KANAN: Ringkasan Belanja & Checkout -->
            <div class="lg:col-span-5 bg-white border border-[#EAE5DF] rounded-[24px] p-6 sm:p-7 shadow-[0_4px_15px_rgba(44,38,35,0.03)] space-y-6">
                
                <h3 class="font-extrabold text-[#2C2623] text-base border-b border-[#F0ECE7] pb-3">Ringkasan Belanja</h3>

                <!-- Input Kode Voucher -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-extrabold text-[#2C2623]">Kode Voucher</label>
                    <div class="flex gap-2">
                        <input type="text" id="voucher-code-input" placeholder="CTH: TERRA20" class="w-full px-4 py-2.5 bg-[#FAF8F5] border border-[#EAE5DF] rounded-xl text-xs font-medium text-[#2C2623] focus:outline-none focus:border-[#2C2623]">
                        <button type="button" onclick="applyVoucher()" class="px-4 py-2.5 bg-[#4A5342] hover:bg-[#3B4235] text-white rounded-xl text-xs font-extrabold transition shadow-2xs cursor-pointer">
                            Terapkan
                        </button>
                    </div>
                    <span class="text-[10px] text-gray-400 block" id="voucher-message">Gunakan TERRA20 untuk diskon 20% (min. Rp50rb).</span>
                </div>

                @php
                    $subtotal = $cart->items->sum(function($i) {
                        $isLarge = str_contains($i->note, 'Large') || str_contains($i->note, 'Besar');
                        $price = $i->price > 0 ? $i->price : ($i->product->price + ($isLarge ? 3000 : 0));
                        return $price * $i->quantity;
                    });
                @endphp

                <!-- Rincian Biaya -->
                <div class="space-y-2.5 pt-2 text-xs border-t border-[#F0ECE7]">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal Belanja</span>
                        <span class="font-bold text-[#2C2623]" id="subtotal-val" data-subtotal="{{ $subtotal }}">
                            Rp {{ number_format($subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Diskon Voucher (<span id="voucher-label">-</span>)</span>
                        <span class="font-bold text-emerald-600" id="discount-text">- Rp 0</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Biaya Pengiriman / Layanan</span>
                        <span class="font-bold text-[#2C2623]" id="shipping-text">Rp 10.000</span>
                    </div>
                </div>

                <!-- Total Tagihan -->
                <div class="border-t border-[#F0ECE7] pt-4 flex justify-between items-center">
                    <span class="font-extrabold text-[#2C2623] text-sm">Total Tagihan</span>
                    <span class="font-extrabold text-[#2C2623] text-xl" id="total-text">
                        Rp {{ number_format($subtotal + 10000, 0, ',', '.') }}
                    </span>
                </div>

                <!-- Tombol Lanjut Checkout -->
                <div class="pt-2">
                    <a href="{{ route('customer.checkout') }}" class="w-full block text-center py-3.5 bg-[#4A5342] hover:bg-[#3B4235] text-white rounded-[20px] text-xs font-extrabold transition duration-300 shadow-sm cursor-pointer">
                        Lanjut Checkout &rarr;
                    </a>
                </div>

            </div>

        </div>

    @else
        <!-- Tampilan Keranjang Kosong -->
        <div class="bg-white border border-[#EAE5DF] rounded-[24px] p-16 text-center shadow-sm space-y-4 max-w-xl mx-auto">
            <div class="w-16 h-16 rounded-2xl bg-[#FAF8F5] border border-[#E5E0D8] flex items-center justify-center mx-auto text-[#C9B49B] text-2xl">
                <i class="fa-solid fa-cart-shopping"></i>
            </div>
            <h4 class="font-extrabold text-[#2C2623] text-base">Keranjang belanja Anda masih kosong</h4>
            <p class="text-xs text-gray-400 max-w-sm mx-auto leading-relaxed">
                Belum ada produk yang ditambahkan. Mari jelajahi katalog menu kami sekarang.
            </p>
            <div class="pt-2">
                <a href="{{ route('public.menu') }}" class="inline-flex items-center gap-2 bg-[#2C2623] text-white px-6 py-3 rounded-xl text-xs font-bold hover:bg-black transition shadow-sm">
                    Mulai Belanja &rarr;
                </a>
            </div>
        </div>
    @endif

</div>

<!-- Script Kalkulasi Dinamis -->
<script>
let appliedDiscount = 0;

function updateShipping() {
    const selectedType = document.querySelector('input[name="order_type"]:checked').value;
    const subtotal = parseInt(document.getElementById('subtotal-val').getAttribute('data-subtotal')) || 0;
    
    let shippingCost = 10000;
    if (selectedType === 'take_away' || selectedType === 'dine_in') {
        shippingCost = 0;
    }

    const total = (subtotal - appliedDiscount) + shippingCost;

    document.getElementById('shipping-text').innerText = 'Rp ' + shippingCost.toLocaleString('id-ID');
    document.getElementById('total-text').innerText = 'Rp ' + Math.max(0, total).toLocaleString('id-ID');
}

function applyVoucher() {
    const code = document.getElementById('voucher-code-input').value;
    const msgEl = document.getElementById('voucher-message');

    fetch("{{ route('customer.cart.voucher') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ code: code })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            appliedDiscount = data.discount;
            document.getElementById('discount-text').innerText = '- Rp ' + appliedDiscount.toLocaleString('id-ID');
            document.getElementById('voucher-label').innerText = data.voucher_code;
            msgEl.innerText = data.message;
            msgEl.className = "text-[10px] text-emerald-600 font-bold block";
            updateShipping();
        } else {
            msgEl.innerText = data.message;
            msgEl.className = "text-[10px] text-red-500 font-bold block";
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>
@endsection
