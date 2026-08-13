<?php
    $checkoutTotal = 0;
    $checkoutItems = [];
    foreach ($_SESSION['cart'] as $idSp => $soLuong) {
        $product = $this->model->detail((int)$idSp);
        if (!$product) continue;
        $price = ((int)$product['gia_km'] > 0 && (int)$product['gia_km'] < (int)$product['gia']) ? (int)$product['gia_km'] : (int)$product['gia'];
        $checkoutTotal += $price * (int)$soLuong;
        $checkoutItems[] = ['product' => $product, 'quantity' => (int)$soLuong, 'price' => $price];
    }
?>
<div class="checkout-page">
    <div class="page-heading"><div><span class="eyebrow">Bước cuối cùng</span><h1>Thông tin nhận hàng</h1></div></div>
    <div class="checkout-layout">
        <form class="checkout-form" action="<?= ROOT_URL ?>checkout_" method="post">
            <div class="form-section-title"><span>1</span><div><h2>Thông tin khách hàng</h2><p>Nhập thông tin để cửa hàng liên hệ xác nhận đơn.</p></div></div>
            <div class="form-grid">
                <div class="form-group full"><label for="hoten">Họ và tên</label><input id="hoten" type="text" name="hoten" value="<?= htmlspecialchars($_SESSION['hoten'] ?? '') ?>" required autocomplete="name"></div>
                <div class="form-group"><label for="email">Email</label><input id="email" type="email" name="email" value="<?= htmlspecialchars($_SESSION['email'] ?? '') ?>" required autocomplete="email"></div>
                <div class="form-group"><label for="dienthoai">Điện thoại</label><input id="dienthoai" type="tel" name="dienthoai" required autocomplete="tel" placeholder="0900 000 000"></div>
                <div class="form-group full"><label for="diachi">Địa chỉ nhận hàng</label><textarea id="diachi" name="diachi" rows="3" required autocomplete="street-address"></textarea></div>
            </div>
            <button class="btn btn-primary btn-lg" type="submit"><i class="bi bi-check2-circle me-2"></i>Xác nhận đặt hàng</button>
        </form>

        <aside class="checkout-summary">
            <h2>Đơn hàng của bạn</h2>
            <?php foreach ($checkoutItems as $item): ?>
                <div class="checkout-item">
                    <img src="<?= ROOT_URL . ltrim($item['product']['hinh'], '/') ?>" alt="<?= htmlspecialchars($item['product']['ten_sp']) ?>">
                    <span><strong><?= htmlspecialchars($item['product']['ten_sp']) ?></strong><small><?= $item['quantity'] ?> × <?= number_format($item['price'], 0, ',', '.') ?> ₫</small></span>
                </div>
            <?php endforeach; ?>
            <div class="checkout-total"><span>Tổng thanh toán</span><strong><?= number_format($checkoutTotal, 0, ',', '.') ?> ₫</strong></div>
            <p><i class="bi bi-info-circle"></i> Đây là đơn hàng cơ bản. Cửa hàng sẽ liên hệ để xác nhận giao hàng.</p>
        </aside>
    </div>
</div>
