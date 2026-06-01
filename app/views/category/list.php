<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fa-solid fa-tags me-2"></i>Danh mục sản phẩm</h2>
        <a href="/project1/Product/list" class="btn btn-primary">
            <i class="fa-solid fa-arrow-left me-1"></i> Về danh sách sản phẩm
        </a>
    </div>

    <?php if (empty($categories)): ?>
        <div class="alert alert-info">Chưa có danh mục nào.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($categories as $cat): ?>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="fa-solid fa-folder me-2 text-primary"></i>
                                <?php echo htmlspecialchars($cat->name); ?>
                            </h5>
                            <a href="/project1/Product/list?category_id=<?php echo $cat->id; ?>" class="btn btn-outline-primary btn-sm mt-2">
                                Xem sản phẩm
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
