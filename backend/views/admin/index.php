<div class="admin-page-heading">
    <div><span class="admin-eyebrow">Tổng quan hoạt động</span><h1>Bảng điều khiển</h1><p>Theo dõi nhanh dữ liệu chính của cửa hàng.</p></div>
    <a class="btn btn-primary" href="<?= ROOT_URL ?>admin/addsp"><i class="bi bi-plus-lg me-1"></i>Thêm sản phẩm</a>
</div>

<div class="stats-grid">
    <a class="stat-card" href="<?= ROOT_URL ?>admin/sp"><span class="stat-icon icon-blue"><i class="bi bi-box-seam"></i></span><span><small>Sản phẩm</small><strong><?= number_format((int)$stats['products']) ?></strong></span><i class="bi bi-arrow-up-right"></i></a>
    <a class="stat-card" href="<?= ROOT_URL ?>admin/loai"><span class="stat-icon icon-purple"><i class="bi bi-tags"></i></span><span><small>Danh mục</small><strong><?= number_format((int)$stats['categories']) ?></strong></span><i class="bi bi-arrow-up-right"></i></a>
    <a class="stat-card" href="<?= ROOT_URL ?>admin/orders"><span class="stat-icon icon-orange"><i class="bi bi-receipt"></i></span><span><small>Đơn hàng</small><strong><?= number_format((int)$stats['orders']) ?></strong></span><i class="bi bi-arrow-up-right"></i></a>
    <div class="stat-card"><span class="stat-icon icon-green"><i class="bi bi-cash-stack"></i></span><span><small>Giá trị đơn hàng</small><strong><?= number_format((int)$stats['revenue'], 0, ',', '.') ?> ₫</strong></span></div>
</div>

<div class="admin-panel">
    <div class="panel-heading"><div><h2>Đơn hàng gần đây</h2><p>5 đơn hàng mới nhất trong hệ thống.</p></div><a href="<?= ROOT_URL ?>admin/orders">Xem tất cả</a></div>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>Liên hệ</th><th>Số lượng</th><th>Tổng tiền</th><th></th></tr></thead>
            <tbody>
            <?php if (empty($recentOrders)): ?>
                <tr><td colspan="6" class="empty-cell">Chưa có đơn hàng.</td></tr>
            <?php else: ?>
                <?php foreach ($recentOrders as $order): ?>
                    <tr>
                        <td data-label="Mã đơn"><strong>#<?= (int)$order['id_dh'] ?></strong></td>
                        <td data-label="Khách hàng"><?= htmlspecialchars($order['hoten']) ?></td>
                        <td data-label="Liên hệ"><small><?= htmlspecialchars($order['email']) ?><br><?= htmlspecialchars($order['dienthoai']) ?></small></td>
                        <td data-label="Số lượng"><?= (int)$order['tong_soluong'] ?></td>
                        <td data-label="Tổng tiền"><strong><?= number_format((int)$order['tong_tien'], 0, ',', '.') ?> ₫</strong></td>
                        <td><a class="icon-button" href="<?= ROOT_URL ?>admin/order?id=<?= (int)$order['id_dh'] ?>"><i class="bi bi-eye"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
