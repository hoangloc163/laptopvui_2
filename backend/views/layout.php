<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Laptop Vui - cửa hàng laptop trực tuyến dễ mua, dễ chọn.">
    <title><?= htmlspecialchars($titlePage ?? 'Laptop Vui') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= PUBLIC_URL ?>css/style.css" rel="stylesheet">
    <link href="<?= PUBLIC_URL ?>css/storefront.css" rel="stylesheet">
</head>
<body>
    <header class="site-header">
        <div class="container-xl">
            <?php include "header.php"; ?>
        </div>
    </header>

    <div class="site-navigation sticky-top">
        <?php include "menu.php"; ?>
    </div>

    <main class="site-main">
        <div class="container-xl">
            <?php if (!empty($_SESSION['success_message'])): ?>
                <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?= htmlspecialchars($_SESSION['success_message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (!empty($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    <?= htmlspecialchars($_SESSION['error_message']) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
                </div>
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            <?php
                $fullWidthViews = ['home.php', 'login.php', 'register.php', 'checkout.php', 'showcart.php', 'showcart_empty.php'];
                $showAside = !in_array($view ?? '', $fullWidthViews, true) && !empty($spxn);
            ?>
            <div class="row g-4 py-4">
                <article class="<?= $showAside ? 'col-lg-9' : 'col-12' ?>">
                    <?php include $view; ?>
                </article>
                <?php if ($showAside): ?>
                    <aside class="col-lg-3">
                        <?php include "aside.php"; ?>
                    </aside>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container-xl">
            <?php include "footer.php"; ?>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
