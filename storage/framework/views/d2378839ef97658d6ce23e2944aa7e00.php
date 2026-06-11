<?php $__env->startSection('title', 'Detail Pesanan'); ?>
<?php $__env->startSection('page-title', 'Detail Pesanan Admin'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<div class="nav-section">Menu Utama</div>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="<?php echo e(route('admin.users')); ?>" class="nav-link"><i class="bi bi-people-fill"></i> Manajemen User</a>
<a href="<?php echo e(route('admin.orders')); ?>" class="nav-link active"><i class="bi bi-clipboard2-data-fill"></i> Semua Pesanan</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.orders')); ?>">Pesanan</a></li>
            <li class="breadcrumb-item active">#<?php echo e($order->id); ?></li>
        </ol>
    </nav>
    <h1>Detail Pesanan #<?php echo e($order->id); ?></h1>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-clipboard2-data text-primary"></i> Info Pesanan</h5>
                <span class="badge badge-status bg-<?php echo e($order->status_color); ?>"><?php echo e($order->status_label); ?></span>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:.9rem;">
                    <tr><td class="text-muted" style="width:40%">Guru</td><td><strong><?php echo e($order->guru->name); ?></strong></td></tr>
                    <tr><td class="text-muted">Email</td><td><?php echo e($order->guru->email); ?></td></tr>
                    <tr><td class="text-muted">Sekolah</td><td><?php echo e($order->guru->school ?? '-'); ?></td></tr>
                    <tr><td class="text-muted">Tgl Kirim</td><td><strong><?php echo e($order->tanggal_pengiriman->format('d M Y')); ?></strong></td></tr>
                    <tr><td class="text-muted">Porsi Besar</td><td><span class="badge bg-primary"><?php echo e($order->jumlah_porsi_besar); ?></span></td></tr>
                    <tr><td class="text-muted">Porsi Kecil</td><td><span class="badge bg-info"><?php echo e($order->jumlah_porsi_kecil); ?></span></td></tr>
                    <tr><td class="text-muted">Total</td><td><strong><?php echo e($order->total_porsi); ?></strong></td></tr>
                    <?php if($order->catatan): ?>
                    <tr><td class="text-muted">Catatan</td><td><?php echo e($order->catatan); ?></td></tr>
                    <?php endif; ?>
                    <tr><td class="text-muted">Dibuat</td><td><?php echo e($order->created_at->format('d M Y H:i')); ?></td></tr>
                </table>
            </div>
        </div>

        <?php if($order->delivery): ?>
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-truck text-warning"></i> Info Pengiriman</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:.9rem;">
                    <tr><td class="text-muted">Driver</td><td><strong><?php echo e($order->delivery->driver->name); ?></strong></td></tr>
                    <tr><td class="text-muted">Status</td>
                        <td><span class="badge badge-status bg-<?php echo e($order->delivery->status_color); ?>"><?php echo e($order->delivery->status_label); ?></span></td>
                    </tr>
                    <tr><td class="text-muted">Tracking</td>
                        <td>
                            <?php if($order->delivery->tracking_active): ?>
                                <span class="badge bg-success"><span class="pulse me-1" style="display:inline-block;width:6px;height:6px;background:white;border-radius:50%;"></span>Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Tidak Aktif</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr><td class="text-muted">Log Tracking</td><td><?php echo e($order->delivery->trackingLogs->count()); ?> titik</td></tr>
                    <?php if($order->delivery->delivered_at): ?>
                    <tr><td class="text-muted">Diterima</td><td class="text-success fw-600"><?php echo e($order->delivery->delivered_at->format('d M Y H:i')); ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-7">
        <?php if($order->menu): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-egg-fried text-success"></i> Menu & Gizi</h5>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <?php $__currentLoopData = ['lauk'=>'Lauk','sayur'=>'Sayur','buah'=>'Buah','sambal'=>'Sambal']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($order->menu->$k): ?>
                    <div class="col-6">
                        <div class="p-2" style="background:#f8fafc;border-radius:10px;">
                            <small class="text-muted"><?php echo e($l); ?></small>
                            <div class="fw-600" style="font-size:.9rem;"><?php echo e($order->menu->$k); ?></div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="row g-2">
                    <div class="col-3"><div class="text-center p-2" style="background:#dbeafe;border-radius:10px;"><div class="fw-800 text-primary"><?php echo e(number_format($order->menu->kalori,0)); ?></div><small>kkal</small></div></div>
                    <div class="col-3"><div class="text-center p-2" style="background:#d1fae5;border-radius:10px;"><div class="fw-800 text-success"><?php echo e($order->menu->protein); ?>g</div><small>Protein</small></div></div>
                    <div class="col-3"><div class="text-center p-2" style="background:#fef3c7;border-radius:10px;"><div class="fw-800 text-warning"><?php echo e($order->menu->lemak); ?>g</div><small>Lemak</small></div></div>
                    <div class="col-3"><div class="text-center p-2" style="background:#ede9fe;border-radius:10px;"><div class="fw-800" style="color:#7c3aed;"><?php echo e($order->menu->karbohidrat); ?>g</div><small>Karbo</small></div></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if($order->delivery && $order->delivery->current_latitude): ?>
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-map-fill text-primary"></i> Peta Tracking</h5>
            </div>
            <div id="admin-map" style="height:350px;border-radius:0 0 16px 16px;"></div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php if($order->delivery && $order->delivery->current_latitude): ?>
<script>
const adminMap = L.map('admin-map').setView([<?php echo e($order->delivery->current_latitude); ?>, <?php echo e($order->delivery->current_longitude); ?>], 14);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(adminMap);

const logs = <?php echo json_encode($order->delivery->trackingLogs->map(fn($l) => [(float)$l->latitude, (float)$l->longitude]), 512) ?>;
if (logs.length > 1) {
    L.polyline(logs, { color: '#2563eb', weight: 4, opacity: .7 }).addTo(adminMap);
}

const icon = L.divIcon({
    html: '<div style="background:#f59e0b;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.25);border:2px solid white;"><i class="bi bi-truck" style="color:white;font-size:15px;"></i></div>',
    className: '', iconSize: [36, 36], iconAnchor: [18, 18],
});

L.marker([<?php echo e($order->delivery->current_latitude); ?>, <?php echo e($order->delivery->current_longitude); ?>], { icon })
 .bindPopup('<b><?php echo e($order->delivery->driver->name); ?></b><br><?php echo e($order->delivery->status_label); ?>')
 .addTo(adminMap).openPopup();
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views\admin\orders\show.blade.php ENDPATH**/ ?>