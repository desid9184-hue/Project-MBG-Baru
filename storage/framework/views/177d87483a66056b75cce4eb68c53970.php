<?php $__env->startSection('title', 'Dashboard Driver'); ?>
<?php $__env->startSection('page-title', 'Dashboard Driver'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<div class="nav-section">Menu Utama</div>
<a href="<?php echo e(route('driver.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('driver.dashboard') ? 'active' : ''); ?>">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<a href="<?php echo e(route('driver.deliveries')); ?>" class="nav-link <?php echo e(request()->routeIs('driver.deliveries*') ? 'active' : ''); ?>">
    <i class="bi bi-truck-front-fill"></i> Pengiriman Saya
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Dashboard Driver</h1>
    <p>Selamat bertugas, <?php echo e(auth()->user()->name); ?>! Kelola pengiriman makanan bergizi Anda.</p>
</div>

<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7;">
                <i class="bi bi-truck" style="color:#f59e0b;"></i>
            </div>
            <div>
                <div class="stat-number"><?php echo e($stats['pengiriman_aktif']); ?></div>
                <div class="stat-label">Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5;">
                <i class="bi bi-check-circle-fill" style="color:#10b981;"></i>
            </div>
            <div>
                <div class="stat-number"><?php echo e($stats['pengiriman_selesai']); ?></div>
                <div class="stat-label">Selesai</div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;">
                <i class="bi bi-calendar-check" style="color:#2563eb;"></i>
            </div>
            <div>
                <div class="stat-number"><?php echo e($stats['hari_ini']); ?></div>
                <div class="stat-label">Hari Ini</div>
            </div>
        </div>
    </div>
</div>

<?php if($active_deliveries->count() > 0): ?>
<div class="row g-4 mb-4">
    <?php $__currentLoopData = $active_deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $cardBorder = $delivery->tracking_active ? '#10b981' : '#e2e8f0';
        $cardBg = $delivery->tracking_active ? 'linear-gradient(135deg,#065f46,#10b981)' : 'white';
    ?>
    <div class="col-lg-6">
        <div class="card" style="border: 2px solid <?php echo e($cardBorder); ?>;">
            <div class="card-header" style="background: <?php echo e($cardBg); ?>;">
                <h5 class="<?php echo e($delivery->tracking_active ? 'text-white' : ''); ?> mb-0">
                    <?php if($delivery->tracking_active): ?>
                    <span class="pulse me-2" style="display:inline-block;width:10px;height:10px;background:#fbbf24;border-radius:50%;"></span>
                    LIVE — Dalam Perjalanan
                    <?php else: ?>
                    <i class="bi bi-truck text-warning me-2"></i>Pengiriman Baru
                    <?php endif; ?>
                </h5>
                <span class="badge bg-<?php echo e($delivery->status_color); ?>"><?php echo e($delivery->status_label); ?></span>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="fw-700"><?php echo e($delivery->order->guru->name); ?></div>
                    <div class="text-muted" style="font-size:.85rem;">
                        <i class="bi bi-building me-1"></i><?php echo e($delivery->order->guru->school ?? '-'); ?>

                    </div>
                    <div class="mt-2">
                        <span class="badge bg-primary"><?php echo e($delivery->order->jumlah_porsi_besar); ?> Porsi Besar</span>
                        <span class="badge bg-info ms-1"><?php echo e($delivery->order->jumlah_porsi_kecil); ?> Porsi Kecil</span>
                    </div>
                    <?php if($delivery->order->menu): ?>
                    <div class="mt-2" style="font-size:.8rem;color:#475569;">
                        <i class="bi bi-egg-fried me-1"></i><?php echo e($delivery->order->menu->lauk); ?> &bull; <?php echo e($delivery->order->menu->sayur); ?>

                    </div>
                    <?php endif; ?>
                </div>

                <div id="tracking-controls-<?php echo e($delivery->id); ?>">
                    <?php if(!$delivery->tracking_active && $delivery->status_pengiriman == 'menunggu'): ?>
                    
                    <button class="btn btn-success w-100 mb-2" data-id="<?php echo e($delivery->id); ?>" onclick="startTracking(this.getAttribute('data-id'))">
                        <i class="bi bi-geo-alt-fill me-2"></i>Mulai Tracking GPS
                    </button>
                    <?php endif; ?>

                    <?php if($delivery->tracking_active && $delivery->status_pengiriman == 'dalam_perjalanan'): ?>
                    
                    <button class="btn btn-info w-100 mb-2 text-white" data-id="<?php echo e($delivery->id); ?>" onclick="arrivedAtSchool(this.getAttribute('data-id'))">
                        <i class="bi bi-building-fill-check me-2"></i>Konfirmasi Sampai Sekolah
                    </button>
                    <?php endif; ?>

                    <?php if($delivery->status_pengiriman == 'sampai_sekolah'): ?>
                    <form method="POST" action="<?php echo e(route('driver.deliveries.complete', $delivery)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-2">
                            <input type="text" name="catatan" class="form-control form-control-sm" placeholder="Catatan pengiriman (opsional)">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-check-circle-fill me-2"></i>Konfirmasi Selesai
                        </button>
                    </form>
                    <?php endif; ?>

                    <a href="<?php echo e(route('driver.deliveries.show', $delivery)); ?>" class="btn btn-outline-secondary w-100 btn-sm">
                        <i class="bi bi-eye me-1"></i>Detail Pengiriman
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="card mb-4">
    <div class="card-header">
        <h5><i class="bi bi-map-fill text-success"></i> Peta Lokasi Saya</h5>
        <div id="gps-status" class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary" id="gps-badge">GPS Tidak Aktif</span>
        </div>
    </div>
    <div id="driver-map"></div>
    <div class="card-footer bg-white">
        <small class="text-muted" id="gps-coords">Koordinat: -</small>
    </div>
</div>
<?php else: ?>
<div class="card text-center py-5">
    <div class="card-body">
        <i class="bi bi-truck-front fs-1 text-muted opacity-25 d-block mb-3"></i>
        <h5 class="text-muted">Tidak Ada Pengiriman Aktif</h5>
        <p class="text-muted">Asisten lapangan akan menugaskan pengiriman untuk Anda.</p>
    </div>
</div>
<?php endif; ?>

<?php if($completed_today->count() > 0): ?>
<div class="card">
    <div class="card-header">
        <h5><i class="bi bi-check-circle-fill text-success"></i> Selesai Hari Ini</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Sekolah</th>
                        <th>Porsi</th>
                        <th>Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $completed_today; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($d->order->guru->school ?? '-'); ?></td>
                        <td><?php echo e($d->order->total_porsi); ?> ompreng</td>
                        <td><?php echo e($d->delivered_at?->format('H:i') ?? '-'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>


<?php
    $currentActiveDeliveryId = null;
    if(isset($active_deliveries) && $active_deliveries->count() > 0) {
        foreach($active_deliveries as $delivery) {
            if($delivery->tracking_active) {
                $currentActiveDeliveryId = $delivery->id;
                break;
            }
        }
    }
?>
<input type="hidden" id="active-delivery-id" value="<?php echo e($currentActiveDeliveryId); ?>">

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let watchId = null;
let driverMap = null;
let driverMarker = null;

// Mengambil data ID dari Hidden Input
const activeDeliveryInput = document.getElementById('active-delivery-id');
let activeDeliveryId = activeDeliveryInput ? activeDeliveryInput.value : null;

// Cek apakah Peta dirender (Ada pengiriman)
const mapContainer = document.getElementById('driver-map');

if (mapContainer) {
    driverMap = L.map('driver-map').setView([0.400229, 101.856809], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(driverMap);

    const myIcon = L.divIcon({
        html: '<div style="background:#2563eb;width:44px;height:44px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 3px 12px rgba(37,99,235,.4);border:3px solid white;"><i class="bi bi-person-fill" style="color:white;font-size:18px;"></i></div>',
        className: '', iconSize: [44, 44], iconAnchor: [22, 22],
    });

    if (activeDeliveryId && navigator.geolocation) {
        startGPSWatch();
    }
}

function startTracking(deliveryId) {
    if (!navigator.geolocation) {
        alert('GPS tidak didukung di perangkat ini.');
        return;
    }

    navigator.geolocation.getCurrentPosition(
        (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            fetch(`/driver/deliveries/${deliveryId}/start-tracking`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng,
                    accuracy: pos.coords.accuracy,
                })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    activeDeliveryId = deliveryId;
                    startGPSWatch();
                    location.reload();
                }
            })
            .catch(err => console.error(err));
        },
        (err) => alert('Gagal mendapatkan lokasi GPS: ' + err.message),
        { enableHighAccuracy: true, timeout: 10000 }
    );
}

