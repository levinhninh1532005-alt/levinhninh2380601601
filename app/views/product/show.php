<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">

    <?php if ($product): ?>

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

        <div class="row g-0">

            <!-- IMAGE -->
            <div class="col-md-5 bg-light d-flex align-items-center justify-content-center p-4">

                <?php if ($product->image): ?>

                    <img
                    src="/project1/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>"
                    class="img-fluid rounded-4 shadow"
                    style="max-height: 450px; object-fit: cover;"
                    alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">

                <?php else: ?>

                    <img
                    src="https://via.placeholder.com/500x400?text=No+Image"
                    class="img-fluid rounded-4 shadow">

                <?php endif; ?>

            </div>

            <!-- PRODUCT INFO -->
            <div class="col-md-7">

                <div class="card-body p-5">

                    <!-- CATEGORY -->
                    <span class="badge bg-primary mb-3 px-3 py-2 fs-6">

                        <?php
                        echo !empty($product->category_name)
                        ? htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8')
                        : 'Chưa có danh mục';
                        ?>

                    </span>

                    <!-- TITLE -->
                    <h1 class="fw-bold mb-4 text-dark">

                        <?php
                        echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8');
                        ?>

                    </h1>

                    <!-- PRICE -->
                    <h2 class="text-danger fw-bold mb-4">

                        <?php
                        echo number_format($product->price, 0, ',', '.');
                        ?> VND

                    </h2>

                    <!-- DESCRIPTION -->
                    <div class="mb-4">

                        <h5 class="fw-bold text-secondary mb-3">
                            Mô tả sản phẩm
                        </h5>

                        <p class="text-muted lh-lg">

                            <?php
                            echo nl2br(htmlspecialchars(
                                $product->description,
                                ENT_QUOTES,
                                'UTF-8'
                            ));
                            ?>

                        </p>

                    </div>

                    <!-- BUTTONS -->
                    <div class="d-flex flex-wrap gap-3 mt-5">

                        <?php if (!$_isAdmin): ?>
                        <form action="/project1/Product/addToCart/<?php echo $product->id; ?>" method="POST" class="d-inline">
                            <input type="number" name="quantity" value="1" min="1" class="form-control d-inline" style="width:80px;">
                            <button type="submit" class="btn btn-success btn-lg px-4 ms-2">
                                <i class="fa-solid fa-cart-shopping"></i>
                                Thêm vào giỏ hàng
                            </button>
                        </form>
                        <?php endif; ?>

                        <?php if ($_isAdmin): ?>
                        <a href="/project1/Product/edit/<?php echo $product->id; ?>"
                        class="btn btn-primary btn-lg px-4">
                            <i class="fa-solid fa-pen"></i>
                            Sửa sản phẩm
                        </a>
                        <?php endif; ?>

                        <a href="/project1/Product/list"
                        class="btn btn-outline-secondary btn-lg px-4">

                            <i class="fa-solid fa-arrow-left"></i>
                            Quay lại

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <?php else: ?>

        <div class="alert alert-danger text-center shadow p-5 rounded-4">

            <h2 class="fw-bold">
                Không tìm thấy sản phẩm!
            </h2>

            <a href="/project1/Product/list"
            class="btn btn-primary mt-3">

                Quay lại danh sách

            </a>

        </div>

    <?php endif; ?>

</div>

<?php include 'app/views/shares/footer.php'; ?>