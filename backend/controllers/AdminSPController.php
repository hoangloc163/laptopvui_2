<?php
require_once "models/sanpham.php";

class AdminSPController
{
    private sanpham $model;

    public function __construct()
    {
        $this->model = new sanpham();
    }

    public function index(): void
    {
        $this->checkLoginAdmin();
        global $params;

        $keyword = trim($params['keyword'] ?? '');
        $pageNum = max(1, (int)($params['page'] ?? 1));
        $pageSize = 10;
        $sort = $params['sort'] ?? '';
        $order = strtoupper($params['order'] ?? '');

        if ($keyword !== '') {
            $demsoSP = $this->model->demSP($keyword, false);
            $listsp = $this->model->timKiemSanPham($keyword, $pageNum, $pageSize, $sort ?: 'ten_sp', $order ?: 'ASC', false);
        } else {
            $demsoSP = $this->model->demSP('', false);
            $listsp = $this->model->danhsachsanpham($pageNum, $pageSize, $sort, $order);
        }

        $tongSoTrang = max(1, (int)ceil($demsoSP / $pageSize));
        $tittlePage = "Quản lý sản phẩm";
        $viewnoidung = "views/admin/sanphamds.php";
        include "views/admin/layout.php";
    }

    public function add(): void
    {
        $this->checkLoginAdmin();
        $listLoaiSP = $this->model->layTatCaLoai();
        $tittlePage = "Thêm sản phẩm";
        $viewnoidung = "views/admin/sanphamthem.php";
        include "views/admin/layout.php";
    }

    public function add_(): void
    {
        $this->checkLoginAdmin();
        [$idLoai, $tenSp, $ngay, $gia, $giaKm, $anhien, $hot, $mota] = $this->validatedProductInput();
        $filePath = $this->uploadImage(true);

        $this->model->luusanpham($idLoai, $tenSp, $ngay, $gia, $giaKm, $anhien, $hot, $mota, $filePath);
        $_SESSION['success_message'] = "Đã thêm sản phẩm mới.";
        header("Location: " . ROOT_URL . "admin/sp");
        exit();
    }

    public function edit(): void
    {
        $this->checkLoginAdmin();
        global $params;

        $id = (int)($params['id'] ?? 0);
        $sp = $this->model->detail($id);
        if (!$sp) {
            http_response_code(404);
            die("Không tìm thấy sản phẩm.");
        }

        $listLoaiSP = $this->model->layTatCaLoai();
        $tittlePage = "Chỉnh sửa sản phẩm";
        $viewnoidung = "views/admin/sanphamsua.php";
        include "views/admin/layout.php";
    }

    public function edit_(): void
    {
        $this->checkLoginAdmin();
        $idSp = (int)($_POST['id_sp'] ?? 0);
        $existing = $this->model->detail($idSp);
        if (!$existing) {
            http_response_code(404);
            die("Không tìm thấy sản phẩm.");
        }

        [$idLoai, $tenSp, $ngay, $gia, $giaKm, $anhien, $hot, $mota] = $this->validatedProductInput();
        $filePath = $this->uploadImage(false) ?: $existing['hinh'];

        $this->model->capnhatsanpham($idSp, $idLoai, $tenSp, $ngay, $gia, $giaKm, $anhien, $hot, $mota, $filePath);
        $_SESSION['success_message'] = "Đã cập nhật sản phẩm.";
        header("Location: " . ROOT_URL . "admin/sp");
        exit();
    }

    public function delete(): void
    {
        $this->checkLoginAdmin();
        global $params;

        $id = (int)($params['id'] ?? 0);
        if ($id > 0) {
            $sp = $this->model->detail($id);
            $this->model->deleteSanPham($id);

            if ($sp && !empty($sp['hinh'])) {
                $absolutePath = BASE_DIR . '/' . ltrim($sp['hinh'], '/');
                if (is_file($absolutePath)) {
                    @unlink($absolutePath);
                }
            }
        }

        $_SESSION['success_message'] = "Đã xóa sản phẩm.";
        header("Location: " . ROOT_URL . "admin/sp");
        exit();
    }

    private function validatedProductInput(): array
    {
        $idLoai = (int)($_POST['id_loai'] ?? 0);
        $tenSp = trim(strip_tags($_POST['ten_sp'] ?? ''));
        $ngay = trim(strip_tags($_POST['ngay'] ?? ''));
        $gia = (int)($_POST['gia'] ?? 0);
        $giaKm = (int)($_POST['gia_km'] ?? 0);
        $anhien = (int)($_POST['anhien'] ?? 0);
        $hot = (int)($_POST['hot'] ?? 0);
        $mota = trim($_POST['mota'] ?? '');

        if ($idLoai <= 0 || !$this->model->layTenLoai($idLoai)) {
            die("Vui lòng chọn danh mục hợp lệ.");
        }
        if ($tenSp === '') {
            die("Tên sản phẩm không được để trống.");
        }
        if ($ngay === '' || !DateTime::createFromFormat('Y-m-d', $ngay)) {
            die("Ngày sản phẩm không hợp lệ.");
        }
        if ($gia <= 0 || $giaKm < 0 || ($giaKm > 0 && $giaKm > $gia)) {
            die("Giá sản phẩm không hợp lệ.");
        }
        if (!in_array($anhien, [0, 1], true) || !in_array($hot, [0, 1], true)) {
            die("Trạng thái sản phẩm không hợp lệ.");
        }

        return [$idLoai, $tenSp, $ngay, $gia, $giaKm, $anhien, $hot, $mota];
    }

    private function uploadImage(bool $required): ?string
    {
        if (!isset($_FILES['hinh']) || $_FILES['hinh']['error'] === UPLOAD_ERR_NO_FILE) {
            if ($required) {
                die("Vui lòng chọn ảnh sản phẩm.");
            }
            return null;
        }

        if ($_FILES['hinh']['error'] !== UPLOAD_ERR_OK) {
            die("Tải ảnh lên thất bại.");
        }

        if ((int)$_FILES['hinh']['size'] > 5 * 1024 * 1024) {
            die("Ảnh sản phẩm không được vượt quá 5 MB.");
        }

        $allowedMime = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['hinh']['tmp_name']);
        if (!isset($allowedMime[$mime])) {
            die("Chỉ chấp nhận ảnh JPG, PNG hoặc WEBP.");
        }

        $uploadDir = BASE_DIR . '/upload';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
            die("Không thể tạo thư mục upload.");
        }

        $filename = bin2hex(random_bytes(12)) . '.' . $allowedMime[$mime];
        $destination = $uploadDir . '/' . $filename;
        if (!move_uploaded_file($_FILES['hinh']['tmp_name'], $destination)) {
            die("Không thể lưu ảnh sản phẩm.");
        }

        return 'upload/' . $filename;
    }

    private function checkLoginAdmin(): void
    {
        if (!isset($_SESSION['vaitro']) || (int)$_SESSION['vaitro'] !== 1) {
            $_SESSION['back'] = $_SERVER['REQUEST_URI'];
            header("Location: " . ROOT_URL . "admin/login");
            exit();
        }
    }
}
