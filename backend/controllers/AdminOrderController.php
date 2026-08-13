<?php
require_once "models/donhang.php";

class AdminOrderController
{
    private donhang $model;

    public function __construct()
    {
        $this->model = new donhang();
    }

    public function index(): void
    {
        $this->checkLoginAdmin();
        global $params;
        $pageNum = max(1, (int)($params['page'] ?? 1));
        $pageSize = 10;
        $tongDonHang = $this->model->demDonHang();
        $tongSoTrang = max(1, (int)ceil($tongDonHang / $pageSize));
        $listDonHang = $this->model->danhSachDonHang($pageNum, $pageSize);
        $tittlePage = "Quản lý đơn hàng";
        $viewnoidung = "views/admin/orders.php";
        include "views/admin/layout.php";
    }

    public function detail(): void
    {
        $this->checkLoginAdmin();
        global $params;
        $idDh = (int)($params['id'] ?? 0);
        $donHang = $this->model->chiTietDonHang($idDh);
        $items = $this->model->sanPhamTrongDonHang($idDh);
        if (!$donHang) {
            http_response_code(404);
            die("Không tìm thấy đơn hàng.");
        }
        $tittlePage = "Chi tiết đơn hàng #{$idDh}";
        $viewnoidung = "views/admin/orderdetail.php";
        include "views/admin/layout.php";
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
