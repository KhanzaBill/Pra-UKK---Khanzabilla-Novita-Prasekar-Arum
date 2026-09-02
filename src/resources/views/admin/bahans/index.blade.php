@extends('layouts.admin')

@section('title', 'Kelola Stok - Yummy Chicken')

@section('content')

<!-- Section KPI Cards Ringkasan Stok -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
    <!-- Card 1: Total Bahan -->
    <div class="card" style="padding: 18px 20px; display: flex; align-items: center; gap: 16px; border-left: 4px solid var(--primary);">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(211, 47, 47, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
            <i class="fa-solid fa-cubes-stacked"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; color: var(--text-sub); font-weight: 500;">Total Bahan Mentah</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-main); font-family: 'Playfair Display', serif;">{{ $totalBahan }}</div>
        </div>
    </div>

    <!-- Card 2: Stok Aman -->
    <div class="card" style="padding: 18px 20px; display: flex; align-items: center; gap: 16px; border-left: 4px solid #2E7D32;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #E8F5E9; color: #2E7D32; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
            <i class="fa-solid fa-circle-check"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; color: var(--text-sub); font-weight: 500;">Stok Aman (>10)</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #2E7D32; font-family: 'Playfair Display', serif;">{{ $stokAmanCount }}</div>
        </div>
    </div>

    <!-- Card 3: Stok Menipis -->
    <div class="card" style="padding: 18px 20px; display: flex; align-items: center; gap: 16px; border-left: 4px solid #F57F17;">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #FFFDE7; color: #F57F17; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; color: var(--text-sub); font-weight: 500;">Stok Menipis (1-10)</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #F57F17; font-family: 'Playfair Display', serif;">{{ $stokMenipisCount }}</div>
        </div>
    </div>

    <!-- Card 4: Stok Habis -->
    <div class="card" style="padding: 18px 20px; display: flex; align-items: center; gap: 16px; border-left: 4px solid var(--danger);">
        <div style="width: 48px; height: 48px; border-radius: 12px; background: #FFEBEE; color: var(--danger); display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
            <i class="fa-solid fa-circle-xmark"></i>
        </div>
        <div>
            <div style="font-size: 0.8rem; color: var(--text-sub); font-weight: 500;">Stok Habis (0)</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger); font-family: 'Playfair Display', serif;">{{ $stokHabisCount }}</div>
        </div>
    </div>
</div>

