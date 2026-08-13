<?php
    $regularPrice = (int)$sp['gia'];
    $salePrice = (int)$sp['gia_km'];
    $hasSale = $salePrice > 0 && $salePrice < $regularPrice;
    $displayPrice = $hasSale ? $salePrice : $regularPrice;
?>
<div class="product-detail">
    <div class="detail-image-wrap">
        <?php if ($hasSale): ?><span class="detail-sale">Đang giảm giá</span><?php endif; ?>
        <img src="<?= ROOT_URL . ltrim($sp['hinh'], '/') ?>" alt="<?= htmlspecialchars($sp['ten_sp']) ?>">
    </div>
    <div class="detail-info">
        <nav class="detail-breadcrumb"><a href="<?= ROOT_URL ?>">Trang chủ</a><i class="bi bi-chevron-right"></i><span>Sản phẩm</span></nav>
        <h1><?= htmlspecialchars($sp['ten_sp']) ?></h1>
        <div class="detail-price">
            <strong><?= number_format($displayPrice, 0, ',', '.') ?> ₫</strong>
            <?php if ($hasSale): ?><del><?= number_format($regularPrice, 0, ',', '.') ?> ₫</del><?php endif; ?>
        </div>
        <div class="detail-meta">
            <span><i class="bi bi-check-circle-fill"></i> Đang hiển thị</span>
            <span><i class="bi bi-eye"></i> <?= number_format((int)($sp['soluotxem'] ?? 0)) ?> lượt xem</span>
            <span><i class="bi bi-calendar3"></i> <?= date('d/m/Y', strtotime($sp['ngay'])) ?></span>
        </div>
        <div class="detail-description">
            <h2>Mô tả sản phẩm</h2>
            <p><?= nl2br(htmlspecialchars($sp['mota'] ?: 'Thông tin sản phẩm đang được cập nhật.')) ?></p>
        </div>
        <form class="add-cart-form" action="<?= ROOT_URL ?>addtocart" method="get">
            <input type="hidden" name="id" value="<?= (int)$sp['id_sp'] ?>">
            <label for="quantity">Số lượng</label>
            <input id="quantity" type="number" name="soluong" value="1" min="1" max="99">
            <button class="btn btn-primary btn-lg" type="submit"><i class="bi bi-cart-plus me-2"></i>Thêm vào giỏ hàng</button>
        </form>
        <div class="purchase-notes">
            <span><i class="bi bi-shield-check"></i> Thông tin sản phẩm minh bạch</span>
            <span><i class="bi bi-phone"></i> Đặt hàng thuận tiện trên điện thoại</span>
        </div>
    </div>
</div>
