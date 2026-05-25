<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow border-0 rounded-4 overflow-hidden">

                <!-- HEADER -->
                <div class="card-header bg-primary text-white p-4">

                    <h2 class="mb-0 fw-bold">
                        <i class="fa-solid fa-credit-card"></i>
                        Thanh toán đơn hàng
                    </h2>

                </div>

                <!-- BODY -->
                <div class="card-body p-4">

                    <form method="POST"
                          action="/project1/Product/processCheckout">

                        <!-- HỌ TÊN -->
                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Họ và tên
                            </label>

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   placeholder="Nhập họ tên"
                                   required>

                        </div>

                        <!-- SỐ ĐIỆN THOẠI -->
                        <div class="mb-3">

                            <label class="form-label fw-bold">
                                Số điện thoại
                            </label>

                            <input type="text"
                                   name="phone"
                                   class="form-control"
                                   placeholder="Nhập số điện thoại"
                                   required>

                        </div>

                        <!-- ĐỊA CHỈ -->
                        <div class="mb-4">

                            <label class="form-label fw-bold">
                                Địa chỉ giao hàng
                            </label>

                            <textarea name="address"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Nhập địa chỉ giao hàng"
                                      required></textarea>

                        </div>

                        <!-- THÔNG TIN ĐƠN HÀNG -->
                        <div class="card border-0 shadow-sm mb-4">

                            <div class="card-body">

                                <h4 class="fw-bold mb-4 text-danger">

                                    <i class="fa-solid fa-cart-shopping"></i>
                                    Thông tin đơn hàng

                                </h4>

                                <?php

                                $total = 0;

                                $discount =
                                    $_SESSION['discount'] ?? 0;

                                $discountName =
                                    $_SESSION['discount_name'] ?? 'Không có';

                                if (isset($_SESSION['cart'])):

                                    foreach ($_SESSION['cart'] as $item):

                                        $subtotal =
                                            $item['price'] * $item['quantity'];

                                        $total += $subtotal;

                                ?>

                                    <div class="d-flex justify-content-between mb-3">

                                        <div>

                                            <strong>

                                                <?php echo $item['name']; ?>

                                            </strong>

                                            <br>

                                            <small class="text-muted">

                                                Số lượng:
                                                <?php echo $item['quantity']; ?>

                                            </small>

                                        </div>

                                        <div class="fw-bold text-danger">

                                            <?php
                                            echo number_format(
                                                $subtotal,
                                                0,
                                                ',',
                                                '.'
                                            );
                                            ?> VND

                                        </div>

                                    </div>

                                    <hr>

                                <?php

                                    endforeach;

                                endif;

                                ?>

                                <?php

                                $discountAmount =
                                    ($total * $discount) / 100;

                                $finalTotal =
                                    $total - $discountAmount;

                                ?>

                                <div class="text-end mt-4">

                                    <h5 class="text-primary">

                                        Mã giảm giá:
                                        <strong>

                                            <?php echo $discountName; ?>

                                        </strong>

                                    </h5>

                                    <h5 class="text-danger mt-2">

                                        Giảm:
                                        <strong>

                                            <?php echo $discount; ?>%

                                        </strong>

                                    </h5>

                                    <hr>

                                    <h3 class="fw-bold text-success">

                                        Tổng thanh toán:
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

                        <!-- PHƯƠNG THỨC THANH TOÁN -->
                        <div class="mb-4">

                            <label class="form-label fw-bold mb-3">

                                Phương thức thanh toán

                            </label>

                            <!-- TIỀN MẶT -->
                            <div class="form-check p-3 border rounded mb-3">

                                <input class="form-check-input"
                                       type="radio"
                                       name="payment_method"
                                       value="cash"
                                       checked
                                       onclick="toggleBankInfo()">

                                <label class="form-check-label fw-semibold">

                                    <i class="fa-solid fa-money-bill text-success"></i>
                                    Thanh toán tiền mặt

                                </label>

                            </div>

                            <!-- CHUYỂN KHOẢN -->
                            <div class="form-check p-3 border rounded">

                                <input class="form-check-input"
                                       type="radio"
                                       name="payment_method"
                                       value="bank"
                                       onclick="toggleBankInfo()">

                                <label class="form-check-label fw-semibold">

                                    <i class="fa-solid fa-building-columns text-primary"></i>
                                    Chuyển khoản ngân hàng

                                </label>

                            </div>

                        </div>

                        <!-- QR CHUYỂN KHOẢN -->
                        <div class="card border-0 shadow-sm mb-4"
                             id="bankInfo"
                             style="display:none;">

                            <div class="card-body">

                                <h5 class="fw-bold text-primary mb-4">

                                    <i class="fa-solid fa-qrcode"></i>
                                    Thông tin chuyển khoản

                                </h5>

                                <div class="row align-items-center">

                                    <!-- QR -->
                                    <div class="col-md-4 text-center">

                                        <img
                                        src="/project1/uploads/qr.png"
                                        class="img-fluid rounded shadow"
                                        style="max-width:250px;">

                                    </div>

                                    <!-- INFO -->
                                    <div class="col-md-8">

                                        <p>

                                            <strong>Ngân hàng:</strong>
                                            MB Bank

                                        </p>

                                        <p>

                                            <strong>Số tài khoản:</strong>
                                            0907543184

                                        </p>

                                        <p>

                                            <strong>Chủ tài khoản:</strong>
                                            LE VINH NINH

                                        </p>

                                        <p>

                                            <strong>Nội dung CK:</strong>
                                            THANHTOAN_<?php echo rand(1000,9999); ?>

                                        </p>

                                        <div class="alert alert-warning mt-3">

                                            Vui lòng quét mã QR để thanh toán.

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <!-- GHI CHÚ -->
                        <div class="mb-4">

                            <label class="form-label fw-bold">
                                Ghi chú
                            </label>

                            <textarea name="note"
                                      class="form-control"
                                      rows="3"
                                      placeholder="Ghi chú thêm..."></textarea>

                        </div>

                        <!-- BUTTON -->
                        <div class="d-flex gap-3">

                            <a href="/project1/Product/cart"
                               class="btn btn-secondary">

                                <i class="fa-solid fa-arrow-left"></i>
                                Quay lại giỏ hàng

                            </a>

                            <button type="submit"
                                    class="btn btn-success">

                                <i class="fa-solid fa-check"></i>
                                Xác nhận đặt hàng

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<script>

function toggleBankInfo() {

    let bankRadio =
        document.querySelector('input[value="bank"]');

    let bankInfo =
        document.getElementById('bankInfo');

    if (bankRadio.checked) {

        bankInfo.style.display = "block";

    } else {

        bankInfo.style.display = "none";
    }
}

// chạy lần đầu
toggleBankInfo();

</script>

<?php include 'app/views/shares/footer.php'; ?>