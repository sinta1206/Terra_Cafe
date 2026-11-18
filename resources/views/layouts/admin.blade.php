<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - TERRA Cafe')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome untuk Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#FAF8F5] text-[#2C2623] antialiased">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-[#FAF8F5] border-r border-[#EFECE6] hidden md:flex flex-col justify-between select-none">
            <div>
                <!-- Brand / Logo -->
                <div class="h-20 flex items-center px-6">
                    <div class="flex items-center space-x-2">
                        <div class="bg-[#2C2623] text-white p-2 rounded-lg">
                            <i class="fa-solid fa-mug-hot text-sm"></i>
                        </div>
                        <div>
                            <span class="text-sm font-bold tracking-widest text-[#2C2623] block">TERRA</span>
                            <span class="text-[9px] text-gray-500 tracking-wider block">CAFE & GOOD COMPANY</span>
                        </div>
                    </div>
                </div>

                <!-- Kategori Teks Menu Kecil -->
                <div class="px-6 pt-2 pb-3">
                    <p class="text-[10px] font-semibold tracking-wider text-gray-400 uppercase">PENGELOLAAN CAFE</p>
                </div>

                <!-- Navigation Menu -->
                <nav class="px-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#EFECE6] text-[#2C2623] font-semibold' : 'text-gray-600 hover:bg-[#F3F0EC]' }}">
                        <i class="fa-solid fa-chart-pie w-5 mr-3 text-gray-500"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.categories.*') ? 'bg-[#EFECE6] text-[#2C2623] font-semibold' : 'text-gray-600 hover:bg-[#F3F0EC]' }}">
                        <i class="fa-solid fa-list w-5 mr-3 text-gray-500"></i> Kategori
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.products.*') ? 'bg-[#EFECE6] text-[#2C2623] font-semibold' : 'text-gray-600 hover:bg-[#F3F0EC]' }}">
                        <i class="fa-solid fa-mug-hot w-5 mr-3 text-gray-500"></i> Produk
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.orders.*') ? 'bg-[#EFECE6] text-[#2C2623] font-semibold' : 'text-gray-600 hover:bg-[#F3F0EC]' }}">
                        <div class="flex items-center justify-between w-full">
                            <span class="flex items-center"><i class="fa-solid fa-receipt w-5 mr-3 text-gray-500"></i> Pesanan</span>
                            @isset($pendingOrdersCount)
                                @if($pendingOrdersCount > 0)
                                    <span class="bg-amber-100 text-amber-700 text-xs px-2 py-0.5 rounded-full font-bold">{{ $pendingOrdersCount }}</span>
                                @endif
                            @endisset
                        </div>
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.customers.*') ? 'bg-[#EFECE6] text-[#2C2623] font-semibold' : 'text-gray-600 hover:bg-[#F3F0EC]' }}">
                        <i class="fa-solid fa-users w-5 mr-3 text-gray-500"></i> Customer
                    </a>
                    <a href="{{ route('admin.vouchers.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.vouchers.*') ? 'bg-[#EFECE6] text-[#2C2623] font-semibold' : 'text-gray-600 hover:bg-[#F3F0EC]' }}">
                        <i class="fa-solid fa-ticket w-5 mr-3 text-gray-500"></i> Voucher
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.reports.*') ? 'bg-[#EFECE6] text-[#2C2623] font-semibold' : 'text-gray-600 hover:bg-[#F3F0EC]' }}">
                        <i class="fa-solid fa-file-excel w-5 mr-3 text-gray-500"></i> Laporan
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2.5 text-sm font-medium rounded-xl transition {{ request()->routeIs('admin.settings.*') ? 'bg-[#EFECE6] text-[#2C2623] font-semibold' : 'text-gray-600 hover:bg-[#F3F0EC]' }}">
                        <i class="fa-solid fa-gear w-5 mr-3 text-gray-500"></i> Pengaturan
                    </a>
                </nav>
            </div>

            <!-- User Info & Logout Bottom (Disesuaikan dengan Akun Login) -->
            <div class="p-4 border-t border-[#EFECE6] flex items-center justify-between">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div class="w-8 h-8 rounded-full bg-[#2C2623] text-white flex items-center justify-center font-bold text-xs uppercase flex-shrink-0">
                        {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-bold text-[#2C2623] truncate">{{ auth()->user()->name ?? 'Admin Cafe' }}</p>
                        <p class="text-[10px] text-gray-500 truncate">{{ auth()->user()->email ?? 'admin@terracafe.com' }}</p>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="flex-shrink-0">
                    @csrf
                    <button type="submit" title="Keluar" class="text-gray-400 hover:text-red-600 p-2 rounded-lg transition">
                        <i class="fa-solid fa-right-from-bracket text-sm"></i>
                    </button>
                </form>
            </div>
        </aside>

        <!-- CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col overflow-y-auto bg-[#FAF8F5]">

            <!-- TOPBAR MOBILE -->
            <header class="h-16 flex items-center justify-between px-8 bg-[#FAF8F5] md:hidden">
                <span class="text-sm font-bold">TERRA ADMIN</span>
                <span class="text-xs font-medium text-gray-700">{{ auth()->user()->name ?? 'Admin' }}</span>
            </header>

            <!-- MAIN VIEW CONTENT -->
            <main class="p-6 md:p-8 max-w-7xl w-full mx-auto">
                <!-- NOTIFIKASI SUCCESS -->
                @if(session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs font-medium flex items-center justify-between shadow-xs">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

</body>
</html>
