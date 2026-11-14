<?php

use Illuminate\Support\Facades\Route;

// --- CONTROLLER IMPORTS ---
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PublicController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\VoucherController as AdminVoucherController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

// Customer Controllers
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\FavoriteController;
use App\Http\Controllers\Customer\PromoController;
use App\Http\Controllers\Customer\ProfileController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. ROUTE PUBLIK (Tamu & Umum)
// ==========================================
Route::get('/', [PublicController::class, 'index'])->name('public.index');
Route::get('/menu', [PublicController::class, 'menu'])->name('public.menu');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/location', [PublicController::class, 'location'])->name('public.location');


// ==========================================
// 2. ROUTE AUTENTIKASI (Login & Register)
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.process');

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.process');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


// ==========================================
// 3. ROUTE CUSTOMER (Khusus Peran Customer)
// ==========================================
Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {

    // Dashboard & Detail Produk
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/products/{id}', [CustomerProductController::class, 'show'])->name('products.show');

    // Keranjang Belanja (Cart)
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'removeItem'])->name('cart.remove');

    // Checkout & Pembayaran QRIS
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/payment/qris/{orderId}', [PaymentController::class, 'showQris'])->name('payment.qris');
    Route::post('/payment/qris/confirm/{orderId}', [PaymentController::class, 'confirmQris'])->name('payment.qris.confirm');

    // Pesanan (Orders) & Pelacakan
    Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [CustomerOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/reorder/{id}', [CustomerOrderController::class, 'reorder'])->name('orders.reorder');

    // Favorit & Promo
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('/favorites/toggle/{product}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/promos', [PromoController::class, 'index'])->name('promos');

    // Profil & Edit Profil
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});


// ==========================================
// 4. ROUTE ADMIN (Khusus Peran Admin)
// ==========================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kelola Kategori
    Route::resource('categories', AdminCategoryController::class)->except(['show', 'edit', 'update']);

    // Kelola Produk Menu
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::patch('/products/{id}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('products.toggle');

    // Kelola Pesanan Masuk
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    // Kelola Voucher & Promo
    Route::resource('vouchers', AdminVoucherController::class)->except(['show', 'edit', 'update']);

    // Data Pelanggan (Customer)
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');

    // Laporan Penjualan & Ekspor CSV
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-csv', [AdminReportController::class, 'exportCsv'])->name('reports.csv');

    // Pengaturan Kafe (Settings)
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
});
