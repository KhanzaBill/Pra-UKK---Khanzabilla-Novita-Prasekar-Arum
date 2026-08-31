<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Tambahan;
use App\Models\Meja;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerController extends Controller
{
    // Halaman 1: Landing (QR Scan entry point)
    public function landing(Request $request)
    {
        $idMeja = $request->query('meja');
        $meja = null;

        if ($idMeja) {
            $meja = Meja::where('id_meja', $idMeja)->orWhere('nomor_meja', 'Meja ' . str_pad($idMeja, 2, '0', STR_PAD_LEFT))->first();
            if ($meja) {
                session(['id_meja' => $meja->id_meja, 'nomor_meja' => $meja->nomor_meja]);
            }
        }

        return view('customer.landing', compact('meja'));
    }

    // Set tipe pesanan dari landing
    public function setOrderType(Request $request)
    {
        $request->validate([
            'tipe_pesanan' => 'required|in:Dine-In,Take Away'
        ]);

        session(['tipe_pesanan' => $request->tipe_pesanan]);

        if ($request->tipe_pesanan === 'Take Away') {
            session()->forget(['id_meja', 'nomor_meja']);
        }

        return redirect()->route('customer.menu');
    }

    // Halaman 2: Daftar Menu
    public function menu(Request $request)
    {
        $search = trim($request->query('search', ''));

        if ($request->has('kategori')) {
            $kategoriActive = $request->query('kategori');
        } elseif ($search !== '') {
            $kategoriActive = 'Semua';
        } else {
            $kategoriActive = 'Paket';
        }

        $query = Menu::query();

        if ($kategoriActive && $kategoriActive !== 'Semua') {
            $query->where('kategori', $kategoriActive);
        }

        if ($search !== '') {
            $query->where('nama_menu', 'like', "%{$search}%");
        }

        $menus = $query->get();
        $cartCount = count(session('cart', []));

        return view('customer.menu', compact('menus', 'kategoriActive', 'search', 'cartCount'));
    }

    // Halaman 3: Detail Menu
    public function detailMenu(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
        $tambahans = Tambahan::all();

        $editHash = $request->query('edit_hash');
        $editItem = null;
        if ($editHash && session()->has("cart.{$editHash}")) {
            $editItem = session("cart.{$editHash}");
        }

        return view('customer.detail_menu', compact('menu', 'tambahans', 'editHash', 'editItem'));
    }

    // Tambah / Edit Item Keranjang
    public function addToCart(Request $request)
    {
        $menu = Menu::findOrFail($request->id_menu);

        $levelPedas = null;
        if ($menu->opsi_pedas === 'Ya') {
            $request->validate([
                'level_pedas' => 'required|integer|min:1|max:5'
            ], [
                'level_pedas.required' => 'Wajib memilih Level Pedas (1-5) untuk menu ini.'
            ]);
            $levelPedas = (int) $request->level_pedas;
        }

        $jumlah = max(1, (int) $request->input('jumlah', 1));
        $catatan = trim($request->input('catatan', ''));
        $selectedTambahanIds = $request->input('tambahans', []);

        if (!is_array($selectedTambahanIds)) {
            $selectedTambahanIds = [];
        }

        // Handle suhu (Dingin/Panas) dan varian untuk minuman
        $suhu = $request->input('suhu', '');
        $varian = $request->input('varian', '');

        // Gabungkan pilihan suhu/varian ke catatan otomatis
        $autoNote = '';
        if ($varian) {
            $autoNote .= $varian;
        }
        if ($suhu) {
            $autoNote .= ($autoNote ? ' - ' : '') . $suhu;
        }

        // Gabungkan auto note dengan catatan manual
        $fullCatatan = $autoNote;
        if ($catatan) {
            $fullCatatan .= ($fullCatatan ? ' | ' : '') . $catatan;
        }

        // Ambil data detail tambahan
        $tambahansData = Tambahan::whereIn('id_tambahan', $selectedTambahanIds)->get();
        $tambahanHargaTotal = $tambahansData->sum('harga');
        
        $itemHarga = $menu->harga + $tambahanHargaTotal;
        $subtotal = $itemHarga * $jumlah;

        // Hash unik untuk item keranjang (agar menu sama tapi opsi beda jadi baris terpisah)
        sort($selectedTambahanIds);
        $itemHash = md5($menu->id_menu . '_' . ($levelPedas ?? 0) . '_' . implode(',', $selectedTambahanIds) . '_' . strtolower($fullCatatan));

        $cart = session('cart', []);
        $oldHash = $request->input('old_hash');

        // Jika ini proses Edit item yang sudah ada, hapus item versi lama dari keranjang
        if ($oldHash && isset($cart[$oldHash])) {
            unset($cart[$oldHash]);
        }

        if (isset($cart[$itemHash])) {
            // Jika hash baru sama dengan hash item lain di keranjang (atau tidak berubah saat edit), perbarui nilainya
            if ($oldHash && $oldHash === $itemHash) {
                $cart[$itemHash]['jumlah'] = $jumlah;
            } else {
                $cart[$itemHash]['jumlah'] += $jumlah;
            }
            $cart[$itemHash]['subtotal'] = $cart[$itemHash]['jumlah'] * $itemHarga;
            $cart[$itemHash]['level_pedas'] = $levelPedas;
            $cart[$itemHash]['tambahans'] = $tambahansData->toArray();
            $cart[$itemHash]['catatan'] = $fullCatatan;
            $cart[$itemHash]['suhu'] = $suhu;
            $cart[$itemHash]['varian'] = $varian;
        } else {
            $cart[$itemHash] = [
                'item_hash' => $itemHash,
                'id_menu' => $menu->id_menu,
                'nama_menu' => $menu->nama_menu,
                'harga_menu' => $menu->harga,
                'opsi_pedas' => $menu->opsi_pedas,
                'level_pedas' => $levelPedas,
                'tambahans' => $tambahansData->toArray(),
                'catatan' => $fullCatatan,
                'suhu' => $suhu,
                'varian' => $varian,
                'jumlah' => $jumlah,
                'subtotal' => $subtotal,
                'foto' => $menu->foto
            ];
        }

        session(['cart' => $cart]);

        if ($oldHash) {
            return redirect()->route('customer.cart')->with('success', 'Pesanan di keranjang berhasil diperbarui!');
        }

        return redirect()->route('customer.menu', ['kategori' => $menu->kategori])->with('success', 'Menu berhasil ditambahkan ke keranjang!');
    }

    // Halaman 4: Keranjang
    public function cart()
    {
        $cart = session('cart', []);
        $totalHarga = array_sum(array_column($cart, 'subtotal'));

        return view('customer.cart', compact('cart', 'totalHarga'));
    }

    
    public function updateCart(Request $request)
    {
        $hash = $request->input('item_hash');
        $action = $request->input('action');
        $cart = session()->get('cart', []);

        if ($hash && isset($cart[$hash])) {
            if ($action === 'increase') {
                $cart[$hash]['jumlah'] += 1;
            } elseif ($action === 'decrease') {
                $cart[$hash]['jumlah'] -= 1;
            }

            // Jika kuantitas 0 atau kurang
            if ($cart[$hash]['jumlah'] <= 0) {
                unset($cart[$hash]);
            } else {
                // Hitung ulang subtotal item
                $tambahanHargaTotal = 0;
                if (!empty($cart[$hash]['tambahans'])) {
                    $tambahanHargaTotal = array_sum(array_column($cart[$hash]['tambahans'], 'harga'));
                }
                
                $itemHarga = $cart[$hash]['harga_menu'] + $tambahanHargaTotal;
                $cart[$hash]['subtotal'] = $cart[$hash]['jumlah'] * $itemHarga;
            }

            session()->put('cart', $cart);
        }

        return redirect()->route('customer.cart')->with('success', 'Keranjang berhasil diperbarui');
    }

    public function removeFromCart($hash)
    {
        $cart = session()->get('cart', []);
        
        if (isset($cart[$hash])) {
            unset($cart[$hash]);
            session()->put('cart', $cart);
        }

        return redirect()->route('customer.cart')->with('success', 'Item berhasil dihapus');
    }

    // Halaman 5: Checkout
    public function checkout()
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.menu')->with('error', 'Keranjang Anda masih kosong');
        }

        $totalHarga = array_sum(array_column($cart, 'subtotal'));
        $tipePesanan = session('tipe_pesanan', 'Dine-In');
        $nomorMeja = session('nomor_meja', null);

        return view('customer.checkout', compact('cart', 'totalHarga', 'tipePesanan', 'nomorMeja'));
    }

    // Process & Store Order
    public function storeOrder(Request $request)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return redirect()->route('customer.menu');
        }

        $request->validate([
            'nama_pemesan' => 'nullable|string|max:100',
            'metode_bayar' => 'required|in:Tunai,QRIS'
        ]);

        $totalHarga = array_sum(array_column($cart, 'subtotal'));
        $tipePesanan = session('tipe_pesanan', 'Dine-In');
        $idMeja = ($tipePesanan === 'Dine-In') ? session('id_meja') : null;

        DB::beginTransaction();
        try {
            $pesanan = Pesanan::create([
                'id_meja' => $idMeja,
                'id_admin' => null,
                'tipe_pesanan' => $tipePesanan,
                'nama_pemesan' => $request->nama_pemesan ?: 'Pelanggan',
                'status' => 'Diterima',
                'status_pembayaran' => 'Belum Lunas',
                'metode_bayar' => $request->metode_bayar,
                'tanggal_waktu' => Carbon::now(),
                'total_harga' => $totalHarga,
            ]);

            foreach ($cart as $item) {
                $detail = DetailPesanan::create([
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_menu' => $item['id_menu'],
                    'jumlah' => $item['jumlah'],
                    'level_pedas' => $item['level_pedas'],
                    'catatan' => $item['catatan'],
                    'subtotal' => $item['subtotal'],
                ]);

                if (!empty($item['tambahans'])) {
                    foreach ($item['tambahans'] as $tambahan) {
                        DB::table('detail_tambahans')->insert([
                            'id_detail' => $detail->id_detail,
                            'id_tambahan' => $tambahan['id_tambahan'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('customer.receipt', $pesanan->id_pesanan);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    // Halaman 6: Status Pesanan / Struk
    public function receipt($id)
    {
        $pesanan = Pesanan::with(['meja', 'detailPesanans.menu', 'detailPesanans.tambahans'])->findOrFail($id);
        return view('customer.receipt', compact('pesanan'));
    }

    // API JSON Polling Status Pesanan
    public function orderStatusJson($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        return response()->json([
            'status' => $pesanan->status,
            'status_pembayaran' => $pesanan->status_pembayaran,
            'uang_dibayar' => $pesanan->uang_dibayar,
            'kembalian' => $pesanan->kembalian,
            'alasan_pembatalan' => $pesanan->alasan_pembatalan,
        ]);
    }
}