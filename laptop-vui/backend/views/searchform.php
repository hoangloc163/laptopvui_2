<div class="auth-card search-card">
    <div class="auth-icon"><i class="bi bi-search"></i></div>
    <h1>Tìm kiếm sản phẩm</h1>
    <p>Nhập tên laptop hoặc từ khóa bạn đang quan tâm.</p>
    <form action="<?= ROOT_URL ?>tk" method="post">
        <div class="input-group input-group-lg">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input class="form-control" type="search" name="keyword" placeholder="Ví dụ: laptop gaming" required autofocus>
            <button class="btn btn-primary" type="submit">Tìm kiếm</button>
        </div>
    </form>
</div>
