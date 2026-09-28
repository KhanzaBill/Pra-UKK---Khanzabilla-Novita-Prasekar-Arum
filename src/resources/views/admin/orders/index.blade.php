@extends('layouts.admin')

@section('title', 'Dashboard Pesanan - Yummy Chicken')

@section('content')
<div class="card">
    <div class="card-header">
        <div>
            <h2 class="card-title">Dashboard Pesanan</h2>
            <p style="font-size: 0.8rem; color: var(--text-sub);">Kelola status & pembayaran pesanan </p>
        </div>

        <!-- Filter Status -->
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('admin.orders') }}" class="btn btn-sm {{ !$statusFilter ? 'btn-primary' : 'btn-secondary' }}">Semua</a>
            <a href="{{ route('admin.orders', ['status' => 'Diterima']) }}" class="btn btn-sm {{ $statusFilter === 'Diterima' ? 'btn-primary' : 'btn-secondary' }}">Diterima</a>
            <a href="{{ route('admin.orders', ['status' => 'Diproses']) }}" class="btn btn-sm {{ $statusFilter === 'Diproses' ? 'btn-primary' : 'btn-secondary' }}">Diproses</a>
            <a href="{{ route('admin.orders', ['status' => 'Selesai']) }}" class="btn btn-sm {{ $statusFilter === 'Selesai' ? 'btn-primary' : 'btn-secondary' }}">Selesai</a>
            <a href="{{ route('admin.orders', ['status' => 'Dibatalkan']) }}" class="btn btn-sm {{ $statusFilter === 'Dibatalkan' ? 'btn-primary' : 'btn-secondary' }}">Dibatalkan</a>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>ID / Waktu</th>
                    <th>Tipe & Meja</th>
                    <th>Pemesan</th>
                    <th>Rincian Pesanan</th>
                    <th>Total</th>
                    <th>Status Pesanan</th>
                    <th>Status Bayar</th>
                    <th>Aksi Admin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $p)
                    <tr>
                        <td>
                            <strong style="color: var(--primary); font-size: 0.95rem;">#{{ str_pad($p->id_pesanan, 4, '0', STR_PAD_LEFT) }}</strong>
                            <div style="font-size: 0.72rem; color: var(--text-sub);">
                                {{ \Carbon\Carbon::parse($p->tanggal_waktu)->format('d/m H:i') }}
                            </div>
                        </td>

                        <td>
                            <strong>{{ $p->tipe_pesanan }}</strong>
                            @if($p->tipe_pesanan === 'Dine-In')
                                <div style="font-size: 0.75rem; color: #F57F17; font-weight: 600;">
                                    {{ $p->meja->nomor_meja ?? 'Meja General' }}
                                </div>
                            @endif
                        </td>

                        <td>{{ $p->nama_pemesan }}</td>

                        <td>
                            @foreach($p->detailPesanans as $d)
                                <div style="font-size: 0.82rem; margin-bottom: 4px;">
                                    <strong>{{ $d->jumlah }}x</strong> {{ $d->menu->nama_menu }}
                                    @if($d->level_pedas)
                                        <span style="color: var(--primary); font-weight: 600;">(Pedas Lvl {{ $d->level_pedas }})</span>
                                    @endif
                                    @if($d->tambahans->count() > 0)
                                        <div style="font-size: 0.72rem; color: #2E7D32;">
                                            + {{ $d->tambahans->pluck('nama_tambahan')->implode(', ') }}
                                        </div>
                                    @endif
                                    @if($d->catatan)
                                        <div style="font-size: 0.72rem; color: #616161; font-style: italic;">
                                            "{{ $d->catatan }}"
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </td>

                        <td>
                            <strong style="font-size: 0.92rem; color: var(--primary);">
                                Rp {{ number_format($p->total_harga, 0, ',', '.') }}
                            </strong>
                            <div style="font-size: 0.72rem; color: var(--text-sub);">
                                Metode: {{ $p->metode_bayar }}
                            </div>
                            @if($p->bukti_pembayaran)
                                <button type="button" onclick="openBuktiModal('{{ asset($p->bukti_pembayaran) }}', {{ $p->id_pesanan }})" style="margin-top: 8px; font-size: 0.8rem; padding: 7px 12px; width: 100%; justify-content: center; background: #E3F2FD; color: #1565C0; border: 1.5px solid #90CAF9; border-radius: 10px; cursor: pointer; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-image"></i> Lihat Bukti Transfer
                                </button>
                            @endif
                        </td>

                        <td>
                            @if($p->status === 'Diterima')
                                <span class="badge badge-warning">Diterima</span>
                            @elseif($p->status === 'Diproses')
                                <span class="badge badge-info">Diproses</span>
                            @elseif($p->status === 'Selesai')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-danger">Dibatalkan</span>
                                @if($p->alasan_pembatalan)
                                    <div style="font-size: 0.7rem; color: var(--danger); max-width: 120px; margin-top: 4px;">
                                        "{{ $p->alasan_pembatalan }}"
                                    </div>
                                @endif
                            @endif
                        </td>

                        <td>
                            @if($p->status_pembayaran === 'Lunas')
                                <span class="badge badge-success">LUNAS</span>
                                @if($p->metode_bayar === 'Tunai' && $p->uang_dibayar)
                                    <div style="font-size: 0.7rem; color: var(--text-sub); margin-top: 4px;">
                                        Bayar: Rp {{ number_format($p->uang_dibayar, 0, ',', '.') }}<br>
                                        Kembali: Rp {{ number_format($p->kembalian, 0, ',', '.') }}
                                    </div>
                                @endif
                            @elseif($p->status === 'Dibatalkan')
                                <span class="badge badge-secondary">DIBATALKAN</span>
                            @else
                                <span class="badge badge-warning">BELUM LUNAS</span>
                            @endif
                        </td>

                        <td>
                            <div style="display: flex; flex-direction: column; gap: 6px; min-width: 140px;">
                                {{-- Tombol Setujui / Proses --}}
                                @if($p->status === 'Diterima')
                                    <form action="{{ route('admin.orders.update_status', $p->id_pesanan) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Diproses">
                                        <button type="submit" class="btn btn-sm btn-success" style="width: 100%; justify-content: center;">
                                           Setujui Pesanan
                                        </button>
                                    </form>
                                @elseif($p->status === 'Diproses')
                                    <form action="{{ route('admin.orders.update_status', $p->id_pesanan) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="status" value="Selesai">
                                        <button type="submit" class="btn btn-sm btn-primary" style="width: 100%; justify-content: center;">
                                          Tandai Selesai
                                        </button>
                                    </form>
                                @endif

                                {{-- Tombol Bayar / Pelunasan (Hanya jika belum lunas dan pesanan tidak dibatalkan) --}}
                                @if($p->status_pembayaran === 'Belum Lunas' && $p->status !== 'Dibatalkan')
                                    <button type="button" class="btn btn-sm btn-accent" onclick="openPaymentModal({{ $p->id_pesanan }}, {{ $p->total_harga }}, '{{ $p->metode_bayar }}', '{{ $p->bukti_pembayaran ? asset($p->bukti_pembayaran) : '' }}')" style="width: 100%; justify-content: center;">
                                      {{ $p->metode_bayar === 'Tunai' ? 'Terima Pembayaran' : 'Konfirmasi Lunas' }}
                                    </button>
                                @endif

                                {{-- Tombol Batalkan (Jika belum selesai / dibatalkan) --}}
                                @if($p->status !== 'Selesai' && $p->status !== 'Dibatalkan')
                                    <button type="button" class="btn btn-sm btn-secondary" onclick="openCancelModal({{ $p->id_pesanan }})" style="width: 100%; justify-content: center; color: var(--danger); border-color: #FFCDD2;">
                                        <i class="fa-solid fa-xmark"></i> Batalkan
                                    </button>
                                @endif

                                @if($p->status === 'Selesai' || $p->status === 'Dibatalkan')
                                    <span style="font-size: 0.78rem; color: var(--text-sub); text-align: center; display: block; padding: 4px 0;">-</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 30px; color: var(--text-sub);">
                            Tidak ada data pesanan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 10px;">
        {{ $pesanans->links('vendor.pagination.custom') }}
    </div>
