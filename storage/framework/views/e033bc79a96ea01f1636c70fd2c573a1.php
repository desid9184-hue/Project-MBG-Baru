<?php $__env->startSection('title', 'Manajemen User'); ?>
<?php $__env->startSection('page-title', 'Manajemen User'); ?>

<?php $__env->startSection('sidebar-menu'); ?>
<div class="nav-section">Menu Utama</div>
<a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
<a href="<?php echo e(route('admin.users')); ?>" class="nav-link active"><i class="bi bi-people-fill"></i> Manajemen User</a>
<a href="<?php echo e(route('admin.orders')); ?>" class="nav-link"><i class="bi bi-clipboard2-data-fill"></i> Semua Pesanan</a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header d-flex justify-content-between align-items-start flex-wrap gap-3">
    <div>
        <h1>Manajemen User</h1>
        <p>Kelola semua akun pengguna sistem MBG.</p>
    </div>
    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>Tambah User
    </a>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form method="GET" class="row g-2">
            <div class="col-md-3">
                <select name="role" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Role</option>
                    <option value="admin"   <?php echo e(request('role') == 'admin'   ? 'selected' : ''); ?>>Admin</option>
                    <option value="guru"    <?php echo e(request('role') == 'guru'    ? 'selected' : ''); ?>>Guru</option>
                    <option value="asisten" <?php echo e(request('role') == 'asisten' ? 'selected' : ''); ?>>Asisten</option>
                    <option value="driver"  <?php echo e(request('role') == 'driver'  ? 'selected' : ''); ?>>Driver</option>
                </select>
            </div>
            <div class="col-md-5">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari nama atau email..." value="<?php echo e(request('search')); ?>">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
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
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Sekolah/HP</th>
                        <th>Terdaftar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        // Logika warna dipisah sepenuhnya agar VS Code tidak bingung
                        $avatarBg = match($user->role) {
                            'admin'   => '#ef4444',
                            'guru'    => '#2563eb',
                            'asisten' => '#10b981',
                            default   => '#f59e0b',
                        };
                        $avatarStyle = "width:34px; height:34px; background-color: {$avatarBg}; border-radius:50%; display:flex; align-items:center; justify-content:center; color:white; font-weight:700; font-size:.8rem; flex-shrink:0;";
                    ?>
                    <tr>
                        <td><?php echo e($user->id); ?></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                               <div style="<?php echo \Illuminate\Support\Arr::toCssStyles([$avatarStyle]) ?>">
                                    <?php echo e(strtoupper(substr($user->name,0,1))); ?>

                                </div>
                                <span class="fw-600"><?php echo e($user->name); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($user->email); ?></td>
                        <td><span class="badge <?php echo e($user->role_badge); ?>"><?php echo e($user->role_label); ?></span></td>
                        <td>
                            <div style="font-size:.8rem;">
                                <?php if($user->school): ?> <div><?php echo e($user->school); ?></div> <?php endif; ?>
                                <?php if($user->phone): ?>  <div class="text-muted"><?php echo e($user->phone); ?></div> <?php endif; ?>
                                <?php if(!$user->school && !$user->phone): ?> <span class="text-muted">-</span> <?php endif; ?>
                            </div>
                        </td>
                        <td style="font-size:.8rem;color:#64748b;"><?php echo e($user->created_at->format('d M Y')); ?></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-fill"></i>
                                </a>
                                <?php if($user->id !== auth()->id()): ?>
                                <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>"
                                      onsubmit="return confirm('Hapus user <?php echo e($user->name); ?>?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash-fill"></i></button>
                                </form>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada user ditemukan.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if($users->hasPages()): ?>
    <div class="card-footer bg-white"><?php echo e($users->links()); ?></div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\mbg-system\resources\views/admin/users/index.blade.php ENDPATH**/ ?>