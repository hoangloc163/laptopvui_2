<div class="admin-page-heading"><div><span class="admin-eyebrow">Cập nhật dữ liệu</span><h1>Chỉnh sửa sản phẩm</h1><p>Thay đổi thông tin, giá, trạng thái hoặc hình ảnh sản phẩm.</p></div><a class="btn btn-light" href="<?= ROOT_URL ?>admin/sp"><i class="bi bi-arrow-left me-1"></i>Quay lại</a></div>
<form class="admin-form" action="<?= ROOT_URL ?>admin/editsp_" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id_sp" value="<?= (int)$sp['id_sp'] ?>">
    <div class="form-card"><h2>Thông tin cơ bản</h2><div class="admin-form-grid">
        <div class="form-group full"><label for="ten_sp">Tên sản phẩm</label><input id="ten_sp" type="text" name="ten_sp" value="<?= htmlspecialchars($sp['ten_sp']) ?>" required></div>
        <div class="form-group"><label for="id_loai">Danh mục</label><select id="id_loai" name="id_loai" required><?php foreach ($listLoaiSP as $loai): ?><option value="<?= (int)$loai['id_loai'] ?>" <?= (int)$sp['id_loai'] === (int)$loai['id_loai'] ? 'selected' : '' ?>><?= htmlspecialchars($loai['ten_loai']) ?><?= (int)$loai['anhien'] === 0 ? ' (đang ẩn)' : '' ?></option><?php endforeach; ?></select></div>
        <div class="form-group"><label for="ngay">Ngày đăng</label><input id="ngay" type="date" name="ngay" value="<?= htmlspecialchars($sp['ngay']) ?>" required></div>
        <div class="form-group"><label for="gia">Giá bán</label><input id="gia" type="number" name="gia" min="1" value="<?= (int)$sp['gia'] ?>" required></div>
        <div class="form-group"><label for="gia_km">Giá khuyến mãi</label><input id="gia_km" type="number" name="gia_km" min="0" value="<?= (int)$sp['gia_km'] ?>"><small>Nhập 0 nếu không giảm giá.</small></div>
        <div class="form-group full"><label for="mota">Mô tả</label><textarea id="mota" name="mota" rows="6"><?= htmlspecialchars($sp['mota']) ?></textarea></div>
    </div></div>
    <div class="form-card"><h2>Hiển thị và hình ảnh</h2><div class="admin-form-grid">
        <div class="form-group"><label>Trạng thái</label><div class="choice-group"><label><input type="radio" name="anhien" value="1" <?= (int)$sp['anhien'] === 1 ? 'checked' : '' ?>> Hiện sản phẩm</label><label><input type="radio" name="anhien" value="0" <?= (int)$sp['anhien'] === 0 ? 'checked' : '' ?>> Ẩn sản phẩm</label></div></div>
        <div class="form-group"><label>Nổi bật</label><div class="choice-group"><label><input type="radio" name="hot" value="0" <?= (int)$sp['hot'] === 0 ? 'checked' : '' ?>> Bình thường</label><label><input type="radio" name="hot" value="1" <?= (int)$sp['hot'] === 1 ? 'checked' : '' ?>> Nổi bật</label></div></div>
        <div class="form-group full image-update"><img src="<?= ROOT_URL . ltrim($sp['hinh'], '/') ?>" alt="Ảnh hiện tại"><div><label for="hinh">Thay ảnh sản phẩm</label><input id="hinh" type="file" name="hinh" accept="image/jpeg,image/png,image/webp"><small>Để trống nếu muốn giữ ảnh hiện tại.</small></div></div>
    </div></div>
    <div class="form-actions"><a class="btn btn-light" href="<?= ROOT_URL ?>admin/sp">Hủy</a><button class="btn btn-primary" type="submit"><i class="bi bi-check2 me-1"></i>Cập nhật sản phẩm</button></div>
</form>
