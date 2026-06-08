<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-header bg-primary text-white p-4">
            <h2 class="mb-0"><i class="fa-solid fa-pen"></i> Sửa sản phẩm</h2>
        </div>

        <div class="card-body p-5">

            <div id="alert-box"></div>

            <input type="hidden" id="product-id" value="<?= (int)$editId ?>">

            <div class="mb-4">
                <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                <input type="text" id="name" class="form-control form-control-lg">
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Mô tả</label>
                <textarea id="description" class="form-control" rows="5"></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Giá sản phẩm <span class="text-danger">*</span></label>
                <input type="number" id="price" class="form-control form-control-lg" min="0">
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Danh mục</label>
                <select id="category_id" class="form-select form-select-lg"></select>
            </div>

            <!-- Ảnh hiện tại -->
            <div class="mb-3">
                <label class="form-label fw-bold">Ảnh hiện tại</label>
                <div id="current-image" class="mb-2"></div>
            </div>

            <!-- Thay ảnh mới -->
            <div class="mb-4">
                <label class="form-label fw-bold">Thay ảnh mới <span class="text-muted fw-normal">(để trống nếu giữ nguyên)</span></label>
                <input type="file" id="image-file" class="form-control" accept="image/*">
                <div id="image-preview" class="mt-2"></div>
            </div>

            <button id="btn-update" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-save"></i> Lưu thay đổi
            </button>
            <a href="/project1/Product/list" class="btn btn-secondary btn-lg">Quay lại</a>

        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
(function ($) {
    'use strict';

    var productId = <?= (int)$editId ?>;

    $(document).ready(function () {

        // Tải thông tin sản phẩm
        $.getJSON('/project1/api/product/' + productId, function (p) {
            $('#name').val(p.name);
            $('#description').val(p.description);
            $('#price').val(p.price);

            // Hiển thị ảnh hiện tại
            if (p.image) {
                $('#current-image').html(
                    '<img src="/project1/' + p.image + '" width="180" class="rounded shadow" alt="current">' +
                    '<br><small class="text-muted mt-1 d-block">Ảnh hiện tại</small>'
                );
            } else {
                $('#current-image').html('<span class="text-muted">Chưa có hình ảnh</span>');
            }

            // Tải danh mục, set selected
            $.getJSON('/project1/api/category', function (cats) {
                $.each(cats, function (i, cat) {
                    var opt = $('<option>').val(cat.id).text(cat.name);
                    if (cat.id == p.category_id) opt.prop('selected', true);
                    $('#category_id').append(opt);
                });
            });
        }).fail(function () {
            showAlert('danger', 'Không thể tải thông tin sản phẩm');
        });

        // Preview ảnh mới khi chọn file
        $('#image-file').on('change', function () {
            var file = this.files && this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#image-preview').html(
                        '<img src="' + e.target.result + '" width="180" class="rounded shadow mt-1" alt="preview">' +
                        '<br><small class="text-success mt-1 d-block"><i class="fa-solid fa-check me-1"></i>Ảnh mới đã chọn</small>'
                    );
                };
                reader.readAsDataURL(file);
            } else {
                $('#image-preview').html('');
            }
        });

        // Cập nhật sản phẩm
        $('#btn-update').on('click', function () {
            var name  = $.trim($('#name').val());
            var price = $('#price').val();

            if (!name || !price) {
                showAlert('danger', 'Vui lòng điền đầy đủ tên và giá sản phẩm');
                return;
            }

            var fileInput = document.getElementById('image-file');
            var hasFile   = fileInput && fileInput.files && fileInput.files.length > 0;

            if (hasFile) {
                // Có ảnh mới → FormData với _method=PUT
                var fd = new FormData();
                fd.append('_method',     'PUT');
                fd.append('name',        name);
                fd.append('description', $('#description').val());
                fd.append('price',       price);
                fd.append('category_id', $('#category_id').val());
                fd.append('image',       fileInput.files[0]);

                $.ajax({
                    url:         '/project1/api/product/' + productId,
                    method:      'POST',
                    data:        fd,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if (res.message === 'Product updated successfully') {
                            window.location.href = '/project1/Product/list';
                        } else {
                            showAlert('danger', 'Cập nhật thất bại');
                        }
                    },
                    error: function (xhr) { handleError(xhr); }
                });
            } else {
                // Không có ảnh mới → JSON, giữ ảnh cũ
                $.ajax({
                    url:         '/project1/api/product/' + productId,
                    method:      'PUT',
                    contentType: 'application/json',
                    data:        JSON.stringify({
                        name:        name,
                        description: $('#description').val(),
                        price:       price,
                        category_id: $('#category_id').val()
                    }),
                    success: function (res) {
                        if (res.message === 'Product updated successfully') {
                            window.location.href = '/project1/Product/list';
                        } else {
                            showAlert('danger', 'Cập nhật thất bại');
                        }
                    },
                    error: function (xhr) { handleError(xhr); }
                });
            }
        });

        function handleError(xhr) {
            var res = xhr.responseJSON;
            var msg = (res && res.errors)
                ? Object.values(res.errors).join('<br>')
                : (res && res.message ? res.message : 'Có lỗi xảy ra');
            showAlert('danger', msg);
        }

        function showAlert(type, msg) {
            $('#alert-box').html('<div class="alert alert-' + type + '">' + msg + '</div>');
        }
    });

})(jQuery);
</script>
