<?php

namespace App\Http\Controllers; // Mendefinisikan namespace controller berada di App\Http\Controllers

use Illuminate\Http\Request;              // Import class Request untuk menangani input HTTP
use App\Models\Menu;                      // Import model Menu untuk akses data menu
use App\Models\Tambahan;                  // Import model Tambahan untuk akses data menu add-on
use App\Models\Bahan;                     // Import model Bahan untuk akses data bahan baku
use App\Models\Meja;                      // Import model Meja untuk akses data meja restoran
use App\Models\Pesanan;                   // Import model Pesanan untuk akses data transaksi pesanan
use App\Models\DetailPesanan;             // Import model DetailPesanan untuk akses data item pesanan
use Illuminate\Support\Facades\DB;        // Import facade DB untuk transaksi database
use Carbon\Carbon;                        // Import library Carbon untuk manipulasi tanggal dan waktu

// Controller untuk menangani seluruh alur pemesanan dari sisi pelanggan
// Meliputi: landing page, daftar menu, detail menu, keranjang, checkout, dan struk pesanan
class CustomerController extends Controller
{
    /**
     * Halaman 1: Landing Page (Entry point setelah scan QR Code meja).
     * Mendeteksi nomor meja dari query string dan menyimpannya ke session.
     */
    // Halaman 1: Landing (QR Scan entry point)
    public function landing(Request $request)
    {
        $idMeja = $request->query('meja'); // Ambil ID meja dari query string (dari QR code scan)
        $meja   = null;                    // Inisialisasi variabel meja sebagai null (default jika tidak ada)

        // Jika ada parameter meja di URL (scan QR code berhasil)
        if ($idMeja) {
            // Cari meja berdasarkan ID numerik atau nomor meja (format "Meja 01")
            $meja = Meja::where('id_meja', $idMeja)
                        ->orWhere('nomor_meja', 'Meja ' . str_pad($idMeja, 2, '0', STR_PAD_LEFT)) // Padding nomor meja dengan angka nol di depan (contoh: "01", "02")
                        ->first();

            if ($meja) {
                // Simpan data meja ke session agar bisa diakses di halaman-halaman berikutnya
                session(['id_meja' => $meja->id_meja, 'nomor_meja' => $meja->nomor_meja]);
            }
        }

        return view('customer.landing', compact('meja')); // Tampilkan halaman landing dengan data meja (bisa null)
    }

    /**
     * Menyimpan tipe pesanan (Dine-In atau Take Away) ke session.
     * Dipanggil ketika pelanggan menekan tombol pilihan di halaman landing.
     */
    // Set tipe pesanan dari landing
    public function setOrderType(Request $request)
    {
        // Validasi input tipe pesanan
        $request->validate([
            'tipe_pesanan' => 'required|in:Dine-In,Take Away' // Hanya dua tipe pesanan yang valid
        ]);

        session(['tipe_pesanan' => $request->tipe_pesanan]); // Simpan tipe pesanan ke session

        // Jika Take Away, hapus data meja dari session (tidak perlu meja untuk Take Away)
        if ($request->tipe_pesanan === 'Take Away') {
            session()->forget(['id_meja', 'nomor_meja']); // Hapus session meja
        }

        return redirect()->route('customer.menu'); // Redirect ke halaman daftar menu
    }

    /**
     * Halaman 2: Daftar Menu.
     * Menampilkan menu dengan filter kategori dan pencarian.
     * Default menampilkan kategori 'Paket' jika tidak ada filter lain.
     */
    // Halaman 2: Daftar Menu
    public function menu(Request $request)
    {
        $search = trim($request->query('search', '')); // Ambil keyword pencarian, hapus spasi di tepi

        // Tentukan kategori yang aktif berdasarkan kondisi:
        if ($request->has('kategori')) {
            $kategoriActive = $request->query('kategori'); // Gunakan kategori dari query string jika ada
        } elseif ($search !== '') {
            $kategoriActive = 'Semua'; // Tampilkan semua kategori saat pencarian aktif
        } else {
            $kategoriActive = 'Paket'; // Default tampilkan kategori Paket
        }

        $query = Menu::with('bahans'); // Mulai query Menu dengan eager load relasi bahans (untuk cek ketersediaan)

        // Terapkan filter kategori jika bukan 'Semua'
        if ($kategoriActive && $kategoriActive !== 'Semua') {
            $query->where('kategori', $kategoriActive); // Filter berdasarkan kategori yang aktif
        }

        // Terapkan filter pencarian berdasarkan nama menu
        if ($search !== '') {
            $query->where('nama_menu', 'like', "%{$search}%"); // Filter nama menu yang mengandung keyword
        }

        $menus     = $query->get();                      // Ambil semua menu yang sesuai filter
        $cartCount = count(session('cart', []));          // Hitung jumlah item unik di keranjang untuk badge

        // Tampilkan view daftar menu dengan semua data yang diperlukan
        return view('customer.menu', compact('menus', 'kategoriActive', 'search', 'cartCount'));
    }

