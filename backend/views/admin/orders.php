<div class="admin-page-heading">
    <div><span class="admin-eyebrow">Bán hàng</span><h1>Quản lý đơn hàng</h1><p>Theo dõi thông tin khách hàng và giá trị từng đơn.</p></div>
</div>

<div class="admin-panel">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Mã đơn</th><th>Khách hàng</th><th>Liên hệ</th><th>Địa chỉ</th><th>Số lượng</th><th>Tổng tiền</th><th></th></tr></thead>
            <tbody>
            <?php if (empty($listDonHang)): ?>
                <tr><td colspan="7" class="empty-cell">Chưa có đơn hàng.</td></tr>
            <?php else: ?>
                <?php foreach ($listDonHang as $order): ?>
                    <tr>
                        <td data-label="Mã đơn"><strong>#<?= (int)$order['id_dh'] ?></strong></td>
                        <td data-label="Khách hàng"><?= htmlspecialchars($order['hoten']) ?></td>
                        <td data-label="Liên hệ"><small><?= htmlspecialchars($order['email']) ?><br><?= htmlspecialchars($order['dienthoai']) ?></small></td>
                        <td data-label="Địa chỉ"><span class="truncate-text"><?= htmlspecialchars($order['diachi']) ?></span></td>
                        <td data-label="Số lượng"><?= (int)$order['tong_soluong'] ?></td>
                        <td data-label="Tổng tiền"><strong><?= number_format((int)$order['tong_tien'], 0, ',', '.') ?> ₫</strong></td>
                        <td><a class="icon-button" href="<?= ROOT_URL ?>admin/order?id=<?= (int)$order['id_dh'] ?>" title="Xem chi tiết"><i class="bi bi-eye"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($tongSoTrang > 1): ?>
<nav class="admin-pagination"><?php for ($i = 1; $i <= $tongSoTrang; $i++): ?><a class="<?= $i === $pageNum ? 'active' : '' ?>" href="<?= ROOT_URL ?>admin/orders?page=<?= $i ?>"><?= $i ?></a><?php endfor; ?></nav>
<?php endif; ?>
