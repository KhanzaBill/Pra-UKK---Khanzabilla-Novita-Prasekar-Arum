<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminMenuController;
use App\Http\Controllers\AdminReportController;
use App\Http\Middleware\AdminAuthMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Pemesanan Makanan QR Code Yummy Chicken
|--------------------------------------------------------------------------
*/

// --- RUTE PELANGGAN (Mobile-First) ---
Route::get('/', [CustomerController::class, 'landing'])->name('customer.landing');
Route::post('/set-order-type', [CustomerController::class, 'setOrderType'])->name('customer.set_order_type');
Route::get('/menu', [CustomerController::class, 'menu'])->name('customer.menu');
Route::get('/menu/{id}', [CustomerController::class, 'detailMenu'])->name('customer.detail_menu');
Route::post('/cart/add', [CustomerController::class, 'addToCart'])->name('customer.add_to_cart');
Route::get('/cart', [CustomerController::class, 'cart'])->name('customer.cart');
Route::post('/cart/update', [CustomerController::class, 'updateCart'])->name('customer.update_cart');
Route::get('/cart/remove/{hash}', [CustomerController::class, 'removeFromCart'])->name('customer.remove_cart');
Route::get('/checkout', [CustomerController::class, 'checkout'])->name('customer.checkout');
Route::post('/checkout/store', [CustomerController::class, 'storeOrder'])->name('customer.store_order');
Route::get('/receipt/{id}', [CustomerController::class, 'receipt'])->name('customer.receipt');
Route::get('/api/order-status/{id}', [CustomerController::class, 'orderStatusJson'])->name('customer.order_status_json');


// --- RUTE KASIR / ADMIN ---
Route::get('/admin', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

Route::middleware([AdminAuthMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Pesanan Masuk
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update_status');
    Route::post('/orders/{id}/payment', [AdminOrderController::class, 'updatePayment'])->name('orders.update_payment');

    // Kelola Menu (CRUD)
    Route::get('/menus', [AdminMenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/create', [AdminMenuController::class, 'create'])->name('menus.create');
    Route::post('/menus', [AdminMenuController::class, 'store'])->name('menus.store');
    Route::get('/menus/{id}/edit', [AdminMenuController::class, 'edit'])->name('menus.edit');
    Route::put('/menus/{id}', [AdminMenuController::class, 'update'])->name('menus.update');
    Route::post('/menus/{id}/toggle-stok', [AdminMenuController::class, 'toggleStok'])->name('menus.toggle_stok');
    Route::delete('/menus/{id}', [AdminMenuController::class, 'destroy'])->name('menus.destroy');

    // Kelola Tambahan (CRUD)
    Route::post('/tambahans', [AdminMenuController::class, 'storeTambahan'])->name('tambahans.store');
    Route::put('/tambahans/{id}', [AdminMenuController::class, 'updateTambahan'])->name('tambahans.update');
    Route::post('/tambahans/{id}/toggle-stok', [AdminMenuController::class, 'toggleStokTambahan'])->name('tambahans.toggle_stok');
    Route::delete('/tambahans/{id}', [AdminMenuController::class, 'destroyTambahan'])->name('tambahans.destroy');

    // Laporan Penjualan
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');

    // QR Code Meja
    Route::get('/qrcodes', [AdminMenuController::class, 'qrCodes'])->name('qrcodes');
});