function startGPSWatch() {
    if (!navigator.geolocation || !activeDeliveryId) return;

    document.getElementById('gps-badge').className = 'badge bg-success';
    document.getElementById('gps-badge').textContent = 'GPS Aktif';

    watchId = navigator.geolocation.watchPosition(
        (pos) => {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;

            if (driverMap && driverMarker) {
                driverMarker.setLatLng([lat, lng]);
                driverMap.panTo([lat, lng]);
            } else if (driverMap) {
                driverMarker = L.marker([lat, lng], { icon: myIcon })
                    .bindPopup('Posisi Saya').addTo(driverMap);
                driverMap.setView([lat, lng], 16);
            }

            document.getElementById('gps-coords').textContent =
                `Koordinat: ${lat.toFixed(6)}, ${lng.toFixed(6)} | Akurasi: ${Math.round(pos.coords.accuracy)}m`;

            fetch(`/driver/deliveries/${activeDeliveryId}/update-location`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    latitude: lat,
                    longitude: lng,
                    speed: pos.coords.speed,
                    accuracy: pos.coords.accuracy,
                })
            }).catch(() => {});
        },
        (err) => {
            document.getElementById('gps-badge').className = 'badge bg-danger';
            document.getElementById('gps-badge').textContent = 'GPS Error';
        },
        { enableHighAccuracy: true, maximumAge: 5000, timeout: 10000 }
    );
}

function arrivedAtSchool(deliveryId) {
    if (!confirm('Konfirmasi bahwa Anda sudah sampai di sekolah?')) return;

    fetch(`/driver/deliveries/${deliveryId}/arrived`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        }
    });
}
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views\driver\dashboard.blade.php ENDPATH**/ ?>