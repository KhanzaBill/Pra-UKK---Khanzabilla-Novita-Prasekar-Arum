@extends('layouts.admin')

@section('title', 'Kelola Menu - Yummy Chicken')

@section('content')

<!-- Section 1: Kelola Menu Utama -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">Kelola Menu Utama</h2>
            <p style="font-size: 0.8rem; color: var(--text-sub); margin-top: 2px;">Atur ketersediaan stok menu</p>
        </div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.menus.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Tambah Menu Baru
            </a>
        </div>
    </div>

    <!-- Filter Kategori & Search -->
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.menus.index', array_filter(['search' => $search])) }}" class="btn btn-sm {{ !$kategori ? 'btn-accent' : 'btn-secondary' }}">Semua Kategori</a>
            <a href="{{ route('admin.menus.index', array_filter(['kategori' => 'Paket', 'search' => $search])) }}" class="btn btn-sm {{ $kategori === 'Paket' ? 'btn-accent' : 'btn-secondary' }}">Paket</a>
            <a href="{{ route('admin.menus.index', array_filter(['kategori' => 'Makanan', 'search' => $search])) }}" class="btn btn-sm {{ $kategori === 'Makanan' ? 'btn-accent' : 'btn-secondary' }}">Makanan</a>
            <a href="{{ route('admin.menus.index', array_filter(['kategori' => 'Minuman', 'search' => $search])) }}" class="btn btn-sm {{ $kategori === 'Minuman' ? 'btn-accent' : 'btn-secondary' }}">Minuman</a>
        </div>

        <form action="{{ route('admin.menus.index') }}" method="GET" style="display: flex; gap: 6px; align-items: center;">
            @if($kategori)
                <input type="hidden" name="kategori" value="{{ $kategori }}">
            @endif
            <div style="position: relative; display: flex; align-items: center;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama menu..." class="form-control form-control-sm" style="padding-left: 30px; padding-right: 28px; width: 220px;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; color: #888; font-size: 0.82rem;"></i>
                @if($search)
                    <a href="{{ route('admin.menus.index', $kategori ? ['kategori' => $kategori] : []) }}" style="position: absolute; right: 8px; color: #888; text-decoration: none; font-size: 0.8rem;" title="Reset">
                        <i class="fa-solid fa-xmark"></i>
                    </a>
                @endif
            </div>
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="fa-solid fa-search"></i> Cari
            </button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Foto</th>
                    <th>Nama Menu</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Opsi Pedas</th>
                    <th>Status Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($menus as $m)
                    <tr>
                        <td><strong style="color: var(--primary);">#{{ str_pad($menus->firstItem() + $loop->index, 3, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>
                            @if($m->foto)
                                <img src="{{ asset('storage/' . $m->foto) }}" alt="{{ $m->nama_menu }}" loading="lazy" style="width: 48px; height: 48px; object-fit: cover; border-radius: 8px; border: 1px solid var(--border);">
                            @else
                                <div style="width: 48px; height: 48px; border-radius: 8px; background: #F4F6F9; display: flex; align-items: center; justify-content: center; color: #CCC; font-size: 1.2rem;">
                                    <i class="fa-solid fa-utensils"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong style="font-size: 0.92rem; font-family: 'Playfair Display', serif;">{{ $m->nama_menu }}</strong>
                            <div style="font-size: 0.75rem; color: var(--text-sub); margin-top: 2px;">{{ Str::limit($m->deskripsi, 50) }}</div>
                        </td>
                        <td><span class="badge badge-info">{{ $m->kategori }}</span></td>
                        <td><strong style="color: var(--primary); font-size: 0.92rem;">Rp {{ number_format($m->harga, 0, ',', '.') }}</strong></td>
                        <td>
                            @if($m->opsi_pedas === 'Ya')
                                <span class="badge badge-danger">Ya</span>
                            @else
                                <span class="badge badge-secondary">Tidak</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.menus.toggle_stok', $m->id_menu) }}" method="POST" style="display: inline;">
                                @csrf
                                @if($m->status_stok === 'Tersedia')
                                    <button type="submit" class="btn btn-sm btn-success" title="Klik untuk ubah status ke Habis">
                                        Tersedia
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-danger" title="Klik untuk ubah status ke Tersedia">
                                        Habis
                                    </button>
                                @endif
                            </form>
                        </td>
                        <td>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <a href="{{ route('admin.menus.edit', $m->id_menu) }}" class="btn btn-sm btn-secondary">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.menus.destroy', $m->id_menu) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus menu {{ $m->nama_menu }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px 20px; color: var(--text-sub);">
                            <i class="fa-solid fa-utensils" style="font-size: 2.5rem; color: #DDD; margin-bottom: 8px; display: block;"></i>
                            Belum ada data menu pada kategori ini
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 10px;">
        {{ $menus->withQueryString()->links('vendor.pagination.custom') }}
    </div>
</div>


