<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0 rounded-4">

        <div class="card-header bg-success text-white p-4">
            <h2 class="mb-0"><i class="fa-solid fa-plus"></i> Thêm sản phẩm mới</h2>
        </div>

        <div class="card-body p-5">

            <div id="alert-box"></div>

            <div class="mb-4">
                <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                <input type="text" id="name" class="form-control form-control-lg">
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Mô tả <span class="text-danger">*</span></label>
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

            <div class="mb-4">
                <label class="form-label fw-bold">Hình ảnh</label>
                <input type="file" id="image-file" class="form-control" accept="image/*">
                <div id="image-preview" class="mt-2"></div>
            </div>

            <button id="btn-save" class="btn btn-success btn-lg">
                <i class="fa-solid fa-plus"></i> Thêm sản phẩm
            </button>
            <a href="/project1/Product/list" class="btn btn-secondary btn-lg">Quay lại</a>

        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
(function ($) {
    'use strict';

    $(document).ready(function () {

        // Tải danh mục từ API
        $.getJSON('/project1/api/category', function (data) {
            $.each(data, function (i, cat) {
                $('#category_id').append($('<option>').val(cat.id).text(cat.name));
            });
        });

        // Preview ảnh ngay khi chọn
        $('#image-file').on('change', function () {
            var file = this.files && this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#image-preview').html(
                        '<img src="' + e.target.result + '" width="180" class="rounded shadow mt-2" alt="preview">'
                    );
                };
                reader.readAsDataURL(file);
            } else {
                $('#image-preview').html('');
            }
        });

        // Thêm sản phẩm qua API
        $('#btn-save').on('click', function () {
            var name  = $.trim($('#name').val());
            var desc  = $.trim($('#description').val());
            var price = $('#price').val();

            if (!name || !price) {
                showAlert('danger', 'Vui lòng điền đầy đủ tên và giá sản phẩm');
                return;
            }

            var fileInput = document.getElementById('image-file');
            var hasFile   = fileInput && fileInput.files && fileInput.files.length > 0;

            if (hasFile) {
                // Có file → gửi FormData (multipart)
                var fd = new FormData();
                fd.append('name',        name);
                fd.append('description', desc);
                fd.append('price',       price);
                fd.append('category_id', $('#category_id').val());
                fd.append('image',       fileInput.files[0]);

                $.ajax({
                    url:         '/project1/api/product',
                    method:      'POST',
                    data:        fd,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if (res.message === 'Product created successfully') {
                            window.location.href = '/project1/Product/list';
                        } else {
                            showAlert('danger', 'Thêm sản phẩm thất bại');
                        }
                    },
                    error: function (xhr) { handleError(xhr); }
                });
            } else {
                // Không có file → gửi JSON
                $.ajax({
                    url:         '/project1/api/product',
                    method:      'POST',
                    contentType: 'application/json',
                    data:        JSON.stringify({ name: name, description: desc, price: price, category_id: $('#category_id').val() }),
                    success: function (res) {
                        if (res.message === 'Product created successfully') {
                            window.location.href = '/project1/Product/list';
                        } else {
                            showAlert('danger', 'Thêm sản phẩm thất bại');
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
                : 'Có lỗi xảy ra, vui lòng thử lại';
            showAlert('danger', msg);
        }

        function showAlert(type, msg) {
            $('#alert-box').html('<div class="alert alert-' + type + '">' + msg + '</div>');
        }
    });

})(jQuery);
</script>
