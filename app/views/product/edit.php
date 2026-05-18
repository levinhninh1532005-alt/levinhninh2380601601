<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">

    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-header bg-primary text-white p-4">

            <h2 class="mb-0">

                <i class="fa-solid fa-pen"></i>
                Sửa sản phẩm

            </h2>

        </div>

        <div class="card-body p-5">

            <form method="POST"
            action="/project1/Product/update"
            enctype="multipart/form-data">

                <input type="hidden"
                name="id"
                value="<?php echo $product->id; ?>">

                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Tên sản phẩm
                    </label>

                    <input type="text"
                    name="name"
                    class="form-control form-control-lg"
                    value="<?php echo $product->name; ?>">

                </div>

                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Mô tả
                    </label>

                    <textarea
                    name="description"
                    class="form-control"
                    rows="5"><?php echo $product->description; ?></textarea>

                </div>

                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Giá sản phẩm
                    </label>

                    <input type="number"
                    name="price"
                    class="form-control form-control-lg"
                    value="<?php echo $product->price; ?>">

                </div>

                <div class="mb-4">

                    <label class="form-label fw-bold">
                        Danh mục
                    </label>

                    <select name="category_id"
                    class="form-select form-select-lg">

                        <?php foreach($categories as $category): ?>

                        <option
                        value="<?php echo $category->id; ?>"
                        <?php echo ($category->id == $product->category_id) ? 'selected' : ''; ?>>

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

                    <br>

                    <?php if($product->image): ?>

                        <img
                        src="/project1/<?php echo $product->image; ?>"
                        width="180"
                        class="rounded shadow">

                    <?php endif; ?>

                </div>

                <button class="btn btn-primary btn-lg">

                    <i class="fa-solid fa-save"></i>
                    Lưu thay đổi

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