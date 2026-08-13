<?php $nextOrder = strtoupper($order) === 'ASC' ? 'DESC' : 'ASC'; ?>
<div class="admin-page-heading">
    <div><span class="admin-eyebrow">Kho sản phẩm</span><h1>Quản lý sản phẩm</h1><p>Thêm, sửa, ẩn hiện và sắp xếp sản phẩm.</p></div>
    <a class="btn btn-primary" href="<?= ROOT_URL ?>admin/addsp"><i class="bi bi-plus-lg me-1"></i>Thêm sản phẩm</a>
</div>

<div class="admin-toolbar">
    <form class="admin-search" action="<?= ROOT_URL ?>admin/sp" method="get">
        <i class="bi bi-search"></i><input type="search" name="keyword" value="<?= htmlspecialchars($keyword) ?>" placeholder="Tìm tên sản phẩm..."><button type="submit">Tìm kiếm</button>
    </form>
    <?php if ($keyword !== ''): ?><a class="clear-filter" href="<?= ROOT_URL ?>admin/sp"><i class="bi bi-x-circle"></i>Xóa bộ lọc</a><?php endif; ?>
</div>

<div class="admin-panel">
    <div class="admin-table-wrap">
        <table class="admin-table product-admin-table">
            <thead><tr>
                <th>Sản phẩm</th><th>Danh mục</th>
                <th><a href="<?= ROOT_URL ?>admin/sp?keyword=<?= urlencode($keyword) ?>&sort=gia&order=<?= $nextOrder ?>">Giá <i class="bi bi-arrow-down-up"></i></a></th>
                <th>Trạng thái</th><th>Nổi bật</th>
                <th><a href="<?= ROOT_URL ?>admin/sp?keyword=<?= urlencode($keyword) ?>&sort=ngay&order=<?= $nextOrder ?>">Ngày <i class="bi bi-arrow-down-up"></i></a></th>
                <th>Thao tác</th>
            </tr></thead>
            <tbody>
            <?php $hasProducts = false; foreach ($listsp as $sp): $hasProducts = true; ?>
                <tr>
                    <td data-label="Sản phẩm"><div class="product-cell"><img src="<?= ROOT_URL . ltrim($sp['hinh'], '/') ?>" alt=""><span><strong><?= htmlspecialchars($sp['ten_sp']) ?></strong><small>#<?= (int)$sp['id_sp'] ?></small></span></div></td>
                    <td data-label="Danh mục"><?= htmlspecialchars($this->model->layTenLoai($sp['id_loai']) ?? 'Chưa phân loại') ?></td>
                    <td data-label="Giá"><strong><?= number_format((int)$sp['gia'], 0, ',', '.') ?> ₫</strong><?php if ((int)$sp['gia_km'] > 0): ?><small class="d-block text-danger">KM: <?= number_format((int)$sp['gia_km'], 0, ',', '.') ?> ₫</small><?php endif; ?></td>
                    <td data-label="Trạng thái"><span class="status-badge <?= (int)$sp['anhien'] === 1 ? 'status-active' : 'status-hidden' ?>"><?= (int)$sp['anhien'] === 1 ? 'Đang hiện' : 'Đang ẩn' ?></span></td>
                    <td data-label="Nổi bật"><?= (int)$sp['hot'] === 1 ? '<span class="status-badge status-featured">Nổi bật</span>' : '<span class="text-muted">Bình thường</span>' ?></td>
                    <td data-label="Ngày"><?= date('d/m/Y', strtotime($sp['ngay'])) ?></td>
                    <td data-label="Thao tác"><div class="table-actions"><a class="icon-button" href="<?= ROOT_URL ?>admin/editsp?id=<?= (int)$sp['id_sp'] ?>" title="Sửa"><i class="bi bi-pencil"></i></a><a class="icon-button danger" href="<?= ROOT_URL ?>admin/deletesp?id=<?= (int)$sp['id_sp'] ?>" title="Xóa" onclick="return confirm('Xóa sản phẩm này?')"><i class="bi bi-trash3"></i></a></div></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$hasProducts): ?><tr><td colspan="7" class="empty-cell">Không tìm thấy sản phẩm.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($tongSoTrang > 1): ?>
<nav class="admin-pagination">
    <?php for ($i = 1; $i <= $tongSoTrang; $i++): ?>
        <a class="<?= $i === $pageNum ? 'active' : '' ?>" href="<?= ROOT_URL ?>admin/sp?page=<?= $i ?>&keyword=<?= urlencode($keyword) ?>&sort=<?= urlencode($sort) ?>&order=<?= urlencode($order) ?>"><?= $i ?></a>
    <?php endfor; ?>
</nav>
<?php endif; ?>
