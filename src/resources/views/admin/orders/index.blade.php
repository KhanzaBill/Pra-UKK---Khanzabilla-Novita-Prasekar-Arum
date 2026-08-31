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
                                    <button type="button" class="btn btn-sm btn-accent" onclick="openPaymentModal({{ $p->id_pesanan }}, {{ $p->total_harga }}, '{{ $p->metode_bayar }}')" style="width: 100%; justify-content: center;">
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
@endsection

@section('scripts')
<script>
    let isModalOpen = false;
    let currentTotalHarga = 0;

    function openPaymentModal(orderId, totalHarga, metodeBayar) {
        isModalOpen = true;
        currentTotalHarga = totalHarga;
        document.getElementById('payModalOrderId').innerText = '#' + String(orderId).padStart(4, '0');
        document.getElementById('payModalTotal').innerText = 'Rp ' + totalHarga.toLocaleString('id-ID');
        document.getElementById('paymentForm').action = "/admin/orders/" + orderId + "/payment";
        
        const groupUang = document.getElementById('groupUangDibayar');
        const inputUang = document.getElementById('uang_dibayar');
        
        if (metodeBayar === 'Tunai') {
            groupUang.style.display = 'block';
            inputUang.value = totalHarga;
            calculateKembalian();
        } else {
            groupUang.style.display = 'none';
            inputUang.value = totalHarga;
        }

        document.getElementById('paymentModal').style.display = 'flex';
    }

    function calculateKembalian() {
        const inputUang = parseInt(document.getElementById('uang_dibayar').value) || 0;
        const kembalian = Math.max(0, inputUang - currentTotalHarga);
        document.getElementById('textKembalian').innerText = 'Kembalian: Rp ' + kembalian.toLocaleString('id-ID');
    }

    function closePaymentModal() {
        isModalOpen = false;
        document.getElementById('paymentModal').style.display = 'none';
    }

    function openCancelModal(orderId) {
        isModalOpen = true;
        document.getElementById('cancelModalOrderId').innerText = '#' + String(orderId).padStart(4, '0');
        document.getElementById('cancelForm').action = "/admin/orders/" + orderId + "/status";
        document.getElementById('alasan_pembatalan').value = '';
        document.getElementById('cancelModal').style.display = 'flex';
    }

    function closeCancelModal() {
        isModalOpen = false;
        document.getElementById('cancelModal').style.display = 'none';
    }

    // Auto Refresh Dashboard Orders Every 10 Seconds (jika modal tidak terbuka)
    setInterval(function() {
        if (!isModalOpen) {
            window.location.reload();
        }
    }, 10000);
</script>
@endsection
