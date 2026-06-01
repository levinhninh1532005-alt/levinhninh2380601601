<?php include 'app/views/shares/header.php'; ?>

<div class="container-fluid py-4 px-4">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-0"><i class="fa-solid fa-users-gear me-2 text-primary"></i>Quản lý người dùng</h4>
            <small class="text-muted">Tổng: <?= count($users); ?> tài khoản</small>
        </div>
        <div class="d-flex gap-2 align-items-center">
            <input type="text" id="searchInput" class="form-control form-control-sm rounded-3"
                   placeholder="Tìm kiếm..." style="width:200px;" oninput="filterTable()">
        </div>
    </div>

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

    <!-- Stats cards -->
    <?php
    $total     = count($users);
    $active    = count(array_filter($users, fn($u) => $u->status === 'active'));
    $locked    = count(array_filter($users, fn($u) => $u->status === 'locked'));
    $unverified= count(array_filter($users, fn($u) => $u->status === 'unverified'));
    ?>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-3">
                <div class="fs-2 fw-bold text-primary"><?= $total; ?></div>
                <div class="text-muted small">Tổng tài khoản</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-3">
                <div class="fs-2 fw-bold text-success"><?= $active; ?></div>
                <div class="text-muted small">Đang hoạt động</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-3">
                <div class="fs-2 fw-bold text-danger"><?= $locked; ?></div>
                <div class="text-muted small">Đã khóa</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 text-center py-3">
                <div class="fs-2 fw-bold text-warning"><?= $unverified; ?></div>
                <div class="text-muted small">Chờ xác thực</div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="userTable">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Người dùng</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Đăng nhập cuối</th>
                            <th>Ngày đăng ký</th>
                            <th class="text-center pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center gap-2">
                                <img src="<?= $u->avatar && file_exists($u->avatar)
                                    ? '/project1/' . $u->avatar
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($u->full_name ?: $u->username) . '&background=4e73df&color=fff&size=64'; ?>"
                                     class="rounded-circle" style="width:38px;height:38px;object-fit:cover;">
                                <div>
                                    <div class="fw-semibold"><?= htmlspecialchars($u->full_name ?: $u->username); ?></div>
                                    <small class="text-muted">@<?= htmlspecialchars($u->username); ?></small>
                                </div>
                            </div>
                        </td>
                        <td class="text-muted small"><?= htmlspecialchars($u->email); ?></td>
                        <td>
                            <?php if ($u->role === 'admin'): ?>
                            <span class="badge bg-danger"><i class="fa-solid fa-shield-halved me-1"></i>Admin</span>
                            <?php else: ?>
                            <span class="badge bg-secondary"><i class="fa-solid fa-user me-1"></i>User</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $map = ['active'=>'success','locked'=>'danger','unverified'=>'warning'];
                            $lbl = ['active'=>'Hoạt động','locked'=>'Đã khóa','unverified'=>'Chờ xác thực'];
                            $c   = $map[$u->status] ?? 'secondary';
                            $l   = $lbl[$u->status] ?? $u->status;
                            ?>
                            <span class="badge bg-<?= $c; ?>"><?= $l; ?></span>
                            <?php if ($u->failed_login_attempts > 0): ?>
                            <small class="text-muted ms-1">(<?= $u->failed_login_attempts; ?> lỗi)</small>
                            <?php endif; ?>
                        </td>
                        <td class="text-muted small">
                            <?= $u->last_login ? date('H:i d/m/Y', strtotime($u->last_login)) : '<em>Chưa đăng nhập</em>'; ?>
                        </td>
                        <td class="text-muted small"><?= date('d/m/Y', strtotime($u->created_at)); ?></td>
                        <td class="text-center pe-4">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="/project1/Admin/userDetail/<?= $u->id; ?>"
                                   class="btn btn-sm btn-outline-primary rounded-3" title="Xem chi tiết">
                                    <i class="fa-solid fa-eye"></i>
                                </a>
                                <?php if (!$u->email_verified_at): ?>
                                <a href="/project1/Admin/verifyUser/<?= $u->id; ?>"
                                   class="btn btn-sm btn-outline-success rounded-3"
                                   onclick="return confirm('Xác thực email cho tài khoản @<?= $u->username; ?>?')" title="Xác thực email">
                                    <i class="fa-solid fa-envelope-circle-check"></i>
                                </a>
                                <?php endif; ?>
                                <?php if ($u->id != ($current ?? 0) && $u->role !== 'admin'): ?>
                                    <?php if ($u->status === 'locked'): ?>
                                    <a href="/project1/Admin/unlockUser/<?= $u->id; ?>"
                                       class="btn btn-sm btn-outline-success rounded-3"
                                       onclick="return confirm('Mở khóa tài khoản này?')" title="Mở khóa">
                                        <i class="fa-solid fa-lock-open"></i>
                                    </a>
                                    <?php else: ?>
                                    <a href="/project1/Admin/lockUser/<?= $u->id; ?>"
                                       class="btn btn-sm btn-outline-danger rounded-3"
                                       onclick="return confirm('Khóa tài khoản này?')" title="Khóa">
                                        <i class="fa-solid fa-lock"></i>
                                    </a>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="btn btn-sm btn-outline-secondary rounded-3 disabled" title="Không thể thao tác">
                                        <i class="fa-solid fa-minus"></i>
                                    </span>
                                <?php endif; ?>
                                <?php if ($u->id != ($current ?? 0) && $u->role !== 'admin'): ?>
                                <a href="/project1/Admin/deleteUser/<?= $u->id; ?>"
                                   class="btn btn-sm btn-danger rounded-3"
                                   onclick="return confirm('Bạn có chắc muốn XÓA tài khoản @<?= $u->username; ?>? Hành động này không thể hoàn tác!')" title="Xóa">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function filterTable() {
    const val   = document.getElementById('searchInput').value.toLowerCase();
    const rows  = document.querySelectorAll('#userTable tbody tr');
    rows.forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
}
</script>

<?php include 'app/views/shares/footer.php'; ?>
