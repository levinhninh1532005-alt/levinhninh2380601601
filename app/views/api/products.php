<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm - API Client</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #f0f2f5; }
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card { border-radius: 12px; box-shadow: 0 2px 15px rgba(0,0,0,0.08); border: none; }
        .card-header { border-radius: 12px 12px 0 0 !important; font-weight: 600; }
        .table-hover tbody tr:hover { background: #f8f4ff; cursor: pointer; }
        .badge-category { background: #e8e0ff; color: #5c35d9; font-weight: 500; }
        #toast-container { position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 280px; }
        .toast { border-radius: 10px; }
        .price-col { color: #d63384; font-weight: 700; }
        .action-btn { border-radius: 8px; }
        .section-divider { border-left: 4px solid #667eea; padding-left: 12px; margin-bottom: 20px; }
        #loading-overlay { display: none; position: fixed; inset: 0; background: rgba(255,255,255,0.7); z-index: 9000; align-items: center; justify-content: center; }
        #loading-overlay.show { display: flex; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark px-4 py-3">
    <span class="navbar-brand fw-bold fs-4">
        <i class="fa-solid fa-box-open me-2"></i>
        Quản lý sản phẩm <span class="badge bg-light text-dark ms-2" style="font-size:12px">jQuery API Client</span>
    </span>
    <div class="d-flex gap-2">
        <span class="text-white-50 small" id="api-status">
            <i class="fa-solid fa-circle text-warning"></i> Đang kết nối...
        </span>
    </div>
</nav>

<!-- LOADING OVERLAY -->
<div id="loading-overlay">
    <div class="text-center">
        <div class="spinner-border text-primary" style="width:3rem;height:3rem;"></div>
        <div class="mt-2 fw-bold text-primary">Đang xử lý...</div>
    </div>
</div>

<!-- TOAST -->
<div id="toast-container"></div>

<div class="container py-4">
    <div class="row g-4">

        <!-- FORM THÊM / SỬA SẢN PHẨM -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-primary text-white py-3">
                    <i class="fa-solid fa-pen-to-square me-2"></i>
                    <span id="form-title">Thêm sản phẩm mới</span>
                </div>
                <div class="card-body p-4">
                    <input type="hidden" id="product-id">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" id="product-name" class="form-control" placeholder="Nhập tên sản phẩm...">
                        <div class="invalid-feedback" id="err-name"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mô tả <span class="text-danger">*</span></label>
                        <textarea id="product-desc" class="form-control" rows="3" placeholder="Nhập mô tả..."></textarea>
                        <div class="invalid-feedback" id="err-desc"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Giá (VND) <span class="text-danger">*</span></label>
                        <input type="number" id="product-price" class="form-control" placeholder="0">
                        <div class="invalid-feedback" id="err-price"></div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Danh mục</label>
                        <select id="product-category" class="form-select">
                            <option value="">-- Chọn danh mục --</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button id="btn-save" class="btn btn-primary action-btn">
                            <i class="fa-solid fa-plus me-1"></i>
                            <span id="btn-save-label">Thêm sản phẩm</span>
                        </button>
                        <button id="btn-cancel" class="btn btn-outline-secondary action-btn" style="display:none">
                            <i class="fa-solid fa-xmark me-1"></i> Hủy chỉnh sửa
                        </button>
                    </div>
                </div>
            </div>

            <!-- THÊM DANH MỤC -->
            <div class="card mt-4">
                <div class="card-header bg-success text-white py-3">
                    <i class="fa-solid fa-tags me-2"></i> Thêm danh mục mới
                </div>
                <div class="card-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Tên danh mục</label>
                        <input type="text" id="cat-name" class="form-control" placeholder="Nhập tên danh mục...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mô tả</label>
                        <input type="text" id="cat-desc" class="form-control" placeholder="Mô tả (tuỳ chọn)">
                    </div>
                    <button id="btn-add-cat" class="btn btn-success w-100 action-btn">
                        <i class="fa-solid fa-plus me-1"></i> Thêm danh mục
                    </button>
                </div>
            </div>
        </div>

        <!-- DANH SÁCH SẢN PHẨM -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="section-divider mb-0">
                        <h5 class="mb-0">
                            <i class="fa-solid fa-list me-2 text-primary"></i>
                            Danh sách sản phẩm
                            <span id="product-count" class="badge bg-primary ms-2">0</span>
                        </h5>
                    </div>
                    <div class="d-flex gap-2">
                        <input type="text" id="search-keyword" class="form-control form-control-sm" placeholder="🔍 Tìm kiếm..." style="width:180px">
                        <select id="filter-category" class="form-select form-select-sm" style="width:160px">
                            <option value="">Tất cả danh mục</option>
                        </select>
                        <button id="btn-refresh" class="btn btn-outline-primary btn-sm action-btn">
                            <i class="fa-solid fa-rotate-right"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width:50px">#ID</th>
                                    <th>Tên sản phẩm</th>
                                    <th>Danh mục</th>
                                    <th>Giá</th>
                                    <th class="text-center" style="width:130px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="product-tbody">
                                <tr><td colspan="5" class="text-center py-4 text-muted">
                                    <div class="spinner-border spinner-border-sm me-2"></div>Đang tải dữ liệu...
                                </td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- CHI TIẾT SẢN PHẨM -->
            <div class="card mt-4" id="detail-card" style="display:none!important">
                <div class="card-header bg-info text-white py-3">
                    <i class="fa-solid fa-circle-info me-2"></i> Chi tiết sản phẩm
                    <button type="button" class="btn-close btn-close-white float-end" id="btn-close-detail"></button>
                </div>
                <div class="card-body p-4" id="detail-body"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const BASE = '/project1/api';
let editMode = false;
let filterTimer = null;

// =====================
// TOAST HELPER
// =====================
function showToast(msg, type = 'success') {
    const icon = type === 'success' ? 'fa-circle-check' : (type === 'danger' ? 'fa-circle-xmark' : 'fa-circle-info');
    const id = 'toast-' + Date.now();
    $('#toast-container').append(`
        <div id="${id}" class="toast align-items-center text-bg-${type} border-0 mb-2 show" role="alert">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fa-solid ${icon} me-2"></i>${msg}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `);
    setTimeout(() => $('#' + id).remove(), 3500);
}

function loading(show) {
    show ? $('#loading-overlay').addClass('show') : $('#loading-overlay').removeClass('show');
}

function formatPrice(p) {
    return parseInt(p).toLocaleString('vi-VN') + ' ₫';
}

// =====================
// LOAD CATEGORIES
// =====================
function loadCategories() {
    $.getJSON(BASE + '/category', function(res) {
        if (!res.success) return;
        const cats = res.data;
        $('#product-category, #filter-category').each(function() {
            const $sel = $(this);
            const isFilter = $sel.attr('id') === 'filter-category';
            const current = $sel.val();
            $sel.find('option:not(:first)').remove();
            cats.forEach(c => {
                $sel.append(`<option value="${c.id}">${c.name}</option>`);
            });
            if (current) $sel.val(current);
        });
        $('#api-status').html('<i class="fa-solid fa-circle text-success"></i> API kết nối thành công');
    }).fail(function() {
        $('#api-status').html('<i class="fa-solid fa-circle text-danger"></i> Lỗi kết nối API');
    });
}

// =====================
// LOAD PRODUCTS
// =====================
function loadProducts() {
    const keyword = $('#search-keyword').val();
    const cat_id  = $('#filter-category').val();
    let url = BASE + '/product';
    const params = {};
    if (keyword) params.keyword = keyword;
    if (cat_id)  params.category_id = cat_id;
    if (Object.keys(params).length) url += '?' + $.param(params);

    $.getJSON(url, function(res) {
        const tbody = $('#product-tbody');
        tbody.empty();
        $('#product-count').text(res.total || 0);

        if (!res.data || res.data.length === 0) {
            tbody.html('<tr><td colspan="5" class="text-center py-4 text-muted"><i class="fa-solid fa-inbox me-2"></i>Không có sản phẩm nào</td></tr>');
            return;
        }

        res.data.forEach(p => {
            tbody.append(`
                <tr>
                    <td class="ps-4 text-muted fw-bold">#${p.id}</td>
                    <td>
                        <div class="fw-semibold">${$('<span>').text(p.name).html()}</div>
                        <small class="text-muted">${$('<span>').text(p.description).html().substring(0, 60)}...</small>
                    </td>
                    <td><span class="badge badge-category">${$('<span>').text(p.category_name || '—').html()}</span></td>
                    <td class="price-col">${formatPrice(p.price)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-info me-1 btn-detail action-btn" data-id="${p.id}" title="Chi tiết">
                            <i class="fa-solid fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-warning me-1 btn-edit action-btn" data-id="${p.id}" title="Sửa">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger btn-delete action-btn" data-id="${p.id}" title="Xóa">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);
        });
    }).fail(function() {
        $('#product-tbody').html('<tr><td colspan="5" class="text-center py-4 text-danger"><i class="fa-solid fa-triangle-exclamation me-2"></i>Lỗi tải dữ liệu API</td></tr>');
    });
}

// =====================
// RESET FORM
// =====================
function resetForm() {
    editMode = false;
    $('#product-id').val('');
    $('#product-name, #product-desc, #product-price').val('');
    $('#product-category').val('');
    $('#form-title').text('Thêm sản phẩm mới');
    $('#btn-save-label').text('Thêm sản phẩm');
    $('#btn-save').removeClass('btn-warning').addClass('btn-primary');
    $('#btn-cancel').hide();
    $('.is-invalid').removeClass('is-invalid');
}

function validateForm() {
    let valid = true;
    ['product-name', 'product-desc', 'product-price'].forEach(id => {
        const val = $('#' + id).val().trim();
        if (!val) {
            $('#' + id).addClass('is-invalid');
            valid = false;
        } else {
            $('#' + id).removeClass('is-invalid');
        }
    });
    return valid;
}

// =====================
// THÊM SẢN PHẨM (POST)
// =====================
$('#btn-save').on('click', function() {
    if (!validateForm()) return;

    const data = {
        name:        $('#product-name').val().trim(),
        description: $('#product-desc').val().trim(),
        price:       $('#product-price').val(),
        category_id: $('#product-category').val()
    };

    if (editMode) {
        // PUT
        loading(true);
        $.ajax({
            url: BASE + '/product/' + $('#product-id').val(),
            method: 'PUT',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(res) {
                loading(false);
                if (res.success) {
                    showToast('✅ Cập nhật sản phẩm thành công!', 'success');
                    resetForm();
                    loadProducts();
                } else {
                    showToast('❌ ' + (res.message || 'Lỗi cập nhật'), 'danger');
                }
            },
            error: function(xhr) {
                loading(false);
                const msg = xhr.responseJSON?.message || 'Lỗi kết nối API';
                showToast('❌ ' + msg, 'danger');
            }
        });
    } else {
        // POST
        loading(true);
        $.ajax({
            url: BASE + '/product',
            method: 'POST',
            contentType: 'application/json',
            data: JSON.stringify(data),
            success: function(res) {
                loading(false);
                if (res.success) {
                    showToast('✅ Thêm sản phẩm thành công!', 'success');
                    resetForm();
                    loadProducts();
                } else if (res.errors) {
                    Object.entries(res.errors).forEach(([k, v]) => showToast('⚠️ ' + v, 'warning'));
                } else {
                    showToast('❌ ' + (res.message || 'Lỗi thêm sản phẩm'), 'danger');
                }
            },
            error: function() {
                loading(false);
                showToast('❌ Lỗi kết nối API', 'danger');
            }
        });
    }
});

// =====================
// SỬA SẢN PHẨM (GET + PUT)
// =====================
$(document).on('click', '.btn-edit', function() {
    const id = $(this).data('id');
    loading(true);
    $.getJSON(BASE + '/product/' + id, function(res) {
        loading(false);
        if (!res.success) { showToast('❌ Không tìm thấy sản phẩm', 'danger'); return; }
        const p = res.data;
        editMode = true;
        $('#product-id').val(p.id);
        $('#product-name').val(p.name);
        $('#product-desc').val(p.description);
        $('#product-price').val(p.price);
        $('#product-category').val(p.category_id);
        $('#form-title').text('Sửa sản phẩm #' + p.id);
        $('#btn-save-label').text('Lưu thay đổi');
        $('#btn-save').removeClass('btn-primary').addClass('btn-warning');
        $('#btn-cancel').show();
        $('html,body').animate({ scrollTop: 0 }, 300);
    }).fail(function() {
        loading(false);
        showToast('❌ Lỗi tải thông tin sản phẩm', 'danger');
    });
});

// =====================
// XEM CHI TIẾT (GET)
// =====================
$(document).on('click', '.btn-detail', function() {
    const id = $(this).data('id');
    $.getJSON(BASE + '/product/' + id, function(res) {
        if (!res.success) return;
        const p = res.data;
        $('#detail-body').html(`
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><th width="120">ID</th><td><span class="badge bg-secondary">#${p.id}</span></td></tr>
                        <tr><th>Tên</th><td class="fw-bold">${$('<span>').text(p.name).html()}</td></tr>
                        <tr><th>Danh mục</th><td><span class="badge badge-category">${$('<span>').text(p.category_name || '—').html()}</span></td></tr>
                        <tr><th>Giá</th><td class="price-col fs-5">${formatPrice(p.price)}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold text-muted small">MÔ TẢ</label>
                    <p>${$('<span>').text(p.description).html()}</p>
                </div>
            </div>
        `);
        $('#detail-card').show();
        $('html,body').animate({ scrollTop: $('#detail-card').offset().top - 80 }, 400);
    });
});

$('#btn-close-detail').on('click', function() {
    $('#detail-card').hide();
});

// =====================
// XÓA SẢN PHẨM (DELETE)
// =====================
$(document).on('click', '.btn-delete', function() {
    const id = $(this).data('id');
    const name = $(this).closest('tr').find('td:nth-child(2) .fw-semibold').text();
    if (!confirm(`Bạn có chắc muốn xóa sản phẩm "${name}"?`)) return;

    loading(true);
    $.ajax({
        url: BASE + '/product/' + id,
        method: 'DELETE',
        success: function(res) {
            loading(false);
            if (res.success) {
                showToast('🗑️ Đã xóa sản phẩm thành công!', 'success');
                loadProducts();
            } else {
                showToast('❌ ' + (res.message || 'Lỗi xóa sản phẩm'), 'danger');
            }
        },
        error: function() {
            loading(false);
            showToast('❌ Lỗi kết nối API', 'danger');
        }
    });
});

// =====================
// THÊM DANH MỤC (POST)
// =====================
$('#btn-add-cat').on('click', function() {
    const name = $('#cat-name').val().trim();
    if (!name) { showToast('⚠️ Vui lòng nhập tên danh mục', 'warning'); return; }

    loading(true);
    $.ajax({
        url: BASE + '/category',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ name: name, description: $('#cat-desc').val().trim() }),
        success: function(res) {
            loading(false);
            if (res.success) {
                showToast('✅ Thêm danh mục thành công!', 'success');
                $('#cat-name, #cat-desc').val('');
                loadCategories();
            } else {
                showToast('❌ ' + (res.message || 'Danh mục đã tồn tại'), 'warning');
            }
        },
        error: function() {
            loading(false);
            showToast('❌ Lỗi kết nối API', 'danger');
        }
    });
});

// =====================
// HỦY CHỈNH SỬA
// =====================
$('#btn-cancel').on('click', resetForm);

// =====================
// TÌM KIẾM / LỌC REALTIME
// =====================
$('#search-keyword').on('input', function() {
    clearTimeout(filterTimer);
    filterTimer = setTimeout(loadProducts, 400);
});

$('#filter-category').on('change', loadProducts);
$('#btn-refresh').on('click', loadProducts);

// =====================
// KHỞI ĐỘNG
// =====================
$(document).ready(function() {
    loadCategories();
    loadProducts();
});
</script>
</body>
</html>