<!-- Main Card: Tabel Bahan -->
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">Daftar Stok Bahan</h2>
            <p style="font-size: 0.8rem; color: var(--text-sub); margin-top: 2px;">Kelola kuantitas bahan untuk ketersediaan menu</p>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="button" class="btn btn-primary" onclick="openTambahBahanModal()">
                <i class="fa-solid fa-plus"></i> Tambah Bahan Baru
            </button>
        </div>
    </div>

    <!-- Filter Status & Search -->
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap;">
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('admin.bahans.index', array_filter(['search' => $search])) }}" class="btn btn-sm {{ !$status ? 'btn-accent' : 'btn-secondary' }}">Semua Bahan</a>
            <a href="{{ route('admin.bahans.index', array_filter(['status' => 'aman', 'search' => $search])) }}" class="btn btn-sm {{ $status === 'aman' ? 'btn-accent' : 'btn-secondary' }}">Aman</a>
            <a href="{{ route('admin.bahans.index', array_filter(['status' => 'menipis', 'search' => $search])) }}" class="btn btn-sm {{ $status === 'menipis' ? 'btn-accent' : 'btn-secondary' }}">Menipis</a>
            <a href="{{ route('admin.bahans.index', array_filter(['status' => 'habis', 'search' => $search])) }}" class="btn btn-sm {{ $status === 'habis' ? 'btn-accent' : 'btn-secondary' }}">Habis</a>
        </div>

        <form action="{{ route('admin.bahans.index') }}" method="GET" style="display: flex; gap: 6px; align-items: center;">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <div style="position: relative; display: flex; align-items: center;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama bahan..." class="form-control form-control-sm" style="padding-left: 30px; padding-right: 28px; width: 220px;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; color: #888; font-size: 0.82rem;"></i>
                @if($search)
                    <a href="{{ route('admin.bahans.index', $status ? ['status' => $status] : []) }}" style="position: absolute; right: 8px; color: #888; text-decoration: none; font-size: 0.8rem;" title="Reset">
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
                    <th>Nama Bahan</th>
                    <th>Jumlah Stok</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bahans as $b)
                    @php
                        $stokVal = $b->stok;
                    @endphp
                    <tr>
                        <td><strong style="color: var(--primary);">#{{ str_pad($bahans->firstItem() + $loop->index, 3, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>
                            <strong style="font-size: 0.92rem; color: var(--text-main); font-family: 'Playfair Display', serif;">{{ $b->nama_bahan }}</strong>
                        </td>
                        <td>
                            <div style="display: flex; align-items: center; gap: 6px;">
                                <strong style="font-size: 1rem; color: {{ $stokVal <= 0 ? 'var(--danger)' : ($stokVal <= 10 ? '#F57F17' : '#2E7D32') }};">
                                    {{ number_format($stokVal, 0, ',', '.') }}
                                </strong>
                                <span style="font-size: 0.75rem; color: var(--text-sub);">porsi</span>
                            </div>
                        </td>
                        <td>
                            @if($stokVal <= 0)
                                <span class="badge badge-danger">Habis</span>
                            @elseif($stokVal <= 10)
                                <span class="badge badge-warning">Menipis</span>
                            @else
                                <span class="badge badge-success">Aman</span>
                            @endif
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <button type="button" class="btn btn-sm btn-secondary" onclick="openEditBahanModal({{ $b->id_bahan }}, '{{ addslashes($b->nama_bahan) }}', {{ $b->stok }})">
                                    <i class="fa-solid fa-pen-to-square"></i> Edit
                                </button>
                                <form action="{{ route('admin.bahans.destroy', $b->id_bahan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus bahan {{ addslashes($b->nama_bahan) }}?');" style="display: inline;">
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
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-sub);">
                            <i class="fa-solid fa-boxes-stacked" style="font-size: 2.5rem; color: #DDD; margin-bottom: 12px; display: block;"></i>
                            <strong>Tidak ada data bahan mentah</strong>
                            <p style="font-size: 0.8rem; margin-top: 4px;">Silakan tambahkan bahan baru atau ubah kata kunci pencarian Anda.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div style="margin-top: 10px;">
        {{ $bahans->withQueryString()->links('vendor.pagination.custom') }}
    </div>
</div>

<!-- Modal 1: Tambah Bahan Baru -->
<div class="modal-overlay" id="modalTambahBahan">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; color: var(--text-main);">
                Tambah Bahan Baru
            </h3>
            <button type="button" onclick="closeModal('modalTambahBahan')" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #888;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form action="{{ route('admin.bahans.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="nama_bahan_add">Nama Bahan Mentah <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nama_bahan" id="nama_bahan_add" class="form-control" placeholder="Contoh: Ayam Sayap, Saus Keju, dll." required maxlength="255">
            </div>

            <div class="form-group">
                <label class="form-label" for="stok_add">Jumlah Stok Awal <span style="color: var(--danger);">*</span></label>
                <input type="number" name="stok" id="stok_add" class="form-control" placeholder="0" min="0" required value="30">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalTambahBahan')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Bahan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal 2: Edit Bahan -->
<div class="modal-overlay" id="modalEditBahan">
    <div class="modal-card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; color: var(--text-main);">
                Edit Data Bahan
            </h3>
            <button type="button" onclick="closeModal('modalEditBahan')" style="background: none; border: none; font-size: 1.2rem; cursor: pointer; color: #888;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="formEditBahan" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label" for="nama_bahan_edit">Nama Bahan Mentah <span style="color: var(--danger);">*</span></label>
                <input type="text" name="nama_bahan" id="nama_bahan_edit" class="form-control" required maxlength="255">
            </div>

            <div class="form-group">
                <label class="form-label" for="stok_edit">Jumlah Stok <span style="color: var(--danger);">*</span></label>
                <input type="number" name="stok" id="stok_edit" class="form-control" min="0" required>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditBahan')">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function openTambahBahanModal() {
        document.getElementById('modalTambahBahan').style.display = 'flex';
    }

    function openEditBahanModal(id, nama, stok) {
        document.getElementById('nama_bahan_edit').value = nama;
        document.getElementById('stok_edit').value = stok;
        document.getElementById('formEditBahan').action = "{{ url('admin/bahans') }}/" + id;
        document.getElementById('modalEditBahan').style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    window.onclick = function(event) {
        ['modalTambahBahan', 'modalEditBahan'].forEach(id => {
            const modal = document.getElementById(id);
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
</script>
@endsection
