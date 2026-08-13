<section class="product-section">
    <div class="section-heading">
        <div>
            <span class="eyebrow">Danh mục sản phẩm</span>
            <h1><?= htmlspecialchars($ten_loai) ?></h1>
        </div>
        <span class="section-note"><?= number_format($demsoSP) ?> sản phẩm</span>
    </div>

    <?php if ($demsoSP === 0): ?>
        <div class="empty-state">
            <i class="bi bi-box-seam"></i>
            <h2>Danh mục chưa có sản phẩm</h2>
            <p>Hãy quay lại sau hoặc xem các danh mục khác.</p>
            <a class="btn btn-primary" href="<?= ROOT_URL ?>">Về trang chủ</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach ($listsp as $sp): ?>
                <?php include "views/partials/product-card.php"; ?>
            <?php endforeach; ?>
        </div>

        <?php if ($tongSoTrang > 1): ?>
            <nav class="pagination-wrap" aria-label="Phân trang sản phẩm">
                <a class="page-link-custom <?= $pageNum <= 1 ? 'disabled' : '' ?>" href="<?= ROOT_URL ?>loai?idloai=<?= $idloai ?>&page=<?= $pagePrev ?>"><i class="bi bi-chevron-left"></i></a>
                <?php for ($i = max(1, $pageNum - 2); $i <= min($tongSoTrang, $pageNum + 2); $i++): ?>
                    <a class="page-link-custom <?= $i === $pageNum ? 'active' : '' ?>" href="<?= ROOT_URL ?>loai?idloai=<?= $idloai ?>&page=<?= $i ?>"><?= $i ?></a>
                <?php endfor; ?>
                <a class="page-link-custom <?= $pageNum >= $tongSoTrang ? 'disabled' : '' ?>" href="<?= ROOT_URL ?>loai?idloai=<?= $idloai ?>&page=<?= $pageNext ?>"><i class="bi bi-chevron-right"></i></a>
            </nav>
        <?php endif; ?>
    <?php endif; ?>
</section>
