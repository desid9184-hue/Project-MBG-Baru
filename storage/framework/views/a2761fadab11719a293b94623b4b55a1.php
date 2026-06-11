<?php $__env->startSection('title', 'Detail Pesanan #' . $order->id); ?>
<?php $__env->startSection('page-title', 'Detail Pesanan'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<div class="nav-section">Menu Utama</div>
<a href="<?php echo e(route('guru.dashboard')); ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="<?php echo e(route('guru.orders')); ?>" class="nav-link active"><i class="bi bi-clipboard2-plus-fill"></i> Pesanan Saya</a>
<a href="<?php echo e(route('guru.orders.create')); ?>" class="nav-link"><i class="bi bi-plus-circle-fill"></i> Buat Pesanan</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('guru.orders')); ?>">Pesanan</a></li>
            <li class="breadcrumb-item active">Pesanan #<?php echo e($order->id); ?></li>
        </ol>
    </nav>
    <h1>Detail Pesanan #<?php echo e($order->id); ?></h1>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <!-- Pesanan Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-clipboard2-data text-primary"></i> Info Pesanan</h5>
                <span class="badge badge-status bg-<?php echo e($order->status_color); ?>"><?php echo e($order->status_label); ?></span>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:.9rem;">
                    <tr>
                        <td class="text-muted" style="width:45%">ID Pesanan</td>
                        <td><strong>#<?php echo e($order->id); ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Kirim</td>
                        <td><strong><?php echo e($order->tanggal_pengiriman->format('d M Y')); ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Porsi Besar</td>
                        <td><span class="badge bg-primary"><?php echo e($order->jumlah_porsi_besar); ?> porsi</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Porsi Kecil</td>
                        <td><span class="badge bg-info"><?php echo e($order->jumlah_porsi_kecil); ?> porsi</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Total</td>
                        <td><strong><?php echo e($order->total_porsi); ?> ompreng</strong></td>
                    </tr>
                    <?php if($order->catatan): ?>
                    <tr>
                        <td class="text-muted">Catatan</td>
                        <td><?php echo e($order->catatan); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="text-muted">Dibuat</td>
                        <td><?php echo e($order->created_at->format('d M Y H:i')); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Menu & Gizi -->
        <?php if($order->menu): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-egg-fried text-success"></i> Menu Makanan</h5>
            </div>
            <div class="card-body">
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="p-2" style="background:#f8fafc;border-radius:10px;">
                            <small class="text-muted d-block">Lauk</small>
                            <strong style="font-size:.9rem;"><?php echo e($order->menu->lauk); ?></strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2" style="background:#f8fafc;border-radius:10px;">
                            <small class="text-muted d-block">Sayur</small>
                            <strong style="font-size:.9rem;"><?php echo e($order->menu->sayur); ?></strong>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2" style="background:#f8fafc;border-radius:10px;">
                            <small class="text-muted d-block">Buah</small>
                            <strong style="font-size:.9rem;"><?php echo e($order->menu->buah); ?></strong>
                        </div>
                    </div>
                    <?php if($order->menu->sambal): ?>
                    <div class="col-6">
                        <div class="p-2" style="background:#f8fafc;border-radius:10px;">
                            <small class="text-muted d-block">Sambal</small>
                            <strong style="font-size:.9rem;"><?php echo e($order->menu->sambal); ?></strong>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <h6 class="fw-700 mb-3">Kandungan Gizi</h6>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="text-center p-2" style="background:#dbeafe;border-radius:10px;">
                            <div class="fw-800 text-primary"><?php echo e(number_format($order->menu->kalori, 0)); ?></div>
                            <small class="text-muted">Kalori (kkal)</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-2" style="background:#d1fae5;border-radius:10px;">
                            <div class="fw-800 text-success"><?php echo e($order->menu->protein); ?>g</div>
                            <small class="text-muted">Protein</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-2" style="background:#fef3c7;border-radius:10px;">
                            <div class="fw-800 text-warning"><?php echo e($order->menu->lemak); ?>g</div>
                            <small class="text-muted">Lemak</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center p-2" style="background:#ede9fe;border-radius:10px;">
                            <div class="fw-800" style="color:#7c3aed;"><?php echo e($order->menu->karbohidrat); ?>g</div>
                            <small class="text-muted">Karbohidrat</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="card mb-4">
            <div class="card-body text-center py-4">
                <i class="bi bi-egg-fried fs-2 text-muted opacity-25 d-block mb-2"></i>
                <p class="text-muted mb-0">Menu belum diinput oleh asisten lapangan.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-7">
        <!-- Driver Info -->
        <?php if($order->delivery): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-truck text-warning"></i> Info Pengiriman</h5>
                <span class="badge badge-status bg-<?php echo e($order->delivery->status_color); ?>">
                    <?php echo e($order->delivery->status_label); ?>

                </span>
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="user-avatar" style="width:50px;height:50px;font-size:1.1rem;background:#f59e0b;">
                        <?php echo e(strtoupper(substr($order->delivery->driver->name, 0, 1))); ?>

                    </div>
                    <div>
                        <div class="fw-700"><?php echo e($order->delivery->driver->name); ?></div>
                        <div class="text-muted" style="font-size:.85rem;">
                            <i class="bi bi-telephone me-1"></i><?php echo e($order->delivery->driver->phone ?? '-'); ?>

                        </div>
                    </div>
                </div>

                <?php if($order->delivery->delivered_at): ?>
                <div class="p-2 mb-3" style="background:#d1fae5;border-radius:10px;">
                    <small class="text-success fw-600">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        Diterima pada <?php echo e($order->delivery->delivered_at->format('d M Y H:i')); ?>

                    </small>
                </div>
                <?php endif; ?>

                <?php if(in_array($order->delivery->status_pengiriman, ['dalam_perjalanan', 'sampai_sekolah'])): ?>
                <a href="<?php echo e(route('guru.tracking', $order)); ?>" class="btn btn-success w-100">
                    <i class="bi bi-geo-alt-fill me-2"></i>Buka Live Tracking
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Mini Map -->
        <?php if($order->delivery && $order->delivery->current_latitude): ?>
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-map text-success"></i> Posisi Terakhir Driver</h5>
            </div>
            <div id="map" style="height:300px;border-radius:0 0 16px 16px;"></div>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-truck fs-1 text-muted opacity-25 d-block mb-2"></i>
                <p class="text-muted">Driver belum ditugaskan. Asisten lapangan akan menugaskan driver setelah pesanan siap.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<?php if($order->delivery && $order->delivery->current_latitude): ?>
<script>
const lat = <?php echo e($order->delivery->current_latitude); ?>;
const lng = <?php echo e($order->delivery->current_longitude); ?>;

const map = L.map('map').setView([lat, lng], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '© OpenStreetMap'
}).addTo(map);

const driverIcon = L.divIcon({
    html: '<div style="background:#f59e0b;width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.3);"><i class="bi bi-truck" style="color:white;font-size:16px;"></i></div>',
    className: '', iconSize: [36, 36], iconAnchor: [18, 18],
});

L.marker([lat, lng], { icon: driverIcon })
 .bindPopup('<b><?php echo e($order->delivery->driver->name); ?></b>')
 .addTo(map)
 .openPopup();
</script>
<?php endif; ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views\guru\orders\show.blade.php ENDPATH**/ ?>