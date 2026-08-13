<?php
require_once "models/sanpham.php";

class SanphamController
{
    private sanpham $model;
    protected $listloai;

    public function __construct()
    {
        $this->model = new sanpham();
        $this->listloai = $this->model->layListLoai();
    }

    public function index(): void
    {
        $spnb = $this->model->sanphamNoiBat(8);
        $spxn = $this->model->sanphamXemNhieu(8);
        $titlePage = "Laptop Vui - Mua laptop dễ dàng";
        $view = "home.php";
        include "views/layout.php";
    }

    public function detail(): void
    {
        global $params;
        $id = max(0, (int)($params['id'] ?? 0));
        $sp = $this->model->detail($id);

        if (!$sp || (int)$sp['anhien'] !== 1) {
            http_response_code(404);
            die("Không tìm thấy sản phẩm.");
        }

        $this->model->tangLuotXem($id);
        $spxn = $this->model->sanphamXemNhieu(5);
        $titlePage = $sp['ten_sp'];
        $view = "detail.php";
        include "views/layout.php";
    }

    public function cat(): void
    {
        global $params;
        $idloai = max(0, (int)($params['idloai'] ?? 0));
        $pageNum = max(1, (int)($params['page'] ?? 1));
        $pageSize = 12;
        $demsoSP = $this->model->demSPTrongLoai($idloai);
        $tongSoTrang = max(1, (int)ceil($demsoSP / $pageSize));
        $pageNum = min($pageNum, $tongSoTrang);
        $pagePrev = max(1, $pageNum - 1);
        $pageNext = min($tongSoTrang, $pageNum + 1);
        $listsp = $this->model->sanphamTrongLoai($idloai, $pageNum, $pageSize);
        $ten_loai = $this->model->layTenLoai($idloai);

        if (!$ten_loai) {
            http_response_code(404);
            die("Không tìm thấy danh mục.");
        }

        $spxn = $this->model->sanphamXemNhieu(5);
        $titlePage = $ten_loai;
        $view = "sptrongloai.php";
        include "views/layout.php";
    }

    public function addtocart(): void
    {
        global $params;
        $idSp = max(0, (int)($params['id'] ?? 0));
        $soLuong = max(1, min(99, (int)($params['soluong'] ?? 1)));
        $sp = $this->model->detail($idSp);

        if (!$sp || (int)$sp['anhien'] !== 1) {
            $_SESSION['error_message'] = "Sản phẩm không tồn tại hoặc đang tạm ẩn.";
            header("Location: " . ROOT_URL);
            exit();
        }

        $_SESSION['cart'] ??= [];
        $_SESSION['cart'][$idSp] = min(99, (int)($_SESSION['cart'][$idSp] ?? 0) + $soLuong);
        $_SESSION['success_message'] = "Đã thêm sản phẩm vào giỏ hàng.";
        header("Location: " . ROOT_URL . "showcart");
        exit();
    }

    public function updatecart(): void
    {
        $quantities = $_POST['soluong'] ?? [];
        if (!is_array($quantities)) {
            header("Location: " . ROOT_URL . "showcart");
            exit();
        }

        foreach ($quantities as $idSp => $soLuong) {
            $idSp = (int)$idSp;
            $soLuong = (int)$soLuong;
            if ($idSp <= 0) {
                continue;
            }
            if ($soLuong <= 0) {
                unset($_SESSION['cart'][$idSp]);
            } else {
                $_SESSION['cart'][$idSp] = min(99, $soLuong);
            }
        }

        $_SESSION['success_message'] = "Đã cập nhật giỏ hàng.";
        header("Location: " . ROOT_URL . "showcart");
        exit();
    }

    public function removefromcart(): void
    {
        global $params;
        $idSp = (int)($params['id'] ?? 0);
        unset($_SESSION['cart'][$idSp]);
        $_SESSION['success_message'] = "Đã xóa sản phẩm khỏi giỏ hàng.";
        header("Location: " . ROOT_URL . "showcart");
        exit();
    }

    public function clearcart(): void
    {
        unset($_SESSION['cart']);
        $_SESSION['success_message'] = "Đã làm trống giỏ hàng.";
        header("Location: " . ROOT_URL . "showcart");
        exit();
    }

    public function showcart(): void
    {
        $spxn = $this->model->sanphamXemNhieu(5);
        $titlePage = "Giỏ hàng";
        $view = empty($_SESSION['cart']) ? "showcart_empty.php" : "showcart.php";
        include "views/layout.php";
    }

    public function checkout(): void
    {
        if (empty($_SESSION['cart'])) {
            $_SESSION['error_message'] = "Giỏ hàng đang trống.";
            header("Location: " . ROOT_URL . "showcart");
            exit();
        }

        $spxn = $this->model->sanphamXemNhieu(5);
        $titlePage = "Thanh toán";
        $view = "checkout.php";
        include "views/layout.php";
    }

    public function checkout_(): void
    {
        if (empty($_SESSION['cart'])) {
            $_SESSION['error_message'] = "Giỏ hàng đang trống.";
            header("Location: " . ROOT_URL . "showcart");
            exit();
        }

        $hoten = trim(strip_tags($_POST['hoten'] ?? ''));
        $email = trim($_POST['email'] ?? '');
        $diachi = trim(strip_tags($_POST['diachi'] ?? ''));
        $dienthoai = trim(strip_tags($_POST['dienthoai'] ?? ''));

        if ($hoten === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $diachi === '') {
            $_SESSION['error_message'] = "Vui lòng nhập đầy đủ và đúng thông tin nhận hàng.";
            header("Location: " . ROOT_URL . "checkout");
            exit();
        }

        if (!preg_match('/^[0-9+\s().-]{8,20}$/', $dienthoai)) {
            $_SESSION['error_message'] = "Số điện thoại không hợp lệ.";
            header("Location: " . ROOT_URL . "checkout");
            exit();
        }

        try {
            $idDh = $this->model->taoDonHang($hoten, $email, $diachi, $dienthoai, $_SESSION['cart']);
            unset($_SESSION['cart']);
            $_SESSION['success_message'] = "Đặt hàng thành công. Mã đơn hàng của bạn là #{$idDh}.";
            header("Location: " . ROOT_URL);
            exit();
        } catch (Throwable $e) {
            $_SESSION['error_message'] = "Không thể lưu đơn hàng. Vui lòng thử lại.";
            header("Location: " . ROOT_URL . "checkout");
            exit();
        }
    }

    public function searchForm(): void
    {
        $spxn = $this->model->sanphamXemNhieu(5);
        $titlePage = "Tìm kiếm sản phẩm";
        $view = "searchform.php";
        include "views/layout.php";
    }

    public function searchResult(): void
    {
        $keyword = trim(strip_tags($_POST['keyword'] ?? $_GET['keyword'] ?? ''));
        $results = $keyword === '' ? [] : $this->model->timKiemSanPham($keyword, 1, 50, 'ten_sp', 'ASC', true);
        $spxn = $this->model->sanphamXemNhieu(5);
        $titlePage = $keyword === '' ? "Tìm kiếm sản phẩm" : "Kết quả tìm kiếm cho '{$keyword}'";
        $view = "searchresult.php";
        include "views/layout.php";
    }
}
