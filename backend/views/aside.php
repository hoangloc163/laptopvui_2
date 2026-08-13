<div class="sidebar-card">
    <div class="sidebar-heading">
        <span><i class="bi bi-fire"></i></span>
        <div><strong>Được xem nhiều</strong><small>Sản phẩm đang được quan tâm</small></div>
    </div>
    <div class="sidebar-products">
        <?php foreach ($spxn as $sp): ?>
            <?php
                $price = ((int)($sp['gia_km'] ?? 0) > 0 && (int)$sp['gia_km'] < (int)$sp['gia']) ? (int)$sp['gia_km'] : (int)$sp['gia'];
            ?>
            <a class="sidebar-product" href="<?= ROOT_URL ?>sp?id=<?= (int)$sp['id_sp'] ?>">
                <img src="<?= ROOT_URL . ltrim($sp['hinh'], '/') ?>" alt="<?= htmlspecialchars($sp['ten_sp']) ?>" loading="lazy">
                <span><strong><?= htmlspecialchars($sp['ten_sp']) ?></strong><small><?= number_format($price, 0, ',', '.') ?> ₫</small></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<div class="sidebar-help">
    <i class="bi bi-chat-heart"></i>
    <h3>Cần tư vấn?</h3>
    <p>Chọn sản phẩm phù hợp với học tập, văn phòng hoặc giải trí.</p>
    <a href="<?= ROOT_URL ?>tk">Tìm sản phẩm</a>
</div>
