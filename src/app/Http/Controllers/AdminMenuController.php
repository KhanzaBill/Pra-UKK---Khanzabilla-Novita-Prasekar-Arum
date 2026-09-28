<?php

namespace App\Http\Controllers; // Mendefinisikan namespace controller berada di App\Http\Controllers

use Illuminate\Http\Request;    // Import class Request untuk menangani input HTTP
use App\Models\Menu;            // Import model Menu untuk akses data menu
use App\Models\Tambahan;        // Import model Tambahan untuk akses data menu tambahan / add-on
use App\Models\Bahan;           // Import model Bahan untuk akses data bahan baku
use App\Models\Meja;            // Import model Meja untuk akses data meja restoran

// Controller untuk mengelola Menu, Tambahan, dan QR Code meja oleh admin
class AdminMenuController extends Controller
{
    /**
     * Menampilkan daftar semua menu dengan filter kategori dan pencarian.
     * Juga memuat daftar tambahan dan bahan untuk keperluan form modal.
     */
    // List Menu
    public function index(Request $request)
    {
        $kategori = $request->query('kategori'); // Ambil filter kategori dari query string (Paket/Makanan/Minuman)
        $search   = trim($request->query('search', '')); // Ambil keyword pencarian, hapus spasi di tepi

        $query = Menu::with('bahans'); // Mulai query Menu dengan eager load relasi bahans

        // Terapkan filter kategori jika dipilih
        if ($kategori) {
            $query->where('kategori', $kategori); // Filter berdasarkan kolom kategori
        }

        // Terapkan filter pencarian berdasarkan nama menu jika keyword tidak kosong
        if ($search !== '') {
            $query->where('nama_menu', 'like', "%{$search}%"); // Filter nama menu yang mengandung keyword
        }

        // Ambil hasil query, urutkan berdasarkan ID numerik secara ascending, paginasi 10 per halaman
        $menus = $query->orderByRaw('CAST(id_menu AS UNSIGNED) ASC')->paginate(10)->withQueryString();
    
        $tambahans = Tambahan::with('bahans')->get(); // Ambil semua tambahan beserta bahannya (untuk form modal)
        $allBahans = Bahan::orderBy('nama_bahan', 'asc')->get(); // Ambil semua bahan urut abjad (untuk form modal)

        // Kirim semua data ke view admin.menus.index
        return view('admin.menus.index', compact('menus', 'tambahans', 'allBahans', 'kategori', 'search'));
    }

    /**
     * Menampilkan form untuk menambah menu baru.
     */
    // Form Tambah Menu
    public function create()
    {
        $allBahans = Bahan::orderBy('nama_bahan', 'asc')->get(); // Ambil semua bahan urut abjad untuk form pilihan bahan
        return view('admin.menus.create', compact('allBahans')); // Tampilkan view form create menu dengan data bahan
    }

    /**
     * Menyimpan menu baru beserta relasinya ke database.
     * Memvalidasi input termasuk file foto, lalu menyimpan menu dan mensinkronkan bahan terkait.
     */
    // Store Menu
    public function store(Request $request)
    {
        // Validasi semua input dari form tambah menu
        $request->validate([
            'nama_menu'                   => 'required|string|max:255',            // Nama menu wajib diisi, maks 255 karakter
            'kategori'                    => 'required|in:Paket,Makanan,Minuman',  // Kategori wajib, hanya 3 pilihan yang valid
            'harga'                       => 'required|integer|min:0',             // Harga wajib diisi, bilangan bulat, minimal 0
            'deskripsi'                   => 'nullable|string',                    // Deskripsi opsional
            'status_stok'                 => 'required|in:Tersedia,Habis',         // Status stok wajib dipilih
            'opsi_pedas'                  => 'required|in:Ya,Tidak',              // Opsi pedas wajib dipilih
            'foto'                        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072', // Foto opsional, maks 3MB
            'bahans'                      => 'nullable|array',                     // Array bahan opsional
            'bahans.*.id_bahan'           => 'nullable|exists:bahans,id_bahan',   // Setiap ID bahan harus valid
            'bahans.*.jumlah_dibutuhkan'  => 'nullable|integer|min:1',            // Jumlah dibutuhkan minimal 1
        ]);

        $data = $request->except(['foto', 'bahans']); // Siapkan data menu, kecuali kolom foto dan bahans (diproses terpisah)

        // Jika ada file foto yang diunggah, simpan ke storage dan masukkan pathnya ke data
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('menu', 'public'); // Simpan foto ke disk public/menu, dapatkan path relatif
        }

        $menu = Menu::create($data); // Simpan data menu baru ke database dan dapatkan objek model yang baru dibuat

