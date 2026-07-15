@extends('layouts.app')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan Admin')

@section('sidebar-menu')
<div class="nav-section">Menu Utama</div>
<a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="{{ route('admin.users') }}" class="nav-link"><i class="bi bi-people-fill"></i> Manajemen User</a>
<a href="{{ route('admin.orders') }}" class="nav-link active"><i class="bi bi-clipboard2-data-fill"></i> Semua Pesanan</a>
@endsection

@section('content')
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.orders') }}">Pesanan</a></li>
            <li class="breadcrumb-item active">#{{ $order->id }}</li>
        </ol>
    </nav>
    <h1>Detail Pesanan #{{ $order->id }}</h1>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-clipboard2-data text-primary"></i> Info Pesanan</h5>
                <span class="badge badge-status bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:.9rem;">
                    <tr><td class="text-muted" style="width:40%">Guru</td><td><strong>{{ $order->guru->name }}</strong></td></tr>
                    <tr><td class="text-muted">Email</td><td>{{ $order->guru->email }}</td></tr>
                    <tr><td class="text-muted">Sekolah</td><td>{{ $order->guru->school ?? '-' }}</td></tr>
                    <tr><td class="text-muted">Tgl Kirim</td><td><strong>{{ $order->tanggal_pengiriman->format('d M Y') }}</strong></td></tr>
                    <tr><td class="text-muted">Porsi Besar</td><td><span class="badge bg-primary">{{ $order->jumlah_porsi_besar }}</span></td></tr>
                    <tr><td class="text-muted">Porsi Kecil</td><td><span class="badge bg-info">{{ $order->jumlah_porsi_kecil }}</span></td></tr>
                    <tr><td class="text-muted">Total</td><td><strong>{{ $order->total_porsi }}</strong></td></tr>
                    @if($order->catatan)
                    <tr><td class="text-muted">Catatan</td><td>{{ $order->catatan }}</td></tr>
                    @endif
                    <tr><td class="text-muted">Dibuat</td><td>{{ $order->created_at->format('d M Y H:i') }}</td></tr>
                </table>
            </div>
        </div>

        @if($order->delivery)
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-truck text-warning"></i> Info Pengiriman</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:.9rem;">
                    <tr><td class="text-muted">Driver</td><td><strong>{{ $order->delivery->driver->name }}</strong></td></tr>
                    <tr><td class="text-muted">Status</td>
                        <td><span class="badge badge-status bg-{{ $order->delivery->status_color }}">{{ $order->delivery->status_label }}</span></td>
                    </tr>
                    <tr><td class="text-muted">Tracking</td>
                        <td>
                            @if($order->delivery->tracking_active)
                                <span class="badge bg-success"><span class="pulse me-1" style="display:inline-block;width:6px;height:6px;background:white;border-radius:50%;"></span>Aktif</span>
                            @else
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr><td class="text-muted">Log Tracking</td><td>{{ $order->delivery->trackingLogs->count() }} titik</td></tr>
                    @if($order->delivery->delivered_at)
                    <tr><td class="text-muted">Diterima</td><td class="text-success fw-600">{{ $order->delivery->delivered_at->format('d M Y H:i') }}</td></tr>
                    @endif
                </table>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-7">
        @if($order->menu)
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-egg-fried text-success"></i> Menu & Gizi</h5>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    @foreach(['lauk'=>'Lauk','sayur'=>'Sayur','buah'=>'Buah','sambal'=>'Sambal'] as $k => $l)
                    @if($order->menu->$k)
                    <div class="col-6">
                        <div class="p-2" style="background:#f8fafc;border-radius:10px;">
                            <small class="text-muted">{{ $l }}</small>
                            <div class="fw-600" style="font-size:.9rem;">{{ $order->menu->$k }}</div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
                <div class="row g-2">
                    <div class="col-3"><div class="text-center p-2" style="background:#dbeafe;border-radius:10px;"><div class="fw-800 text-primary">{{ number_format($order->menu->kalori,0) }}</div><small>kkal</small></div></div>
                    <div class="col-3"><div class="text-center p-2" style="background:#d1fae5;border-radius:10px;"><div class="fw-800 text-success">{{ $order->menu->protein }}g</div><small>Protein</small></div></div>
                    <div class="col-3"><div class="text-center p-2" style="background:#fef3c7;border-radius:10px;"><div class="fw-800 text-warning">{{ $order->menu->lemak }}g</div><small>Lemak</small></div></div>
                    <div class="col-3"><div class="text-center p-2" style="background:#ede9fe;border-radius:10px;"><div class="fw-800" style="color:#7c3aed;">{{ $order->menu->karbohidrat }}g</div><small>Karbo</small></div></div>
                </div>
            </div>
        </div>
        @endif

        @if($order->delivery && $order->delivery->current_latitude)
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-map-fill text-primary"></i> Peta Tracking</h5>
            </div>
            <div id="admin-map" style="height:350px;border-radius:0 0 16px 16px;"></div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
@if($order->delivery && $order->delivery->current_latitude)
<script>
const adminMap = L.map('admin-map').setView([{{ $order->delivery->current_latitude }}, {{ $order->delivery->current_longitude }}], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(adminMap);

const logs = @json($order->delivery->trackingLogs->map(fn($l) => [(float)$l->latitude, (float)$l->longitude]));
if (logs.length > 1) {
    L.polyline(logs, { color: '#2563eb', weight: 4, opacity: .7 }).addTo(adminMap);
}

const icon = L.divIcon({
    html: '<div style="background:#f59e0b;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.25);border:2px solid white;"><i class="bi bi-truck" style="color:white;font-size:15px;"></i></div>',
    className: '', iconSize: [36, 36], iconAnchor: [18, 18],
});

L.marker([{{ $order->delivery->current_latitude }}, {{ $order->delivery->current_longitude }}], { icon })
 .bindPopup('<b>{{ $order->delivery->driver->name }}</b><br>{{ $order->delivery->status_label }}')
 .addTo(adminMap).openPopup();
</script>
@endif
@endpush
