<div class="footer-grid">
    <div>
        <a class="footer-brand" href="<?= ROOT_URL ?>"><i class="bi bi-laptop"></i>LAPTOP VUI</a>
        <p>Cửa hàng laptop trực tuyến cơ bản, dễ sử dụng trên cả máy tính và điện thoại.</p>
    </div>
    <div>
        <h3>Liên kết</h3>
        <a href="<?= ROOT_URL ?>">Trang chủ</a>
        <a href="<?= ROOT_URL ?>tk">Tìm kiếm</a>
        <a href="<?= ROOT_URL ?>showcart">Giỏ hàng</a>
    </div>
    <div>
        <h3>Tài khoản</h3>
        <?php if (empty($_SESSION['id_user'])): ?>
            <a href="<?= ROOT_URL ?>login">Đăng nhập</a>
            <a href="<?= ROOT_URL ?>register">Đăng ký</a>
        <?php else: ?>
            <span><?= htmlspecialchars($_SESSION['hoten'] ?? '') ?></span>
            <a href="<?= ROOT_URL ?>logout">Đăng xuất</a>
        <?php endif; ?>
    </div>
    <div>
        <h3>Hỗ trợ</h3>
        <span><i class="bi bi-envelope"></i> support@laptopvui.local</span>
        <span><i class="bi bi-clock"></i> 08:00 - 21:00</span>
    </div>
</div>
<div class="footer-bottom">© <?= date('Y') ?> Laptop Vui. Phát triển bởi TungLeDoThanh.</div>