        // Jika ada data bahan yang dikirim, sinkronkan relasi bahan dengan menu
        if ($request->has('bahans') && is_array($request->bahans)) {
            $syncData = []; // Array untuk data sync pivot many-to-many
            foreach ($request->bahans as $b) { // Iterasi setiap bahan yang dipilih
                if (!empty($b['id_bahan'])) { // Lewati jika ID bahan kosong
                    // Tambahkan ke sync data dengan jumlah_dibutuhkan (minimal 1)
                    $syncData[$b['id_bahan']] = ['jumlah_dibutuhkan' => max(1, (int) ($b['jumlah_dibutuhkan'] ?? 1))];
                }
            }
            $menu->bahans()->sync($syncData); // Sinkronkan relasi pivot menu ↔ bahan (hapus lama, tambah baru)
        }

        // Redirect ke daftar menu dengan pesan sukses
        return redirect()->route('admin.menus.index')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit menu berdasarkan ID.
     * Memuat data menu beserta bahan-bahannya untuk pre-fill form.
     */
    // Form Edit Menu
    public function edit($id)
    {
        $menu      = Menu::with('bahans')->findOrFail($id);               // Ambil menu beserta bahans, throw 404 jika tidak ada
        $allBahans = Bahan::orderBy('nama_bahan', 'asc')->get();          // Ambil semua bahan urut abjad untuk form pilihan
        return view('admin.menus.edit', compact('menu', 'allBahans'));     // Tampilkan view form edit dengan data menu dan bahan
    }

