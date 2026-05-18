<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">

    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-header bg-success text-white p-4">

            <h2 class="mb-0">
                <i class="fa-solid fa-plus"></i>
                Thêm sản phẩm mới
            </h2>

        </div>

        <div class="card-body p-5">

            <form method="POST"
            action="/project1/Product/save"
            enctype="multipart/form-data">

                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Tên sản phẩm
                    </label>

                    <input type="text"
                    name="name"
                    class="form-control form-control-lg"
                    required>

                </div>

                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Mô tả
                    </label>

                    <textarea
                    name="description"
                    class="form-control"
                    rows="5"></textarea>

                </div>

                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Giá sản phẩm
                    </label>

                    <input type="number"
                    name="price"
                    class="form-control form-control-lg"
                    required>

                </div>

                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Danh mục
                    </label>

                    <select name="category_id"
                    class="form-select form-select-lg">

                        <?php foreach($categories as $category): ?>

                        <option value="<?php echo $category->id; ?>">

                            <?php echo $category->name; ?>

                        </option>

                        <?php endforeach; ?>

                    </select>

                </div>

                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Hình ảnh
                    </label>

                    <input type="file"
                    name="image"
                    class="form-control">

                </div>

                <button class="btn btn-success btn-lg">

                    <i class="fa-solid fa-plus"></i>
                    Thêm sản phẩm

                </button>

                <a href="/project1/Product/list"
                class="btn btn-secondary btn-lg">

                    Quay lại

                </a>

            </form>

        </div>

    </div>

</div>

<?php include 'app/views/shares/footer.php'; ?>