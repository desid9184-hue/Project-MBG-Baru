<?php $__env->startSection('title', 'Input Menu'); ?>
<?php $__env->startSection('page-title', 'Input Menu & Gizi'); ?>

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
            <li class="breadcrumb-item"><a href="<?php echo e(route('asisten.orders.show', $order)); ?>">#<?php echo e($order->id); ?></a></li>
            <li class="breadcrumb-item active">Input Menu</li>
        </ol>
    </nav>
    <h1>Input Menu & Kandungan Gizi</h1>
    <p>Pesanan #<?php echo e($order->id); ?> — <?php echo e($order->guru->name); ?> (<?php echo e($order->tanggal_pengiriman->format('d M Y')); ?>)</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-egg-fried text-success"></i> Form Menu Makanan</h5>
                <?php if($menu): ?>
                <span class="badge bg-info">Edit Mode</span>
                <?php else: ?>
                <span class="badge bg-success">Input Baru</span>
                <?php endif; ?>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="<?php echo e(route('asisten.orders.menu.store', $order)); ?>">
                    <?php echo csrf_field(); ?>

                    <!-- Komponen Menu -->
                    <h6 class="fw-700 mb-3 text-primary">
                        <i class="bi bi-basket-fill me-2"></i>Komponen Makanan
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Lauk <span class="text-danger">*</span></label>
                            <input type="text" name="lauk"
                                   class="form-control <?php $__errorArgs = ['lauk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('lauk', $menu->lauk ?? '')); ?>"
                                   placeholder="Contoh: Ayam Goreng Bumbu Kuning" required>
                            <?php $__errorArgs = ['lauk'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sayur <span class="text-danger">*</span></label>
                            <input type="text" name="sayur"
                                   class="form-control <?php $__errorArgs = ['sayur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('sayur', $menu->sayur ?? '')); ?>"
                                   placeholder="Contoh: Tumis Kangkung Bawang" required>
                            <?php $__errorArgs = ['sayur'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Buah <span class="text-danger">*</span></label>
                            <input type="text" name="buah"
                                   class="form-control <?php $__errorArgs = ['buah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('buah', $menu->buah ?? '')); ?>"
                                   placeholder="Contoh: Pisang Ambon" required>
                            <?php $__errorArgs = ['buah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sambal <span class="text-muted">(opsional)</span></label>
                            <input type="text" name="sambal"
                                   class="form-control <?php $__errorArgs = ['sambal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('sambal', $menu->sambal ?? '')); ?>"
                                   placeholder="Contoh: Sambal Terasi">
                            <?php $__errorArgs = ['sambal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Kandungan Gizi -->
                    <div class="p-3 mb-4" style="background:#f0fdf4;border-radius:12px;border:1.5px solid #bbf7d0;">
                        <h6 class="fw-700 mb-3 text-success">
                            <i class="bi bi-bar-chart-fill me-2"></i>Kandungan Gizi per Porsi
                        </h6>
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <label class="form-label">Kalori (kkal) <span class="text-danger">*</span></label>
                                <input type="number" name="kalori" step="0.01"
                                       class="form-control <?php $__errorArgs = ['kalori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('kalori', $menu->kalori ?? '')); ?>"
                                       placeholder="0" min="0" max="9999" required>
                                <?php $__errorArgs = ['kalori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Protein (g) <span class="text-danger">*</span></label>
                                <input type="number" name="protein" step="0.1"
                                       class="form-control <?php $__errorArgs = ['protein'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('protein', $menu->protein ?? '')); ?>"
                                       placeholder="0" min="0" max="999" required>
                                <?php $__errorArgs = ['protein'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Lemak (g) <span class="text-danger">*</span></label>
                                <input type="number" name="lemak" step="0.1"
                                       class="form-control <?php $__errorArgs = ['lemak'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('lemak', $menu->lemak ?? '')); ?>"
                                       placeholder="0" min="0" max="999" required>
                                <?php $__errorArgs = ['lemak'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Karbohidrat (g) <span class="text-danger">*</span></label>
                                <input type="number" name="karbohidrat" step="0.1"
                                       class="form-control <?php $__errorArgs = ['karbohidrat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('karbohidrat', $menu->karbohidrat ?? '')); ?>"
                                       placeholder="0" min="0" max="999" required>
                                <?php $__errorArgs = ['karbohidrat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <!-- Kalori Preview -->
                        <div class="mt-3 p-2" style="background:white;border-radius:8px;text-align:center;">
                            <small class="text-muted">Estimasi Total Kalori (per porsi):</small>
                            <div class="fw-800 text-success fs-5" id="kalori-preview">0 kkal</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2"
                                  placeholder="Catatan khusus, alergen, atau informasi tambahan..."><?php echo e(old('keterangan', $menu->keterangan ?? '')); ?></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success flex-grow-1">
                            <i class="bi bi-check-circle-fill me-2"></i>Simpan Menu & Gizi
                        </button>
                        <a href="<?php echo e(route('asisten.orders.show', $order)); ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('kalori').addEventListener('input', updateKalori);
function updateKalori() {
    const val = parseFloat(document.getElementById('kalori').value) || 0;
    document.getElementById('kalori-preview').textContent = val.toLocaleString('id-ID') + ' kkal';
}
// Rename input id references
document.addEventListener('DOMContentLoaded', () => {
    const kaloriInput = document.querySelector('input[name="kalori"]');
    if (kaloriInput) {
        kaloriInput.setAttribute('id', 'kalori');
        kaloriInput.addEventListener('input', updateKalori);
        updateKalori.call(kaloriInput);
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views/asisten/orders/input-menu.blade.php ENDPATH**/ ?>