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
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="flex h-screen overflow-hidden">

        <!-- SIDEBAR -->
        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col justify-between">
            <div>
                <!-- Brand / Logo -->
                <div class="h-16 flex items-center px-6 border-b border-gray-100">
                    <span class="text-lg font-bold tracking-wider text-gray-900">☕ TERRA <span class="text-xs bg-gray-900 text-white px-2 py-0.5 rounded ml-1">ADMIN</span></span>
                </div>

                <!-- Navigation Menu -->
                <nav class="p-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fa-solid fa-chart-pie w-5 mr-3"></i> Beranda
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.categories.*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fa-solid fa-list w-5 mr-3"></i> Kelola Kategori
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.products.*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fa-solid fa-mug-hot w-5 mr-3"></i> Kelola Produk
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.orders.*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fa-solid fa-receipt w-5 mr-3"></i> Kelola Pesanan
                    </a>
                    <a href="{{ route('admin.vouchers.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.vouchers.*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fa-solid fa-ticket w-5 mr-3"></i> Voucher & Promo
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.customers.*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fa-solid fa-users w-5 mr-3"></i> Data Customer
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.reports.*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fa-solid fa-file-excel w-5 mr-3"></i> Laporan
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 text-sm font-medium rounded-xl {{ request()->routeIs('admin.settings.*') ? 'bg-gray-900 text-white' : 'text-gray-600 hover:bg-gray-50' }}">
                        <i class="fa-solid fa-gear w-5 mr-3"></i> Pengaturan
                    </a>
                </nav>
            </div>

            <!-- Logout Bottom -->
            <div class="p-4 border-t border-gray-100">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-flex flex items-center w-full px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 rounded-xl transition">
                        <i class="fa-solid fa-right-from-bracket w-5 mr-3"></i> Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- CONTENT WRAPPER -->
        <div class="flex-1 flex flex-col overflow-y-auto">

            <!-- TOPBAR -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 z-10">
                <h1 class="text-lg font-semibold text-gray-800">@yield('header-title', 'Dashboard Admin')</h1>
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }} (Admin)</span>
                </div>
            </header>

            <!-- MAIN VIEW -->
            <main class="p-8">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center justify-between">
                        <span>{{ session('success') }}</span>
                        <button onclick="this.parentElement.remove()" class="text-green-500">&times;</button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>

    </div>

</body>
</html>
