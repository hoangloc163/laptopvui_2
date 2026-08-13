<div class="auth-card">
    <div class="auth-icon"><i class="bi bi-person-plus"></i></div>
    <span class="eyebrow">Tạo tài khoản mới</span>
    <h1>Đăng ký</h1>
    <p>Chỉ cần vài thông tin cơ bản để bắt đầu.</p>
    <form action="<?= ROOT_URL ?>register_" method="post">
        <div class="form-group"><label for="register-name">Họ và tên</label><div class="input-icon"><i class="bi bi-person"></i><input id="register-name" type="text" name="hoten" minlength="2" required autocomplete="name"></div></div>
        <div class="form-group"><label for="register-email">Email</label><div class="input-icon"><i class="bi bi-envelope"></i><input id="register-email" type="email" name="email" required autocomplete="email"></div></div>
        <div class="form-group"><label for="register-password">Mật khẩu</label><div class="input-icon"><i class="bi bi-lock"></i><input id="register-password" type="password" name="matkhau" minlength="6" required autocomplete="new-password"></div><small>Tối thiểu 6 ký tự.</small></div>
        <button class="btn btn-primary btn-lg w-100" type="submit">Tạo tài khoản</button>
    </form>
    <div class="auth-footer">Đã có tài khoản? <a href="<?= ROOT_URL ?>login">Đăng nhập</a></div>
</div>
