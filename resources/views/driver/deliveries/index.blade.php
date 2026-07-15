@extends('layouts.app')

@section('title', 'Pengiriman Saya')
@section('page-title', 'Daftar Pengiriman')

@section('sidebar-menu')
<div class="nav-section">Menu Utama</div>
<a href="{{ route('driver.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="{{ route('driver.deliveries') }}" class="nav-link active"><i class="bi bi-truck-front-fill"></i> Pengiriman Saya</a>
@endsection

@section('content')
<div class="page-header">
    <h1>Daftar Pengiriman</h1>
    <p>Riwayat semua pengiriman Anda.</p>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="menunggu"         {{ request('status') == 'menunggu'         ? 'selected' : '' }}>Menunggu</option>
                    <option value="dalam_perjalanan" {{ request('status') == 'dalam_perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                    <option value="sampai_sekolah"   {{ request('status') == 'sampai_sekolah'   ? 'selected' : '' }}>Sampai Sekolah</option>
                    <option value="selesai"          {{ request('status') == 'selesai'          ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Sekolah / Guru</th>
                        <th>Tgl Kirim</th>
                        <th>Porsi</th>
                        <th>Status</th>
                        <th>Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $d)
                    <tr>
                        <td><span class="text-muted">#{{ $d->id }}</span></td>
                        <td>
                            <div class="fw-600">{{ $d->order->guru->school ?? '-' }}</div>
                            <small class="text-muted">{{ $d->order->guru->name }}</small>
                        </td>
                        <td>{{ $d->order->tanggal_pengiriman->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $d->order->jumlah_porsi_besar }}B</span>
                            <span class="badge bg-info ms-1">{{ $d->order->jumlah_porsi_kecil }}K</span>
                        </td>
                        <td>
                            <span class="badge badge-status bg-{{ $d->status_color }}">{{ $d->status_label }}</span>
                        </td>
                        <td>
                            {{ $d->delivered_at ? $d->delivered_at->format('d M H:i') : '-' }}
                        </td>
                        <td>
                            <a href="{{ route('driver.deliveries.show', $d) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-truck fs-1 text-muted opacity-25 d-block mb-2"></i>
                            <p class="text-muted">Belum ada pengiriman</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($deliveries->hasPages())
    <div class="card-footer bg-white">{{ $deliveries->links() }}</div>
    @endif
</div>
@endsection
