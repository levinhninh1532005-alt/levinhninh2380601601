<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lê Vĩnh Ninh</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>

        body{
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .navbar{
            background: linear-gradient(90deg,#4e73df,#224abe);
        }

        .navbar-brand,
        .nav-link{
            color:white !important;
            font-weight:500;
        }

        .hero{
            background: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)),
            url('https://images.unsplash.com/photo-1519389950473-47ba0277781c?q=80&w=2070');
            background-size: cover;
            background-position: center;
            color:white;
            padding:80px 0;
            text-align:center;
            margin-bottom:40px;
        }

        .hero h1{
            font-size:50px;
            font-weight:bold;
        }

        .product-card{
            border:none;
            border-radius:15px;
            overflow:hidden;
            transition:0.3s;
            box-shadow:0 4px 10px rgba(0,0,0,.1);
        }

        .product-card:hover{
            transform:translateY(-5px);
            box-shadow:0 8px 20px rgba(0,0,0,.2);
        }

        .product-card img{
            height:220px;
            object-fit:cover;
        }

        .price{
            color:#e74a3b;
            font-size:22px;
            font-weight:bold;
        }

        footer{
            background:#1f2937;
            color:white;
            margin-top:60px;
            padding:50px 0 20px;
        }

        footer h5{
            margin-bottom:20px;
            font-weight:bold;
        }

        footer a{
            color:#d1d5db;
            text-decoration:none;
        }

        footer a:hover{
            color:white;
        }

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">

        <a class="navbar-brand" href="/project1/Product/list">
            <i class="fa-solid fa-store"></i>
            Lê Vĩnh Ninh
        </a>

        <button class="navbar-toggler" type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/project1/Product/list">
                        Danh sách sản phẩm
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/project1/Product/add">
                        Thêm sản phẩm
                    </a>
                </li>
                <li class="nav-item">
    <a class="nav-link" href="/project1/product/Cart/view">
        🛒 Giỏ hàng
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="/project1/Product/myOrders">
        <i class="fa-solid fa-box"></i>
        Đơn hàng của tôi
    </a>
</li>
            </ul>

        </div>

    </div>
</nav>