</div>


<div class="modal-overlay" id="paymentModal">
    <div class="modal-card">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; margin-bottom: 16px; color: var(--text-main);">
            Proses Pembayaran <span id="payModalOrderId"></span>
        </h3>
        <form id="paymentForm" method="POST">
            @csrf
            <input type="hidden" name="status_pembayaran" value="Lunas">

            <div class="form-group" style="margin-bottom: 12px;">
                <label class="form-label">Total Tagihan:</label>
                <div style="font-size: 1.3rem; font-weight: 800; color: var(--primary);" id="payModalTotal">Rp 0</div>
            </div>

            <!-- Preview Bukti Pembayaran jika QRIS -->
            <div id="groupBuktiPembayaran" style="display: none; margin-bottom: 16px;">
                <label class="form-label" style="font-weight: 600; color: var(--text-main);">Bukti Pembayaran Pelanggan (QRIS):</label>
                <div style="background: #F9F9F9; padding: 10px; border-radius: 12px; border: 1.5px dashed var(--primary); text-align: center;">
                    <img id="payModalBuktiImg" src="" alt="Bukti Pembayaran" style="max-width: 100%; max-height: 260px; object-fit: contain; border-radius: 8px; cursor: pointer; display: block; margin: 0 auto;" onclick="openBuktiModal(this.src, currentPayOrderId)">
                    <span style="font-size: 0.75rem; color: var(--text-sub); display: block; margin-top: 6px;">
                        <i class="fa-solid fa-magnifying-glass-plus"></i> Klik foto untuk memperbesar
                    </span>
                </div>
            </div>

            <div class="form-group" id="groupUangDibayar">
                <label class="form-label" for="uang_dibayar">Uang Diterima dari Pelanggan (Rp):</label>
                <input type="number" name="uang_dibayar" id="uang_dibayar" class="form-control" placeholder="Masukkan nominal uang" min="0" oninput="calculateKembalian()">
                <div style="font-size: 0.85rem; font-weight: 600; margin-top: 8px; color: var(--success);" id="textKembalian">
                    Kembalian: Rp 0
                </div>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 24px;">
                <button type="button" class="btn btn-secondary" onclick="closePaymentModal()">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Konfirmasi Lunas</button>
            </div>
        </form>
    </div>
