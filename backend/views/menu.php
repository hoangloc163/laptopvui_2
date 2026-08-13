<?php $cartCount = !empty($_SESSION['cart']) ? array_sum(array_map('intval', $_SESSION['cart'])) : 0; ?>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#storeNavbar" aria-controls="storeNavbar" aria-expanded="false" aria-label="Mở menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="mobile-brand d-lg-none" href="<?= ROOT_URL ?>">Laptop Vui</a>

        <a class="cart-link d-lg-none" href="<?= ROOT_URL ?>showcart" aria-label="Giỏ hàng">
            <i class="bi bi-cart3"></i>
            <?php if ($cartCount > 0): ?><span><?= $cartCount ?></span><?php endif; ?>
        </a>

        <div class="collapse navbar-collapse" id="storeNavbar">
            <ul class="navbar-nav me-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="<?= ROOT_URL ?>"><i class="bi bi-house-door me-1"></i>Trang chủ</a></li>
                <?php if (!empty($this->listloai)): ?>
                    <?php foreach ($this->listloai as $loai): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= ROOT_URL ?>loai?idloai=<?= (int)$loai['id_loai'] ?>">
                                <?= htmlspecialchars($loai['ten_loai']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <form class="nav-search" action="<?= ROOT_URL ?>tk" method="post" role="search">
                <i class="bi bi-search"></i>
                <input type="search" name="keyword" placeholder="Tìm laptop..." aria-label="Tìm kiếm sản phẩm" required>
                <button type="submit">Tìm</button>
            </form>

            <div class="nav-actions">
                <?php if (!empty($_SESSION['id_user'])): ?>
                    <span class="user-greeting"><i class="bi bi-person-circle"></i><?= htmlspecialchars($_SESSION['hoten'] ?? 'Tài khoản') ?></span>
                    <?php if ((int)($_SESSION['vaitro'] ?? 0) === 1): ?>
                        <a href="<?= ROOT_URL ?>admin" title="Quản trị"><i class="bi bi-speedometer2"></i></a>
                    <?php endif; ?>
                    <a href="<?= ROOT_URL ?>logout" title="Đăng xuất"><i class="bi bi-box-arrow-right"></i></a>
                <?php else: ?>
                    <a class="text-link" href="<?= ROOT_URL ?>login">Đăng nhập</a>
                    <a class="text-link" href="<?= ROOT_URL ?>register">Đăng ký</a>
                <?php endif; ?>
                <a class="cart-link d-none d-lg-flex" href="<?= ROOT_URL ?>showcart" title="Giỏ hàng">
                    <i class="bi bi-cart3"></i>
                    <?php if ($cartCount > 0): ?><span><?= $cartCount ?></span><?php endif; ?>
                </a>
            </div>
        </div>
    </div>
</nav>
