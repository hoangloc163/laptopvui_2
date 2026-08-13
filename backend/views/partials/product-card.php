<?php
    $productId = (int)($sp['id_sp'] ?? 0);
    $productName = htmlspecialchars($sp['ten_sp'] ?? 'Sản phẩm');
    $imageUrl = ROOT_URL . ltrim($sp['hinh'] ?? '', '/');
    $regularPrice = (int)($sp['gia'] ?? 0);
    $salePrice = (int)($sp['gia_km'] ?? 0);
    $hasSale = $salePrice > 0 && $salePrice < $regularPrice;
    $displayPrice = $hasSale ? $salePrice : $regularPrice;
?>
<div class="product-card">
    <a class="product-image" href="<?= ROOT_URL ?>sp?id=<?= $productId ?>">
        <?php if ($hasSale): ?>
            <span class="sale-badge">-<?= max(1, (int)round((1 - $salePrice / $regularPrice) * 100)) ?>%</span>
        <?php endif; ?>
        <img src="<?= htmlspecialchars($imageUrl) ?>" alt="<?= $productName ?>" loading="lazy">
    </a>
    <div class="product-body">
        <h3><a href="<?= ROOT_URL ?>sp?id=<?= $productId ?>"><?= $productName ?></a></h3>
        <div class="product-price">
            <strong><?= number_format($displayPrice, 0, ',', '.') ?> ₫</strong>
            <?php if ($hasSale): ?><del><?= number_format($regularPrice, 0, ',', '.') ?> ₫</del><?php endif; ?>
        </div>
        <div class="product-actions">
            <a class="btn btn-light" href="<?= ROOT_URL ?>sp?id=<?= $productId ?>">Chi tiết</a>
            <a class="btn btn-primary" href="<?= ROOT_URL ?>addtocart?id=<?= $productId ?>&soluong=1" aria-label="Thêm <?= $productName ?> vào giỏ">
                <i class="bi bi-cart-plus"></i>
            </a>
        </div>
    </div>
</div>
