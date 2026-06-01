<?php
if (!function_exists('isLoggedIn')) require_once __DIR__ . '/../../helpers/auth_helper.php';
?>
<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center g-4">

        <!-- Sidebar -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center p-4">
                <!-- Avatar -->
                <div class="position-relative d-inline-block mx-auto mb-3">
                    <img src="<?= getAvatarUrl($user->avatar ?? ''); ?>"
                         alt="Avatar" id="avatarPreview"
                         class="rounded-circle" style="width:120px;height:120px;object-fit:cover;border:4px solid #4e73df;">
                    <label for="avatarInput"
                           class="position-absolute bottom-0 end-0 btn btn-sm btn-primary rounded-circle"
                           style="width:34px;height:34px;padding:0;display:flex;align-items:center;justify-content:center;cursor:pointer;">
                        <i class="fa-solid fa-camera" style="font-size:14px;"></i>
                    </label>
                </div>
                <h5 class="fw-bold mb-0"><?= htmlspecialchars($user->full_name ?: $user->username); ?></h5>
                <span class="badge <?= $user->role === 'admin' ? 'bg-danger' : 'bg-primary'; ?> mt-1">
                    <?= $user->role === 'admin' ? 'Admin' : 'Khách hàng'; ?>
                </span>
                <p class="text-muted small mt-2 mb-0"><?= htmlspecialchars($user->email); ?></p>
                <hr>
                <nav class="d-flex flex-column gap-1">
                    <a href="/project1/User/profile" class="btn btn-outline-primary btn-sm rounded-3 text-start">
                        <i class="fa-solid fa-user me-2"></i>Hồ sơ cá nhân
                    </a>
                    <a href="/project1/User/changePassword" class="btn btn-outline-secondary btn-sm rounded-3 text-start">
                        <i class="fa-solid fa-key me-2"></i>Đổi mật khẩu
                    </a>
                    <a href="/project1/Product/myOrders" class="btn btn-outline-secondary btn-sm rounded-3 text-start">
                        <i class="fa-solid fa-box me-2"></i>Đơn hàng của tôi
                    </a>
                    <a href="/project1/User/logout" class="btn btn-outline-danger btn-sm rounded-3 text-start">
                        <i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main content -->
        <div class="col-md-8">
            <!-- Flash messages -->
            <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                <?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            <?php if (!empty($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                <?= htmlspecialchars($_SESSION['flash_error']); unset($_SESSION['flash_error']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
            <div class="alert alert-danger rounded-3">
                <?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e); ?></div><?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Upload avatar (hidden form) -->
            <form id="avatarForm" method="POST" action="/project1/User/uploadAvatar" enctype="multipart/form-data">
                <input type="file" id="avatarInput" name="avatar" accept="image/*" style="display:none;"
                       onchange="previewAndUpload(this)">
            </form>

            <!-- Thông tin cá nhân -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 rounded-4 pt-4 pb-0 px-4">
                    <h5 class="fw-bold"><i class="fa-solid fa-user-circle me-2 text-primary"></i>Thông tin cá nhân</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form method="POST" action="/project1/User/updateProfile">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">TÊN ĐĂNG NHẬP</label>
                                <input type="text" class="form-control bg-light" value="<?= htmlspecialchars($user->username); ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">EMAIL</label>
                                <input type="email" class="form-control bg-light" value="<?= htmlspecialchars($user->email); ?>" disabled>
                                <?php if ($user->email_verified_at): ?>
                                <small class="text-success"><i class="fa-solid fa-circle-check me-1"></i>Đã xác thực</small>
                                <?php else: ?>
                                <div class="d-flex align-items-center gap-2 mt-1">
                                    <small class="text-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i>Chưa xác thực</small>
                                    <a href="/project1/User/resendVerification" class="btn btn-warning btn-sm py-0 px-2" style="font-size:12px;">
                                        <i class="fa-solid fa-paper-plane me-1"></i>Gửi lại xác thực
                                    </a>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">HỌ VÀ TÊN <span class="text-danger">*</span></label>
                                <input type="text" name="full_name" class="form-control"
                                       value="<?= htmlspecialchars($user->full_name ?? ''); ?>"
                                       placeholder="Nhập họ tên đầy đủ">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">SỐ ĐIỆN THOẠI</label>
                                <input type="tel" name="phone" class="form-control"
                                       value="<?= htmlspecialchars($user->phone ?? ''); ?>"
                                       placeholder="0901234567">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">ĐĂNG NHẬP CUỐI</label>
                                <input type="text" class="form-control bg-light"
                                       value="<?= $user->last_login ? date('H:i d/m/Y', strtotime($user->last_login)) : 'Chưa có'; ?>" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted small">NGÀY ĐĂNG KÝ</label>
                                <input type="text" class="form-control bg-light"
                                       value="<?= date('d/m/Y', strtotime($user->created_at)); ?>" disabled>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary rounded-3 px-4">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu thay đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Trạng thái tài khoản -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body px-4 py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <span class="fw-semibold">Trạng thái tài khoản:</span>
                        <?php
                        $statusMap = ['active'=>['success','Hoạt động'], 'locked'=>['danger','Đã khóa'], 'unverified'=>['warning','Chờ xác thực']];
                        [$color, $label] = $statusMap[$user->status] ?? ['secondary','Không rõ'];
                        ?>
                        <span class="badge bg-<?= $color; ?> ms-2"><?= $label; ?></span>
                    </div>
                    <div class="text-muted small">
                        <i class="fa-solid fa-shield-halved me-1"></i>
                        Vai trò: <strong><?= ucfirst($user->role); ?></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewAndUpload(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
        // Auto submit
        setTimeout(() => document.getElementById('avatarForm').submit(), 300);
    }
}
</script>

<?php include 'app/views/shares/footer.php'; ?>
