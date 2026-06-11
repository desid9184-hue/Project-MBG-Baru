@extends('layouts.app')

@section('title', 'Semua Pesanan')
@section('page-title', 'Semua Pesanan')

@section('sidebar-menu')
<div class="nav-section">Menu Utama</div>
<a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="{{ route('admin.users') }}" class="nav-link"><i class="bi bi-people-fill"></i> Manajemen User</a>
<a href="{{ route('admin.orders') }}" class="nav-link active"><i class="bi bi-clipboard2-data-fill"></i> Semua Pesanan</a>
@endsection

@section('content')
<div class="page-header">
    <h1>Semua Pesanan</h1>
    <p>Monitor seluruh pesanan makanan bergizi dari semua guru.</p>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending"          {{ request('status') == 'pending'          ? 'selected' : '' }}>Menunggu</option>
                    <option value="diterima"         {{ request('status') == 'diterima'         ? 'selected' : '' }}>Diterima</option>
                    <option value="diproses"         {{ request('status') == 'diproses'         ? 'selected' : '' }}>Diproses</option>
                    <option value="dikemas"          {{ request('status') == 'dikemas'          ? 'selected' : '' }}>Dikemas</option>
                    <option value="siap_dikirim"     {{ request('status') == 'siap_dikirim'     ? 'selected' : '' }}>Siap Dikirim</option>
                    <option value="dalam_perjalanan" {{ request('status') == 'dalam_perjalanan' ? 'selected' : '' }}>Dalam Perjalanan</option>
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
                        <th>Guru / Sekolah</th>
                        <th>Tgl Kirim</th>
                        <th>Porsi</th>
                        <th>Menu</th>
                        <th>Driver</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><span class="text-muted">#{{ $order->id }}</span></td>
                        <td>
                            <div class="fw-600">{{ $order->guru->name }}</div>
                            <small class="text-muted">{{ $order->guru->school ?? '-' }}</small>
                        </td>
                        <td>{{ $order->tanggal_pengiriman->format('d M Y') }}</td>
                        <td>
                            <span class="badge bg-primary">{{ $order->jumlah_porsi_besar }}B</span>
                            <span class="badge bg-info ms-1">{{ $order->jumlah_porsi_kecil }}K</span>
                        </td>
                        <td>
                            @if($order->menu)
                                <span class="badge bg-success">Ada</span>
                            @else
                                <span class="badge bg-secondary">Belum</span>
                            @endif
                        </td>
                        <td>
                            @if($order->delivery)
                                <span style="font-size:.8rem;">{{ $order->delivery->driver->name }}</span>
                            @else
                                <span class="text-muted" style="font-size:.8rem;">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-status bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Tidak ada pesanan ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
    <div class="card-footer bg-white">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
