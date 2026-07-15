@extends('layouts.app')

@section('title', 'Kelola Pesanan')
@section('page-title', 'Kelola Pesanan')

@section('sidebar-menu')
<div class="nav-section">Menu Utama</div>
<a href="{{ route('asisten.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="{{ route('asisten.orders') }}" class="nav-link active"><i class="bi bi-clipboard2-list-fill"></i> Kelola Pesanan</a>
@endsection

@section('content')
<div class="page-header">
    <h1>Daftar Pesanan</h1>
    <p>Kelola semua pesanan makanan bergizi dari guru.</p>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Filter Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Aktif</option>
                    <option value="pending"      {{ request('status') == 'pending'      ? 'selected' : '' }}>Menunggu</option>
                    <option value="diterima"     {{ request('status') == 'diterima'     ? 'selected' : '' }}>Diterima</option>
                    <option value="diproses"     {{ request('status') == 'diproses'     ? 'selected' : '' }}>Diproses</option>
                    <option value="dikemas"      {{ request('status') == 'dikemas'      ? 'selected' : '' }}>Dikemas</option>
                    <option value="siap_dikirim" {{ request('status') == 'siap_dikirim' ? 'selected' : '' }}>Siap Dikirim</option>
                    <option value="selesai"      {{ request('status') == 'selesai'      ? 'selected' : '' }}>Selesai</option>
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
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><span class="text-muted fw-600">#{{ $order->id }}</span></td>
                        <td>
                            <div class="fw-600">{{ $order->guru->name }}</div>
                            <small class="text-muted">{{ $order->guru->school ?? '-' }}</small>
                        </td>
                        <td>
                            <div class="fw-600">{{ $order->tanggal_pengiriman->format('d M Y') }}</div>
                        </td>
                        <td>
                            <span class="badge bg-primary">{{ $order->jumlah_porsi_besar }}B</span>
                            <span class="badge bg-info ms-1">{{ $order->jumlah_porsi_kecil }}K</span>
                            <div style="font-size:.78rem;color:#64748b;">Total: {{ $order->total_porsi }}</div>
                        </td>
                        <td>
                            @if($order->menu)
                                <span class="badge bg-success"><i class="bi bi-check-lg"></i> Ada</span>
                            @else
                                <span class="badge bg-warning text-dark">Belum</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge badge-status bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('asisten.orders.show', $order) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($order->status == 'pending')
                                <form method="POST" action="{{ route('asisten.orders.accept', $order) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-success" title="Terima">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                @endif
                                @if(in_array($order->status, ['diterima', 'diproses', 'dikemas']))
                                <a href="{{ route('asisten.orders.menu', $order) }}" class="btn btn-sm btn-warning text-dark" title="Input Menu">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted opacity-25 d-block mb-2"></i>
                            <p class="text-muted">Tidak ada pesanan</p>
                        </td>
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