<!-- Section 2: Kelola Menu Tambahan -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">Kelola Menu Tambahan</h2>
            <p style="font-size: 0.8rem; color: var(--text-sub); margin-top: 2px;">Tambahan untuk Menu Utama</p>
        </div>

        <button type="button" class="btn btn-primary" onclick="openTambahanModal()">
            <i class="fa-solid fa-plus"></i> Tambah Menu Tambahan
        </button>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Tambahan</th>
                    <th>Bahan Mentah Terkait</th>
                    <th>Harga Tambahan</th>
                    <th>Status Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tambahans as $t)
                    @php $firstBahan = $t->bahans->first(); @endphp
                    <tr>
                        <td><strong style="color: var(--primary);">#{{ str_pad($loop->iteration, 3, '0', STR_PAD_LEFT) }}</strong></td>
                        <td><strong style="font-size: 0.9rem;">{{ $t->nama_tambahan }}</strong></td>
                        <td>
                            @if($firstBahan)
                                <span style="font-size: 0.88rem; color: var(--text-main); font-weight: 500;">Stok: {{ $firstBahan->stok }}</span>
                            @else
                                <span style="font-size: 0.85rem; color: var(--text-sub);">-</span>
                            @endif
                        </td>
                        <td><strong style="color: var(--success); font-size: 0.92rem;">+ Rp {{ number_format($t->harga, 0, ',', '.') }}</strong></td>
                        <td>
                            <form action="{{ route('admin.tambahans.toggle_stok', $t->id_tambahan) }}" method="POST" style="display: inline;">
                                @csrf
                                @if($t->status_stok === 'Tersedia')
                                    <button type="submit" class="btn btn-sm btn-success" title="Klik untuk ubah status ke Habis">
                                        Tersedia
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-danger" title="Klik untuk ubah status ke Tersedia">
                                        Habis
                                    </button>
                                @endif
                            </form>
                        </td>
                        <td>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <button type="button" class="btn btn-sm btn-secondary" onclick="editTambahanModal({{ $t->id_tambahan }}, '{{ addslashes($t->nama_tambahan) }}', {{ $t->harga }}, '{{ $t->status_stok }}', '{{ $firstBahan ? $firstBahan->id_bahan : '' }}')">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                <form action="{{ route('admin.tambahans.destroy', $t->id_tambahan) }}" method="POST" onsubmit="return confirm('Hapus item tambahan {{ addslashes($t->nama_tambahan) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 36px 20px; color: var(--text-sub);">
                            <i class="fa-solid fa-folder-open" style="font-size: 2.5rem; color: #DDD; margin-bottom: 8px; display: block;"></i>
                            Belum ada menu tambahan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah / Edit Menu Tambahan -->
<div class="modal-overlay" id="tambahanModal">
    <div class="modal-card">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; margin-bottom: 16px; color: var(--text-main);" id="tambahanModalTitle">
            Tambah Menu Tambahan
        </h3>
        <form id="tambahanForm" method="POST">
            @csrf
            <div id="tambahanMethod"></div>

            <div class="form-group">
                <label class="form-label" for="nama_tambahan">Nama Tambahan:</label>
                <input type="text" name="nama_tambahan" id="nama_tambahan" class="form-control" placeholder="Contoh: Sambal Matah" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="id_bahan_tambahan">Bahan Mentah Terkait (Otomatisasi Stok):</label>
                <select name="id_bahan" id="id_bahan_tambahan" class="form-control">
                    <option value="">-- Pilih Bahan Mentah (Opsional) --</option>
                    @foreach($allBahans as $ab)
                        <option value="{{ $ab->id_bahan }}">{{ $ab->nama_bahan }} (Stok: {{ $ab->stok }})</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="harga_tambahan">Harga (Rp):</label>
                <input type="number" name="harga" id="harga_tambahan" class="form-control" placeholder="Contoh: 5000" min="0" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="status_stok_tambahan">Status Stok:</label>
                <select name="status_stok" id="status_stok_tambahan" class="form-control">
                    <option value="Tersedia">Tersedia</option>
                    <option value="Habis">Habis</option>
                </select>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="closeTambahanModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openTambahanModal() {
        document.getElementById('tambahanModalTitle').innerText = 'Tambah Menu Tambahan';
        document.getElementById('tambahanForm').action = "{{ route('admin.tambahans.store') }}";
        document.getElementById('tambahanMethod').innerHTML = '';
        document.getElementById('nama_tambahan').value = '';
        document.getElementById('id_bahan_tambahan').value = '';
        document.getElementById('harga_tambahan').value = '';
        document.getElementById('status_stok_tambahan').value = 'Tersedia';
        document.getElementById('tambahanModal').style.display = 'flex';
    }

    function editTambahanModal(id, nama, harga, statusStok, idBahan = '') {
        document.getElementById('tambahanModalTitle').innerText = 'Edit Menu Tambahan';
        document.getElementById('tambahanForm').action = "/admin/tambahans/" + id;
        document.getElementById('tambahanMethod').innerHTML = '@method("PUT")';
        document.getElementById('nama_tambahan').value = nama;
        document.getElementById('id_bahan_tambahan').value = idBahan || '';
        document.getElementById('harga_tambahan').value = harga;
        document.getElementById('status_stok_tambahan').value = statusStok || 'Tersedia';
        document.getElementById('tambahanModal').style.display = 'flex';
    }

    function closeTambahanModal() {
        document.getElementById('tambahanModal').style.display = 'none';
    }
</script>
@endsection

