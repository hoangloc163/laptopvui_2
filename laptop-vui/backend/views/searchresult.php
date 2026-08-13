<section class="product-section">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Tìm kiếm</span>
            <h1><?= htmlspecialchars($titlePage) ?></h1>
        </div>
        <span class="section-note"><?= count($results) ?> kết quả</span>
    </div>

    <?php if (empty($results)): ?>
        <div class="empty-state">
            <i class="bi bi-search"></i>
            <h2>Chưa tìm thấy sản phẩm phù hợp</h2>
            <p>Thử từ khóa ngắn hơn hoặc xem các sản phẩm nổi bật.</p>
            <a class="btn btn-primary" href="<?= ROOT_URL ?>">Về trang chủ</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($results as $sp): ?>
                <?php include "views/partials/product-card.php"; ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
