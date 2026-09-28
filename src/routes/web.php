<?php

use Illuminate\Support\Facades\Route;                        // Import facade Route untuk mendefinisikan rute web
use App\Http\Controllers\CustomerController;                  // Import CustomerController untuk rute halaman pelanggan
use App\Http\Controllers\AdminAuthController;                 // Import AdminAuthController untuk rute login/logout admin
use App\Http\Controllers\AdminOrderController;                // Import AdminOrderController untuk rute manajemen pesanan
use App\Http\Controllers\AdminMenuController;                 // Import AdminMenuController untuk rute manajemen menu & tambahan
use App\Http\Controllers\AdminBahanController;                // Import AdminBahanController untuk rute manajemen bahan baku
use App\Http\Controllers\AdminReportController;               // Import AdminReportController untuk rute laporan penjualan
use App\Http\Middleware\AdminAuthMiddleware;                   // Import middleware autentikasi admin

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Pemesanan Makanan QR Code Yummy Chicken
|--------------------------------------------------------------------------
*/

// --- RUTE PELANGGAN (Mobile-First) ---
// Semua rute ini dapat diakses publik tanpa login

Route::get('/', [CustomerController::class, 'landing'])->name('customer.landing');
// GET /  → Halaman landing (entry point setelah scan QR Code meja)

Route::post('/set-order-type', [CustomerController::class, 'setOrderType'])->name('customer.set_order_type');
// POST /set-order-type  → Menyimpan tipe pesanan (Dine-In / Take Away) ke session dan redirect ke menu

Route::get('/menu', [CustomerController::class, 'menu'])->name('customer.menu');
// GET /menu  → Halaman daftar menu dengan filter kategori dan pencarian

Route::get('/menu/{id}', [CustomerController::class, 'detailMenu'])->name('customer.detail_menu');
// GET /menu/{id}  → Halaman detail satu menu beserta pilihan level pedas, bagian ayam, dan tambahan

Route::post('/cart/add', [CustomerController::class, 'addToCart'])->name('customer.add_to_cart');
// POST /cart/add  → Menambah atau mengedit item di keranjang belanja

Route::get('/cart', [CustomerController::class, 'cart'])->name('customer.cart');
// GET /cart  → Halaman keranjang belanja yang menampilkan semua item yang dipilih

Route::post('/cart/update', [CustomerController::class, 'updateCart'])->name('customer.update_cart');
// POST /cart/update  → Memperbarui kuantitas item di keranjang (tambah / kurangi)

Route::get('/cart/remove/{hash}', [CustomerController::class, 'removeFromCart'])->name('customer.remove_cart');
// GET /cart/remove/{hash}  → Menghapus satu item dari keranjang berdasarkan hash uniknya

Route::get('/checkout', [CustomerController::class, 'checkout'])->name('customer.checkout');
// GET /checkout  → Halaman konfirmasi pembayaran (validasi stok sebelum ditampilkan)

Route::post('/checkout/store', [CustomerController::class, 'storeOrder'])->name('customer.store_order');
// POST /checkout/store  → Memproses dan menyimpan pesanan ke database (transaksi atomik + kurangi stok)

Route::get('/receipt/{id}', [CustomerController::class, 'receipt'])->name('customer.receipt');
// GET /receipt/{id}  → Halaman struk pesanan yang menampilkan status dan detail pesanan

Route::get('/api/order-status/{id}', [CustomerController::class, 'orderStatusJson'])->name('customer.order_status_json');
// GET /api/order-status/{id}  → API endpoint JSON untuk polling status pesanan secara real-time (digunakan JavaScript)


// --- RUTE KASIR / ADMIN ---

