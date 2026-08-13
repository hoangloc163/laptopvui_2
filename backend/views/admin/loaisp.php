<div class="admin-page-heading">
    <div><span class="admin-eyebrow">Phân loại sản phẩm</span><h1>Quản lý danh mục</h1><p>Sắp xếp và kiểm soát các nhóm sản phẩm trên menu cửa hàng.</p></div>
    <a class="btn btn-primary" href="<?= ROOT_URL ?>admin/addloai"><i class="bi bi-plus-lg me-1"></i>Thêm danh mục</a>
</div>

<div class="admin-panel">
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead><tr><th>Tên danh mục</th><th>Thứ tự</th><th>Trạng thái</th><th>Thao tác</th></tr></thead>
            <tbody>
            <?php $hasCategories = false; foreach ($listLoaiSP as $loai): $hasCategories = true; ?>
                <tr>
                    <td data-label="Tên danh mục"><strong><?= htmlspecialchars($loai['ten_loai']) ?></strong></td>
                    <td data-label="Thứ tự"><?= (int)$loai['thutu'] ?></td>
                    <td data-label="Trạng thái"><span class="status-badge <?= (int)$loai['anhien'] === 1 ? 'status-active' : 'status-hidden' ?>"><?= (int)$loai['anhien'] === 1 ? 'Đang hiện' : 'Đang ẩn' ?></span></td>
                    <td data-label="Thao tác"><div class="table-actions"><a class="icon-button" href="<?= ROOT_URL ?>admin/editloai?id=<?= (int)$loai['id_loai'] ?>" title="Sửa"><i class="bi bi-pencil"></i></a><a class="icon-button danger" href="<?= ROOT_URL ?>admin/deleteloai?id=<?= (int)$loai['id_loai'] ?>" title="Xóa" onclick="return confirm('Xóa danh mục này?')"><i class="bi bi-trash3"></i></a></div></td>
                </tr>
            <?php endforeach; ?>
            <?php if (!$hasCategories): ?><tr><td colspan="4" class="empty-cell">Chưa có danh mục.</td></tr><?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if ($tongSoTrang > 1): ?>
<nav class="admin-pagination">
    <?php for ($i = 1; $i <= $tongSoTrang; $i++): ?><a class="<?= $i === $pageNum ? 'active' : '' ?>" href="<?= ROOT_URL ?>admin/loai?page=<?= $i ?>"><?= $i ?></a><?php endfor; ?>
</nav>
<?php endif; ?>
