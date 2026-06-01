<?php include 'app/views/shares/header.php'; ?>

<div class="container py-4">
    <div class="mb-4">
        <a href="/project1/Admin/users" class="btn btn-outline-secondary rounded-3">
            <i class="fa-solid fa-arrow-left me-2"></i>Quay lại danh sách
        </a>
    </div>

    <div class="row g-4">
        <!-- User info -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <img src="<?= $user->avatar && file_exists($user->avatar)
                    ? '/project1/' . $user->avatar
                    : 'https://ui-avatars.com/api/?name=' . urlencode($user->full_name ?: $user->username) . '&background=4e73df&color=fff&size=128'; ?>"
                     class="rounded-circle mx-auto mb-3"
                     style="width:100px;height:100px;object-fit:cover;border:4px solid #4e73df;">
                <h5 class="fw-bold"><?= htmlspecialchars($user->full_name ?: $user->username); ?></h5>
                <p class="text-muted small mb-2">@<?= htmlspecialchars($user->username); ?></p>
                <?php
                $roleColor = $user->role === 'admin' ? 'danger' : 'primary';
                $statColor = ['active'=>'success','locked'=>'danger','unverified'=>'warning'][$user->status] ?? 'secondary';
                $statLabel = ['active'=>'Hoạt động','locked'=>'Đã khóa','unverified'=>'Chờ xác thực'][$user->status] ?? $user->status;
                ?>
                <div class="d-flex justify-content-center gap-2">
                    <span class="badge bg-<?= $roleColor; ?>"><?= ucfirst($user->role); ?></span>
                    <span class="badge bg-<?= $statColor; ?>"><?= $statLabel; ?></span>
                </div>
                <hr>
                <div class="text-start small">
                    <div class="mb-2"><i class="fa-solid fa-envelope me-2 text-muted"></i><?= htmlspecialchars($user->email); ?></div>
                    <div class="mb-2"><i class="fa-solid fa-phone me-2 text-muted"></i><?= htmlspecialchars($user->phone ?: 'Chưa cập nhật'); ?></div>
                    <div class="mb-2"><i class="fa-solid fa-calendar me-2 text-muted"></i>Đăng ký: <?= date('d/m/Y', strtotime($user->created_at)); ?></div>
                    <div class="mb-2"><i class="fa-solid fa-clock me-2 text-muted"></i>Đăng nhập cuối: <?= $user->last_login ? date('H:i d/m/Y', strtotime($user->last_login)) : 'Chưa có'; ?></div>
                    <div class="mb-2"><i class="fa-solid fa-shield-halved me-2 text-muted"></i>Email xác thực: <?= $user->email_verified_at ? '<span class="text-success">Đã xác thực</span>' : '<span class="text-warning">Chưa</span>'; ?></div>
                    <?php if ($user->failed_login_attempts > 0): ?>
                    <div><i class="fa-solid fa-triangle-exclamation me-2 text-warning"></i><?= $user->failed_login_attempts; ?> lần đăng nhập thất bại</div>
                    <?php endif; ?>
                </div>
                <?php if ($user->role !== 'admin'): ?>
                <hr>
                <?php if ($user->status === 'locked'): ?>
                <a href="/project1/Admin/unlockUser/<?= $user->id; ?>"
                   class="btn btn-success btn-sm rounded-3 w-100"
                   onclick="return confirm('Mở khóa tài khoản này?')">
                    <i class="fa-solid fa-lock-open me-2"></i>Mở khóa tài khoản
                </a>
                <?php else: ?>
                <a href="/project1/Admin/lockUser/<?= $user->id; ?>"
                   class="btn btn-danger btn-sm rounded-3 w-100"
                   onclick="return confirm('Khóa tài khoản này?')">
                    <i class="fa-solid fa-lock me-2"></i>Khóa tài khoản
                </a>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Activity logs -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <h5 class="fw-bold"><i class="fa-solid fa-timeline me-2 text-primary"></i>Lịch sử hoạt động</h5>
                </div>
                <div class="card-body px-4">
                    <?php if (empty($logs)): ?>
                    <div class="text-center text-muted py-4">
                        <i class="fa-solid fa-inbox fa-2x mb-2"></i><br>Chưa có hoạt động nào
                    </div>
                    <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($logs as $log): ?>
                        <?php
                        $iconMap = [
                            'login'           => ['primary', 'fa-right-to-bracket'],
                            'logout'          => ['secondary', 'fa-right-from-bracket'],
                            'change_password' => ['warning', 'fa-key'],
                            'reset_password'  => ['danger', 'fa-rotate-right'],
                            'update_profile'  => ['info', 'fa-user-pen'],
                            'lock_user'       => ['danger', 'fa-lock'],
                            'unlock_user'     => ['success', 'fa-lock-open'],
                        ];
                        [$ic, $ia] = $iconMap[$log->action] ?? ['secondary', 'fa-circle'];
                        ?>
                        <div class="d-flex gap-3 mb-3">
                            <div class="flex-shrink-0">
                                <span class="badge bg-<?= $ic; ?> rounded-circle p-2" style="width:34px;height:34px;display:flex;align-items:center;justify-content:center;">
                                    <i class="fa-solid <?= $ia; ?>" style="font-size:13px;"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold small"><?= htmlspecialchars($log->action); ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($log->description ?: ''); ?></div>
                                <div class="text-muted" style="font-size:11px;">
                                    <i class="fa-solid fa-clock me-1"></i><?= date('H:i d/m/Y', strtotime($log->created_at)); ?>
                                    &nbsp;·&nbsp;<i class="fa-solid fa-globe me-1"></i><?= htmlspecialchars($log->ip_address ?? ''); ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
