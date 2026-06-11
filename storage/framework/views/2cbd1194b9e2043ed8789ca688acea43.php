<?php $__env->startSection('title', 'Detail Pengiriman'); ?>
<?php $__env->startSection('page-title', 'Detail Pengiriman'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<div class="nav-section">Menu Utama</div>
<a href="<?php echo e(route('driver.dashboard')); ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="<?php echo e(route('driver.deliveries')); ?>" class="nav-link active"><i class="bi bi-truck-front-fill"></i> Pengiriman Saya</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('driver.deliveries')); ?>">Pengiriman</a></li>
            <li class="breadcrumb-item active">Detail #<?php echo e($delivery->id); ?></li>
        </ol>
    </nav>
    <h1>Detail Pengiriman #<?php echo e($delivery->id); ?></h1>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <!-- Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-truck text-warning"></i> Info Pengiriman</h5>
                <span class="badge badge-status bg-<?php echo e($delivery->status_color); ?>"><?php echo e($delivery->status_label); ?></span>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:.875rem;">
                    <tr><td class="text-muted">Sekolah</td><td><strong><?php echo e($delivery->order->guru->school ?? '-'); ?></strong></td></tr>
                    <tr><td class="text-muted">Guru</td><td><?php echo e($delivery->order->guru->name); ?></td></tr>
                    <tr><td class="text-muted">Telepon</td><td><?php echo e($delivery->order->guru->phone ?? '-'); ?></td></tr>
                    <tr><td class="text-muted">Tgl Kirim</td><td><strong><?php echo e($delivery->order->tanggal_pengiriman->format('d M Y')); ?></strong></td></tr>
                    <tr><td class="text-muted">Porsi Besar</td><td><span class="badge bg-primary"><?php echo e($delivery->order->jumlah_porsi_besar); ?></span></td></tr>
                    <tr><td class="text-muted">Porsi Kecil</td><td><span class="badge bg-info"><?php echo e($delivery->order->jumlah_porsi_kecil); ?></span></td></tr>
                    <?php if($delivery->delivered_at): ?>
                    <tr><td class="text-muted">Selesai</td><td class="text-success fw-600"><?php echo e($delivery->delivered_at->format('d M Y H:i')); ?></td></tr>
                    <?php endif; ?>
                    <?php if($delivery->catatan_driver): ?>
                    <tr><td class="text-muted">Catatan</td><td><?php echo e($delivery->catatan_driver); ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Menu -->
        <?php if($delivery->order->menu): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-egg-fried text-success"></i> Menu Hari Ini</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0" style="font-size:.875rem;">
                    <li class="mb-2"><i class="bi bi-dot me-1 text-primary"></i><strong>Lauk:</strong> <?php echo e($delivery->order->menu->lauk); ?></li>
                    <li class="mb-2"><i class="bi bi-dot me-1 text-success"></i><strong>Sayur:</strong> <?php echo e($delivery->order->menu->sayur); ?></li>
                    <li class="mb-2"><i class="bi bi-dot me-1 text-warning"></i><strong>Buah:</strong> <?php echo e($delivery->order->menu->buah); ?></li>
                    <?php if($delivery->order->menu->sambal): ?>
                    <li class="mb-2"><i class="bi bi-dot me-1 text-danger"></i><strong>Sambal:</strong> <?php echo e($delivery->order->menu->sambal); ?></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>

        <!-- Tracking Stats -->
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-geo-alt-fill text-primary"></i> Statistik Tracking</h5>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="fw-800 fs-3 text-primary"><?php echo e($delivery->trackingLogs->count()); ?></div>
                    <div class="text-muted" style="font-size:.85rem;">Total Log Lokasi</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Map with Route -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-map-fill text-primary"></i> Rute Perjalanan</h5>
                <?php if($delivery->tracking_active): ?>
                <span class="badge bg-success">
                    <span class="pulse me-1" style="display:inline-block;width:8px;height:8px;background:white;border-radius:50%;"></span>
                    LIVE
                </span>
                <?php endif; ?>
            </div>
            <div id="route-map" style="height:500px;border-radius:0 0 16px 16px;"></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const routeMap = L.map('route-map').setView([-7.9666, 112.6326], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(routeMap);

const logs = <?php echo json_encode($delivery->trackingLogs->map(fn($l) => ['lat' => (float)$l->latitude, 'lng' => (float)$l->longitude]), 512) ?>;
const currentLat = <?php echo e($delivery->current_latitude ?? 'null'); ?>;
const currentLng = <?php echo e($delivery->current_longitude ?? 'null'); ?>;

const driverIcon = L.divIcon({
    html: '<div style="background:#2563eb;width:40px;height:40px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 3px 12px rgba(37,99,235,.4);border:3px solid white;"><i class="bi bi-truck" style="color:white;font-size:16px;"></i></div>',
    className: '', iconSize: [40, 40], iconAnchor: [20, 20],
});

const startIcon = L.divIcon({
    html: '<div style="background:#10b981;width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.2);border:2px solid white;"><i class="bi bi-play-fill" style="color:white;font-size:14px;"></i></div>',
    className: '', iconSize: [32, 32], iconAnchor: [16, 16],
});

if (logs.length > 0) {
    const coords = logs.map(l => [l.lat, l.lng]);

    // Draw route polyline
    L.polyline(coords, {
        color: '#2563eb',
        weight: 5,
        opacity: .7,
        dashArray: '10, 5'
    }).addTo(routeMap);

    // Start marker
    L.marker(coords[0], { icon: startIcon })
     .bindPopup('Titik Awal').addTo(routeMap);

    // Fit bounds
    routeMap.fitBounds(L.latLngBounds(coords).pad(.1));
}

// Current position marker
if (currentLat && currentLng) {
    L.marker([currentLat, currentLng], { icon: driverIcon })
     .bindPopup('Posisi Terakhir Driver')
     .addTo(routeMap)
     .openPopup();
}

// Auto refresh if tracking active
<?php if($delivery->tracking_active): ?>
setInterval(() => {
    fetch(`/driver/deliveries/<?php echo e($delivery->id); ?>`)
    .then(() => location.reload())
    .catch(() => {});
}, 10000);
<?php endif; ?>
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views\driver\deliveries\show.blade.php ENDPATH**/ ?>