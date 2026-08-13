<div class="admin-login-card">
    <div class="auth-icon"><i class="bi bi-shield-lock"></i></div>
    <span class="admin-eyebrow">Khu vực dành cho quản trị viên</span>
    <h1>Đăng nhập quản trị</h1>
    <p>Sử dụng tài khoản có quyền admin để tiếp tục.</p>
    <form action="<?= ROOT_URL ?>admin/login_" method="post">
        <div class="form-group"><label for="admin-email">Email</label><div class="input-icon"><i class="bi bi-envelope"></i><input id="admin-email" type="email" name="email" required autocomplete="email"></div></div>
        <div class="form-group"><label for="admin-password">Mật khẩu</label><div class="input-icon"><i class="bi bi-lock"></i><input id="admin-password" type="password" name="matkhau" required autocomplete="current-password"></div></div>
        <button class="btn btn-primary btn-lg w-100" type="submit">Đăng nhập</button>
    </form>
    <a class="back-store" href="<?= ROOT_URL ?>"><i class="bi bi-arrow-left"></i>Về cửa hàng</a>
</div>
