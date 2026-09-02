<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bahan;

class AdminBahanController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));
        $status = $request->query('status'); // aman, menipis, habis

        $query = Bahan::with(['menus', 'tambahans']);

        if ($search !== '') {
            $query->where('nama_bahan', 'like', "%{$search}%");
        }

        if ($status === 'habis') {
            $query->where('stok', '<=', 0);
        } elseif ($status === 'menipis') {
            $query->where('stok', '>', 0)->where('stok', '<=', 10);
        } elseif ($status === 'aman') {
            $query->where('stok', '>', 10);
        }

        $bahans = $query->orderBy('nama_bahan', 'asc')->paginate(12)->withQueryString();

        // Statistik Stok Bahan
        $totalBahan = Bahan::count();
        $stokHabisCount = Bahan::where('stok', '<=', 0)->count();
        $stokMenipisCount = Bahan::where('stok', '>', 0)->where('stok', '<=', 10)->count();
        $stokAmanCount = Bahan::where('stok', '>', 10)->count();

        return view('admin.bahans.index', compact(
            'bahans',
            'search',
            'status',
            'totalBahan',
            'stokHabisCount',
            'stokMenipisCount',
            'stokAmanCount'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255|unique:bahans,nama_bahan',
            'stok' => 'required|integer|min:0'
        ], [
            'nama_bahan.unique' => 'Bahan dengan nama tersebut sudah terdaftar.',
            'stok.min' => 'Stok bahan tidak boleh bernilai negatif.'
        ]);

        Bahan::create([
            'nama_bahan' => $request->nama_bahan,
            'stok' => (int) $request->stok
        ]);

        return redirect()->route('admin.bahans.index')->with('success', 'Bahan baru "' . $request->nama_bahan . '" berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bahan' => 'required|string|max:255|unique:bahans,nama_bahan,' . $id . ',id_bahan',
            'stok' => 'required|integer|min:0'
        ]);

        $bahan = Bahan::findOrFail($id);
        $bahan->update([
            'nama_bahan' => $request->nama_bahan,
            'stok' => (int) $request->stok
        ]);

        return redirect()->back()->with('success', 'Data bahan "' . $bahan->nama_bahan . '" berhasil diperbarui!');
    }

    public function quickStock(Request $request, $id)
    {
        $request->validate([
            'aksi' => 'required|in:tambah,kurang,set',
            'jumlah' => 'required|integer|min:0'
        ]);

        $bahan = Bahan::findOrFail($id);
        $jumlah = (int) $request->jumlah;

        if ($request->aksi === 'tambah') {
            $bahan->increment('stok', $jumlah);
            $pesan = 'Berhasil menambah ' . $jumlah . ' stok untuk bahan "' . $bahan->nama_bahan . '". Stok sekarang: ' . $bahan->stok;
        } elseif ($request->aksi === 'kurang') {
            $kurangSebesar = min($bahan->stok, $jumlah);
            $bahan->decrement('stok', $kurangSebesar);
            $pesan = 'Berhasil mengurangi ' . $kurangSebesar . ' stok dari bahan "' . $bahan->nama_bahan . '". Stok sekarang: ' . $bahan->stok;
        } else {
            $bahan->update(['stok' => $jumlah]);
            $pesan = 'Stok bahan "' . $bahan->nama_bahan . '" berhasil disetel menjadi ' . $jumlah . '.';
        }

        return redirect()->back()->with('success', $pesan);
    }

    public function destroy($id)
    {
        $bahan = Bahan::withCount(['menus', 'tambahans'])->findOrFail($id);

        if ($bahan->menus_count > 0 || $bahan->tambahans_count > 0) {
            // Lepaskan relasi pivot lalu hapus
            $bahan->menus()->detach();
            $bahan->tambahans()->detach();
        }

        $nama = $bahan->nama_bahan;
        $bahan->delete();

        return redirect()->route('admin.bahans.index')->with('success', 'Bahan "' . $nama . '" berhasil dihapus!');
    }
}
