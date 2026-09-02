@extends('layouts.admin')

@section('title', 'Tambah Menu Baru - Yummy Chicken')

@section('content')
<div class="card" style="max-width: 640px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h2 class="card-title">Tambah Menu Baru</h2>
            <p style="font-size: 0.8rem; color: var(--text-sub); margin-top: 2px;">Lengkapi detail menu baru untuk ditampilkan ke pelanggan</p>
        </div>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label class="form-label" for="nama_menu">Nama Menu <span style="color: var(--danger);">*</span></label>
            <input type="text" name="nama_menu" id="nama_menu" class="form-control @error('nama_menu') is-invalid @enderror" placeholder="Contoh: Paket 13 Geprek Mozarella" value="{{ old('nama_menu') }}" required autofocus>
            @error('nama_menu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label class="form-label" for="kategori">Kategori <span style="color: var(--danger);">*</span></label>
                <select name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="Paket" {{ old('kategori') === 'Paket' ? 'selected' : '' }}>Paket</option>
                    <option value="Makanan" {{ old('kategori') === 'Makanan' ? 'selected' : '' }}>Makanan</option>
                    <option value="Minuman" {{ old('kategori') === 'Minuman' ? 'selected' : '' }}>Minuman</option>
                </select>
                @error('kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="harga">Harga (Rp) <span style="color: var(--danger);">*</span></label>
                <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" placeholder="Contoh: 18000" value="{{ old('harga') }}" min="0" required>
                @error('harga')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="foto">Foto Menu (Opsional):</label>
            <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewImage(this)">
            <p style="font-size: 0.75rem; color: var(--text-sub); margin-top: 4px;">Format: JPG, PNG, WEBP. Maksimal 3 MB.</p>
            @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div id="imgPreviewWrapper" style="margin-top: 10px; display: none;">
                <img id="imgPreview" src="#" alt="Preview Foto" loading="lazy" style="max-width: 120px; max-height: 120px; border-radius: 10px; border: 1.5px solid var(--border); object-fit: cover;">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="deskripsi">Deskripsi Menu</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3" placeholder="Tuliskan komposisi / deskripsi singkat menu...">{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label class="form-label" for="opsi_pedas">Pilihan Pedas <span style="color: var(--danger);">*</span></label>
                <select name="opsi_pedas" id="opsi_pedas" class="form-control @error('opsi_pedas') is-invalid @enderror" required>
                    <option value="Ya" {{ old('opsi_pedas') === 'Ya' ? 'selected' : '' }}>Ya</option>
                    <option value="Tidak" {{ old('opsi_pedas', 'Tidak') === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                </select>
                @error('opsi_pedas')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="status_stok">Status Stok <span style="color: var(--danger);">*</span></label>
                <select name="status_stok" id="status_stok" class="form-control" required>
                    <option value="Tersedia" {{ old('status_stok', 'Tersedia') === 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Habis" {{ old('status_stok') === 'Habis' ? 'selected' : '' }}>Habis</option>
                </select>
            </div>
        </div>

        <!-- Resep Bahan Mentah -->
        <div style="background: #FAFAFA; border: 1.5px solid var(--border); border-radius: 14px; padding: 20px; margin-top: 16px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; flex-wrap: wrap; gap: 8px;">
                <div>
                    <h3 style="font-size: 1rem; font-weight: 700; color: var(--text-main); margin-bottom: 2px;">
                        <i class="fa-solid fa-cubes-stacked" style="color: var(--primary);"></i> Resep Bahan yang Dibutuhkan (Otomatisasi Stok)
                    </h3>
                    <p style="font-size: 0.78rem; color: var(--text-sub);">Tentukan bahan mentah yang akan otomatis dipotong saat menu ini dipesan.</p>
                </div>
                <button type="button" class="btn btn-sm btn-accent" onclick="addBahanRow()">
                    <i class="fa-solid fa-plus"></i> Tambah Bahan
                </button>
            </div>

            <div id="bahanRowsContainer" style="display: flex; flex-direction: column; gap: 10px;">
                <!-- Baris Bahan akan ditambahkan via JS -->
            </div>
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 10px;">
            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Simpan Menu Baru</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    const availableBahans = @json($allBahans);
    let bahanIndex = 0;

    function addBahanRow(selectedId = '', qty = 1) {
        const container = document.getElementById('bahanRowsContainer');
        const row = document.createElement('div');
        row.className = 'bahan-row';
        row.id = 'bahanRow_' + bahanIndex;
        row.style.display = 'grid';
        row.style.gridTemplateColumns = '2fr 1fr auto';
        row.style.gap = '10px';
        row.style.alignItems = 'center';

        let options = '<option value="">-- Pilih Bahan Mentah --</option>';
        availableBahans.forEach(b => {
            const selected = (b.id_bahan == selectedId) ? 'selected' : '';
            options += `<option value="${b.id_bahan}" ${selected}>${b.nama_bahan} (Stok: ${b.stok})</option>`;
        });

        row.innerHTML = `
            <div>
                <select name="bahans[${bahanIndex}][id_bahan]" class="form-control" required>
                    ${options}
                </select>
            </div>
            <div>
                <input type="number" name="bahans[${bahanIndex}][jumlah_dibutuhkan]" class="form-control" placeholder="Qty" min="1" value="${qty}" required>
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeBahanRow('bahanRow_${bahanIndex}')" title="Hapus Baris">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </div>
        `;

        container.appendChild(row);
        bahanIndex++;
    }

    function removeBahanRow(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            row.remove();
        }
    }

    function previewImage(input) {
        const wrapper = document.getElementById('imgPreviewWrapper');
        const img = document.getElementById('imgPreview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                wrapper.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            wrapper.style.display = 'none';
        }
    }

    // Default: Tambahkan 1 baris kosong jika belum ada
    document.addEventListener('DOMContentLoaded', function() {
        addBahanRow();
    });
</script>
@endsection

