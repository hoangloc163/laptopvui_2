<div class="admin-page-heading">
    <div><span class="admin-eyebrow">Chi tiết đơn hàng</span><h1>Đơn hàng #<?= (int)$donHang['id_dh'] ?></h1><p>Thông tin giao hàng và danh sách sản phẩm.</p></div>
    <a class="btn btn-light" href="<?= ROOT_URL ?>admin/orders"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
</div>

<div class="order-detail-grid">
    <section class="admin-panel order-customer-card">
        <div class="panel-heading"><div><h2>Thông tin khách hàng</h2><p>Dữ liệu dùng để liên hệ và giao hàng.</p></div></div>
        <dl class="order-info-list">
            <div><dt>Họ tên</dt><dd><?= htmlspecialchars($donHang['hoten']) ?></dd></div>
            <div><dt>Email</dt><dd><?= htmlspecialchars($donHang['email']) ?></dd></div>
            <div><dt>Điện thoại</dt><dd><?= htmlspecialchars($donHang['dienthoai']) ?></dd></div>
            <div><dt>Địa chỉ</dt><dd><?= nl2br(htmlspecialchars($donHang['diachi'])) ?></dd></div>
        </dl>
    </section>

    <aside class="admin-panel order-total-card">
        <h2>Tổng quan</h2>
        <div><span>Số lượng</span><strong><?= (int)$donHang['tong_soluong'] ?> sản phẩm</strong></div>
        <div class="order-grand-total"><span>Tổng tiền</span><strong><?= number_format((int)$donHang['tong_tien'], 0, ',', '.') ?> ₫</strong></div>
    </aside>
</div>

<div class="admin-panel mt-4">
    <div class="panel-heading"><div><h2>Sản phẩm trong đơn</h2><p>Giá được lưu tại thời điểm khách đặt hàng.</p></div></div>
    <div class="admin-table-wrap"><table class="admin-table"><thead><tr><th>Sản phẩm</th><th>Số lượng</th><th>Đơn giá</th><th>Thành tiền</th></tr></thead><tbody>
        <?php foreach ($items as $item): ?><tr><td data-label="Sản phẩm"><strong><?= htmlspecialchars($item['ten_sp']) ?></strong><small class="d-block text-muted">#<?= (int)$item['id_sp'] ?></small></td><td data-label="Số lượng"><?= (int)$item['soluong'] ?></td><td data-label="Đơn giá"><?= number_format((int)$item['gia'], 0, ',', '.') ?> ₫</td><td data-label="Thành tiền"><strong><?= number_format((int)$item['thanh_tien'], 0, ',', '.') ?> ₫</strong></td></tr><?php endforeach; ?>
    </tbody></table></div>
</div>