Route::get('/admin', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
// GET /admin  → Halaman form login admin/kasir

Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
// POST /admin/login  → Memproses autentikasi login admin

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
// POST /admin/logout  → Memproses logout admin (menghapus session)

// Grup rute admin yang dilindungi middleware AdminAuthMiddleware
// Semua rute di grup ini hanya bisa diakses jika sudah login (session admin_id ada)
Route::middleware([AdminAuthMiddleware::class])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard Pesanan Masuk
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    // GET /admin/orders  → Dashboard pesanan masuk dengan filter status dan paginasi

    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update_status');
    // POST /admin/orders/{id}/status  → Update status pesanan (Diterima/Diproses/Selesai/Dibatalkan + kembalikan stok)

    Route::post('/orders/{id}/payment', [AdminOrderController::class, 'updatePayment'])->name('orders.update_payment');
    // POST /admin/orders/{id}/payment  → Update status pembayaran dan hitung kembalian (Tunai)

    // Kelola Menu (CRUD)
    Route::get('/menus', [AdminMenuController::class, 'index'])->name('menus.index');
    // GET /admin/menus  → Daftar semua menu dengan filter kategori dan pencarian

    Route::get('/menus/create', [AdminMenuController::class, 'create'])->name('menus.create');
    // GET /admin/menus/create  → Form tambah menu baru

    Route::post('/menus', [AdminMenuController::class, 'store'])->name('menus.store');
    // POST /admin/menus  → Simpan menu baru ke database (beserta foto dan relasi bahan)

    Route::get('/menus/{id}/edit', [AdminMenuController::class, 'edit'])->name('menus.edit');
    // GET /admin/menus/{id}/edit  → Form edit menu yang sudah ada

    Route::put('/menus/{id}', [AdminMenuController::class, 'update'])->name('menus.update');
    // PUT /admin/menus/{id}  → Update data menu (beserta foto dan sinkronisasi bahan)

    Route::post('/menus/{id}/toggle-stok', [AdminMenuController::class, 'toggleStok'])->name('menus.toggle_stok');
    // POST /admin/menus/{id}/toggle-stok  → Toggle status stok menu antara Tersedia/Habis secara cepat

    Route::delete('/menus/{id}', [AdminMenuController::class, 'destroy'])->name('menus.destroy');
    // DELETE /admin/menus/{id}  → Hapus menu dari database (setelah lepas relasi bahan)

    // Kelola Stok Bahan (CRUD)
    Route::get('/bahans', [AdminBahanController::class, 'index'])->name('bahans.index');
    // GET /admin/bahans  → Daftar semua bahan baku dengan filter status stok dan pencarian

    Route::post('/bahans', [AdminBahanController::class, 'store'])->name('bahans.store');
    // POST /admin/bahans  → Tambah bahan baku baru ke database

    Route::put('/bahans/{id}', [AdminBahanController::class, 'update'])->name('bahans.update');
    // PUT /admin/bahans/{id}  → Update data bahan baku

    Route::post('/bahans/{id}/quick-stock', [AdminBahanController::class, 'quickStock'])->name('bahans.quick_stock');
    // POST /admin/bahans/{id}/quick-stock  → Manajemen stok cepat (tambah / kurangi / set jumlah stok)

    Route::delete('/bahans/{id}', [AdminBahanController::class, 'destroy'])->name('bahans.destroy');
    // DELETE /admin/bahans/{id}  → Hapus bahan baku (setelah lepas semua relasi pivot)

    // Kelola Tambahan (CRUD) - dikelola melalui AdminMenuController
    Route::post('/tambahans', [AdminMenuController::class, 'storeTambahan'])->name('tambahans.store');
    // POST /admin/tambahans  → Tambah menu tambahan baru

    Route::put('/tambahans/{id}', [AdminMenuController::class, 'updateTambahan'])->name('tambahans.update');
    // PUT /admin/tambahans/{id}  → Update data menu tambahan

    Route::post('/tambahans/{id}/toggle-stok', [AdminMenuController::class, 'toggleStokTambahan'])->name('tambahans.toggle_stok');
    // POST /admin/tambahans/{id}/toggle-stok  → Toggle status stok tambahan antara Tersedia/Habis

    Route::delete('/tambahans/{id}', [AdminMenuController::class, 'destroyTambahan'])->name('tambahans.destroy');
    // DELETE /admin/tambahans/{id}  → Hapus menu tambahan dari database

    // Laporan Penjualan
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports');
    // GET /admin/reports  → Halaman laporan penjualan dengan filter rentang tanggal

    // QR Code Meja
    Route::get('/qrcodes', [AdminMenuController::class, 'qrCodes'])->name('qrcodes');
    // GET /admin/qrcodes  → Halaman tampilan QR Code untuk setiap meja restoran
});
