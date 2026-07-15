<?php $__env->startSection('title', 'Dashboard Admin'); ?>
<?php $__env->startSection('page-title', 'Dashboard Admin'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<div class="nav-section">Menu Utama</div>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>
<a href="<?php echo e(route('admin.users')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.users*') ? 'active' : ''); ?>">
    <i class="bi bi-people-fill"></i> Manajemen User
</a>
<a href="<?php echo e(route('admin.orders')); ?>" class="nav-link <?php echo e(request()->routeIs('admin.orders*') ? 'active' : ''); ?>">
    <i class="bi bi-clipboard2-data-fill"></i> Semua Pesanan
</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Dashboard Administrator</h1>
    <p>Selamat datang, <?php echo e(auth()->user()->name); ?>! Pantau semua aktivitas sistem MBG.</p>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dbeafe;">
                <i class="bi bi-people-fill" style="color:#2563eb;"></i>
            </div>
            <div>
                <div class="stat-number"><?php echo e($stats['total_users']); ?></div>
                <div class="stat-label">Total Pengguna</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#d1fae5;">
                <i class="bi bi-clipboard2-check-fill" style="color:#10b981;"></i>
            </div>
            <div>
                <div class="stat-number"><?php echo e($stats['total_orders']); ?></div>
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
                <div class="stat-number"><?php echo e($stats['active_orders']); ?></div>
                <div class="stat-label">Pesanan Aktif</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fce7f3;">
                <i class="bi bi-truck" style="color:#ec4899;"></i>
            </div>
            <div>
                <div class="stat-number"><?php echo e($stats['today_delivery']); ?></div>
                <div class="stat-label">Pengiriman Hari Ini</div>
            </div>
        </div>
    </div>
</div>

<!-- Role Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#ede9fe;">
                <i class="bi bi-person-badge-fill" style="color:#7c3aed;"></i>
            </div>
            <div>
                <div class="stat-number"><?php echo e($stats['total_guru']); ?></div>
                <div class="stat-label">Guru Terdaftar</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#dcfce7;">
                <i class="bi bi-person-fill-gear" style="color:#16a34a;"></i>
            </div>
            <div>
                <div class="stat-number"><?php echo e($stats['total_asisten']); ?></div>
                <div class="stat-label">Asisten Lapangan</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fff7ed;">
                <i class="bi bi-truck-front-fill" style="color:#ea580c;"></i>
            </div>
            <div>
                <div class="stat-number"><?php echo e($stats['total_driver']); ?></div>
                <div class="stat-label">Driver Aktif</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-clock-history text-primary"></i> Pesanan Terbaru</h5>
                <a href="<?php echo e(route('admin.orders')); ?>" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Guru</th>
                                <th>Tgl Kirim</th>
                                <th>Porsi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $recent_orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><span class="text-muted">#<?php echo e($order->id); ?></span></td>
                                <td>
                                    <div class="fw-600"><?php echo e($order->guru->name); ?></div>
                                    <small class="text-muted"><?php echo e($order->guru->school); ?></small>
                                </td>
                                <td><?php echo e($order->tanggal_pengiriman->format('d M Y')); ?></td>
                                <td>
                                    <span class="badge bg-primary"><?php echo e($order->jumlah_porsi_besar); ?>B</span>
                                    <span class="badge bg-info"><?php echo e($order->jumlah_porsi_kecil); ?>K</span>
                                </td>
                                <td>
                                    <span class="badge badge-status bg-<?php echo e($order->status_color); ?>">
                                        <?php echo e($order->status_label); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada pesanan</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Deliveries -->
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header">
                <h5><i class="bi bi-geo-alt-fill text-warning"></i> Pengiriman Aktif</h5>
                <span class="badge bg-warning text-dark"><?php echo e($active_deliveries->count()); ?></span>
            </div>
            <div class="card-body p-0">
                <?php $__empty_1 = true; $__currentLoopData = $active_deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $delivery): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-3 border-bottom">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <div class="pulse" style="width:10px;height:10px;background:#10b981;border-radius:50%;flex-shrink:0;"></div>
                        <strong style="font-size:.875rem;"><?php echo e($delivery->driver->name); ?></strong>
                    </div>
                    <div style="font-size:.8rem;color:#64748b;">
                        → <?php echo e($delivery->order->guru->school ?? '-'); ?>

                    </div>
                    <span class="badge badge-status bg-<?php echo e($delivery->status_color); ?> mt-1">
                        <?php echo e($delivery->status_label); ?>

                    </span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-4 text-center text-muted">
                    <i class="bi bi-truck fs-2 opacity-25 d-block mb-2"></i>
                    Tidak ada pengiriman aktif
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>