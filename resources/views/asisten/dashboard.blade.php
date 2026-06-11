@extends('layouts.app')

@section('title', 'Dashboard Asisten')
@section('page-title', 'Dashboard Asisten')

@section('sidebar-menu')
<div class="nav-section">Menu Utama</div>
<a href="{{ route('asisten.dashboard') }}" class="nav-link {{ request()->routeIs('asisten.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<a href="{{ route('asisten.orders') }}" class="nav-link {{ request()->routeIs('asisten.orders*') ? 'active' : '' }}">
    <i class="bi bi-clipboard2-list-fill"></i> Kelola Pesanan
</a>
@endsection

@section('content')
<div class="page-header">
    <h1>Dashboard Asisten Lapangan</h1>
    <p>Selamat datang, {{ auth()->user()->name }}! Kelola pesanan dan input menu makanan bergizi.</p>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;">
                <i class="bi bi-bell-fill" style="color:#f59e0b;"></i>
            </div>
            <div>
                <div class="stat-number">{{ $stats['pesanan_baru'] }}</div>
                <div class="stat-label">Pesanan Baru</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;">
                <i class="bi bi-gear-fill" style="color:#2563eb;"></i>
            </div>
            <div>
                <div class="stat-number">{{ $stats['sedang_diproses'] }}</div>
                <div class="stat-label">Sedang Diproses</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5;">
                <i class="bi bi-box-seam-fill" style="color:#10b981;"></i>
            </div>
            <div>
                <div class="stat-number">{{ $stats['siap_dikirim'] }}</div>
                <div class="stat-label">Siap Dikirim</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;">
                <i class="bi bi-check-circle-fill" style="color:#16a34a;"></i>
            </div>
            <div>
                <div class="stat-number">{{ $stats['selesai_hari_ini'] }}</div>
                <div class="stat-label">Selesai Hari Ini</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pesanan Baru (Pending) -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-bell-fill text-warning"></i> Pesanan Baru Masuk</h5>
                <span class="badge bg-warning text-dark">{{ $pending_orders->count() }}</span>
            </div>
            <div class="card-body p-0">
                @forelse($pending_orders as $order)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-700">{{ $order->guru->name }}</div>
                            <small class="text-muted">{{ $order->guru->school ?? '-' }}</small>
                            <div class="mt-1">
                                <span class="badge bg-primary">{{ $order->jumlah_porsi_besar }}B</span>
                                <span class="badge bg-info ms-1">{{ $order->jumlah_porsi_kecil }}K</span>
                                <span class="text-muted ms-2" style="font-size:.8rem;">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $order->tanggal_pengiriman->format('d M Y') }}
                                </span>
                            </div>
                        </div>
                        <div class="d-flex gap-1">
                            <form method="POST" action="{{ route('asisten.orders.accept', $order) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success" title="Terima">
                                    <i class="bi bi-check-lg"></i> Terima
                                </button>
                            </form>
                            <a href="{{ route('asisten.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-inbox fs-2 opacity-25 d-block mb-2"></i>
                    Tidak ada pesanan baru
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sedang Diproses -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-gear-fill text-primary"></i> Sedang Diproses</h5>
                <a href="{{ route('asisten.orders') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                @forelse($processing_orders as $order)
                <div class="p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="fw-700">{{ $order->guru->name }}
                                <span class="ms-2 badge badge-status bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                            </div>
                            <small class="text-muted">{{ $order->tanggal_pengiriman->format('d M Y') }} &bull; {{ $order->total_porsi }} ompreng</small>
                            @if(!$order->menu)
                            <div class="mt-1">
                                <span class="badge bg-warning text-dark" style="font-size:.72rem;">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Menu belum diinput
                                </span>
                            </div>
                            @endif
                        </div>
                        <div class="d-flex gap-1">
                            @if(!$order->menu)
                            <a href="{{ route('asisten.orders.menu', $order) }}" class="btn btn-sm btn-warning text-dark">
                                <i class="bi bi-pencil-fill"></i> Input Menu
                            </a>
                            @else
                            <a href="{{ route('asisten.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-check-circle fs-2 opacity-25 d-block mb-2"></i>
                    Tidak ada pesanan yang diproses
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
