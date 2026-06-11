<?php $__env->startSection('title', 'Pengiriman Saya'); ?>
<?php $__env->startSection('page-title', 'Daftar Pengiriman'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<div class="nav-section">Menu Utama</div>
<a href="<?php echo e(route('driver.dashboard')); ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="<?php echo e(route('driver.deliveries')); ?>" class="nav-link active"><i class="bi bi-truck-front-fill"></i> Pengiriman Saya</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Daftar Pengiriman</h1>
    <p>Riwayat semua pengiriman Anda.</p>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="menunggu"         <?php echo e(request('status') == 'menunggu'         ? 'selected' : ''); ?>>Menunggu</option>
                    <option value="dalam_perjalanan" <?php echo e(request('status') == 'dalam_perjalanan' ? 'selected' : ''); ?>>Dalam Perjalanan</option>
                    <option value="sampai_sekolah"   <?php echo e(request('status') == 'sampai_sekolah'   ? 'selected' : ''); ?>>Sampai Sekolah</option>
                    <option value="selesai"          <?php echo e(request('status') == 'selesai'          ? 'selected' : ''); ?>>Selesai</option>
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
                        <th>Sekolah / Guru</th>
                        <th>Tgl Kirim</th>
                        <th>Porsi</th>
                        <th>Status</th>
                        <th>Selesai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $deliveries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="text-muted">#<?php echo e($d->id); ?></span></td>
                        <td>
                            <div class="fw-600"><?php echo e($d->order->guru->school ?? '-'); ?></div>
                            <small class="text-muted"><?php echo e($d->order->guru->name); ?></small>
                        </td>
                        <td><?php echo e($d->order->tanggal_pengiriman->format('d M Y')); ?></td>
                        <td>
                            <span class="badge bg-primary"><?php echo e($d->order->jumlah_porsi_besar); ?>B</span>
                            <span class="badge bg-info ms-1"><?php echo e($d->order->jumlah_porsi_kecil); ?>K</span>
                        </td>
                        <td>
                            <span class="badge badge-status bg-<?php echo e($d->status_color); ?>"><?php echo e($d->status_label); ?></span>
                        </td>
                        <td>
                            <?php echo e($d->delivered_at ? $d->delivered_at->format('d M H:i') : '-'); ?>

                        </td>
                        <td>
                            <a href="<?php echo e(route('driver.deliveries.show', $d)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-truck fs-1 text-muted opacity-25 d-block mb-2"></i>
                            <p class="text-muted">Belum ada pengiriman</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($deliveries->hasPages()): ?>
    <div class="card-footer bg-white"><?php echo e($deliveries->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views\driver\deliveries\index.blade.php ENDPATH**/ ?>