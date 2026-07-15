@extends('layouts.app')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Guru')

@section('sidebar-menu')
<div class="nav-section">Menu Utama</div>
<a href="{{ route('guru.dashboard') }}" class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<a href="{{ route('guru.orders') }}" class="nav-link {{ request()->routeIs('guru.orders*') ? 'active' : '' }}">
    <i class="bi bi-clipboard2-plus-fill"></i> Pesanan Saya
</a>
<a href="{{ route('guru.orders.create') }}" class="nav-link {{ request()->routeIs('guru.orders.create') ? 'active' : '' }}">
    <i class="bi bi-plus-circle-fill"></i> Buat Pesanan
</a>
@endsection

@section('content')
<div class="page-header">
    <h1>Dashboard Guru</h1>
    <p>Selamat datang, {{ auth()->user()->name }}! Pantau pesanan makanan bergizi Anda.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;">
                <i class="bi bi-clipboard2-fill" style="color:#2563eb;"></i>
            </div>
            <div>
                <div class="stat-number">{{ $stats['total_pesanan'] }}</div>
                <div class="stat-label">Total Pesanan</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;">
                <i class="bi bi-hourglass-split" style="color:#f59e0b;"></i>
            </div>
            <div>
                <div class="stat-number">{{ $stats['pesanan_aktif'] }}</div>
                <div class="stat-label">Pesanan Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5;">
                <i class="bi bi-truck" style="color:#10b981;"></i>
            </div>
            <div>
                <div class="stat-number">{{ $stats['pengiriman_hari_ini'] }}</div>
                <div class="stat-label">Kirim Hari Ini</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;">
                <i class="bi bi-check-circle-fill" style="color:#16a34a;"></i>
            </div>
            <div>
                <div class="stat-number">{{ $stats['pesanan_selesai'] }}</div>
                <div class="stat-label">Pesanan Selesai</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-clock-history text-primary"></i> Pesanan Terbaru</h5>
                <a href="{{ route('guru.orders.create') }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Buat Pesanan
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Tgl Kirim</th>
                                <th>Porsi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_orders as $order)
                            <tr>
                                <td>
                                    <div class="fw-600">{{ $order->tanggal_pengiriman->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $order->tanggal_pengiriman->format('l') }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $order->jumlah_porsi_besar }} Besar</span><br>
                                    <span class="badge bg-info mt-1">{{ $order->jumlah_porsi_kecil }} Kecil</span>
                                </td>
                                <td>
                                    <span class="badge badge-status bg-{{ $order->status_color }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('guru.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(in_array($order->status, ['dalam_perjalanan', 'sampai_sekolah']) && $order->delivery)
                                    <a href="{{ route('guru.tracking', $order) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-geo-alt-fill"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted opacity-25 d-block mb-2"></i>
                                    <p class="text-muted mb-3">Belum ada pesanan</p>
                                    <a href="{{ route('guru.orders.create') }}" class="btn btn-primary btn-sm">
                                        Buat Pesanan Pertama
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        @if($active_delivery)
        <div class="card">
            <div class="card-header" style="background:linear-gradient(135deg,#2563eb,#1d4ed8);">
                <h5 class="text-white mb-0">
                    <span class="pulse me-2" style="display:inline-block;width:10px;height:10px;background:#10b981;border-radius:50%;"></span>
                    Live Tracking Aktif
                </h5>
            </div>
            <div id="map"></div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="stat-icon" style="background:#dbeafe;width:44px;height:44px;">
                        <i class="bi bi-truck-front" style="color:#2563eb;font-size:1.3rem;"></i>
                    </div>
                    <div>
                        <div class="fw-700">{{ $active_delivery->driver->name }}</div>
                        <span class="badge bg-{{ $active_delivery->status_color }}">
                            {{ $active_delivery->status_label }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
        // Koordinat peta awal disesuaikan ke lokasi baru kamu
        const map = L.map('map').setView([0.400229, 101.856809], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        const driverIcon = L.divIcon({
            html: '<div style="background:#2563eb;width:36px;height:36px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.3);"><i class="bi bi-truck" style="transform:rotate(45deg);color:white;font-size:16px;"></i></div>',
            className: '',
            iconSize: [36, 36],
            iconAnchor: [18, 36],
        });

        let driverMarker = null;
        let routePolyline = null;
        const deliveryId = "{{ $active_delivery->order->id }}";

        function fetchTracking() {
            fetch(`/guru/tracking/${deliveryId}/data`)
                .then(r => r.json())
                .then(data => {
                    if (data.error) return;

                    if (data.current_latitude && data.current_longitude) {
                        const pos = [parseFloat(data.current_latitude), parseFloat(data.current_longitude)];
                        if (driverMarker) {
                            driverMarker.setLatLng(pos);
                        } else {
                            driverMarker = L.marker(pos, { icon: driverIcon })
                                .bindPopup(`<b>${data.driver_name}</b><br>${data.status_label}`)
                                .addTo(map);
                        }
                        map.panTo(pos);
                    }

                    if (data.logs && data.logs.length > 1) {
                        const coords = data.logs.map(l => [parseFloat(l.latitude), parseFloat(l.longitude)]);
                        if (routePolyline) map.removeLayer(routePolyline);
                        routePolyline = L.polyline(coords, { color: '#2563eb', weight: 4, opacity: .7, dashArray: '8,4' }).addTo(map);
                    }
                })
                .catch(() => {});
        }

        fetchTracking();
        setInterval(fetchTracking, 5000);
        </script>
        @endpush
        @else
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-map text-muted"></i> Live Tracking</h5>
            </div>
            <div class="card-body text-center py-5">
                <i class="bi bi-geo-alt fs-1 text-muted opacity-25 d-block mb-2"></i>
                <p class="text-muted">Belum ada pengiriman aktif saat ini.</p>
                <small class="text-muted">Tracking akan tampil saat driver memulai pengiriman.</small>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection