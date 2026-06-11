<?php $__env->startSection('title', 'Live Tracking'); ?>
<?php $__env->startSection('page-title', 'Live Tracking'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<div class="nav-section">Menu Utama</div>
<a href="<?php echo e(route('guru.dashboard')); ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="<?php echo e(route('guru.orders')); ?>" class="nav-link active"><i class="bi bi-clipboard2-plus-fill"></i> Pesanan Saya</a>
<a href="<?php echo e(route('guru.orders.create')); ?>" class="nav-link"><i class="bi bi-plus-circle-fill"></i> Buat Pesanan</a>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
#tracking-map { height: calc(100vh - 180px); min-height: 500px; border-radius: 16px; }
.tracking-info-bar {
    background: white;
    border-radius: 16px;
    padding: 16px 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 16px;
}
.tracking-dot { width:12px;height:12px;background:#10b981;border-radius:50%;animation:pulse-dot 1.5s infinite; }
.status-chip {
    display: flex; align-items: center; gap: 8px;
    background: #f1f5f9; border-radius: 20px;
    padding: 6px 14px; font-size: .85rem; font-weight: 600;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('guru.orders')); ?>">Pesanan</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('guru.orders.show', $order)); ?>">#<?php echo e($order->id); ?></a></li>
            <li class="breadcrumb-item active">Live Tracking</li>
        </ol>
    </nav>
    <h1>Live Tracking Pengiriman #<?php echo e($order->id); ?></h1>
</div>

<!-- Info Bar -->
<div class="tracking-info-bar">
    <div class="d-flex align-items-center gap-3">
        <?php if($delivery && $delivery->tracking_active): ?>
        <div class="tracking-dot"></div>
        <span class="fw-700 text-success">LIVE</span>
        <?php else: ?>
        <i class="bi bi-wifi-off text-muted"></i>
        <span class="text-muted">Tracking tidak aktif</span>
        <?php endif; ?>
    </div>

    <div class="d-flex align-items-center gap-3 flex-wrap">
        <?php if($delivery): ?>
        <div class="status-chip">
            <i class="bi bi-truck text-warning"></i>
            <span><?php echo e($delivery->driver->name); ?></span>
        </div>
        <div class="status-chip" id="status-chip">
            <span id="status-text"><?php echo e($delivery->status_label); ?></span>
        </div>
        <?php endif; ?>
        <small class="text-muted" id="last-update">Auto refresh: 5 detik</small>
    </div>
</div>

<!-- Map -->
<div id="tracking-map"></div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const orderId = "<?php echo e($order->id); ?>";
const trackingUrl = "<?php echo e(route('guru.tracking.data', $order)); ?>";

const map = L.map('tracking-map').setView([-7.9666, 112.6326], 14);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 19
}).addTo(map);

// School marker (destination)
const schoolIcon = L.divIcon({
    html: '<div style="background:#10b981;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.3);"><i class="bi bi-building" style="color:white;font-size:16px;"></i></div>',
    className: '', iconSize: [36, 36], iconAnchor: [18, 18],
});

// Driver marker
const driverIcon = L.divIcon({
    html: '<div style="background:#2563eb;width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 3px 12px rgba(37,99,235,.4);border:3px solid white;"><i class="bi bi-truck" style="color:white;font-size:18px;"></i></div>',
    className: '', iconSize: [44, 44], iconAnchor: [22, 22],
});

let driverMarker = null;
let routePolyline = null;
let firstLoad = true;

function fetchTracking() {
    fetch(trackingUrl)
        .then(r => r.json())
        .then(data => {
            if (data.error) {
                document.getElementById('status-text').textContent = 'Belum dimulai';
                return;
            }

            // Update status chip
            document.getElementById('status-text').textContent = data.status_label;
            document.getElementById('last-update').textContent = 'Update: ' + (data.last_update || 'baru saja');

            // Move driver marker
            if (data.current_latitude && data.current_longitude) {
                const pos = [parseFloat(data.current_latitude), parseFloat(data.current_longitude)];

                if (driverMarker) {
                    driverMarker.setLatLng(pos);
                    driverMarker.getPopup().setContent(`
                        <b>${data.driver_name}</b><br>
                        Status: ${data.status_label}<br>
                        <small class="text-muted">${data.last_update}</small>
                    `);
                } else {
                    driverMarker = L.marker(pos, { icon: driverIcon })
                        .bindPopup(`<b>${data.driver_name}</b><br>Status: ${data.status_label}`)
                        .addTo(map);
                }

                if (firstLoad) {
                    map.setView(pos, 15);
                    firstLoad = false;
                } else {
                    map.panTo(pos, { animate: true, duration: 1 });
                }
            }

            // Draw route polyline
            if (data.logs && data.logs.length > 1) {
                const coords = data.logs.map(l => [parseFloat(l.latitude), parseFloat(l.longitude)]);
                if (routePolyline) map.removeLayer(routePolyline);
                routePolyline = L.polyline(coords, {
                    color: '#2563eb',
                    weight: 5,
                    opacity: .6,
                    dashArray: '10, 5'
                }).addTo(map);
            }
        })
        .catch(err => console.error('Tracking error:', err));
}

fetchTracking();
setInterval(fetchTracking, 5000);
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views/guru/tracking.blade.php ENDPATH**/ ?>