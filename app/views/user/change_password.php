<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 rounded-top-4 pt-4 pb-0 px-4">
                    <h5 class="fw-bold"><i class="fa-solid fa-key me-2 text-warning"></i>Đổi mật khẩu</h5>
                    <p class="text-muted small">Mật khẩu mới phải khác mật khẩu hiện tại và có ít nhất 6 ký tự.</p>
                </div>
                <div class="card-body px-4 pb-4">
                    <?php if (!empty($_SESSION['flash_success'])): ?>
                    <div class="alert alert-success rounded-3">
                        <?= htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger rounded-3">
                        <?php foreach ($errors as $e): ?><div><?= htmlspecialchars($e); ?></div><?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="/project1/User/changePasswordPost">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small">MẬT KHẨU HIỆN TẠI</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="current_password" class="form-control"
                                       placeholder="Nhập mật khẩu hiện tại" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-muted small">MẬT KHẨU MỚI</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-lock-open text-muted"></i></span>
                                <input type="password" name="new_password" id="newPwd" class="form-control"
                                       placeholder="Ít nhất 6 ký tự" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted small">XÁC NHẬN MẬT KHẨU MỚI</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="confirm_password" id="confirmPwd" class="form-control"
                                       placeholder="Nhập lại mật khẩu mới" required oninput="checkMatch()">
                            </div>
                            <small id="matchMsg"></small>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning rounded-3 px-4 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Đổi mật khẩu
                            </button>
                            <a href="/project1/User/profile" class="btn btn-outline-secondary rounded-3 px-4">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function checkMatch() {
    const np = document.getElementById('newPwd').value;
    const cp = document.getElementById('confirmPwd').value;
    const msg = document.getElementById('matchMsg');
    if (cp && np !== cp) {
        msg.innerHTML = '<span class="text-danger"><i class="fa-solid fa-xmark me-1"></i>Mật khẩu không khớp</span>';
    } else if (cp) {
        msg.innerHTML = '<span class="text-success"><i class="fa-solid fa-check me-1"></i>Mật khẩu khớp</span>';
    } else {
        msg.innerHTML = '';
    }
}
</script>

<?php include 'app/views/shares/footer.php'; ?>