</div>


<div class="modal-overlay" id="cancelModal">
    <div class="modal-card">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.25rem; font-weight: 700; margin-bottom: 16px; color: var(--danger);">
            Batalkan Pesanan <span id="cancelModalOrderId"></span>
        </h3>
        <form id="cancelForm" method="POST">
            @csrf
            <input type="hidden" name="status" value="Dibatalkan">

            <div class="form-group">
                <label class="form-label" for="alasan_pembatalan">Alasan Pembatalan:</label>
                <input type="text" name="alasan_pembatalan" id="alasan_pembatalan" class="form-control" placeholder="Contoh: Stok bahan habis" required>
            </div>

            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="closeCancelModal()">Tutup</button>
                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-xmark"></i> Batalkan Pesanan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Preview Bukti Pembayaran -->
<div id="buktiModal" class="modal-overlay" style="display: none; align-items: center; justify-content: center; z-index: 1050;">
    <div class="modal-card" style="max-width: 520px; width: 90%; text-align: center;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
            <h3 class="modal-title" style="font-size: 1.1rem; font-weight: 700; color: var(--text-main);">
                <i class="fa-solid fa-receipt" style="color: var(--primary);"></i> Bukti Transfer <span id="buktiModalOrderId" style="color: var(--primary);"></span>
            </h3>
            <button type="button" onclick="closeBuktiModal()" style="background: #F5F5F5; border: none; width: 34px; height: 34px; border-radius: 50%; font-size: 1.3rem; cursor: pointer; color: #616161; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">&times;</button>
        </div>
        <div style="background: #F9F9F9; padding: 12px; border-radius: 12px; border: 1.5px solid #EEEEEE;">
            <img id="imgBuktiPreview" src="" alt="Bukti Transfer" style="width: 100%; max-height: 500px; object-fit: contain; border-radius: 8px; display: block; margin: 0 auto;">
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let isModalOpen = false;        // Status flag untuk mengetahui apakah ada modal pop-up yang sedang terbuka
    let currentTotalHarga = 0;     // Menyimpan total harga pesanan yang sedang diproses di modal
    let currentPayOrderId = '';     // Menyimpan ID pesanan yang sedang diproses

    // Membuka modal preview bukti transfer foto QRIS
    function openBuktiModal(imgUrl, orderIdFormatted) {
        isModalOpen = true; // Tandai modal terbuka (menghentikan sementara auto refresh dashboard)
        document.getElementById('buktiModalOrderId').innerText = '#' + String(orderIdFormatted).padStart(4, '0'); // Format ID 4 digit
        document.getElementById('imgBuktiPreview').src = imgUrl; // Set gambar preview
        document.getElementById('buktiModal').style.display = 'flex'; // Tampilkan modal
    }

    // Menutup modal preview bukti transfer
    function closeBuktiModal() {
        isModalOpen = false; // Tandai modal ditutup
        document.getElementById('buktiModal').style.display = 'none'; // Sembunyikan modal
    }

    // Membuka modal konfirmasi pembayaran pesanan (Tunai / QRIS)
    function openPaymentModal(orderId, totalHarga, metodeBayar, buktiUrl = '') {
        isModalOpen = true; // Tandai modal terbuka
        currentTotalHarga = totalHarga; // Simpan total harga pesanan saat ini
        currentPayOrderId = String(orderId).padStart(4, '0'); // Format ID 4 digit
        document.getElementById('payModalOrderId').innerText = '#' + currentPayOrderId; // Set judul modal
        document.getElementById('payModalTotal').innerText = 'Rp ' + totalHarga.toLocaleString('id-ID'); // Set total harga di modal
        document.getElementById('paymentForm').action = "/admin/orders/" + orderId + "/payment"; // Action form update payment
        
        const groupUang = document.getElementById('groupUangDibayar');
        const inputUang = document.getElementById('uang_dibayar');
        const groupBukti = document.getElementById('groupBuktiPembayaran');
        const payModalBuktiImg = document.getElementById('payModalBuktiImg');

        if (metodeBayar === 'Tunai') {
            groupUang.style.display = 'block'; // Tampilkan input uang dibayar untuk pembayaran Tunai
            inputUang.value = totalHarga; // Default isi dengan pas total harga
            groupBukti.style.display = 'none'; // Sembunyikan bukti gambar (karena Tunai)
            calculateKembalian(); // Hitung kembalian awal
        } else {
            groupUang.style.display = 'none'; // Sembunyikan input uang dibayar untuk QRIS
            inputUang.value = totalHarga;
            if (buktiUrl && buktiUrl.trim() !== '') {
                payModalBuktiImg.src = buktiUrl;
                groupBukti.style.display = 'block'; // Tampilkan thumbnail bukti QRIS jika ada
            } else {
                groupBukti.style.display = 'none';
            }
        }

        document.getElementById('paymentModal').style.display = 'flex'; // Tampilkan modal pembayaran
    }

    // Menghitung kembalian uang tunai secara real-time saat kasir mengetikkan nominal dibayar
    function calculateKembalian() {
        const inputUang = parseInt(document.getElementById('uang_dibayar').value) || 0; // Ambil nilai nominal input
        const kembalian = Math.max(0, inputUang - currentTotalHarga); // Hitung kembalian (uang - total harga, min 0)
        document.getElementById('textKembalian').innerText = 'Kembalian: Rp ' + kembalian.toLocaleString('id-ID'); // Tampilkan teks kembalian
    }

    // Menutup modal pembayaran
    function closePaymentModal() {
        isModalOpen = false; // Reset flag modal
        document.getElementById('paymentModal').style.display = 'none'; // Sembunyikan modal
    }

    // Membuka modal konfirmasi pembatalan pesanan
    function openCancelModal(orderId) {
        isModalOpen = true; // Tandai modal terbuka
        document.getElementById('cancelModalOrderId').innerText = '#' + String(orderId).padStart(4, '0'); // Format ID 4 digit
        document.getElementById('cancelForm').action = "/admin/orders/" + orderId + "/status"; // Set action form cancel
        document.getElementById('alasan_pembatalan').value = ''; // Reset isi input alasan pembatalan
        document.getElementById('cancelModal').style.display = 'flex'; // Tampilkan modal cancel
    }

    // Menutup modal pembatalan pesanan
    function closeCancelModal() {
        isModalOpen = false; // Reset flag modal
        document.getElementById('cancelModal').style.display = 'none'; // Sembunyikan modal cancel
    }

    // Auto Refresh Dashboard Orders Setiap 10 Detik (hanya jika modal tidak sedang terbuka)
    setInterval(function() {
        if (!isModalOpen) {
            window.location.reload(); // Reload halaman untuk memuat pesanan baru yang masuk
        }
    }, 10000); // Poll interval 10000ms = 10 detik
</script>
@endsection

