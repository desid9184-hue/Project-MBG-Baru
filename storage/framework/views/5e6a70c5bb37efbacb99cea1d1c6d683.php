<?php $__env->startSection('title', 'Detail Pesanan #' . $order->id); ?>
<?php $__env->startSection('page-title', 'Detail Pesanan'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<div class="nav-section">Menu Utama</div>
<a href="<?php echo e(route('asisten.dashboard')); ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="<?php echo e(route('asisten.orders')); ?>" class="nav-link active"><i class="bi bi-clipboard2-list-fill"></i> Kelola Pesanan</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('asisten.orders')); ?>">Pesanan</a></li>
            <li class="breadcrumb-item active">Pesanan #<?php echo e($order->id); ?></li>
        </ol>
    </nav>
    <h1>Detail Pesanan #<?php echo e($order->id); ?></h1>
</div>

<div class="row g-4">
    <!-- Left Column -->
    <div class="col-lg-5">
        <!-- Info Pesanan -->
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-clipboard2-data text-primary"></i> Info Pesanan</h5>
                <span class="badge badge-status bg-<?php echo e($order->status_color); ?>"><?php echo e($order->status_label); ?></span>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0" style="font-size:.9rem;">
                    <tr><td class="text-muted" style="width:40%">Guru</td><td><strong><?php echo e($order->guru->name); ?></strong></td></tr>
                    <tr><td class="text-muted">Sekolah</td><td><?php echo e($order->guru->school ?? '-'); ?></td></tr>
                    <tr><td class="text-muted">Tgl Kirim</td><td><strong><?php echo e($order->tanggal_pengiriman->format('d M Y')); ?></strong></td></tr>
                    <tr><td class="text-muted">Porsi Besar</td><td><span class="badge bg-primary"><?php echo e($order->jumlah_porsi_besar); ?></span></td></tr>
                    <tr><td class="text-muted">Porsi Kecil</td><td><span class="badge bg-info"><?php echo e($order->jumlah_porsi_kecil); ?></span></td></tr>
                    <tr><td class="text-muted">Total</td><td><strong><?php echo e($order->total_porsi); ?> ompreng</strong></td></tr>
                    <?php if($order->catatan): ?>
                    <tr><td class="text-muted">Catatan Guru</td><td class="text-warning-emphasis"><?php echo e($order->catatan); ?></td></tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- Update Status -->
        <?php if(in_array($order->status, ['diterima','diproses','dikemas','siap_dikirim'])): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h5><i class="bi bi-arrow-repeat text-info"></i> Update Status</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('asisten.orders.status', $order)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Status Baru</label>
                        <select name="status" class="form-select" required>
                            <?php if(in_array($order->status, ['diterima'])): ?>
                                <option value="diproses">Sedang Diproses</option>
                            <?php endif; ?>
                            <?php if(in_array($order->status, ['diterima','diproses'])): ?>
                                <option value="dikemas">Sedang Dikemas</option>
                            <?php endif; ?>
                            <?php if(in_array($order->status, ['diterima','diproses','dikemas'])): ?>
                                <option value="siap_dikirim">Siap Dikirim</option>
                            <?php endif; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle me-2"></i>Update Status
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <!-- Assign Driver -->
        <?php if($order->status == 'siap_dikirim'): ?>
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-truck text-warning"></i> Tugaskan Driver</h5>
            </div>
            <div class="card-body">
                <?php if($order->delivery): ?>
                <div class="alert alert-success mb-3">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    Driver sudah ditugaskan: <strong><?php echo e($order->delivery->driver->name); ?></strong>
                </div>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('asisten.orders.driver', $order)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Pilih Driver</label>
                        <select name="driver_id" class="form-select" required>
                            <option value="">-- Pilih Driver --</option>
                            <?php $__currentLoopData = \App\Models\User::where('role','driver')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $driver): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($driver->id); ?>" <?php echo e(optional($order->delivery)->driver_id == $driver->id ? 'selected' : ''); ?>>
                                <?php echo e($driver->name); ?> (<?php echo e($driver->phone ?? 'No HP tidak ada'); ?>)
                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-warning text-dark w-100">
                        <i class="bi bi-person-fill-up me-2"></i>Tugaskan Driver
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right: Menu Input -->
    <div class="col-lg-7">
        <?php if($order->menu): ?>
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-egg-fried text-success"></i> Menu & Kandungan Gizi</h5>
                <?php if(in_array($order->status, ['diterima','diproses','dikemas'])): ?>
                <a href="<?php echo e(route('asisten.orders.menu', $order)); ?>" class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil-fill me-1"></i>Edit Menu
                </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-4">
                    <?php $__currentLoopData = ['lauk' => 'Lauk', 'sayur' => 'Sayur', 'buah' => 'Buah', 'sambal' => 'Sambal']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($order->menu->$key): ?>
                    <div class="col-6">
                        <div class="p-3" style="background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;">
                            <small class="text-muted d-block fw-600 mb-1"><?php echo e($label); ?></small>
                            <strong><?php echo e($order->menu->$key); ?></strong>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <h6 class="fw-700 mb-3"><i class="bi bi-bar-chart-fill me-2 text-primary"></i>Kandungan Gizi per Porsi</h6>
                <div class="row g-2">
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3" style="background:#dbeafe;border-radius:12px;">
                            <div class="fw-800 fs-5 text-primary"><?php echo e(number_format($order->menu->kalori,0)); ?></div>
                            <small class="text-muted">kkal</small>
                            <div style="font-size:.75rem;font-weight:600;color:#1e40af;">Kalori</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3" style="background:#d1fae5;border-radius:12px;">
                            <div class="fw-800 fs-5 text-success"><?php echo e($order->menu->protein); ?>g</div>
                            <div style="font-size:.75rem;font-weight:600;color:#065f46;">Protein</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3" style="background:#fef3c7;border-radius:12px;">
                            <div class="fw-800 fs-5 text-warning"><?php echo e($order->menu->lemak); ?>g</div>
                            <div style="font-size:.75rem;font-weight:600;color:#92400e;">Lemak</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-center p-3" style="background:#ede9fe;border-radius:12px;">
                            <div class="fw-800 fs-5" style="color:#7c3aed;"><?php echo e($order->menu->karbohidrat); ?>g</div>
                            <div style="font-size:.75rem;font-weight:600;color:#5b21b6;">Karbo</div>
                        </div>
                    </div>
                </div>

                <?php if($order->menu->keterangan): ?>
                <div class="mt-3 p-3" style="background:#f8fafc;border-radius:10px;">
                    <small class="text-muted fw-600">Keterangan:</small>
                    <p class="mb-0 mt-1" style="font-size:.875rem;"><?php echo e($order->menu->keterangan); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php else: ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="bi bi-egg-fried fs-1 text-muted opacity-25 d-block mb-2"></i>
                <p class="text-muted mb-3">Menu belum diinput.</p>
                <?php if(in_array($order->status, ['diterima','diproses','dikemas'])): ?>
                <a href="<?php echo e(route('asisten.orders.menu', $order)); ?>" class="btn btn-warning text-dark">
                    <i class="bi bi-plus-circle me-2"></i>Input Menu Sekarang
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views\asisten\orders\show.blade.php ENDPATH**/ ?>