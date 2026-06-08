<?php include 'app/views/shares/header.php'; ?>

<div class="hero">
    <div class="container">

        <h1 class="fw-bold">Danh sách sản phẩm</h1>

        <div class="container mt-4">
            <div class="row g-2 align-items-center" id="filter-bar">
                <div class="col-md-5">
                    <input type="text" id="search-keyword" class="form-control"
                        placeholder="🔍 Tìm kiếm sản phẩm...">
                </div>
                <div class="col-md-4">
                    <select id="search-category" class="form-select">
                        <option value="">-- Tất cả danh mục --</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button id="btn-filter" class="btn btn-primary w-100">
                        🔎 Lọc sản phẩm
                    </button>
                </div>
            </div>
        </div>

        <p class="lead mt-3">Quản lý sản phẩm hiện đại và chuyên nghiệp</p>

        <?php if ($_isAdmin): ?>
        <a href="/project1/Product/add" class="btn btn-warning btn-lg mt-3">
            <i class="fa-solid fa-plus"></i> Thêm sản phẩm
        </a>
        <?php endif; ?>

    </div>
</div>

<div class="container mb-5">
    <div class="row" id="product-list">
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2">Đang tải sản phẩm...</p>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
var IS_ADMIN = <?php echo $_isAdmin ? 'true' : 'false'; ?>;
var allProducts   = [];
var allCategories = [];

$(document).ready(function () {

    // Load categories vào select
    $.getJSON('/project1/api/category', function (data) {
        allCategories = data;
        data.forEach(function (cat) {
            $('#search-category').append(
                $('<option>').val(cat.id).text(cat.name)
            );
        });
    });

    // Load sản phẩm ban đầu
    loadProducts();

    // Nút lọc
    $('#btn-filter').on('click', function () {
        var keyword    = $('#search-keyword').val().toLowerCase();
        var categoryId = $('#search-category').val();
        var filtered   = allProducts.filter(function (p) {
            var matchKw  = !keyword || p.name.toLowerCase().includes(keyword) ||
                           (p.description && p.description.toLowerCase().includes(keyword));
            var matchCat = !categoryId || String(p.category_id) === String(categoryId);
            return matchKw && matchCat;
        });
        renderProducts(filtered);
    });
});

function loadProducts() {
    $.getJSON('/project1/api/product', function (data) {
        allProducts = data;
        renderProducts(data);
    }).fail(function () {
        $('#product-list').html('<p class="text-danger">Không thể tải sản phẩm.</p>');
    });
}

function renderProducts(products) {
    var list = $('#product-list');
    list.empty();

    if (products.length === 0) {
        list.html('<p class="text-muted text-center py-4">Không tìm thấy sản phẩm nào.</p>');
        return;
    }

    products.forEach(function (product) {
        var imgSrc = product.image
            ? '/project1/' + product.image
            : 'https://via.placeholder.com/400x250';

        var adminBtns = IS_ADMIN ? `
            <a href="/project1/Product/edit/${product.id}" class="btn btn-primary">
                <i class="fa-solid fa-pen"></i> Sửa
            </a>
            <button class="btn btn-danger" onclick="deleteProduct(${product.id})">
                <i class="fa-solid fa-trash"></i> Xóa
            </button>` : '';

        var cartForm = !IS_ADMIN ? `
            <form action="/project1/Product/addToCart/${product.id}" method="POST" class="mb-3">
                <div class="d-flex gap-2">
                    <input type="number" name="quantity" value="1" min="1"
                           class="form-control" style="width:90px;">
                    <button type="submit" class="btn btn-warning flex-grow-1">
                        <i class="fa-solid fa-cart-shopping"></i> Thêm giỏ hàng
                    </button>
                </div>
            </form>` : '';

        list.append(`
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card product-card h-100 border-0 shadow">
                    <a href="/project1/Product/show/${product.id}">
                        <img src="${imgSrc}" class="card-img-top"
                             style="height:250px; object-fit:cover;"
                             onerror="this.src='https://via.placeholder.com/400x250'">
                    </a>
                    <div class="card-body">
                        <h4 class="fw-bold">
                            <a href="/project1/Product/show/${product.id}"
                               class="text-decoration-none text-dark">
                                ${escHtml(product.name)}
                            </a>
                        </h4>
                        <span class="badge bg-primary mb-2">${escHtml(product.category_name || '')}</span>
                        <p class="text-muted">${escHtml((product.description || '').substring(0, 80))}...</p>
                        <h3 class="text-danger fw-bold">
                            ${Number(product.price).toLocaleString('vi-VN')} VND
                        </h3>
                    </div>
                    <div class="card-footer bg-white border-0 pb-4">
                        ${cartForm}
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="/project1/Product/show/${product.id}" class="btn btn-success">
                                <i class="fa-solid fa-eye"></i> Xem
                            </a>
                            ${adminBtns}
                        </div>
                    </div>
                </div>
            </div>
        `);
    });
}

function deleteProduct(id) {
    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) return;
    $.ajax({
        url: '/project1/api/product/' + id,
        method: 'DELETE',
        success: function (data) {
            if (data.message === 'Product deleted successfully') {
                allProducts = allProducts.filter(function (p) { return p.id != id; });
                renderProducts(allProducts);
            } else {
                alert('Xóa sản phẩm thất bại');
            }
        },
        error: function () { alert('Xóa sản phẩm thất bại'); }
    });
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}
</script>