    /**
     * Halaman 3: Detail Menu.
     * Menampilkan informasi lengkap satu menu beserta daftar tambahan yang tersedia.
     * Jika menu tidak tersedia, redirect ke halaman daftar menu.
     * Mendukung mode edit item keranjang jika ada parameter edit_hash.
     */
    // Halaman 3: Detail Menu
    public function detailMenu(Request $request, $id)
    {
        $menu = Menu::with('bahans')->findOrFail($id); // Ambil menu beserta bahannya, throw 404 jika tidak ada

        // Cek ketersediaan menu sebelum ditampilkan
        if (!$menu->isTersedia()) {
            // Redirect ke daftar menu dengan pesan error jika menu tidak tersedia
            return redirect()->route('customer.menu')->with('error', 'Maaf, menu ' . $menu->nama_menu . ' saat ini sedang habis / tidak tersedia.');
        }

        $tambahans = Tambahan::with('bahans')->get(); // Ambil semua tambahan beserta bahannya (untuk cek ketersediaan)

        $editHash = $request->query('edit_hash'); // Ambil hash item yang akan diedit (jika mode edit)
        $editItem = null;                          // Inisialisasi data item yang diedit sebagai null

        // Jika ada hash edit dan item tersebut ada di keranjang, ambil data itemnya
        if ($editHash && session()->has("cart.{$editHash}")) {
            $editItem = session("cart.{$editHash}"); // Ambil data item dari session keranjang
        }

        // Tampilkan view detail menu dengan semua data yang diperlukan
        return view('customer.detail_menu', compact('menu', 'tambahans', 'editHash', 'editItem'));
    }

