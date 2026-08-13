<div class="auth-card">
    <div class="auth-icon"><i class="bi bi-person-check"></i></div>
    <span class="eyebrow">Chào mừng trở lại</span>
    <h1>Đăng nhập</h1>
    <p>Đăng nhập để thao tác nhanh hơn khi mua hàng.</p>
    <form action="<?= ROOT_URL ?>login_" method="post">
        <div class="form-group"><label for="login-email">Email</label><div class="input-icon"><i class="bi bi-envelope"></i><input id="login-email" type="email" name="email" required autocomplete="email"></div></div>
        <div class="form-group"><label for="login-password">Mật khẩu</label><div class="input-icon"><i class="bi bi-lock"></i><input id="login-password" type="password" name="matkhau" required autocomplete="current-password"></div></div>
        <button class="btn btn-primary btn-lg w-100" type="submit">Đăng nhập</button>
    </form>
    <div class="auth-footer">Chưa có tài khoản? <a href="<?= ROOT_URL ?>register">Đăng ký ngay</a></div>
</div>
