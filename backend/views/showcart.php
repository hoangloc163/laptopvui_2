<?php
    $tongTien = 0;
    $tongSoLuong = 0;
?>
<div class="cart-page">
    <div class="page-heading">
        <div><span class="eyebrow">Đơn hàng của bạn</span><h1>Giỏ hàng</h1></div>
        <a class="btn btn-outline-danger btn-sm" href="<?= ROOT_URL ?>clearcart" onclick="return confirm('Làm trống toàn bộ giỏ hàng?')"><i class="bi bi-trash3 me-1"></i>Làm trống</a>
    </div>

    <form action="<?= ROOT_URL ?>updatecart" method="post">
        <div class="cart-layout">
            <div class="cart-list">
                <?php foreach ($_SESSION['cart'] as $idSp => $soLuong): ?>
                    <?php
                        $sp = $this->model->detail((int)$idSp);
                        if (!$sp) continue;
                        $regularPrice = (int)$sp['gia'];
                        $salePrice = (int)$sp['gia_km'];
                        $unitPrice = ($salePrice > 0 && $salePrice < $regularPrice) ? $salePrice : $regularPrice;
                        $lineTotal = $unitPrice * (int)$soLuong;
                        $tongTien += $lineTotal;
                        $tongSoLuong += (int)$soLuong;
                    ?>
                    <div class="cart-item">
                        <img src="<?= ROOT_URL . ltrim($sp['hinh'], '/') ?>" alt="<?= htmlspecialchars($sp['ten_sp']) ?>">
                        <div class="cart-item-info">
                            <a href="<?= ROOT_URL ?>sp?id=<?= (int)$sp['id_sp'] ?>"><?= htmlspecialchars($sp['ten_sp']) ?></a>
                            <span>Đơn giá: <?= number_format($unitPrice, 0, ',', '.') ?> ₫</span>
                            <a class="remove-item" href="<?= ROOT_URL ?>removefromcart?id=<?= (int)$sp['id_sp'] ?>"><i class="bi bi-x-circle"></i> Xóa</a>
                        </div>
                        <div class="cart-quantity">
                            <label for="qty-<?= (int)$sp['id_sp'] ?>">Số lượng</label>
                            <input id="qty-<?= (int)$sp['id_sp'] ?>" type="number" name="soluong[<?= (int)$sp['id_sp'] ?>]" value="<?= (int)$soLuong ?>" min="0" max="99">
                        </div>
                        <strong class="cart-line-total"><?= number_format($lineTotal, 0, ',', '.') ?> ₫</strong>
                    </div>
                <?php endforeach; ?>
                <div class="cart-list-actions">
                    <a class="btn btn-light" href="<?= ROOT_URL ?>"><i class="bi bi-arrow-left me-1"></i>Tiếp tục mua</a>
                    <button class="btn btn-outline-primary" type="submit"><i class="bi bi-arrow-repeat me-1"></i>Cập nhật giỏ</button>
                </div>
            </div>

            <aside class="cart-summary">
                <h2>Tóm tắt đơn hàng</h2>
                <div><span>Số lượng</span><strong><?= $tongSoLuong ?> sản phẩm</strong></div>
                <div><span>Tạm tính</span><strong><?= number_format($tongTien, 0, ',', '.') ?> ₫</strong></div>
                <div><span>Phí vận chuyển</span><strong>Liên hệ</strong></div>
                <div class="summary-total"><span>Tổng cộng</span><strong><?= number_format($tongTien, 0, ',', '.') ?> ₫</strong></div>
                <a class="btn btn-primary btn-lg w-100" href="<?= ROOT_URL ?>checkout">Tiến hành thanh toán</a>
                <small><i class="bi bi-shield-check"></i> Thông tin đặt hàng được xử lý nội bộ.</small>
            </aside>
        </div>
    </form>
</div>
