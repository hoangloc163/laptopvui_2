<div class="admin-page-heading"><div><span class="admin-eyebrow">Sản phẩm mới</span><h1>Thêm sản phẩm</h1><p>Nhập đầy đủ thông tin để sản phẩm hiển thị đúng trên cửa hàng.</p></div><a class="btn btn-light" href="<?= ROOT_URL ?>admin/sp"><i class="bi bi-arrow-left me-1"></i>Quay lại</a></div>
<form class="admin-form" action="<?= ROOT_URL ?>admin/addsp_" method="post" enctype="multipart/form-data">
    <div class="form-card"><h2>Thông tin cơ bản</h2><div class="admin-form-grid">
        <div class="form-group full"><label for="ten_sp">Tên sản phẩm</label><input id="ten_sp" type="text" name="ten_sp" required></div>
        <div class="form-group"><label for="id_loai">Danh mục</label><select id="id_loai" name="id_loai" required><option value="">-- Chọn danh mục --</option><?php foreach ($listLoaiSP as $loai): ?><option value="<?= (int)$loai['id_loai'] ?>"><?= htmlspecialchars($loai['ten_loai']) ?><?= (int)$loai['anhien'] === 0 ? ' (đang ẩn)' : '' ?></option><?php endforeach; ?></select></div>
        <div class="form-group"><label for="ngay">Ngày đăng</label><input id="ngay" type="date" name="ngay" value="<?= date('Y-m-d') ?>" required></div>
        <div class="form-group"><label for="gia">Giá bán</label><input id="gia" type="number" name="gia" min="1" required></div>
        <div class="form-group"><label for="gia_km">Giá khuyến mãi</label><input id="gia_km" type="number" name="gia_km" min="0" value="0"><small>Nhập 0 nếu không giảm giá.</small></div>
        <div class="form-group full"><label for="mota">Mô tả</label><textarea id="mota" name="mota" rows="6"></textarea></div>
    </div></div>
    <div class="form-card"><h2>Hiển thị và hình ảnh</h2><div class="admin-form-grid">
        <div class="form-group"><label>Trạng thái</label><div class="choice-group"><label><input type="radio" name="anhien" value="1" checked> Hiện sản phẩm</label><label><input type="radio" name="anhien" value="0"> Ẩn sản phẩm</label></div></div>
        <div class="form-group"><label>Nổi bật</label><div class="choice-group"><label><input type="radio" name="hot" value="0" checked> Bình thường</label><label><input type="radio" name="hot" value="1"> Nổi bật</label></div></div>
        <div class="form-group full"><label for="hinh">Ảnh sản phẩm</label><input id="hinh" type="file" name="hinh" accept="image/jpeg,image/png,image/webp" required><small>JPG, PNG hoặc WEBP; tối đa 5 MB.</small></div>
    </div></div>
    <div class="form-actions"><a class="btn btn-light" href="<?= ROOT_URL ?>admin/sp">Hủy</a><button class="btn btn-primary" type="submit"><i class="bi bi-check2 me-1"></i>Lưu sản phẩm</button></div>
</form>
