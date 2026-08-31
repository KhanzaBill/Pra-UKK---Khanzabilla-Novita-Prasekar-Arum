<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Tambahan;
use App\Models\Meja;

class AdminMenuController extends Controller
{
    // List Menu
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');
        $search = trim($request->query('search', ''));

        $query = Menu::query();

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        if ($search !== '') {
            $query->where('nama_menu', 'like', "%{$search}%");
        }

        $menus = $query->orderByRaw('CAST(id_menu AS UNSIGNED) ASC')->paginate(10)->withQueryString();
    
        $tambahans = Tambahan::all();

        return view('admin.menus.index', compact('menus', 'tambahans', 'kategori', 'search'));
    }

    // Form Tambah Menu
    public function create()
    {
        return view('admin.menus.create');
    }

    // Store Menu
    public function store(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'required|in:Paket,Makanan,Minuman',
            'harga' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status_stok' => 'required|in:Tersedia,Habis',
            'opsi_pedas' => 'required|in:Ya,Tidak',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $data = $request->except('foto');

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('menu', 'public');
        }

        Menu::create($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    // Form Edit Menu
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.menus.edit', compact('menu'));
    }

    // Update Menu
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori' => 'required|in:Paket,Makanan,Minuman',
            'harga' => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'status_stok' => 'required|in:Tersedia,Habis',
            'opsi_pedas' => 'required|in:Ya,Tidak',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $menu = Menu::findOrFail($id);
        $data = $request->except(['foto', 'hapus_foto']);

        if ($request->has('hapus_foto') && $request->hapus_foto == '1') {
            if ($menu->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($menu->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($menu->foto);
            }
            $data['foto'] = null;
        }

        if ($request->hasFile('foto')) {
            if ($menu->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($menu->foto)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($menu->foto);
            }
            $data['foto'] = $request->file('foto')->store('menu', 'public');
        }

        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui!');
    }

    // Toggle Status Stok Quick Action
    public function toggleStok($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->status_stok = ($menu->status_stok === 'Tersedia') ? 'Habis' : 'Tersedia';
        $menu->save();

        return redirect()->back()->with('success', 'Status stok menu ' . $menu->nama_menu . ' berhasil diubah menjadi ' . $menu->status_stok);
    }

    // Delete Menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus!');
    }

    // --- CRUD TAMBAHAN ---
    public function storeTambahan(Request $request)
    {
        $request->validate([
            'nama_tambahan' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'status_stok' => 'nullable|in:Tersedia,Habis'
        ]);

        $data = $request->all();
        if (empty($data['status_stok'])) {
            $data['status_stok'] = 'Tersedia';
        }

        Tambahan::create($data);

        return redirect()->back()->with('success', 'Menu Tambahan berhasil ditambahkan!');
    }

    public function updateTambahan(Request $request, $id)
    {
        $request->validate([
            'nama_tambahan' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'status_stok' => 'nullable|in:Tersedia,Habis'
        ]);

        $tambahan = Tambahan::findOrFail($id);
        $tambahan->update($request->all());

        return redirect()->back()->with('success', 'Menu Tambahan berhasil diperbarui!');
    }

    public function toggleStokTambahan($id)
    {
        $tambahan = Tambahan::findOrFail($id);
        $tambahan->status_stok = ($tambahan->status_stok === 'Tersedia') ? 'Habis' : 'Tersedia';
        $tambahan->save();

        return redirect()->back()->with('success', 'Status stok menu tambahan ' . $tambahan->nama_tambahan . ' berhasil diubah menjadi ' . $tambahan->status_stok);
    }

    public function destroyTambahan($id)
    {
        $tambahan = Tambahan::findOrFail($id);
        $tambahan->delete();

        return redirect()->back()->with('success', 'Menu Tambahan berhasil dihapus!');
    }

    // --- CETAK / GENERATE QR CODE MEJA ---
    public function qrCodes()
    {
        $mejas = Meja::orderBy('id_meja')->get();
        return view('admin.qrcodes.index', compact('mejas'));
    }
}