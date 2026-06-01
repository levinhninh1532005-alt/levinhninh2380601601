<?php
// Load helper nếu chưa load
if (!function_exists('isAdmin')) {
    require_once __DIR__ . '/../../helpers/auth_helper.php';
}
$_isAdmin    = isAdmin();
$_isLoggedIn = isLoggedIn();
$_curUser    = $_isLoggedIn ? currentUser() : null;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', sans-serif; }
        .navbar { background: linear-gradient(90deg,#4e73df,#224abe); }
        .navbar-brand, .nav-link { color:white !important; font-weight:500; }
        .hero {
            background: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)),
            url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=2070');
            background-size: cover; background-position: center;
            color:white; padding:80px 0; text-align:center; margin-bottom:40px;
        }
        .hero h1 { font-size:50px; font-weight:bold; }
        .product-card { border:none; border-radius:15px; overflow:hidden; transition:0.3s; box-shadow:0 4px 10px rgba(0,0,0,.1); }
        .product-card:hover { transform:translateY(-5px); box-shadow:0 8px 20px rgba(0,0,0,.2); }
        .product-card img { height:220px; object-fit:cover; }
        .price { color:#e74a3b; font-size:22px; font-weight:bold; }
        footer { background:#1f2937; color:white; margin-top:60px; padding:50px 0 20px; }
        footer h5 { margin-bottom:20px; font-weight:bold; }
        footer a { color:#d1d5db; text-decoration:none; }
        footer a:hover { color:white; }
        .admin-badge { background: rgba(255,193,7,0.25); border: 1px solid rgba(255,193,7,0.5); color: #ffc107; font-size: 12px; padding: 3px 10px; border-radius: 20px; font-weight: 600; }
        .user-avatar-nav { width:32px; height:32px; border-radius:50%; object-fit:cover; border:2px solid rgba(255,255,255,0.5); }
        .dropdown-menu { border-radius:12px; border:none; box-shadow:0 8px 24px rgba(0,0,0,0.12); }
    </style>
</head>
<body>

<!-- THÔNG BÁO FLASH -->
<?php if (isset($_SESSION['flash_success'])): ?>
<div class="alert alert-success alert-dismissible fade show m-0 rounded-0 text-center" role="alert">
    <i class="fa-solid fa-circle-check me-2"></i>
    <?php echo htmlspecialchars($_SESSION['flash_success']); unset($_SESSION['flash_success']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>
<?php if (isset($_SESSION['auth_error'])): ?>
<div class="alert alert-warning alert-dismissible fade show m-0 rounded-0 text-center" role="alert">
    <i class="fa-solid fa-triangle-exclamation me-2"></i>
    <?php echo htmlspecialchars($_SESSION['auth_error']); unset($_SESSION['auth_error']); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="/project1/Product/list">
            <i class="fa-solid fa-store"></i> MyStore
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center gap-1">

                <li class="nav-item">
                    <a class="nav-link" href="/project1/Product/list">
                        <i class="fa-solid fa-list me-1"></i>Sản phẩm
                    </a>
                </li>

                <?php if ($_isAdmin): ?>
                <!-- ===== ADMIN ===== -->
                <li class="nav-item">
                    <a class="nav-link" href="/project1/Product/add">
                        <i class="fa-solid fa-plus me-1"></i>Thêm sản phẩm
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/project1/Admin/users">
                        <i class="fa-solid fa-users-gear me-1"></i>Quản lý Users
                    </a>
                </li>
                <li class="nav-item d-flex align-items-center ms-2">
                    <span class="admin-badge">
                        <i class="fa-solid fa-shield-halved me-1"></i>
                        <?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?>
                    </span>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/project1/Auth/adminLogout">
                        <i class="fa-solid fa-right-from-bracket me-1"></i>Đăng xuất
                    </a>
                </li>

                <?php elseif ($_isLoggedIn && $_curUser): ?>
                <!-- ===== USER ĐÃ ĐĂNG NHẬP ===== -->
                <li class="nav-item">
                    <a class="nav-link" href="/project1/Product/cart">
                        <i class="fa-solid fa-cart-shopping me-1"></i>Giỏ hàng
                        <?php $cartCount = array_sum(array_column($_SESSION['cart'] ?? [], 'quantity')); if ($cartCount > 0): ?>
                        <span class="badge bg-warning text-dark"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/project1/Product/myOrders">
                        <i class="fa-solid fa-box me-1"></i>Đơn hàng
                    </a>
                </li>
                <!-- User dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                       data-bs-toggle="dropdown">
                        <img src="<?= getAvatarUrl($_curUser->avatar); ?>"
                             alt="avatar" class="user-avatar-nav">
                        <span><?= htmlspecialchars($_curUser->full_name ?: $_curUser->username); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li class="px-3 py-2">
                            <div class="fw-semibold"><?= htmlspecialchars($_curUser->full_name ?: $_curUser->username); ?></div>
                            <small class="text-muted"><?= htmlspecialchars($_curUser->email); ?></small>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/project1/User/profile"><i class="fa-solid fa-user me-2 text-primary"></i>Hồ sơ cá nhân</a></li>
                        <li><a class="dropdown-item" href="/project1/User/changePassword"><i class="fa-solid fa-key me-2 text-warning"></i>Đổi mật khẩu</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/project1/User/logout"><i class="fa-solid fa-right-from-bracket me-2"></i>Đăng xuất</a></li>
                    </ul>
                </li>

                <?php else: ?>
                <!-- ===== KHÁCH CHƯA ĐĂNG NHẬP ===== -->
                <li class="nav-item">
                    <a class="nav-link" href="/project1/Product/cart">
                        <i class="fa-solid fa-cart-shopping me-1"></i>Giỏ hàng
                        <?php $cartCount = array_sum(array_column($_SESSION['cart'] ?? [], 'quantity')); if ($cartCount > 0): ?>
                        <span class="badge bg-warning text-dark"><?php echo $cartCount; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/project1/User/login">
                        <i class="fa-solid fa-right-to-bracket me-1"></i>Đăng nhập
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/project1/User/register"
                       style="background:rgba(255,255,255,0.15); border-radius:20px; padding:6px 14px;">
                        <i class="fa-solid fa-user-plus me-1"></i>Đăng ký
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/project1/Auth/adminLogin" style="opacity:0.5; font-size:12px;">
                        <i class="fa-solid fa-lock me-1"></i>Admin
                    </a>
                </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
