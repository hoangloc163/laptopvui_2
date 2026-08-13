<div class="admin-page-heading"><div><span class="admin-eyebrow">Cập nhật danh mục</span><h1>Chỉnh sửa danh mục</h1><p>Điều chỉnh tên, thứ tự và trạng thái hiển thị.</p></div><a class="btn btn-light" href="<?= ROOT_URL ?>admin/loai"><i class="bi bi-arrow-left me-1"></i>Quay lại</a></div>
<form class="admin-form compact-form" action="<?= ROOT_URL ?>admin/editloai_" method="post">
    <input type="hidden" name="id_loai" value="<?= (int)$loai['id_loai'] ?>">
    <div class="form-card"><div class="admin-form-grid">
        <div class="form-group full"><label for="ten_loai">Tên danh mục</label><input id="ten_loai" type="text" name="ten_loai" value="<?= htmlspecialchars($loai['ten_loai']) ?>" required></div>
        <div class="form-group"><label for="thutu">Thứ tự hiển thị</label><input id="thutu" type="number" name="thutu" min="0" value="<?= (int)$loai['thutu'] ?>"></div>
        <div class="form-group"><label>Trạng thái</label><div class="choice-group"><label><input type="radio" name="anhien" value="1" <?= (int)$loai['anhien'] === 1 ? 'checked' : '' ?>> Hiện</label><label><input type="radio" name="anhien" value="0" <?= (int)$loai['anhien'] === 0 ? 'checked' : '' ?>> Ẩn</label></div></div>
    </div></div>
    <div class="form-actions"><a class="btn btn-light" href="<?= ROOT_URL ?>admin/loai">Hủy</a><button class="btn btn-primary" type="submit">Cập nhật danh mục</button></div>
</form>
