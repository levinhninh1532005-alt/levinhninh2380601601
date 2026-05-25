<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <!-- CARD -->
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <!-- HEADER -->
                <div class="card-header bg-success text-white text-center p-5">

                    <i class="fa-solid fa-circle-check"
                       style="font-size:90px;"></i>

                    <h1 class="fw-bold mt-4">

                        Đặt hàng thành công!

                    </h1>

                    <p class="mb-0 mt-3 fs-5">

                        Cảm ơn bạn đã mua hàng tại cửa hàng của chúng tôi

                    </p>

                </div>

                <!-- BODY -->
                <div class="card-body p-5">

                    <!-- ORDER INFO -->
                    <div class="row g-4">

                        <!-- LEFT -->
                        <div class="col-md-6">

                            <div class="border rounded-4 p-4 h-100">

                                <h4 class="fw-bold text-primary mb-4">

                                    <i class="fa-solid fa-receipt"></i>
                                    Thông tin đơn hàng

                                </h4>

                                <p class="mb-3">

                                    <strong>Mã đơn hàng:</strong>

                                    <span class="text-danger fw-bold">

                                        #<?php echo $order->id; ?>

                                    </span>

                                </p>

                                <p class="mb-3">

                                    <strong>Khách hàng:</strong>

                                    <?php
                                    echo $order->customer_name;
                                    ?>

                                </p>

                                <p class="mb-3">

                                    <strong>Số điện thoại:</strong>

                                    <?php
                                    echo $order->phone;
                                    ?>

                                </p>

                                <p class="mb-3">

                                    <strong>Địa chỉ:</strong>

                                    <?php
                                    echo $order->address;
                                    ?>

                                </p>

                            </div>

                        </div>

                        <!-- RIGHT -->
                        <div class="col-md-6">

                            <div class="border rounded-4 p-4 h-100">

                                <h4 class="fw-bold text-success mb-4">

                                    <i class="fa-solid fa-truck"></i>
                                    Theo dõi đơn hàng

                                </h4>

                                <p class="mb-4">

                                    <strong>Trạng thái:</strong>

                                    <?php if($order->order_status == 'Đang xử lý'): ?>

                                        <span class="badge bg-warning text-dark p-2">

                                            Đang xử lý

                                        </span>

                                    <?php elseif($order->order_status == 'Đang giao'): ?>

                                        <span class="badge bg-primary p-2">

                                            Đang giao

                                        </span>

                                    <?php elseif($order->order_status == 'Đã giao'): ?>

                                        <span class="badge bg-success p-2">

                                            Đã giao

                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger p-2">

                                            Đã hủy

                                        </span>

                                    <?php endif; ?>

                                </p>

                                <p class="mb-3">

                                    <strong>Thanh toán:</strong>

                                    <?php
                                    echo $order->payment_method == 'bank'
                                    ? 'Chuyển khoản'
                                    : 'Tiền mặt';
                                    ?>

                                </p>

                                <p class="mb-3">

                                    <strong>Ngày đặt:</strong>

                                    <?php
                                    echo date(
                                        'd/m/Y H:i',
                                        strtotime($order->created_at)
                                    );
                                    ?>

                                </p>

                                <hr>

                                <h3 class="text-danger fw-bold">

                                    <?php
                                    echo number_format(
                                        $order->total_price,
                                        0,
                                        ',',
                                        '.'
                                    );
                                    ?> VND

                                </h3>

                            </div>

                        </div>

                    </div>

                    <!-- TRACKING -->
                    <div class="mt-5">

                        <h3 class="fw-bold text-center mb-5">

                            Trạng thái vận chuyển

                        </h3>

                        <div class="row text-center">

                            <!-- STEP 1 -->
                            <div class="col-md-4">

                                <div class="p-4">

                                    <div class="bg-warning rounded-circle d-inline-flex
                                                align-items-center justify-content-center"
                                         style="width:80px;height:80px;">

                                        <i class="fa-solid fa-box fs-2 text-white"></i>

                                    </div>

                                    <h5 class="mt-3 fw-bold">

                                        Đơn hàng đã tạo

                                    </h5>

                                </div>

                            </div>

                            <!-- STEP 2 -->
                            <div class="col-md-4">

                                <div class="p-4">

                                    <div class="bg-primary rounded-circle d-inline-flex
                                                align-items-center justify-content-center"
                                         style="width:80px;height:80px;">

                                        <i class="fa-solid fa-truck-fast fs-2 text-white"></i>

                                    </div>

                                    <h5 class="mt-3 fw-bold">

                                        Đang vận chuyển

                                    </h5>

                                </div>

                            </div>

                            <!-- STEP 3 -->
                            <div class="col-md-4">

                                <div class="p-4">

                                    <div class="bg-success rounded-circle d-inline-flex
                                                align-items-center justify-content-center"
                                         style="width:80px;height:80px;">

                                        <i class="fa-solid fa-house fs-2 text-white"></i>

                                    </div>

                                    <h5 class="mt-3 fw-bold">

                                        Giao hàng thành công

                                    </h5>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- BUTTON -->
                    <div class="text-center mt-5">

                        <a href="/project1/Product"
                           class="btn btn-primary btn-lg px-5">

                            <i class="fa-solid fa-cart-shopping"></i>
                            Tiếp tục mua sắm

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include 'app/views/shares/footer.php'; ?>