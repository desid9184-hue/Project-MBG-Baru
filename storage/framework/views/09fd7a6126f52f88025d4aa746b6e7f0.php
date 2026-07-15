<?php $__env->startSection('title', 'Buat Pesanan'); ?>
<?php $__env->startSection('page-title', 'Buat Pesanan Baru'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<div class="nav-section">Menu Utama</div>
<a href="<?php echo e(route('guru.dashboard')); ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="<?php echo e(route('guru.orders')); ?>" class="nav-link"><i class="bi bi-clipboard2-plus-fill"></i> Pesanan Saya</a>
<a href="<?php echo e(route('guru.orders.create')); ?>" class="nav-link active"><i class="bi bi-plus-circle-fill"></i> Buat Pesanan</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo e(route('guru.dashboard')); ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?php echo e(route('guru.orders')); ?>">Pesanan</a></li>
            <li class="breadcrumb-item active">Buat Pesanan</li>
        </ol>
    </nav>
    <h1>Buat Pesanan Baru</h1>
    <p>Isi form berikut untuk memesan makanan bergizi. Pesanan dibuat minimal H-3 sebelum tanggal pengiriman.</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header">
                <h5><i class="bi bi-clipboard2-plus text-primary"></i> Form Pesanan</h5>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="<?php echo e(route('guru.orders.store')); ?>">
                    <?php echo csrf_field(); ?>

                    <div class="mb-4">
                        <label class="form-label">Tanggal Pengiriman <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_pengiriman"
                               class="form-control <?php $__errorArgs = ['tanggal_pengiriman'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               min="<?php echo e($min_date); ?>"
                               value="<?php echo e(old('tanggal_pengiriman')); ?>" required>
                        <?php $__errorArgs = ['tanggal_pengiriman'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">
                            <i class="bi bi-info-circle me-1"></i>
                            Minimal tanggal: <strong><?php echo e(\Carbon\Carbon::parse($min_date)->format('d M Y')); ?></strong> (H-3 dari hari ini)
                        </small>
                    </div>

                    <div class="p-3 mb-4" style="background:#f8fafc;border-radius:12px;border:1.5px solid #e2e8f0;">
                        <h6 class="fw-700 mb-3"><i class="bi bi-box-seam me-2 text-primary"></i>Jumlah Ompreng</h6>
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">
                                    <span class="badge bg-primary me-1">Besar</span>
                                    Porsi Besar
                                </label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="decrement('besar')">−</button>
                                    <input type="number" name="jumlah_porsi_besar" id="qty-besar"
                                           class="form-control text-center <?php $__errorArgs = ['jumlah_porsi_besar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('jumlah_porsi_besar', 0)); ?>" min="0" max="200" readonly>
                                    <button type="button" class="btn btn-outline-secondary" onclick="increment('besar')">+</button>
                                </div>
                                <small class="text-muted">Untuk siswa kelas 4-6</small>
                            </div>
                            <div class="col-6">
                                <label class="form-label">
                                    <span class="badge bg-info me-1">Kecil</span>
                                    Porsi Kecil
                                </label>
                                <div class="input-group">
                                    <button type="button" class="btn btn-outline-secondary" onclick="decrement('kecil')">−</button>
                                    <input type="number" name="jumlah_porsi_kecil" id="qty-kecil"
                                           class="form-control text-center <?php $__errorArgs = ['jumlah_porsi_kecil'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('jumlah_porsi_kecil', 0)); ?>" min="0" max="200" readonly>
                                    <button type="button" class="btn btn-outline-secondary" onclick="increment('kecil')">+</button>
                                </div>
                                <small class="text-muted">Untuk siswa kelas 1-3</small>
                            </div>
                        </div>

                        <div class="mt-3 pt-3" style="border-top:1px solid #e2e8f0;">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-600">Total Ompreng:</span>
                                <span class="fw-800 text-primary fs-5" id="total-display">0 ompreng</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Catatan (Opsional)</label>
                        <textarea name="catatan" class="form-control" rows="3"
                                  placeholder="Contoh: Ada 2 siswa alergi kacang, mohon diperhatikan..."
                                  maxlength="500"><?php echo e(old('catatan')); ?></textarea>
                        <small class="text-muted">Maksimal 500 karakter</small>
                    </div>

                    <div class="p-3 mb-4" style="background:#fffbeb;border-radius:12px;border:1px solid #fde68a;">
                        <h6 class="fw-700 text-warning-emphasis mb-2">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Perhatian
                        </h6>
                        <ul class="mb-0" style="font-size:.85rem;color:#92400e;">
                            <li>Pesanan dibuat <strong>minimal H-3</strong> sebelum tanggal pengiriman.</li>
                            <li>Setelah dikirim, pesanan tidak bisa dibatalkan oleh guru.</li>
                            <li>Hubungi asisten lapangan jika ada perubahan mendesak.</li>
                        </ul>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-send-fill me-2"></i>Kirim Pesanan
                        </button>
                        <a href="<?php echo e(route('guru.orders')); ?>" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function increment(type) {
    const el = document.getElementById(`qty-${type}`);
    el.value = parseInt(el.value) + 1;
    updateTotal();
}
function decrement(type) {
    const el = document.getElementById(`qty-${type}`);
    if (parseInt(el.value) > 0) { el.value = parseInt(el.value) - 1; }
    updateTotal();
}
function updateTotal() {
    const b = parseInt(document.getElementById('qty-besar').value) || 0;
    const k = parseInt(document.getElementById('qty-kecil').value) || 0;
    document.getElementById('total-display').textContent = (b + k) + ' ompreng';
}
updateTotal();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views/guru/orders/create.blade.php ENDPATH**/ ?>