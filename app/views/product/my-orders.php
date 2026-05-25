<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">

    <h1 class="fw-bold mb-4 text-primary">

        <i class="fa-solid fa-box"></i>
        Đơn hàng của tôi

    </h1>

    <?php if (empty($orders)): ?>

        <div class="alert alert-warning">

            Bạn chưa có đơn hàng nào.

        </div>

    <?php else: ?>

        <?php foreach ($orders as $index => $order): ?>

            <div class="card border-0 shadow-lg mb-4 rounded-4">

                <div class="card-header bg-primary text-white p-3">

                <div class="d-flex justify-content-between align-items-center">

<div>

    <h5 class="mb-0">

    Đơn hàng #<?php echo $order['order_id']; ?>

    </h5>

</div>

<div class="d-flex gap-2 align-items-center">

    <!-- TRẠNG THÁI -->
    <?php if($order['status'] == 'Đang xử lý'): ?>

        <span class="badge bg-warning text-dark p-2">

            Đang xử lý

        </span>

    <?php elseif($order['status'] == 'Đang giao'): ?>

        <span class="badge bg-primary p-2">

            Đang giao

        </span>

    <?php elseif($order['status'] == 'Đã giao'): ?>

        <span class="badge bg-success p-2">

            Đã giao

        </span>
        <?php elseif($order['status'] == 'Chờ hoàn tiền'): ?>

<span class="badge bg-info p-2">

    Chờ hoàn tiền

</span>
    <?php else: ?>

        <span class="badge bg-danger p-2">

            Đã hủy

        </span>

    <?php endif; ?>

    <!-- NÚT HỦY -->
    <?php if($order['status'] != 'Đã hủy'): ?>

        <a href="/project1/Product/cancelOrder/<?php echo $index; ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Bạn có chắc muốn hủy đơn hàng?')">

            <i class="fa-solid fa-xmark"></i>
            Hủy đơn

        </a>

    <?php endif; ?>
<!-- NÚT XÓA -->
<a href="/project1/Product/deleteOrder/<?php echo $index; ?>"
   class="btn btn-dark btn-sm"
   onclick="return confirm('Bạn có chắc muốn xóa đơn hàng?')">

    <i class="fa-solid fa-trash"></i>
    Xóa

</a>
</div>

</div>

                </div>

                <div class="card-body">

                    <div class="row mb-4">

                        <div class="col-md-6">

                            <p>
                                <strong>Khách hàng:</strong>
                                <?php echo $order['customer_name']; ?>
                            </p>

                            <p>
                                <strong>SĐT:</strong>
                                <?php echo $order['phone']; ?>
                            </p>

                            <p>
                                <strong>Địa chỉ:</strong>
                                <?php echo $order['address']; ?>
                            </p>

                        </div>

                        <div class="col-md-6 text-md-end">

                            <p>
                                <strong>Thanh toán:</strong>

                                <?php
                                echo $order['payment'] == 'bank'
                                    ? 'Chuyển khoản'
                                    : 'Tiền mặt';
                                ?>
                            </p>

                            <p>
                                <strong>Ngày đặt:</strong>
                                <?php echo $order['created_at']; ?>
                            </p>

                        </div>

                    </div>

                    <div class="table-responsive">

                        <table class="table align-middle">

                            <thead class="table-light">

                                <tr>

                                    <th>Hình</th>
                                    <th>Sản phẩm</th>
                                    <th>SL</th>
                                    <th>Giá</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php foreach ($order['items'] as $item): ?>

                                    <tr>

                                        <td width="100">

                                            <img
                                            src="/project1/<?php echo $item['image']; ?>"
                                            class="img-fluid rounded"
                                            style="height:80px; object-fit:cover;">

                                        </td>

                                        <td>

                                            <?php echo $item['name']; ?>

                                        </td>

                                        <td>

                                            <?php echo $item['quantity']; ?>

                                        </td>

                                        <td class="fw-bold text-danger">

                                            <?php
                                            echo number_format(
                                                $item['price'],
                                                0,
                                                ',',
                                                '.'
                                            );
                                            ?> VND

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                    <div class="text-end mt-4">

                        <h4 class="fw-bold text-success">

                            Tổng tiền:
                            <?php
                            echo number_format(
                                $order['total'],
                                0,
                                ',',
                                '.'
                            );
                            ?> VND

                        </h4>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<?php include 'app/views/shares/footer.php'; ?>