<?php
require_once "models/loai.php";

class AdminLoaiController
{
    private loai $model;

    public function __construct()
    {
        $this->model = new loai();
    }

    public function index(): void
    {
        $this->checkLoginAdmin();
        global $params;
        $pageNum = max(1, (int)($params['page'] ?? 1));
        $pageSize = 10;
        $demLoaiSP = $this->model->demLoaiSP();
        $tongSoTrang = max(1, (int)ceil($demLoaiSP / $pageSize));
        $listLoaiSP = $this->model->danhSachLoaiSP($pageNum, $pageSize);
        $tittlePage = "Quản lý danh mục";
        $viewnoidung = "views/admin/loaisp.php";
        include "views/admin/layout.php";
    }

    public function add(): void
    {
        $this->checkLoginAdmin();
        $tittlePage = "Thêm danh mục";
        $viewnoidung = "views/admin/loaispthem.php";
        include "views/admin/layout.php";
    }

    public function add_(): void
    {
        $this->checkLoginAdmin();
        [$tenLoai, $thuTu, $anHien] = $this->validatedInput();
        if ($this->model->isTenLoaiExists($tenLoai)) {
            die("Tên danh mục đã tồn tại.");
        }
        $this->model->luuloaisanpham($tenLoai, $thuTu, $anHien);
        $_SESSION['success_message'] = "Đã thêm danh mục.";
        header("Location: " . ROOT_URL . "admin/loai");
        exit();
    }

    public function edit(): void
    {
        $this->checkLoginAdmin();
        global $params;
        $id = (int)($params['id'] ?? 0);
        $loai = $this->model->detail($id);
        if (!$loai) {
            http_response_code(404);
            die("Không tìm thấy danh mục.");
        }
        $tittlePage = "Chỉnh sửa danh mục";
        $viewnoidung = "views/admin/loaispsua.php";
        include "views/admin/layout.php";
    }

    public function edit_(): void
    {
        $this->checkLoginAdmin();
        $idLoai = (int)($_POST['id_loai'] ?? 0);
        [$tenLoai, $thuTu, $anHien] = $this->validatedInput();
        $this->model->capnhatloai($idLoai, $tenLoai, $thuTu, $anHien);
        $_SESSION['success_message'] = "Đã cập nhật danh mục.";
        header("Location: " . ROOT_URL . "admin/loai");
        exit();
    }

    public function delete(): void
    {
        $this->checkLoginAdmin();
        global $params;
        $id = (int)($params['id'] ?? 0);
        if ($this->model->demSanPhamTrongLoai($id) > 0) {
            $_SESSION['error_message'] = "Không thể xóa danh mục đang có sản phẩm.";
        } else {
            $this->model->deleteLoaiSP($id);
            $_SESSION['success_message'] = "Đã xóa danh mục.";
        }
        header("Location: " . ROOT_URL . "admin/loai");
        exit();
    }

    private function validatedInput(): array
    {
        $tenLoai = trim(strip_tags($_POST['ten_loai'] ?? ''));
        $thuTu = max(0, (int)($_POST['thutu'] ?? 0));
        $anHien = (int)($_POST['anhien'] ?? 1);
        if ($tenLoai === '') {
            die("Tên danh mục không được để trống.");
        }
        if (!in_array($anHien, [0, 1], true)) {
            die("Trạng thái danh mục không hợp lệ.");
        }
        return [$tenLoai, $thuTu, $anHien];
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
