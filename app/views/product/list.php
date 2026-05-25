<?php include 'app/views/shares/header.php'; ?>

<div class="hero">
    <div class="container">

        <h1 class="fw-bold">Danh sách sản phẩm</h1>
        <div class="container mt-4">

    <form method="GET" action="/project1/Product/list"
    class="row g-2 align-items-center">

        <!-- SEARCH -->
        <div class="col-md-5">
            <input type="text"
            name="keyword"
            class="form-control"
            placeholder="🔍 Tìm kiếm sản phẩm..."
            value="<?php echo $_GET['keyword'] ?? ''; ?>">
        </div>

        <!-- CATEGORY FILTER -->
        <div class="col-md-4">

            <select name="category_id" class="form-select">

                <option value="">-- Tất cả danh mục --</option>

                <?php foreach ($categories as $category): ?>

                    <option value="<?php echo $category->id; ?>"
                        <?php echo (isset($_GET['category_id']) 
                        && $_GET['category_id'] == $category->id) ? 'selected' : ''; ?>>

                        <?php echo $category->name; ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <!-- BUTTON -->
        <div class="col-md-3">
            <button class="btn btn-primary w-100">
                🔎 Lọc sản phẩm
            </button>
        </div>

    </form>

</div>
        <p class="lead">
            Quản lý sản phẩm hiện đại và chuyên nghiệp
        </p>

        <a href="/project1/Product/add"
        class="btn btn-warning btn-lg mt-3">

            <i class="fa-solid fa-plus"></i>
            Thêm sản phẩm

        </a>

    </div>
</div>

<div class="container mb-5">

    <div class="row">

        <?php foreach ($products as $product): ?>

        <div class="col-lg-4 col-md-6 mb-4">

            <div class="card product-card h-100 border-0 shadow">

                <!-- IMAGE -->
                <a href="/project1/Product/show/<?php echo $product->id; ?>">

                    <?php if ($product->image): ?>

                        <img
                        src="/project1/<?php echo $product->image; ?>"
                        class="card-img-top"
                        style="height:250px; object-fit:cover;">

                    <?php else: ?>

                        <img
                        src="https://via.placeholder.com/400x250"
                        class="card-img-top">

                    <?php endif; ?>

                </a>

                <!-- BODY -->
                <div class="card-body">

                    <h4 class="fw-bold">

                        <a href="/project1/Product/show/<?php echo $product->id; ?>"
                        class="text-decoration-none text-dark">

                            <?php echo htmlspecialchars($product->name); ?>

                        </a>

                    </h4>

                    <span class="badge bg-primary mb-2">

                        <?php echo htmlspecialchars($product->category_name); ?>

                    </span>

                    <p class="text-muted">

                        <?php
                        echo substr(
                            htmlspecialchars($product->description),
                            0,
                            80
                        );
                        ?>...

                    </p>

                    <h3 class="text-danger fw-bold">

                        <?php
                        echo number_format(
                            $product->price,
                            0,
                            ',',
                            '.'
                        );
                        ?> VND

                    </h3>

                </div>

                <!-- FOOTER -->
<div class="card-footer bg-white border-0 pb-4">

<!-- FORM ADD TO CART -->
<form action="/project1/Product/addToCart/<?php echo $product->id; ?>"
      method="POST"
      class="mb-3">

    <div class="d-flex gap-2">

        <!-- SỐ LƯỢNG -->
        <input type="number"
               name="quantity"
               value="1"
               min="1"
               class="form-control"
               style="width:90px;">

        <!-- ADD TO CART -->
        <button type="submit"
                class="btn btn-warning flex-grow-1">

            <i class="fa-solid fa-cart-shopping"></i>
            Thêm giỏ hàng

        </button>

    </div>

</form>

<!-- ACTION BUTTON -->
<div class="d-flex gap-2 flex-wrap">

    <a href="/project1/Product/show/<?php echo $product->id; ?>"
    class="btn btn-success">

        <i class="fa-solid fa-eye"></i>
        Xem

    </a>

    <a href="/project1/Product/edit/<?php echo $product->id; ?>"
    class="btn btn-primary">

        <i class="fa-solid fa-pen"></i>
        Sửa

    </a>

    <a href="/project1/Product/delete/<?php echo $product->id; ?>"
    class="btn btn-danger"
    onclick="return confirm('Bạn có chắc muốn xóa?')">

        <i class="fa-solid fa-trash"></i>
        Xóa

    </a>

</div>

</div>

            </div>

        </div>

        <?php endforeach; ?>

    </div>

</div>

<?php include 'app/views/shares/footer.php'; ?>