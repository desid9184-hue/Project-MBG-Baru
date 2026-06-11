<?php $__env->startSection('title', 'Kelola Pesanan'); ?>
<?php $__env->startSection('page-title', 'Kelola Pesanan'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<div class="nav-section">Menu Utama</div>
<a href="<?php echo e(route('asisten.dashboard')); ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="<?php echo e(route('asisten.orders')); ?>" class="nav-link active"><i class="bi bi-clipboard2-list-fill"></i> Kelola Pesanan</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h1>Daftar Pesanan</h1>
    <p>Kelola semua pesanan makanan bergizi dari guru.</p>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Filter Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Aktif</option>
                    <option value="pending"      <?php echo e(request('status') == 'pending'      ? 'selected' : ''); ?>>Menunggu</option>
                    <option value="diterima"     <?php echo e(request('status') == 'diterima'     ? 'selected' : ''); ?>>Diterima</option>
                    <option value="diproses"     <?php echo e(request('status') == 'diproses'     ? 'selected' : ''); ?>>Diproses</option>
                    <option value="dikemas"      <?php echo e(request('status') == 'dikemas'      ? 'selected' : ''); ?>>Dikemas</option>
                    <option value="siap_dikirim" <?php echo e(request('status') == 'siap_dikirim' ? 'selected' : ''); ?>>Siap Dikirim</option>
                    <option value="selesai"      <?php echo e(request('status') == 'selesai'      ? 'selected' : ''); ?>>Selesai</option>
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
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="text-muted fw-600">#<?php echo e($order->id); ?></span></td>
                        <td>
                            <div class="fw-600"><?php echo e($order->guru->name); ?></div>
                            <small class="text-muted"><?php echo e($order->guru->school ?? '-'); ?></small>
                        </td>
                        <td>
                            <div class="fw-600"><?php echo e($order->tanggal_pengiriman->format('d M Y')); ?></div>
                        </td>
                        <td>
                            <span class="badge bg-primary"><?php echo e($order->jumlah_porsi_besar); ?>B</span>
                            <span class="badge bg-info ms-1"><?php echo e($order->jumlah_porsi_kecil); ?>K</span>
                            <div style="font-size:.78rem;color:#64748b;">Total: <?php echo e($order->total_porsi); ?></div>
                        </td>
                        <td>
                            <?php if($order->menu): ?>
                                <span class="badge bg-success"><i class="bi bi-check-lg"></i> Ada</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Belum</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-status bg-<?php echo e($order->status_color); ?>"><?php echo e($order->status_label); ?></span>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?php echo e(route('asisten.orders.show', $order)); ?>" class="btn btn-sm btn-outline-primary" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if($order->status == 'pending'): ?>
                                <form method="POST" action="<?php echo e(route('asisten.orders.accept', $order)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-sm btn-success" title="Terima">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                                <?php endif; ?>
                                <?php if(in_array($order->status, ['diterima', 'diproses', 'dikemas'])): ?>
                                <a href="<?php echo e(route('asisten.orders.menu', $order)); ?>" class="btn btn-sm btn-warning text-dark" title="Input Menu">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 text-muted opacity-25 d-block mb-2"></i>
                            <p class="text-muted">Tidak ada pesanan</p>
                        </td>
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views\asisten\orders\index.blade.php ENDPATH**/ ?>