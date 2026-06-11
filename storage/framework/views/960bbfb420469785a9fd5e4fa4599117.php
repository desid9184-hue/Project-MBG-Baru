<?php $__env->startSection('title', 'Semua Pesanan'); ?>
<?php $__env->startSection('page-title', 'Semua Pesanan'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<div class="nav-section">Menu Utama</div>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="<?php echo e(route('admin.users')); ?>" class="nav-link"><i class="bi bi-people-fill"></i> Manajemen User</a>
<a href="<?php echo e(route('admin.orders')); ?>" class="nav-link active"><i class="bi bi-clipboard2-data-fill"></i> Semua Pesanan</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Semua Pesanan</h1>
    <p>Monitor seluruh pesanan makanan bergizi dari semua guru.</p>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2">
            <div class="col-md-4">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="pending"          <?php echo e(request('status') == 'pending'          ? 'selected' : ''); ?>>Menunggu</option>
                    <option value="diterima"         <?php echo e(request('status') == 'diterima'         ? 'selected' : ''); ?>>Diterima</option>
                    <option value="diproses"         <?php echo e(request('status') == 'diproses'         ? 'selected' : ''); ?>>Diproses</option>
                    <option value="dikemas"          <?php echo e(request('status') == 'dikemas'          ? 'selected' : ''); ?>>Dikemas</option>
                    <option value="siap_dikirim"     <?php echo e(request('status') == 'siap_dikirim'     ? 'selected' : ''); ?>>Siap Dikirim</option>
                    <option value="dalam_perjalanan" <?php echo e(request('status') == 'dalam_perjalanan' ? 'selected' : ''); ?>>Dalam Perjalanan</option>
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
                        <th>Guru / Sekolah</th>
                        <th>Tgl Kirim</th>
                        <th>Porsi</th>
                        <th>Menu</th>
                        <th>Driver</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="text-muted">#<?php echo e($order->id); ?></span></td>
                        <td>
                            <div class="fw-600"><?php echo e($order->guru->name); ?></div>
                            <small class="text-muted"><?php echo e($order->guru->school ?? '-'); ?></small>
                        </td>
                        <td><?php echo e($order->tanggal_pengiriman->format('d M Y')); ?></td>
                        <td>
                            <span class="badge bg-primary"><?php echo e($order->jumlah_porsi_besar); ?>B</span>
                            <span class="badge bg-info ms-1"><?php echo e($order->jumlah_porsi_kecil); ?>K</span>
                        </td>
                        <td>
                            <?php if($order->menu): ?>
                                <span class="badge bg-success">Ada</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Belum</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($order->delivery): ?>
                                <span style="font-size:.8rem;"><?php echo e($order->delivery->driver->name); ?></span>
                            <?php else: ?>
                                <span class="text-muted" style="font-size:.8rem;">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-status bg-<?php echo e($order->status_color); ?>"><?php echo e($order->status_label); ?></span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('admin.orders.show', $order)); ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Tidak ada pesanan ditemukan.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($orders->hasPages()): ?>
    <div class="card-footer bg-white"><?php echo e($orders->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views\admin\orders\index.blade.php ENDPATH**/ ?>