    /**
     * Tambah atau edit item di keranjang belanja.
     * Menangani validasi ketersediaan menu dan bahan,
     * penghitungan subtotal, serta penyimpanan ke session keranjang.
     */
    // Tambah / Edit Item Keranjang
    public function addToCart(Request $request)
    {
        $menu = Menu::with('bahans')->findOrFail($request->id_menu); // Ambil menu beserta bahannya berdasarkan ID dari request

        // Cek ketersediaan menu sebelum ditambahkan ke keranjang
        if (!$menu->isTersedia()) {
            return redirect()->route('customer.menu')->with('error', 'Maaf, menu ' . $menu->nama_menu . ' saat ini sedang tidak tersedia.');
        }

        $levelPedas = null; // Inisialisasi level pedas sebagai null (tidak semua menu ada opsi pedas)

        // Jika menu memiliki opsi pedas, validasi dan ambil level pedas dari input
        if ($menu->opsi_pedas === 'Ya') {
            $request->validate([
                'level_pedas' => 'required|integer|min:1|max:5' // Level pedas wajib diisi antara 1-5
            ], [
                'level_pedas.required' => 'Wajib memilih Level Pedas (1-5) untuk menu ini.' // Pesan error khusus
            ]);
            $levelPedas = (int) $request->level_pedas; // Konversi level pedas ke integer
        }

        $jumlah              = max(1, (int) $request->input('jumlah', 1)); // Ambil jumlah porsi (minimal 1)
        $catatan             = trim($request->input('catatan', ''));         // Ambil catatan khusus pelanggan
        $selectedTambahanIds = $request->input('tambahans', []);             // Ambil array ID tambahan yang dipilih

        // Pastikan selectedTambahanIds adalah array
        if (!is_array($selectedTambahanIds)) {
            $selectedTambahanIds = []; // Reset ke array kosong jika bukan array
        }

        // Handle bagian ayam (Dada/Paha Atas/Paha Bawah/Sayap), suhu (Dingin/Panas) dan varian
        $bagianAyam = $request->input('bagian_ayam', ''); // Ambil pilihan bagian ayam (khusus menu ayam)
        $suhu       = $request->input('suhu', '');        // Ambil pilihan suhu minuman (Dingin/Panas)
        $varian     = $request->input('varian', '');      // Ambil pilihan varian menu

        // Gabungkan pilihan bagian_ayam/suhu/varian ke catatan otomatis
        $autoNote = ''; // Inisialisasi catatan otomatis kosong

        if ($bagianAyam) {
            $autoNote .= 'Bagian: ' . $bagianAyam; // Tambahkan bagian ayam ke catatan otomatis
        }
        if ($varian) {
            $autoNote .= ($autoNote ? ' - ' : '') . $varian; // Tambahkan varian, pisah dengan " - " jika sudah ada catatan sebelumnya
        }
        if ($suhu) {
            $autoNote .= ($autoNote ? ' - ' : '') . $suhu; // Tambahkan suhu, pisah dengan " - " jika sudah ada catatan sebelumnya
        }

        // Gabungkan auto note dengan catatan manual dari pelanggan
        $fullCatatan = $autoNote; // Mulai dari auto note
        if ($catatan) {
            $fullCatatan .= ($fullCatatan ? ' | ' : '') . $catatan; // Gabungkan catatan manual, pisah dengan " | "
        }

        // Ambil data detail tambahan yang dipilih dari database (beserta bahannya untuk validasi stok)
        $tambahansData = Tambahan::with('bahans')->whereIn('id_tambahan', $selectedTambahanIds)->get();

        // Validasi ketersediaan setiap tambahan yang dipilih
        foreach ($tambahansData as $tItem) {
            if (!$tItem->isTersedia()) {
                // Redirect kembali dengan error jika ada tambahan yang tidak tersedia
                return redirect()->back()->with('error', 'Maaf, menu tambahan ' . $tItem->nama_tambahan . ' saat ini sedang habis / tidak tersedia.');
            }
        }

        // Hash unik untuk item keranjang (agar menu sama tapi opsi beda jadi baris terpisah)
        sort($selectedTambahanIds); // Urutkan ID tambahan agar hash konsisten apapun urutan pilihan
        $itemHash = md5($menu->id_menu . '_' . ($levelPedas ?? 0) . '_' . strtolower($bagianAyam) . '_' . implode(',', $selectedTambahanIds) . '_' . strtolower($fullCatatan)); // Generate hash MD5 unik berdasarkan kombinasi opsi

        $cart    = session('cart', []);        // Ambil keranjang dari session (default array kosong)
        $oldHash = $request->input('old_hash'); // Ambil hash item lama jika ini proses edit

        // Jika ini proses Edit item yang sudah ada, hapus item versi lama dari keranjang
        if ($oldHash && isset($cart[$oldHash])) {
            unset($cart[$oldHash]); // Hapus item lama dari keranjang
        }

        // Tentukan jumlah target untuk validasi stok
        $targetJumlah = $jumlah; // Default: jumlah yang baru diinput
        if (isset($cart[$itemHash])) {
            if ($oldHash && $oldHash === $itemHash) {
                $targetJumlah = $jumlah; // Jika edit dan hash sama: gunakan jumlah baru (ganti, bukan tambah)
            } else {
                $targetJumlah = $cart[$itemHash]['jumlah'] + $jumlah; // Jika item sudah ada di keranjang: akumulasikan
            }
        }

        // Validasi akumulasi kuantitas terhadap stok bahan menu
        foreach ($menu->bahans as $bahan) {
            $targetBahan = $bahan; // Default target bahan

            // Khusus menu dengan pilihan bagian ayam, cari bahan yang sesuai bagian
            if ($bagianAyam && str_starts_with($bahan->nama_bahan, 'Ayam ')) {
                $specificBahan = Bahan::where('nama_bahan', 'Ayam ' . $bagianAyam)->first(); // Cari bahan spesifik bagian ayam
                if ($specificBahan) {
                    $targetBahan = $specificBahan; // Ganti target ke bahan spesifik
                }
            }

            $kebutuhanPerPorsi = $bahan->pivot->jumlah_dibutuhkan ?? 1; // Ambil kebutuhan bahan per porsi

            // Cek apakah stok cukup untuk jumlah yang diminta (termasuk yang sudah di keranjang)
            if ($targetBahan->stok < ($kebutuhanPerPorsi * $targetJumlah)) {
                $maxPorsi = (int) floor($targetBahan->stok / $kebutuhanPerPorsi); // Hitung maksimal porsi yang bisa dipesan
                return redirect()->back()->with('error', "Maaf, stok bahan '{$targetBahan->nama_bahan}' untuk menu '{$menu->nama_menu}' hanya tersisa {$maxPorsi} porsi."); // Tampilkan error dengan info stok tersisa
            }
        }

        // Validasi akumulasi kuantitas terhadap stok bahan tambahan
        foreach ($tambahansData as $tItem) {
            foreach ($tItem->bahans as $bahan) { // Iterasi setiap bahan yang dibutuhkan tambahan ini
                $kebutuhanPerPorsi = $bahan->pivot->jumlah_dibutuhkan ?? 1; // Kebutuhan bahan per porsi tambahan

                // Cek apakah stok cukup untuk jumlah yang diminta
                if ($bahan->stok < ($kebutuhanPerPorsi * $targetJumlah)) {
                    $maxPorsi = (int) floor($bahan->stok / $kebutuhanPerPorsi); // Hitung maksimal porsi
                    return redirect()->back()->with('error', "Maaf, stok bahan '{$bahan->nama_bahan}' untuk tambahan '{$tItem->nama_tambahan}' hanya tersisa {$maxPorsi} porsi."); // Error dengan info stok tersisa
                }
            }
        }

        $tambahanHargaTotal = $tambahansData->sum('harga'); // Hitung total harga dari semua tambahan yang dipilih
        
        $itemHarga = $menu->harga + $tambahanHargaTotal; // Total harga satu porsi (menu + semua tambahan)
        $subtotal  = $itemHarga * $jumlah;               // Total harga item ini (harga × jumlah)

        // Hash unik untuk item keranjang (agar menu sama tapi opsi beda jadi baris terpisah)
        sort($selectedTambahanIds); // Urutkan ulang agar hash konsisten
        $itemHash = md5($menu->id_menu . '_' . ($levelPedas ?? 0) . '_' . strtolower($bagianAyam) . '_' . implode(',', $selectedTambahanIds) . '_' . strtolower($fullCatatan)); // Generate ulang hash MD5 unik

        $cart    = session('cart', []);        // Ambil keranjang terbaru dari session
        $oldHash = $request->input('old_hash'); // Ambil hash item lama (untuk mode edit)

        // Jika ini proses Edit item yang sudah ada, hapus item versi lama dari keranjang
        if ($oldHash && isset($cart[$oldHash])) {
            unset($cart[$oldHash]); // Hapus item lama dari keranjang
        }

        if (isset($cart[$itemHash])) {
            // Jika hash baru sama dengan hash item lain di keranjang (atau tidak berubah saat edit), perbarui nilainya
            if ($oldHash && $oldHash === $itemHash) {
                $cart[$itemHash]['jumlah'] = $jumlah; // Mode edit dengan hash sama: ganti jumlah (bukan tambah)
            } else {
                $cart[$itemHash]['jumlah'] += $jumlah; // Item sudah ada dengan hash sama: tambah jumlahnya
            }
            $cart[$itemHash]['subtotal']    = $cart[$itemHash]['jumlah'] * $itemHarga; // Hitung ulang subtotal
            $cart[$itemHash]['level_pedas'] = $levelPedas;                              // Update level pedas
            $cart[$itemHash]['bagian_ayam'] = $bagianAyam;                             // Update bagian ayam
            $cart[$itemHash]['tambahans']   = $tambahansData->toArray();               // Update data tambahan
            $cart[$itemHash]['catatan']     = $fullCatatan;                            // Update catatan
            $cart[$itemHash]['suhu']        = $suhu;                                   // Update pilihan suhu
            $cart[$itemHash]['varian']      = $varian;                                 // Update pilihan varian

        } else {
            // Item belum ada di keranjang, buat entri baru
            $cart[$itemHash] = [
                'item_hash'  => $itemHash,                    // Hash unik item untuk identifikasi
                'id_menu'    => $menu->id_menu,               // ID menu
                'nama_menu'  => $menu->nama_menu,             // Nama menu untuk tampilan
                'harga_menu' => $menu->harga,                 // Harga dasar menu (tanpa tambahan)
                'opsi_pedas' => $menu->opsi_pedas,            // Info apakah menu ada opsi pedas
                'level_pedas'=> $levelPedas,                  // Level pedas yang dipilih (null jika tidak ada)
                'bagian_ayam'=> $bagianAyam,                  // Pilihan bagian ayam (kosong jika tidak ada)
                'tambahans'  => $tambahansData->toArray(),    // Array data tambahan yang dipilih
                'catatan'    => $fullCatatan,                 // Catatan lengkap (auto + manual)
                'suhu'       => $suhu,                        // Pilihan suhu (untuk minuman)
                'varian'     => $varian,                      // Pilihan varian
                'jumlah'     => $jumlah,                      // Jumlah porsi
                'subtotal'   => $subtotal,                    // Total harga item ini
                'foto'       => $menu->foto                   // Path foto menu untuk tampilan keranjang
            ];
        }

        session(['cart' => $cart]); // Simpan keranjang yang sudah diperbarui ke session

        // Redirect berbeda tergantung mode (edit atau tambah baru)
        if ($oldHash) {
            return redirect()->route('customer.cart')->with('success', 'Pesanan di keranjang berhasil diperbarui!'); // Kembali ke keranjang setelah edit
        }

        // Kembali ke halaman daftar menu (kategori yang sama) setelah tambah item baru
        return redirect()->route('customer.menu', ['kategori' => $menu->kategori])->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    /**
     * Halaman 4: Keranjang Belanja.
     * Menampilkan semua item yang sudah ditambahkan ke keranjang beserta total harganya.
     */
    // Halaman 4: Keranjang
    public function cart()
    {
        $cart       = session('cart', []);                         // Ambil data keranjang dari session
        $totalHarga = array_sum(array_column($cart, 'subtotal')); // Hitung total harga semua item di keranjang

        return view('customer.cart', compact('cart', 'totalHarga')); // Tampilkan view keranjang dengan data dan total harga
    }

    /**
     * Memperbarui kuantitas item di keranjang (tambah atau kurangi).
     * Jika kuantitas menjadi 0 atau kurang, hapus item dari keranjang.
     * Hitung ulang subtotal setelah perubahan.
     */
    public function updateCart(Request $request)
    {
        $hash   = $request->input('item_hash'); // Ambil hash item yang akan diperbarui
        $action = $request->input('action');    // Ambil aksi: 'increase' atau 'decrease'
        $cart   = session()->get('cart', []);   // Ambil data keranjang dari session

        // Proses hanya jika hash valid dan item ada di keranjang
        if ($hash && isset($cart[$hash])) {
            if ($action === 'increase') {
                $cart[$hash]['jumlah'] += 1; // Tambah jumlah item sebanyak 1
            } elseif ($action === 'decrease') {
                $cart[$hash]['jumlah'] -= 1; // Kurangi jumlah item sebanyak 1
            }

            // Jika kuantitas 0 atau kurang
            if ($cart[$hash]['jumlah'] <= 0) {
                unset($cart[$hash]); // Hapus item dari keranjang jika kuantitas habis

            } else {
                // Hitung ulang subtotal item setelah perubahan kuantitas
                $tambahanHargaTotal = 0; // Inisialisasi total harga tambahan

                if (!empty($cart[$hash]['tambahans'])) {
                    $tambahanHargaTotal = array_sum(array_column($cart[$hash]['tambahans'], 'harga')); // Hitung total harga semua tambahan yang dipilih
                }
                
                $itemHarga            = $cart[$hash]['harga_menu'] + $tambahanHargaTotal; // Harga per porsi = harga menu + harga tambahan
                $cart[$hash]['subtotal'] = $cart[$hash]['jumlah'] * $itemHarga;           // Hitung ulang subtotal
            }

            session()->put('cart', $cart); // Simpan keranjang yang sudah diperbarui ke session
        }

        return redirect()->route('customer.cart')->with('success', 'Keranjang berhasil diperbarui'); // Redirect ke halaman keranjang
    }

    /**
     * Menghapus satu item dari keranjang berdasarkan hash-nya.
     */
    public function removeFromCart($hash)
    {
        $cart = session()->get('cart', []); // Ambil data keranjang dari session
        
        if (isset($cart[$hash])) {           // Cek apakah item dengan hash tersebut ada di keranjang
            unset($cart[$hash]);             // Hapus item dari array keranjang
            session()->put('cart', $cart);   // Simpan keranjang yang sudah diperbarui ke session
        }

        return redirect()->route('customer.cart')->with('success', 'Item berhasil dihapus'); // Redirect ke halaman keranjang
    }

    /**
     * Helper private untuk memvalidasi ketersediaan stok semua item di keranjang.
     * Mengagregasi kebutuhan bahan dari semua item dan tambahan, lalu membandingkan dengan stok aktual.
     *
     * @param  array   $cart  Array data keranjang dari session
     * @return string|null    Null jika semua stok mencukupi, atau string pesan error jika ada masalah
     */
    private function checkCartStockAvailability($cart): ?string
    {
        if (empty($cart)) {
            return 'Keranjang Anda masih kosong.'; // Return error jika keranjang kosong
        }

        $bahanRequirements = []; // [id_bahan => total_needed] - Akumulasi total kebutuhan setiap bahan
        $itemBahanSources  = []; // [id_bahan => [...]] - Sumber informasi untuk pesan error yang informatif

        // Iterasi setiap item di keranjang
        foreach ($cart as $item) {
            $menu = Menu::with('bahans')->find($item['id_menu']); // Ambil data menu beserta bahan terkini dari database

            // Cek apakah menu masih ada dan status stoknya tidak Habis
            if (!$menu || $menu->status_stok === 'Habis') {
                $menuName = $menu ? $menu->nama_menu : 'Menu'; // Gunakan nama menu jika ada, atau 'Menu' sebagai fallback
                return "Maaf, menu '{$menuName}' saat ini sedang habis / tidak tersedia."; // Return pesan error
            }

            $qty        = (int) $item['jumlah'];     // Jumlah porsi item ini
            $bagianAyam = $item['bagian_ayam'] ?? ''; // Pilihan bagian ayam (jika ada)

            // Agregasi kebutuhan bahan dari menu
            foreach ($menu->bahans as $bahan) {
                $targetBahan = $bahan; // Default target bahan

                // Khusus bahan ayam dengan pilihan bagian spesifik
                if ($bagianAyam && str_starts_with($bahan->nama_bahan, 'Ayam ')) {
                    $specificBahan = Bahan::where('nama_bahan', 'Ayam ' . $bagianAyam)->first(); // Cari bahan ayam spesifik
                    if ($specificBahan) {
                        $targetBahan = $specificBahan; // Ganti target ke bahan spesifik
                    }
                }

                $needed = ($bahan->pivot->jumlah_dibutuhkan ?? 1) * $qty;                               // Hitung total kebutuhan bahan untuk item ini
                $bahanRequirements[$targetBahan->id_bahan] = ($bahanRequirements[$targetBahan->id_bahan] ?? 0) + $needed; // Akumulasikan kebutuhan bahan

                // Simpan info sumber untuk pesan error yang informatif
                $itemBahanSources[$targetBahan->id_bahan] = [
                    'menu_name' => $menu->nama_menu,          // Nama menu sebagai sumber informasi error
                    'is_paket'  => ($menu->kategori === 'Paket'), // Flag apakah menu adalah paket
                    'bahan_name'=> $targetBahan->nama_bahan,  // Nama bahan untuk pesan error
                    'type'      => 'menu'                      // Tipe sumber: menu
                ];
            }

            // Agregasi kebutuhan bahan dari tambahan yang dipilih
            if (!empty($item['tambahans'])) {
                foreach ($item['tambahans'] as $t) { // Iterasi setiap tambahan di item ini
                    $tambahan = Tambahan::with('bahans')->find($t['id_tambahan']); // Ambil data tambahan dari database

                    // Cek apakah tambahan masih ada dan status stoknya tidak Habis
                    if (!$tambahan || $tambahan->status_stok === 'Habis') {
                        $tName = $tambahan ? $tambahan->nama_tambahan : 'Tambahan'; // Nama tambahan untuk pesan error
                        return "Maaf, menu tambahan '{$tName}' saat ini sedang habis."; // Return pesan error
                    }

                    // Agregasi kebutuhan bahan dari tambahan ini
                    foreach ($tambahan->bahans as $bahan) {
                        $needed = ($bahan->pivot->jumlah_dibutuhkan ?? 1) * $qty;                // Hitung total kebutuhan bahan tambahan
                        $bahanRequirements[$bahan->id_bahan] = ($bahanRequirements[$bahan->id_bahan] ?? 0) + $needed; // Akumulasikan

                        // Simpan info sumber untuk pesan error
                        $itemBahanSources[$bahan->id_bahan] = [
                            'menu_name' => $tambahan->nama_tambahan, // Nama tambahan sebagai sumber
                            'is_paket'  => false,                    // Tambahan bukan paket
                            'bahan_name'=> $bahan->nama_bahan,       // Nama bahan
                            'type'      => 'tambahan'                // Tipe sumber: tambahan
                        ];
                    }
                }
            }
        }

        // Validasi ketersediaan stok untuk semua bahan yang dibutuhkan
        if (!empty($bahanRequirements)) {
            // Ambil data stok aktual semua bahan yang dibutuhkan dari database sekaligus (efisiensi)
            $bahans = Bahan::whereIn('id_bahan', array_keys($bahanRequirements))->get()->keyBy('id_bahan');

            // Bandingkan kebutuhan dengan stok aktual
            foreach ($bahanRequirements as $idBahan => $totalNeeded) {
                $bahan = $bahans->get($idBahan); // Ambil data bahan dari koleksi

                // Jika bahan tidak ditemukan atau stok kurang dari yang dibutuhkan
                if (!$bahan || $bahan->stok < $totalNeeded) {
                    $stokTersedia = $bahan ? $bahan->stok : 0; // Stok yang tersedia (0 jika bahan tidak ada)
                    $source = $itemBahanSources[$idBahan] ?? null; // Ambil info sumber untuk pesan error

                    if ($source) {
                        if ($source['type'] === 'tambahan') {
                            return "Stok bahan '{$source['bahan_name']}' untuk tambahan '{$source['menu_name']}' tidak mencukupi. Silakan sesuaikan pesanan Anda."; // Pesan error untuk bahan tambahan
                        } else {
                            return "Stok bahan '{$source['bahan_name']}' untuk menu '{$source['menu_name']}' tidak mencukupi. Silakan sesuaikan pesanan Anda."; // Pesan error untuk bahan menu
                        }
                    } else {
                        return "Stok bahan tidak mencukupi untuk memenuhi pesanan Anda."; // Pesan error generik jika tidak ada info sumber
                    }
                }
            }
        }

        return null; // Return null jika semua stok mencukupi (tidak ada masalah)
    }

    /**
     * Halaman 5: Checkout / Konfirmasi Pembayaran.
     * Memvalidasi stok terlebih dahulu sebelum menampilkan halaman konfirmasi.
     * Menampilkan ringkasan pesanan, total harga, dan form metode pembayaran.
     */
    // Halaman 5: Checkout
    public function checkout()
    {
        $cart = session('cart', []); // Ambil data keranjang dari session

        // Redirect ke menu jika keranjang kosong
        if (empty($cart)) {
            return redirect()->route('customer.menu')->with('error', 'Keranjang Anda masih kosong');
        }

        // Cek ketersediaan stok sebelum masuk halaman konfirmasi pembayaran
        $stockError = $this->checkCartStockAvailability($cart); // Validasi stok semua item di keranjang
        if ($stockError) {
            return redirect()->route('customer.cart')->with('error', $stockError); // Kembali ke keranjang jika ada error stok
        }

        $totalHarga  = array_sum(array_column($cart, 'subtotal')); // Hitung total harga semua item
        $tipePesanan = session('tipe_pesanan', 'Dine-In');          // Ambil tipe pesanan dari session (default Dine-In)
        $nomorMeja   = session('nomor_meja', null);                 // Ambil nomor meja dari session (null jika Take Away)

        // Tampilkan view checkout dengan data yang diperlukan
        return view('customer.checkout', compact('cart', 'totalHarga', 'tipePesanan', 'nomorMeja'));
    }

    /**
     * Memproses dan menyimpan pesanan ke database.
     * Melakukan:
     * 1. Validasi input pembayaran dan bukti (QRIS)
     * 2. Agregasi kebutuhan bahan dan validasi stok dengan database locking
     * 3. Pengurangan stok bahan secara atomik
     * 4. Penyimpanan pesanan dan detail pesanan
     * 5. Redirect ke halaman struk setelah berhasil
     */
    // Process & Store Order
    public function storeOrder(Request $request)
    {
        $cart = session('cart', []); // Ambil data keranjang dari session

        // Redirect ke menu jika keranjang kosong (pengaman tambahan)
        if (empty($cart)) {
            return redirect()->route('customer.menu');
        }

        // Validasi data input dari form checkout
        $request->validate([
            'nama_pemesan'     => 'nullable|string|max:100',                                       // Nama pemesan opsional, maks 100 karakter
            'metode_bayar'     => 'required|in:Tunai,QRIS',                                        // Metode bayar wajib dipilih
            'bukti_pembayaran' => 'required_if:metode_bayar,QRIS|nullable|image|mimes:jpeg,png,jpg,webp|max:5120' // Bukti pembayaran wajib jika QRIS, maks 5MB
        ], [
            'bukti_pembayaran.required_if' => 'Wajib mengunggah foto bukti pembayaran / transfer untuk pembayaran QRIS.', // Pesan error custom
            'bukti_pembayaran.image'       => 'File bukti pembayaran harus berupa gambar (JPG, PNG, WEBP).',              // Pesan error custom
            'bukti_pembayaran.max'         => 'Ukuran foto bukti pembayaran maksimal 5MB.'                               // Pesan error custom
        ]);

        $buktiPath = null; // Inisialisasi path bukti pembayaran sebagai null

        // Jika ada file bukti pembayaran yang diunggah (khusus metode QRIS)
        if ($request->hasFile('bukti_pembayaran')) {
            $file     = $request->file('bukti_pembayaran');                                                    // Ambil file dari request
            $filename = 'bukti_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();       // Generate nama file unik berdasarkan timestamp dan random string

            // Buat direktori uploads jika belum ada
            if (!file_exists(public_path('uploads/bukti_pembayaran'))) {
                mkdir(public_path('uploads/bukti_pembayaran'), 0777, true); // Buat direktori secara rekursif dengan permission 777
            }

            $file->move(public_path('uploads/bukti_pembayaran'), $filename); // Pindahkan file ke direktori uploads
            $buktiPath = 'uploads/bukti_pembayaran/' . $filename;             // Simpan path relatif untuk disimpan di database
        }

        $totalHarga  = array_sum(array_column($cart, 'subtotal'));          // Hitung total harga semua item
        $tipePesanan = session('tipe_pesanan', 'Dine-In');                   // Ambil tipe pesanan dari session
        $idMeja      = ($tipePesanan === 'Dine-In') ? session('id_meja') : null; // ID meja hanya untuk Dine-In, null untuk Take Away

        DB::beginTransaction(); // Mulai transaksi database agar semua operasi atomik

        try {
            // 1. Agregasi total kebutuhan setiap bahan dan validasi status_stok manual
            $bahanRequirements = []; // [id_bahan => total_needed] - Total kebutuhan per bahan
            $itemBahanSources  = []; // [id_bahan => [...]] - Sumber info untuk pesan error

            // Iterasi setiap item di keranjang untuk kalkulasi kebutuhan bahan
            foreach ($cart as $item) {
                $menu = Menu::with('bahans')->find($item['id_menu']); // Ambil menu terkini dari database

                // Validasi menu masih tersedia
                if (!$menu || $menu->status_stok === 'Habis') {
                    DB::rollBack(); // Batalkan transaksi jika menu tidak tersedia
                    $menuName = $menu ? $menu->nama_menu : 'Menu';
                    return redirect()->route('customer.cart')->with('error', "Maaf, menu '{$menuName}' saat ini sedang habis / tidak tersedia.");
                }

                $qty        = (int) $item['jumlah'];     // Jumlah porsi
                $bagianAyam = $item['bagian_ayam'] ?? ''; // Pilihan bagian ayam

                // Kalkulasi kebutuhan bahan dari menu
                foreach ($menu->bahans as $bahan) {
                    $targetBahan = $bahan; // Default target bahan

                    // Resolusi bahan ayam spesifik berdasarkan pilihan bagian
                    if ($bagianAyam && str_starts_with($bahan->nama_bahan, 'Ayam ')) {
                        $specificBahan = Bahan::where('nama_bahan', 'Ayam ' . $bagianAyam)->first(); // Cari bahan ayam spesifik
                        if ($specificBahan) {
                            $targetBahan = $specificBahan; // Gunakan bahan spesifik
                        }
                    }

                    $needed = ($bahan->pivot->jumlah_dibutuhkan ?? 1) * $qty;                                // Hitung total kebutuhan
                    $bahanRequirements[$targetBahan->id_bahan] = ($bahanRequirements[$targetBahan->id_bahan] ?? 0) + $needed; // Akumulasikan
                    $itemBahanSources[$targetBahan->id_bahan]  = [
                        'menu_name' => $menu->nama_menu,
                        'is_paket'  => ($menu->kategori === 'Paket'),
                        'bahan_name'=> $targetBahan->nama_bahan,
                        'type'      => 'menu'
                    ];
                }

                // Kalkulasi kebutuhan bahan dari tambahan yang dipilih
                if (!empty($item['tambahans'])) {
                    foreach ($item['tambahans'] as $t) {
                        $tambahan = Tambahan::with('bahans')->find($t['id_tambahan']); // Ambil tambahan dari database

                        // Validasi tambahan masih tersedia
                        if (!$tambahan || $tambahan->status_stok === 'Habis') {
                            DB::rollBack(); // Batalkan transaksi
                            $tName = $tambahan ? $tambahan->nama_tambahan : 'Tambahan';
                            return redirect()->route('customer.cart')->with('error', "Maaf, menu tambahan '{$tName}' saat ini sedang habis.");
                        }

                        // Kalkulasi kebutuhan bahan tambahan
                        foreach ($tambahan->bahans as $bahan) {
                            $needed = ($bahan->pivot->jumlah_dibutuhkan ?? 1) * $qty;                            // Hitung kebutuhan bahan tambahan
                            $bahanRequirements[$bahan->id_bahan] = ($bahanRequirements[$bahan->id_bahan] ?? 0) + $needed; // Akumulasikan
                            $itemBahanSources[$bahan->id_bahan]  = [
                                'menu_name' => $tambahan->nama_tambahan,
                                'is_paket'  => false,
                                'bahan_name'=> $bahan->nama_bahan,
                                'type'      => 'tambahan'
                            ];
                        }
                    }
                }
            }

            // 2. Kunci baris bahan untuk mencegah race condition dan validasi kuantitas
            if (!empty($bahanRequirements)) {
                // Ambil semua bahan yang dibutuhkan dengan database row lock (FOR UPDATE) untuk mencegah race condition
                $lockedBahans = Bahan::whereIn('id_bahan', array_keys($bahanRequirements))
                    ->lockForUpdate() // Lock baris database agar tidak bisa dimodifikasi transaksi lain bersamaan
                    ->get()
                    ->keyBy('id_bahan'); // Kelompokkan berdasarkan ID untuk akses cepat

                // Validasi ketersediaan stok setelah locking
                foreach ($bahanRequirements as $idBahan => $totalNeeded) {
                    $bahan = $lockedBahans->get($idBahan); // Ambil data bahan dari koleksi yang sudah di-lock

                    // Jika bahan tidak ada atau stok kurang
                    if (!$bahan || $bahan->stok < $totalNeeded) {
                        DB::rollBack(); // Batalkan transaksi
                        $stokTersedia = $bahan ? $bahan->stok : 0; // Stok yang tersedia
                        $source = $itemBahanSources[$idBahan] ?? null;

                        if ($source) {
                            if ($source['type'] === 'tambahan') {
                                $pesan = "Stok bahan '{$source['bahan_name']}' untuk tambahan '{$source['menu_name']}' tidak mencukupi (Tersedia: {$stokTersedia}, Dibutuhkan: {$totalNeeded}). Silakan sesuaikan pesanan Anda."; // Pesan error detail untuk bahan tambahan
                            } else {
                                $pesan = "Stok bahan '{$source['bahan_name']}' untuk menu '{$source['menu_name']}' tidak mencukupi (Tersedia: {$stokTersedia}, Dibutuhkan: {$totalNeeded}). Silakan sesuaikan pesanan Anda."; // Pesan error detail untuk bahan menu
                            }
                        } else {
                            $pesan = "Stok bahan tidak mencukupi untuk memenuhi pesanan Anda."; // Pesan error generik
                        }
                        return redirect()->route('customer.cart')->with('error', $pesan); // Kembali ke keranjang dengan pesan error
                    }
                }

                // 3. Kurangi stok bahan secara otomatis setelah validasi berhasil
                foreach ($bahanRequirements as $idBahan => $totalNeeded) {
                    $bahan = $lockedBahans->get($idBahan); // Ambil bahan dari koleksi locked
                    $bahan->decrement('stok', $totalNeeded); // Kurangi stok sebesar total yang dibutuhkan
                }
            }

            // 4. Simpan data pesanan ke database
            $pesanan = Pesanan::create([
                'id_meja'          => $idMeja,                                  // ID meja (null untuk Take Away)
                'id_admin'         => null,                                      // Admin belum assign (baru masuk)
                'tipe_pesanan'     => $tipePesanan,                              // Dine-In atau Take Away
                'nama_pemesan'     => $request->nama_pemesan ?: 'Pelanggan',    // Nama pemesan (default 'Pelanggan' jika tidak diisi)
                'status'           => 'Diterima',                                // Status awal pesanan
                'status_pembayaran'=> 'Belum Lunas',                            // Status pembayaran awal
                'metode_bayar'     => $request->metode_bayar,                   // Metode pembayaran yang dipilih
                'bukti_pembayaran' => $buktiPath,                               // Path bukti pembayaran (null jika Tunai)
                'tanggal_waktu'    => Carbon::now(),                            // Waktu pesanan dibuat (sekarang)
                'total_harga'      => $totalHarga,                              // Total harga semua item
            ]);

            // Simpan setiap item keranjang sebagai DetailPesanan
            foreach ($cart as $item) {
                // Buat record detail pesanan untuk setiap item
                $detail = DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan, // Hubungkan ke pesanan yang baru dibuat
                    'id_menu'    => $item['id_menu'],     // ID menu yang dipesan
                    'jumlah'     => $item['jumlah'],      // Jumlah porsi
                    'level_pedas'=> $item['level_pedas'], // Level pedas (null jika tidak ada)
                    'catatan'    => $item['catatan'],     // Catatan khusus item
                    'subtotal'   => $item['subtotal'],    // Total harga item ini
                ]);

                // Simpan tambahan yang dipilih ke tabel pivot detail_tambahans
                if (!empty($item['tambahans'])) {
                    foreach ($item['tambahans'] as $tambahan) { // Iterasi setiap tambahan yang dipilih
                        DB::table('detail_tambahans')->insert([
                            'id_detail'   => $detail->id_detail,       // FK ke detail pesanan yang baru dibuat
                            'id_tambahan' => $tambahan['id_tambahan'], // FK ke tambahan yang dipilih
                            'created_at'  => now(),                    // Waktu insert
                            'updated_at'  => now(),                    // Waktu update
                        ]);
                    }
                }
            }

            DB::commit();             // Commit transaksi: semua operasi berhasil, simpan ke database
            session()->forget('cart'); // Kosongkan keranjang dari session setelah pesanan berhasil dibuat

            return redirect()->route('customer.receipt', $pesanan->id_pesanan); // Redirect ke halaman struk pesanan

        } catch (\Exception $e) {
            DB::rollBack(); // Batalkan semua operasi database jika terjadi exception
            return redirect()->route('customer.cart')->with('error', 'Gagal memproses pesanan: ' . $e->getMessage()); // Kembali ke keranjang dengan pesan error
        }
    }

    /**
     * Halaman 6: Struk / Status Pesanan.
     * Menampilkan detail lengkap pesanan yang sudah dibuat beserta statusnya.
     * Halaman ini di-polling oleh JavaScript untuk update status real-time.
     */
    // Halaman 6: Status Pesanan / Struk
    public function receipt($id)
    {
        // Ambil pesanan beserta semua relasi yang diperlukan untuk tampilan struk
        $pesanan = Pesanan::with(['meja', 'detailPesanans.menu', 'detailPesanans.tambahans'])->findOrFail($id);
        return view('customer.receipt', compact('pesanan')); // Tampilkan view struk dengan data pesanan
    }

    /**
     * API Endpoint: Mengembalikan status pesanan dalam format JSON.
     * Digunakan oleh JavaScript di halaman struk untuk polling status secara real-time.
     *
     * @param  int  $id  ID pesanan yang akan dicek statusnya
     * @return \Illuminate\Http\JsonResponse  Data status pesanan dalam format JSON
     */
    // API JSON Polling Status Pesanan
    public function orderStatusJson($id)
    {
        $pesanan = Pesanan::findOrFail($id); // Ambil pesanan berdasarkan ID, throw 404 jika tidak ada

        // Kembalikan data status pesanan dalam format JSON untuk dikonsumsi JavaScript
        return response()->json([
            'status'              => $pesanan->status,             // Status pesanan (Diterima/Diproses/Selesai/dll)
            'status_pembayaran'   => $pesanan->status_pembayaran, // Status pembayaran (Lunas/Belum Lunas)
            'uang_dibayar'        => $pesanan->uang_dibayar,      // Nominal uang yang dibayar (untuk Tunai)
            'kembalian'           => $pesanan->kembalian,          // Nominal kembalian (untuk Tunai)
            'alasan_pembatalan'   => $pesanan->alasan_pembatalan, // Alasan pembatalan (jika dibatalkan)
        ]);
    }
}