    /**
     * Memperbarui data menu yang sudah ada beserta relasi bahans-nya.
     * Menangani penghapusan/penggantian foto dan sinkronisasi bahan.
     */
    // Update Menu
    public function update(Request $request, $id)
    {
        // Validasi semua input dari form edit menu
        $request->validate([
            'nama_menu'                   => 'required|string|max:255',
            'kategori'                    => 'required|in:Paket,Makanan,Minuman',
            'harga'                       => 'required|integer|min:0',
            'deskripsi'                   => 'nullable|string',
            'status_stok'                 => 'required|in:Tersedia,Habis',
            'opsi_pedas'                  => 'required|in:Ya,Tidak',
            'foto'                        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'bahans'                      => 'nullable|array',
            'bahans.*.id_bahan'           => 'nullable|exists:bahans,id_bahan',
            'bahans.*.jumlah_dibutuhkan'  => 'nullable|integer|min:1',
        ]);

        $menu = Menu::findOrFail($id);                                       // Ambil menu berdasarkan ID, throw 404 jika tidak ada
        $data = $request->except(['foto', 'hapus_foto', 'bahans']);          // Siapkan data tanpa kolom yang diproses terpisah

        // Jika admin meminta penghapusan foto (checkbox hapus_foto dicentang)
        if ($request->has('hapus_foto') && $request->hapus_foto == '1') {
            if ($menu->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($menu->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($menu->foto); // Hapus file foto lama dari storage
            }
            $data['foto'] = null; // Set kolom foto menjadi null di database
        }

        // Jika ada file foto baru yang diunggah
        if ($request->hasFile('foto')) {
            if ($menu->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($menu->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($menu->foto); // Hapus foto lama dari storage sebelum menyimpan yang baru
            }
            $data['foto'] = $request->file('foto')->store('menu', 'public'); // Simpan foto baru ke storage dan dapatkan pathnya
        }

        $menu->update($data); // Update data menu di database

        // Sinkronkan atau lepas relasi bahan berdasarkan input
        if ($request->has('bahans') && is_array($request->bahans)) {
            $syncData = []; // Array untuk data sync pivot
            foreach ($request->bahans as $b) { // Iterasi setiap bahan yang dipilih
                if (!empty($b['id_bahan'])) {   // Lewati jika ID bahan kosong
                    $syncData[$b['id_bahan']] = ['jumlah_dibutuhkan' => max(1, (int) ($b['jumlah_dibutuhkan'] ?? 1))]; // Tambahkan ke sync data
                }
            }
            $menu->bahans()->sync($syncData); // Sinkronkan relasi pivot (hapus lama, tambah baru sesuai input)
        } else {
            $menu->bahans()->detach(); // Jika tidak ada bahan yang dipilih, lepas semua relasi bahan
        }

        // Redirect ke daftar menu dengan pesan sukses
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui!');
    }

    /**
     * Toggle status stok menu secara cepat antara 'Tersedia' dan 'Habis'.
     */
    // Toggle Status Stok Quick Action
    public function toggleStok($id)
    {
        $menu = Menu::findOrFail($id); // Ambil menu berdasarkan ID, throw 404 jika tidak ada
        $menu->status_stok = ($menu->status_stok === 'Tersedia') ? 'Habis' : 'Tersedia'; // Toggle status: jika Tersedia ganti ke Habis, dan sebaliknya
        $menu->save(); // Simpan perubahan status ke database

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Status stok menu ' . $menu->nama_menu . ' berhasil diubah menjadi ' . $menu->status_stok);
    }

    /**
     * Menghapus menu dari database.
     * Terlebih dahulu melepas semua relasi bahan sebelum menghapus menu.
     */
    // Delete Menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id); // Ambil menu berdasarkan ID, throw 404 jika tidak ada
        $menu->bahans()->detach();      // Lepaskan semua relasi pivot menu ↔ bahan sebelum menghapus
        $menu->delete();               // Hapus data menu dari database

        // Redirect ke daftar menu dengan pesan sukses
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus!');
    }

    // --- CRUD TAMBAHAN (Menu Add-On / Topping) ---

    /**
     * Menyimpan menu tambahan baru ke database.
     * Juga menghubungkan tambahan ke satu bahan baku jika dipilih.
     */
    public function storeTambahan(Request $request)
    {
        // Validasi input dari form tambah tambahan
        $request->validate([
            'nama_tambahan' => 'required|string|max:255',     // Nama tambahan wajib diisi
            'harga'         => 'required|integer|min:0',       // Harga wajib diisi, minimal 0
            'status_stok'   => 'nullable|in:Tersedia,Habis',   // Status stok opsional
            'id_bahan'      => 'nullable|exists:bahans,id_bahan' // Bahan terkait opsional, harus valid jika diisi
        ]);

        $data = $request->except(['id_bahan']); // Siapkan data tambahan, kecuali id_bahan (diproses terpisah)
        if (empty($data['status_stok'])) {
            $data['status_stok'] = 'Tersedia'; // Set default status stok ke 'Tersedia' jika tidak diisi
        }

        $tambahan = Tambahan::create($data); // Simpan tambahan baru ke database

        // Jika bahan terkait dipilih, hubungkan tambahan ke bahan tersebut dengan jumlah_dibutuhkan = 1
        if ($request->filled('id_bahan')) {
            $tambahan->bahans()->sync([$request->id_bahan => ['jumlah_dibutuhkan' => 1]]); // Sinkronkan relasi pivot tambahan ↔ bahan
        }

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Menu Tambahan berhasil ditambahkan!');
    }

    /**
     * Memperbarui data menu tambahan yang sudah ada.
     * Juga memperbarui relasi bahan yang terhubung.
     */
    public function updateTambahan(Request $request, $id)
    {
        // Validasi input dari form edit tambahan
        $request->validate([
            'nama_tambahan' => 'required|string|max:255',
            'harga'         => 'required|integer|min:0',
            'status_stok'   => 'nullable|in:Tersedia,Habis',
            'id_bahan'      => 'nullable|exists:bahans,id_bahan'
        ]);

        $tambahan = Tambahan::findOrFail($id);      // Ambil tambahan berdasarkan ID, throw 404 jika tidak ada
        $data     = $request->except(['id_bahan']); // Siapkan data tanpa id_bahan
        $tambahan->update($data);                   // Update data tambahan di database

        // Sinkronkan atau lepas relasi bahan berdasarkan input
        if ($request->filled('id_bahan')) {
            $tambahan->bahans()->sync([$request->id_bahan => ['jumlah_dibutuhkan' => 1]]); // Hubungkan ke bahan yang dipilih
        } else {
            $tambahan->bahans()->detach(); // Lepas semua relasi bahan jika tidak ada bahan yang dipilih
        }

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Menu Tambahan berhasil diperbarui!');
    }

    /**
     * Toggle status stok tambahan secara cepat antara 'Tersedia' dan 'Habis'.
     */
    public function toggleStokTambahan($id)
    {
        $tambahan = Tambahan::findOrFail($id); // Ambil tambahan berdasarkan ID, throw 404 jika tidak ada
        $tambahan->status_stok = ($tambahan->status_stok === 'Tersedia') ? 'Habis' : 'Tersedia'; // Toggle status stok
        $tambahan->save(); // Simpan perubahan ke database

        // Redirect kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Status stok menu tambahan ' . $tambahan->nama_tambahan . ' berhasil diubah menjadi ' . $tambahan->status_stok);
    }

    /**
     * Menghapus menu tambahan dari database.
     * Terlebih dahulu melepas semua relasi bahan sebelum menghapus.
     */
    public function destroyTambahan($id)
    {
        $tambahan = Tambahan::findOrFail($id); // Ambil tambahan berdasarkan ID, throw 404 jika tidak ada
        $tambahan->bahans()->detach();          // Lepaskan semua relasi pivot tambahan ↔ bahan sebelum menghapus
        $tambahan->delete();                   // Hapus data tambahan dari database

        // Redirect kembali ke halaman sebelumnya dengan pesan sukses
        return redirect()->back()->with('success', 'Menu Tambahan berhasil dihapus!');
    }

    // --- CETAK / GENERATE QR CODE MEJA ---

    /**
     * Menampilkan halaman QR Code untuk semua meja.
     * QR Code digunakan pelanggan untuk scan dan mulai memesan.
     */
    public function qrCodes()
    {
        $mejas = Meja::orderBy('id_meja')->get(); // Ambil semua data meja, urutkan berdasarkan ID meja
        return view('admin.qrcodes.index', compact('mejas')); // Tampilkan view halaman QR code dengan data meja
    }
}