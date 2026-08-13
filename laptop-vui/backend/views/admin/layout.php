<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($tittlePage ?? 'Quản trị Laptop Vui') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= PUBLIC_URL ?>css/admin.css" rel="stylesheet">
    <link href="<?= PUBLIC_URL ?>css/admin-storefront.css" rel="stylesheet">
</head>
<body class="admin-body <?= !empty($isLoginPage) ? 'login-page' : '' ?>">
<?php if (!empty($isLoginPage)): ?>
    <main class="admin-login-shell">
        <a class="admin-login-brand" href="<?= ROOT_URL ?>"><i class="bi bi-laptop"></i><span>LAPTOP VUI<small>Khu vực quản trị</small></span></a>
        <?php if (!empty($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success_message']) ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>
        <?php include $viewnoidung; ?>
    </main>
<?php else: ?>
    <div class="admin-shell">
        <aside class="admin-sidebar" id="adminSidebar">
            <a class="admin-brand" href="<?= ROOT_URL ?>admin"><i class="bi bi-laptop"></i><span>LAPTOP VUI<small>Admin panel</small></span></a>
            <?php include 'menu.php'; ?>
            <div class="sidebar-footer"><a href="<?= ROOT_URL ?>" target="_blank"><i class="bi bi-box-arrow-up-right"></i>Xem cửa hàng</a></div>
        </aside>

        <div class="admin-main">
            <header class="admin-topbar">
                <button class="sidebar-toggle" type="button" id="sidebarToggle" aria-label="Mở menu"><i class="bi bi-list"></i></button>
                <div><span>Trang quản trị</span><strong><?= htmlspecialchars($tittlePage ?? '') ?></strong></div>
                <div class="admin-user"><span><i class="bi bi-person-circle"></i><b><?= htmlspecialchars($_SESSION['hoten'] ?? 'Admin') ?></b></span><a href="<?= ROOT_URL ?>admin/logout" title="Đăng xuất"><i class="bi bi-box-arrow-right"></i></a></div>
            </header>

            <main class="admin-content">
                <?php if (!empty($_SESSION['success_message'])): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert"><?= htmlspecialchars($_SESSION['success_message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>
                <?php if (!empty($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert"><?= htmlspecialchars($_SESSION['error_message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
                <?php include $viewnoidung; ?>
            </main>
        </div>
        <button class="sidebar-backdrop" id="sidebarBackdrop" aria-label="Đóng menu"></button>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(() => {
    const sidebar = document.getElementById('adminSidebar');
    const toggle = document.getElementById('sidebarToggle');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (!sidebar || !toggle || !backdrop) return;
    const close = () => document.body.classList.remove('sidebar-open');
    toggle.addEventListener('click', () => document.body.classList.toggle('sidebar-open'));
    backdrop.addEventListener('click', close);
    window.addEventListener('resize', () => { if (window.innerWidth >= 992) close(); });
})();
</script>
</body>
</html>
