<div class="admin-page-heading"><div><span class="admin-eyebrow">Danh mục mới</span><h1>Thêm danh mục</h1><p>Tạo nhóm sản phẩm để khách hàng dễ tìm kiếm.</p></div><a class="btn btn-light" href="<?= ROOT_URL ?>admin/loai"><i class="bi bi-arrow-left me-1"></i>Quay lại</a></div>
<form class="admin-form compact-form" action="<?= ROOT_URL ?>admin/addloai_" method="post">
    <div class="form-card"><div class="admin-form-grid">
        <div class="form-group full"><label for="ten_loai">Tên danh mục</label><input id="ten_loai" type="text" name="ten_loai" required></div>
        <div class="form-group"><label for="thutu">Thứ tự hiển thị</label><input id="thutu" type="number" name="thutu" min="0" value="0"></div>
        <div class="form-group"><label>Trạng thái</label><div class="choice-group"><label><input type="radio" name="anhien" value="1" checked> Hiện</label><label><input type="radio" name="anhien" value="0"> Ẩn</label></div></div>
    </div></div>
    <div class="form-actions"><a class="btn btn-light" href="<?= ROOT_URL ?>admin/loai">Hủy</a><button class="btn btn-primary" type="submit">Lưu danh mục</button></div>
</form>
