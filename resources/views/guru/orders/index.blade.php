@extends('layouts.app')

@section('title', 'Pesanan Saya')
@section('page-title', 'Pesanan Saya')

@section('sidebar-menu')
<div class="nav-section">Menu Utama</div>
<a href="{{ route('guru.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="{{ route('guru.orders') }}" class="nav-link active"><i class="bi bi-clipboard2-plus-fill"></i> Pesanan Saya</a>
<a href="{{ route('guru.orders.create') }}" class="nav-link"><i class="bi bi-plus-circle-fill"></i> Buat Pesanan</a>
@endsection

@section('content')
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
        <h1>Daftar Pesanan</h1>
        <p>Riwayat semua pesanan makanan bergizi Anda.</p>
    </div>
    <a href="{{ route('guru.orders.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Buat Pesanan
    </a>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Filter Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                    <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="dikemas" {{ request('status') == 'dikemas' ? 'selected' : '' }}>Dikemas</option>
                    <option value="siap_dikirim" {{ request('status') == 'siap_dikirim' ? 'selected' : '' }}>Siap Dikirim</option>
                    <option value="dalam_perjalanan" {{ request('status') == 'dalam_perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
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
                        <th>Tanggal Pengiriman</th>
                        <th>Porsi Besar</th>
                        <th>Porsi Kecil</th>
                        <th>Total</th>
                        <th>Menu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><span class="text-muted fw-600">#{{ $order->id }}</span></td>
                        <td>
                            <div class="fw-600">{{ $order->tanggal_pengiriman->format('d M Y') }}</div>
                            <small class="text-muted">{{ $order->tanggal_pengiriman->format('l') }}</small>
                        </td>
                        <td><span class="badge bg-primary">{{ $order->jumlah_porsi_besar }}</span></td>
                        <td><span class="badge bg-info">{{ $order->jumlah_porsi_kecil }}</span></td>
                        <td><strong>{{ $order->total_porsi }}</strong></td>
                        <td>
                            @if($order->menu)
                                <span class="badge bg-success"><i class="bi bi-check-lg me-1"></i>Ada</span>
                            @else
                                <span class="badge bg-secondary">Belum</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-status bg-{{ $order->status_color }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('guru.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if(in_array($order->status, ['dalam_perjalanan', 'sampai_sekolah']) && $order->delivery)
                                <a href="{{ route('guru.tracking', $order) }}" class="btn btn-sm btn-success" title="Live Tracking">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted opacity-25 d-block mb-2"></i>
                            <p class="text-muted mb-3">Belum ada pesanan</p>
                            <a href="{{ route('guru.orders.create') }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-2"></i>Buat Pesanan Pertama
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
    <div class="card-footer bg-white">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
