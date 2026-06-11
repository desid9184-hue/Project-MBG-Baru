<?php $__env->startSection('title', 'Pesanan Saya'); ?>
<?php $__env->startSection('page-title', 'Pesanan Saya'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<div class="nav-section">Menu Utama</div>
<a href="<?php echo e(route('guru.dashboard')); ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="<?php echo e(route('guru.orders')); ?>" class="nav-link active"><i class="bi bi-clipboard2-plus-fill"></i> Pesanan Saya</a>
<a href="<?php echo e(route('guru.orders.create')); ?>" class="nav-link"><i class="bi bi-plus-circle-fill"></i> Buat Pesanan</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
        <h1>Daftar Pesanan</h1>
        <p>Riwayat semua pesanan makanan bergizi Anda.</p>
    </div>
    <a href="<?php echo e(route('guru.orders.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Buat Pesanan
    </a>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Filter Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>Menunggu</option>
                    <option value="diterima" <?php echo e(request('status') == 'diterima' ? 'selected' : ''); ?>>Diterima</option>
                    <option value="diproses" <?php echo e(request('status') == 'diproses' ? 'selected' : ''); ?>>Diproses</option>
                    <option value="dikemas" <?php echo e(request('status') == 'dikemas' ? 'selected' : ''); ?>>Dikemas</option>
                    <option value="siap_dikirim" <?php echo e(request('status') == 'siap_dikirim' ? 'selected' : ''); ?>>Siap Dikirim</option>
                    <option value="dalam_perjalanan" <?php echo e(request('status') == 'dalam_perjalanan' ? 'selected' : ''); ?>>Dalam Perjalanan</option>
                    <option value="selesai" <?php echo e(request('status') == 'selesai' ? 'selected' : ''); ?>>Selesai</option>
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
                        <th>Tanggal Pengiriman</th>
                        <th>Porsi Besar</th>
                        <th>Porsi Kecil</th>
                        <th>Total</th>
                        <th>Menu</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="text-muted fw-600">#<?php echo e($order->id); ?></span></td>
                        <td>
                            <div class="fw-600"><?php echo e($order->tanggal_pengiriman->format('d M Y')); ?></div>
                            <small class="text-muted"><?php echo e($order->tanggal_pengiriman->format('l')); ?></small>
                        </td>
                        <td><span class="badge bg-primary"><?php echo e($order->jumlah_porsi_besar); ?></span></td>
                        <td><span class="badge bg-info"><?php echo e($order->jumlah_porsi_kecil); ?></span></td>
                        <td><strong><?php echo e($order->total_porsi); ?></strong></td>
                        <td>
                            <?php if($order->menu): ?>
                                <span class="badge bg-success"><i class="bi bi-check-lg me-1"></i>Ada</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Belum</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-status bg-<?php echo e($order->status_color); ?>">
                                <?php echo e($order->status_label); ?>

                            </span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?php echo e(route('guru.orders.show', $order)); ?>" class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if(in_array($order->status, ['dalam_perjalanan', 'sampai_sekolah']) && $order->delivery): ?>
                                <a href="<?php echo e(route('guru.tracking', $order)); ?>" class="btn btn-sm btn-success" title="Live Tracking">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted opacity-25 d-block mb-2"></i>
                            <p class="text-muted mb-3">Belum ada pesanan</p>
                            <a href="<?php echo e(route('guru.orders.create')); ?>" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-lg me-2"></i>Buat Pesanan Pertama
                            </a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($orders->hasPages()): ?>
    <div class="card-footer bg-white">
        <?php echo e($orders->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views\guru\orders\index.blade.php ENDPATH**/ ?>