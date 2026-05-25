<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">

    <h1 class="fw-bold mb-4">
        <i class="fa-solid fa-cart-shopping"></i>
        Giỏ hàng
    </h1>

    <?php if (empty($cart)): ?>

        <div class="alert alert-warning">

            Giỏ hàng đang trống!

        </div>

    <?php else: ?>

    <?php

    $total = 0;

    // DANH SÁCH MÃ GIẢM GIÁ
    $coupons = [

        "SALE10" => 10,
        "GIAM20" => 20,
        "VIP30" => 30,
        "BLACKFRIDAY" => 50,
        "WELCOME" => 15

    ];

    $discount = $_SESSION['discount'] ?? 0;

$discountName =
    $_SESSION['discount_name'] ?? "Không có";

    // KIỂM TRA MÃ GIẢM GIÁ
    if (isset($_POST['coupon'])) {

        $coupon = strtoupper(trim($_POST['coupon']));

        if (isset($coupons[$coupon])) {

            $discount = $coupons[$coupon];
        
            $discountName = $coupon;
        
            // LƯU SESSION
            $_SESSION['discount'] = $discount;
        
            $_SESSION['discount_name'] = $discountName;
        }
    }

    ?>

    <!-- UPDATE CART -->
    <form method="POST"
          action="/project1/Product/updateCart">

        <div class="table-responsive">

            <table class="table table-bordered align-middle text-center">

                <thead class="table-dark">

                    <tr>

                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Thao tác</th>

                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($cart as $item): ?>

                    <?php

                    $subtotal =
                        $item['price'] * $item['quantity'];

                    $total += $subtotal;

                    ?>

                    <tr>

                        <!-- IMAGE -->
                        <td width="150">

                            <img
                            src="/project1/<?php echo $item['image']; ?>"
                            class="img-fluid rounded"
                            style="height:100px; object-fit:cover;">

                        </td>

                        <!-- NAME -->
                        <td class="fw-bold">

                            <?php echo $item['name']; ?>

                        </td>

                        <!-- PRICE -->
                        <td class="text-danger fw-bold">

                            <?php
                            echo number_format(
                                $item['price'],
                                0,
                                ',',
                                '.'
                            );
                            ?> VND

                        </td>

                        <!-- QUANTITY -->
                        <td width="150">

                            <input type="number"
                                   name="quantity[<?php echo $item['id']; ?>]"
                                   value="<?php echo $item['quantity']; ?>"
                                   min="1"
                                   class="form-control">

                        </td>

                        <!-- SUBTOTAL -->
                        <td class="fw-bold text-success">

                            <?php
                            echo number_format(
                                $subtotal,
                                0,
                                ',',
                                '.'
                            );
                            ?> VND

                        </td>

                        <!-- DELETE -->
                        <td>

                            <a href="/project1/Product/removeFromCart/<?php echo $item['id']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Xóa sản phẩm?')">

                                <i class="fa-solid fa-trash"></i>
                                Xóa

                            </a>

                        </td>

                    </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        </div>

        <!-- BUTTON -->
        <div class="mb-4">

            <button type="submit"
                    class="btn btn-primary">

                <i class="fa-solid fa-rotate"></i>
                Cập nhật giỏ hàng

            </button>

        </div>

    </form>

    <?php

    $discountAmount = ($total * $discount) / 100;

    $finalTotal = $total - $discountAmount;

    ?>

    <!-- COUPON + TOTAL -->
    <div class="row mt-4">

        <!-- MÃ GIẢM GIÁ -->
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body">

                    <h5 class="fw-bold mb-3">

                        <i class="fa-solid fa-ticket"></i>
                        Mã giảm giá

                    </h5>

                    <!-- FORM COUPON -->
                    <form method="POST">

                        <div class="input-group">

                            <input type="text"
                                   name="coupon"
                                   class="form-control"
                                   placeholder="Nhập mã giảm giá">

                            <button class="btn btn-warning"
                                    type="submit">

                                Áp dụng

                            </button>

                        </div>

                    </form>

                    <!-- LIST COUPON -->
                    <div class="mt-3">

                        <?php foreach ($coupons as $code => $percent): ?>

                            <span class="badge bg-success me-2 mb-2">

                                <?php echo $code; ?>
                                →
                                Giảm <?php echo $percent; ?>%

                            </span>

                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

        </div>

        <!-- TOTAL -->
        <div class="col-lg-6">

            <div class="card border-0 shadow-sm">

                <div class="card-body text-end">

                    <h6>

                        Tổng gốc:
                        <strong>

                            <?php
                            echo number_format(
                                $total,
                                0,
                                ',',
                                '.'
                            );
                            ?> VND

                        </strong>

                    </h6>

                    <h6 class="text-primary mt-3">

                        Mã áp dụng:
                        <strong>

                            <?php echo $discountName; ?>

                        </strong>

                    </h6>

                    <h6 class="text-danger mt-2">

                        Giảm:
                        <strong>

                            <?php echo $discount; ?>%

                        </strong>

                    </h6>

                    <hr>

                    <h3 class="fw-bold text-danger">

                        Thành tiền:
                        <?php
                        echo number_format(
                            $finalTotal,
                            0,
                            ',',
                            '.'
                        );
                        ?> VND

                    </h3>

                </div>

            </div>

        </div>

    </div>

    <!-- CHECKOUT -->
    <div class="mt-4">

        <a href="/project1/Product/checkout"
           class="btn btn-success btn-lg">

            <i class="fa-solid fa-money-check"></i>
            Thanh toán

        </a>

    </div>

    <?php endif; ?>

</div>

<?php include 'app/views/shares/footer.php'; ?>