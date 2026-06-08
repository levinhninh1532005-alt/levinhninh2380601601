<?php include 'app/views/shares/header.php'; ?>

<div class="container-fluid mt-4 mb-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold"><i class="fa-solid fa-box"></i> Quản lý sản phẩm</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalAdd">
            <i class="fa-solid fa-plus"></i> Thêm sản phẩm
        </button>
    </div>

    <!-- Thanh tìm kiếm -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-2">
                <div class="col-md-5">
                    <input type="text" id="search-keyword" class="form-control" placeholder="🔍 Tìm theo tên, mô tả...">
                </div>
                <div class="col-md-4">
                    <select id="search-category" class="form-select">
                        <option value="">-- Tất cả danh mục --</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button id="btn-filter" class="btn btn-primary w-100">Lọc</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng sản phẩm -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0" id="product-table">
                <thead class="table-dark">
                    <tr>
                        <th style="width:60px">ID</th>
                        <th style="width:80px">Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th style="width:160px">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="product-tbody">
                    <tr><td colspan="6" class="text-center py-4">
                        <div class="spinner-border text-primary"></div>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ====== MODAL THÊM ====== -->
<div class="modal fade" id="modalAdd" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fa-solid fa-plus"></i> Thêm sản phẩm</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="add-alert"></div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" id="add-name" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả <span class="text-danger">*</span></label>
                    <textarea id="add-description" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Giá <span class="text-danger">*</span></label>
                    <input type="number" id="add-price" class="form-control" min="0">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Danh mục</label>
                    <select id="add-category" class="form-select"></select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Hình ảnh</label>
                    <input type="file" id="add-image-file" class="form-control" accept="image/*">
                    <div id="add-image-preview" class="mt-2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button class="btn btn-success" id="btn-add-save">
                    <i class="fa-solid fa-plus"></i> Thêm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ====== MODAL SỬA ====== -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fa-solid fa-pen"></i> Sửa sản phẩm</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="edit-alert"></div>
                <input type="hidden" id="edit-id">
                <div class="mb-3">
                    <label class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" id="edit-name" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Mô tả</label>
                    <textarea id="edit-description" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Giá <span class="text-danger">*</span></label>
                    <input type="number" id="edit-price" class="form-control" min="0">
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Danh mục</label>
                    <select id="edit-category" class="form-select"></select>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Ảnh hiện tại</label>
                    <div id="edit-image-current" class="mb-2"></div>
                    <label class="form-label">Thay ảnh mới (để trống nếu giữ nguyên)</label>
                    <input type="file" id="edit-image-file" class="form-control" accept="image/*">
                    <div id="edit-image-preview" class="mt-2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button class="btn btn-primary" id="btn-edit-save">
                    <i class="fa-solid fa-save"></i> Lưu
                </button>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
