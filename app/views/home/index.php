<?php include 'app/views/shares/header.php'; ?>

<style>
    .hero-hutech {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)),
        url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1');
        background-size: cover;
        background-position: center;
        color: white;
        padding: 120px 20px;
        text-align: center;
        border-radius: 0 0 30px 30px;
    }

    .hero-hutech h1 {
        font-size: 55px;
        font-weight: bold;
    }

    .hero-hutech p {
        font-size: 20px;
        margin-top: 10px;
    }

    .hutech-card {
        border: none;
        border-radius: 20px;
        transition: 0.3s;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .hutech-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }

    .icon-box {
        font-size: 40px;
        color: #4e73df;
        margin-bottom: 15px;
    }
</style>

<!-- HERO -->
<div class="hero-hutech">
    <h1>HELLO HUTECH 🎓</h1>
    <p>Hệ thống quản lý sản phẩm dành của sinh viên</p>

    <a href="/project1/Product/list" class="btn btn-warning btn-lg mt-4">
        🚀 Vào trang quản lý
    </a>
</div>

<!-- CONTENT -->
<div class="container mt-5">

    <div class="text-center mb-5">
        <h2>Chào mừng bạn đến với hệ thống</h2>
        <p class="text-muted">
            Làm bởi sinh viên HUTECH
        </p>
    </div>

    <div class="row">

        <div class="col-md-4 mb-4">
            <div class="card hutech-card p-4 text-center">
                <div class="icon-box">📦</div>
                <h4>Quản lý sản phẩm</h4>
                <p>Theo dõi, thêm, sửa, xóa sản phẩm dễ dàng</p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card hutech-card p-4 text-center">
                <div class="icon-box">📂</div>
                <h4>Quản lý danh mục</h4>
                <p>Phân loại sản phẩm rõ ràng và khoa học</p>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card hutech-card p-4 text-center">
                <div class="icon-box">🛒</div>
                <h4>Giỏ hàng</h4>
                <p>Thêm sản phẩm vào giỏ và quản lý đơn hàng</p>
            </div>
        </div>

    </div>

    <div class="text-center mt-5">
        <a href="/project1/Product/list" class="btn btn-primary btn-lg">
            Bắt đầu ngay
        </a>
    </div>

</div>

<?php include 'app/views/shares/footer.php'; ?>