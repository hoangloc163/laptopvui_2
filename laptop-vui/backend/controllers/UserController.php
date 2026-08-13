<?php
require_once "models/user.php";

class UserController
{
    private user $model;
    public $listloai;

    public function __construct()
    {
        $this->model = new user();
        $this->listloai = $this->model->layDanhSachLoai();
    }

    public function register(): void
    {
        $titlePage = "Đăng ký thành viên";
        $view = "register.php";
        include "views/layout.php";
    }

    public function register_(): void
    {
        $hoten = trim(strip_tags($_POST['hoten'] ?? ''));
        $email = strtolower(trim($_POST['email'] ?? ''));
        $matkhau = $_POST['matkhau'] ?? '';

        if (mb_strlen($hoten) < 2) {
            $_SESSION['error_message'] = "Họ tên phải có ít nhất 2 ký tự.";
            header("Location: " . ROOT_URL . "register");
            exit();
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error_message'] = "Email không hợp lệ.";
            header("Location: " . ROOT_URL . "register");
            exit();
        }
        if (strlen($matkhau) < 6) {
            $_SESSION['error_message'] = "Mật khẩu phải có ít nhất 6 ký tự.";
            header("Location: " . ROOT_URL . "register");
            exit();
        }
        if ($this->model->emailExists($email)) {
            $_SESSION['error_message'] = "Email này đã được sử dụng.";
            header("Location: " . ROOT_URL . "register");
            exit();
        }

        $this->model->luuuser($hoten, $email, password_hash($matkhau, PASSWORD_BCRYPT));
        $_SESSION['success_message'] = "Đăng ký thành công. Bạn có thể đăng nhập ngay.";
        header("Location: " . ROOT_URL . "login");
        exit();
    }

    public function login(): void
    {
        $titlePage = "Đăng nhập";
        $view = "login.php";
        include "views/layout.php";
    }

    public function login_(): void
    {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $matkhau = $_POST['matkhau'] ?? '';
        $user = $this->model->checkuser($email, $matkhau);

        if (!is_array($user)) {
            $_SESSION['error_message'] = $user;
            header("Location: " . ROOT_URL . "login");
            exit();
        }

        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['hoten'] = $user['hoten'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['vaitro'] = (int)($user['vaitro'] ?? 0);
        $_SESSION['success_message'] = "Đăng nhập thành công.";
        header("Location: " . ROOT_URL);
        exit();
    }

    public function logout(): void
    {
        unset($_SESSION['id_user'], $_SESSION['hoten'], $_SESSION['email'], $_SESSION['vaitro']);
        $_SESSION['success_message'] = "Bạn đã đăng xuất.";
        header("Location: " . ROOT_URL);
        exit();
    }
}
