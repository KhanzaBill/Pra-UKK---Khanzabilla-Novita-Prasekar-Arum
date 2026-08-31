@extends('layouts.admin')

@section('title', 'Edit Menu - Yummy Chicken')

@section('content')
<div class="card" style="max-width: 640px; margin: 0 auto;">
    <div class="card-header">
        <div>
            <h2 class="card-title">Edit Menu #{{ $menu->id_menu }}</h2>
            <p style="font-size: 0.8rem; color: var(--text-sub); margin-top: 2px;">Perbarui informasi menu atau ubah ketersediaan stok</p>
        </div>
        <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <form action="{{ route('admin.menus.update', $menu->id_menu) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label class="form-label" for="nama_menu">Nama Menu <span style="color: var(--danger);">*</span></label>
            <input type="text" name="nama_menu" id="nama_menu" class="form-control @error('nama_menu') is-invalid @enderror" value="{{ old('nama_menu', $menu->nama_menu) }}" required>
            @error('nama_menu')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label class="form-label" for="kategori">Kategori Menu <span style="color: var(--danger);">*</span></label>
                <select name="kategori" id="kategori" class="form-control @error('kategori') is-invalid @enderror" required>
                    <option value="Paket" {{ old('kategori', $menu->kategori) === 'Paket' ? 'selected' : '' }}>Paket</option>
                    <option value="Makanan" {{ old('kategori', $menu->kategori) === 'Makanan' ? 'selected' : '' }}>Makanan</option>
                    <option value="Minuman" {{ old('kategori', $menu->kategori) === 'Minuman' ? 'selected' : '' }}>Minuman</option>
                </select>
                @error('kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="harga">Harga (Rp) <span style="color: var(--danger);">*</span></label>
                <input type="number" name="harga" id="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga', $menu->harga) }}" min="0" required>
                @error('harga')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="foto">Foto Menu (Biarkan kosong jika tidak diubah)</label>
            @if($menu->foto)
                <div style="margin-bottom: 10px; display: flex; align-items: center; gap: 12px;">
                    <img src="{{ asset('storage/' . $menu->foto) }}" alt="{{ $menu->nama_menu }}" loading="lazy" style="width: 70px; height: 70px; border-radius: 10px; object-fit: cover; border: 1px solid var(--border);">
                    <label style="font-size: 0.82rem; color: var(--danger); cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        <input type="checkbox" name="hapus_foto" value="1"> Hapus foto saat ini
                    </label>
                </div>
            @endif
            <input type="file" name="foto" id="foto" class="form-control @error('foto') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg,image/webp" onchange="previewImage(this)">
            @error('foto')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div id="imgPreviewWrapper" style="margin-top: 10px; display: none;">
                <img id="imgPreview" src="#" alt="Preview Foto" loading="lazy" style="max-width: 120px; max-height: 120px; border-radius: 10px; border: 1.5px solid var(--border); object-fit: cover;">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label" for="deskripsi">Deskripsi Menu:</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi', $menu->deskripsi) }}</textarea>
            @error('deskripsi')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
            <div class="form-group">
                <label class="form-label" for="opsi_pedas">Opsi Level Pedas <span style="color: var(--danger);">*</span></label>
                <select name="opsi_pedas" id="opsi_pedas" class="form-control" required>
                    <option value="Ya" {{ old('opsi_pedas', $menu->opsi_pedas) === 'Ya' ? 'selected' : '' }}>Ya</option>
                    <option value="Tidak" {{ old('opsi_pedas', $menu->opsi_pedas) === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="status_stok">Status Stok <span style="color: var(--danger);">*</span></label>
                <select name="status_stok" id="status_stok" class="form-control" required>
                    <option value="Tersedia" {{ old('status_stok', $menu->status_stok) === 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Habis" {{ old('status_stok', $menu->status_stok) === 'Habis' ? 'selected' : '' }}>Habis</option>
                </select>
            </div>
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: flex-end; gap: 10px;">
            <a href="{{ route('admin.menus.index') }}" class="btn btn-secondary">Batal</a>
            <button type="submit" class="btn btn-primary">Perbarui Menu</button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
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
</script>
@endsection