/* ===== Admin Products — jQuery + API (không xung đột Bootstrap/global) ===== */
(function ($) {
    'use strict';

    var allProducts  = [];
    var allCategories = [];

    /* ---- Khởi động ---- */
    $(document).ready(function () {

        // Load danh mục
        $.getJSON('/project1/api/category', function (data) {
            allCategories = data;
            $.each(data, function (i, cat) {
                var opt = $('<option>').val(cat.id).text(cat.name);
                $('#search-category').append(opt.clone());
                $('#add-category').append(opt.clone());
                $('#edit-category').append(opt.clone());
            });
        });

        // Load sản phẩm
        loadProducts();

        /* ---- Lọc ---- */
        $('#btn-filter').on('click', filterProducts);
        $('#search-keyword').on('keyup', function (e) {
            if (e.key === 'Enter') filterProducts();
        });

        /* ---- Preview ảnh khi chọn file (modal Thêm) ---- */
        $('#add-image-file').on('change', function () {
            previewFile(this, '#add-image-preview');
        });

        /* ---- Preview ảnh khi chọn file (modal Sửa) ---- */
        $('#edit-image-file').on('change', function () {
            previewFile(this, '#edit-image-preview');
        });

        /* ---- THÊM sản phẩm ---- */
        $('#btn-add-save').on('click', function () {
            var name = $.trim($('#add-name').val());
            var desc = $.trim($('#add-description').val());
            var price = $('#add-price').val();

            if (!name || !price) {
                adminAlert('#add-alert', 'danger', 'Vui lòng điền đầy đủ tên và giá');
                return;
            }

            var fileInput = document.getElementById('add-image-file');
            var hasFile   = fileInput && fileInput.files && fileInput.files.length > 0;

            if (hasFile) {
                // Gửi FormData (có file)
                var fd = new FormData();
                fd.append('name',        name);
                fd.append('description', desc);
                fd.append('price',       price);
                fd.append('category_id', $('#add-category').val());
                fd.append('image',       fileInput.files[0]);

                $.ajax({
                    url:         '/project1/api/product',
                    method:      'POST',
                    data:        fd,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        onAddSuccess(res);
                    },
                    error: function (xhr) {
                        onApiError('#add-alert', xhr);
                    }
                });
            } else {
                // Gửi JSON (không có file)
                $.ajax({
                    url:         '/project1/api/product',
                    method:      'POST',
                    contentType: 'application/json',
                    data:        JSON.stringify({
                        name:        name,
                        description: desc,
                        price:       price,
                        category_id: $('#add-category').val()
                    }),
                    success: function (res) {
                        onAddSuccess(res);
                    },
                    error: function (xhr) {
                        onApiError('#add-alert', xhr);
                    }
                });
            }
        });

        function onAddSuccess(res) {
            if (res.message === 'Product created successfully') {
                $('#modalAdd').modal('hide');
                loadProducts();
            } else {
                adminAlert('#add-alert', 'danger', 'Thêm thất bại');
            }
        }

        // Reset modal thêm khi đóng
        $('#modalAdd').on('hidden.bs.modal', function () {
            $('#add-alert').html('');
            $('#add-name, #add-description, #add-price').val('');
            $('#add-image-file').val('');
            $('#add-image-preview').html('');
        });

        /* ---- LƯU SỬA sản phẩm ---- */
        $('#btn-edit-save').on('click', function () {
            var id    = $('#edit-id').val();
            var name  = $.trim($('#edit-name').val());
            var desc  = $.trim($('#edit-description').val());
            var price = $('#edit-price').val();

            if (!name || !price) {
                adminAlert('#edit-alert', 'danger', 'Vui lòng điền đầy đủ tên và giá');
                return;
            }

            var fileInput = document.getElementById('edit-image-file');
            var hasFile   = fileInput && fileInput.files && fileInput.files.length > 0;

            if (hasFile) {
                // FormData với _method=PUT (method spoofing vì browser không gửi PUT+file)
                var fd = new FormData();
                fd.append('_method',     'PUT');
                fd.append('name',        name);
                fd.append('description', desc);
                fd.append('price',       price);
                fd.append('category_id', $('#edit-category').val());
                fd.append('image',       fileInput.files[0]);

                $.ajax({
                    url:         '/project1/api/product/' + id,
                    method:      'POST',         // POST + _method=PUT
                    data:        fd,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        onEditSuccess(res);
                    },
                    error: function (xhr) {
                        onApiError('#edit-alert', xhr);
                    }
                });
            } else {
                // JSON (giữ ảnh cũ, không gửi field image)
                $.ajax({
                    url:         '/project1/api/product/' + id,
                    method:      'PUT',
                    contentType: 'application/json',
                    data:        JSON.stringify({
                        name:        name,
                        description: desc,
                        price:       price,
                        category_id: $('#edit-category').val()
                    }),
                    success: function (res) {
                        onEditSuccess(res);
                    },
                    error: function (xhr) {
                        onApiError('#edit-alert', xhr);
                    }
                });
            }
        });

        function onEditSuccess(res) {
            if (res.message === 'Product updated successfully') {
                $('#modalEdit').modal('hide');
                loadProducts();
            } else {
                adminAlert('#edit-alert', 'danger', 'Cập nhật thất bại');
            }
        }

        // Reset modal sửa khi đóng
        $('#modalEdit').on('hidden.bs.modal', function () {
            $('#edit-alert').html('');
            $('#edit-image-file').val('');
            $('#edit-image-preview').html('');
        });

    }); // end ready

    /* ---- Helpers ---- */

    function previewFile(input, targetSelector) {
        var file = input.files && input.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(targetSelector).html(
                    '<img src="' + e.target.result + '" width="140" class="rounded shadow mt-1" alt="preview">'
                );
            };
            reader.readAsDataURL(file);
        } else {
            $(targetSelector).html('');
        }
    }

    function loadProducts() {
        $('#product-tbody').html(
            '<tr><td colspan="6" class="text-center py-4"><div class="spinner-border text-primary"></div></td></tr>'
        );
        $.getJSON('/project1/api/product', function (data) {
            allProducts = data;
            renderTable(data);
        }).fail(function () {
            $('#product-tbody').html(
                '<tr><td colspan="6" class="text-center text-danger py-3">Không thể tải dữ liệu</td></tr>'
            );
        });
    }

    function filterProducts() {
        var keyword    = $('#search-keyword').val().toLowerCase();
        var categoryId = $('#search-category').val();
        var filtered   = $.grep(allProducts, function (p) {
            var matchKw  = !keyword ||
                p.name.toLowerCase().indexOf(keyword) >= 0 ||
                (p.description && p.description.toLowerCase().indexOf(keyword) >= 0);
            var matchCat = !categoryId || String(p.category_id) === String(categoryId);
            return matchKw && matchCat;
        });
        renderTable(filtered);
    }

    function renderTable(products) {
        var tbody = $('#product-tbody');
        tbody.empty();
        if (!products || products.length === 0) {
            tbody.html('<tr><td colspan="6" class="text-center text-muted py-4">Không có sản phẩm nào</td></tr>');
            return;
        }
        $.each(products, function (i, p) {
            var imgSrc = p.image
                ? '/project1/' + p.image
                : 'https://via.placeholder.com/50x50?text=No+img';
            tbody.append(
                '<tr>' +
                    '<td>' + p.id + '</td>' +
                    '<td>' +
                        '<img src="' + imgSrc + '" width="50" height="50" ' +
                             'style="object-fit:cover;border-radius:6px;" ' +
                             'onerror="this.src=\'https://via.placeholder.com/50x50?text=No+img\'" ' +
                             'alt="' + escHtml(p.name) + '">' +
                    '</td>' +
                    '<td>' +
                        '<strong>' + escHtml(p.name) + '</strong><br>' +
                        '<small class="text-muted">' + escHtml((p.description || '').substring(0, 60)) + '...</small>' +
                    '</td>' +
                    '<td><span class="badge bg-primary">' + escHtml(p.category_name || '') + '</span></td>' +
                    '<td class="text-danger fw-bold">' + Number(p.price).toLocaleString('vi-VN') + ' đ</td>' +
                    '<td>' +
                        '<button class="btn btn-sm btn-warning me-1 btn-open-edit" data-id="' + p.id + '">' +
                            '<i class="fa-solid fa-pen"></i>' +
                        '</button>' +
                        '<button class="btn btn-sm btn-danger btn-delete-product" data-id="' + p.id + '" data-name="' + escHtml(p.name) + '">' +
                            '<i class="fa-solid fa-trash"></i>' +
                        '</button>' +
                    '</td>' +
                '</tr>'
            );
        });

        // Gán sự kiện sau khi render (event delegation)
        tbody.find('.btn-open-edit').on('click', function () {
            openEdit($(this).data('id'));
        });
        tbody.find('.btn-delete-product').on('click', function () {
            deleteProduct($(this).data('id'), $(this).data('name'));
        });
    }

    function openEdit(id) {
        $('#edit-alert').html('');
        $('#edit-image-file').val('');
        $('#edit-image-preview').html('');

        $.getJSON('/project1/api/product/' + id, function (p) {
            $('#edit-id').val(p.id);
            $('#edit-name').val(p.name);
            $('#edit-description').val(p.description);
            $('#edit-price').val(p.price);
            $('#edit-category').val(p.category_id);

            if (p.image) {
                $('#edit-image-current').html(
                    '<img src="/project1/' + p.image + '" width="100" class="rounded shadow" alt="current">' +
                    '<br><small class="text-muted mt-1 d-block">Ảnh hiện tại</small>'
                );
            } else {
                $('#edit-image-current').html('<span class="text-muted">Chưa có ảnh</span>');
            }

            $('#modalEdit').modal('show');
        }).fail(function () {
            alert('Không thể tải thông tin sản phẩm');
        });
    }

    function deleteProduct(id, name) {
        if (!confirm('Xóa sản phẩm "' + name + '"?')) return;
        $.ajax({
            url:    '/project1/api/product/' + id,
            method: 'DELETE',
            success: function (res) {
                if (res.message === 'Product deleted successfully') {
                    allProducts = $.grep(allProducts, function (p) { return p.id != id; });
                    renderTable(allProducts);
                } else {
                    alert('Xóa thất bại');
                }
            },
            error: function () { alert('Xóa thất bại, vui lòng thử lại'); }
        });
    }

    function adminAlert(selector, type, msg) {
        $(selector).html('<div class="alert alert-' + type + ' py-2 mb-0">' + msg + '</div>');
    }

    function onApiError(alertSelector, xhr) {
        var res = xhr.responseJSON;
        var msg = (res && res.errors)
            ? Object.values(res.errors).join('<br>')
            : (res && res.message ? res.message : 'Có lỗi xảy ra');
        adminAlert(alertSelector, 'danger', msg);
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

})(jQuery); // IIFE tránh xung đột $ toàn cục
</script>
