<section class="home-showcase">
    <div class="category-panel">
        <div class="category-title"><i class="bi bi-list"></i> Danh mục laptop</div>
        <?php foreach ($this->listloai as $loai): ?>
            <a href="<?= ROOT_URL ?>loai?idloai=<?= (int)$loai['id_loai'] ?>"><i class="bi bi-laptop"></i><span><?= htmlspecialchars($loai['ten_loai']) ?></span><i class="bi bi-chevron-right"></i></a>
        <?php endforeach; ?>
        <a href="<?= ROOT_URL ?>tk"><i class="bi bi-search"></i><span>Tìm theo nhu cầu</span><i class="bi bi-chevron-right"></i></a>
        <a href="#featured-products"><i class="bi bi-lightning-charge"></i><span>Ưu đãi hôm nay</span><i class="bi bi-chevron-right"></i></a>
    </div>
    <div class="hero-section">
        <div class="hero-content">
            <span class="eyebrow">Laptop chính hãng · Giá tốt mỗi ngày</span>
            <h1>Chọn đúng laptop.<br><span>Mua sắm thật vui.</span></h1>
            <p>Giá minh bạch, thông tin dễ hiểu và nhiều lựa chọn phù hợp cho học tập, văn phòng lẫn gaming.</p>
            <div class="hero-actions"><a class="btn btn-primary" href="#featured-products">Xem ưu đãi</a><a class="btn btn-light" href="<?= ROOT_URL ?>tk">Tìm laptop phù hợp</a></div>
        </div>
        <div class="hero-visual" aria-hidden="true"><i class="bi bi-laptop"></i></div>
    </div>
    <div class="promo-column">
        <a href="#featured-products"><i class="bi bi-lightning-charge-fill"></i><span><small>DEAL NỔI BẬT</small><strong>Giảm đến 15%</strong><em>Xem sản phẩm</em></span></a>
        <a href="<?= ROOT_URL ?>showcart"><i class="bi bi-truck"></i><span><small>GIAO HÀNG</small><strong>Nhanh & tiện lợi</strong><em>Kiểm tra giỏ hàng</em></span></a>
    </div>
</section>
<section class="benefit-grid">
    <div><i class="bi bi-patch-check"></i><strong>Sản phẩm chính hãng</strong><span>Thông tin rõ ràng, minh bạch</span></div>
    <div><i class="bi bi-truck"></i><strong>Giao hàng tiện lợi</strong><span>Đặt hàng nhanh trên mọi thiết bị</span></div>
    <div><i class="bi bi-arrow-repeat"></i><strong>Mua sắm an tâm</strong><span>Hỗ trợ tận tình khi bạn cần</span></div>
    <div><i class="bi bi-headset"></i><strong>Tư vấn dễ dàng</strong><span>Chọn đúng máy theo nhu cầu</span></div>
</section>
<section id="featured-products" class="product-section">
    <div class="section-heading"><div><h2>Laptop nổi bật</h2><span class="section-note">Sản phẩm được quan tâm trong tuần</span></div><a class="section-link" href="<?= ROOT_URL ?>tk">Xem tất cả <i class="bi bi-chevron-right"></i></a></div>
    <div class="product-grid"><?php foreach ($spnb as $sp): include "views/partials/product-card.php"; endforeach; ?></div>
</section>
<section class="product-section">
    <div class="section-heading"><div><h2>Sản phẩm xem nhiều</h2><span class="section-note">Những lựa chọn đang được khách hàng chú ý</span></div></div>
    <div class="product-grid"><?php foreach ($spxn as $sp): include "views/partials/product-card.php"; endforeach; ?></div>
</section>
