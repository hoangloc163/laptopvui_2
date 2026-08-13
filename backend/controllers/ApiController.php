<?php
class ApiController
{
    private function jsonResponse($data, $status = 200) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit;
        }
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($status);
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public function products() {
        $model = new sanpham();
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        $products = $model->sanphamXemNhieu($limit);
        
        // Cập nhật đường dẫn hình ảnh tuyệt đối để app mobile tải được
        // Lấy IP/Host hiện tại từ request (ví dụ: http://10.0.2.2:8080/banhang/)
        $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseFullUrl = $scheme . '://' . $host . rtrim(ROOT_URL, '/');

        foreach ($products as &$p) {
            $p['hinh_url'] = $baseFullUrl . '/' . ltrim($p['hinh'], '/');
        }
        $this->jsonResponse(['status' => 'success', 'data' => $products]);
    }

    public function productDetail() {
        $id = $_GET['id'] ?? 0;
        $model = new sanpham();
        $sp = $model->detail($id);
        if ($sp) {
            $scheme = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $baseFullUrl = $scheme . '://' . $host . rtrim(ROOT_URL, '/');
            $sp['hinh_url'] = $baseFullUrl . '/' . ltrim($sp['hinh'], '/');
            $this->jsonResponse(['status' => 'success', 'data' => $sp]);
        }
        $this->jsonResponse(['status' => 'error', 'message' => 'Sản phẩm không tồn tại'], 404);
    }
    
    public function categories() {
        $model = new sanpham();
        $cats = $model->layListLoai();
        $this->jsonResponse(['status' => 'success', 'data' => $cats]);
    }

    public function checkout() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) $data = $_POST;
        
        $hoten = $data['hoten'] ?? '';
        $email = $data['email'] ?? '';
        $diachi = $data['diachi'] ?? '';
        $dienthoai = $data['dienthoai'] ?? '';
        $cart = $data['cart'] ?? []; 
        
        if (empty($hoten) || empty($cart)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Vui lòng điền đủ thông tin cá nhân và giỏ hàng'], 400);
        }
        
        $model = new sanpham();
        try {
            $orderId = $model->taoDonHang($hoten, $email, $diachi, $dienthoai, $cart);
            $this->jsonResponse(['status' => 'success', 'message' => 'Đặt hàng thành công', 'order_id' => $orderId]);
        } catch (Exception $e) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Có lỗi xảy ra: ' . $e->getMessage()], 400);
        }
    }

    public function login() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) $data = $_POST;
        
        $email = strtolower(trim($data['email'] ?? ''));
        $matkhau = $data['matkhau'] ?? '';
        
        if (empty($email) || empty($matkhau)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Vui lòng nhập đủ email và mật khẩu'], 400);
        }
        
        require_once "models/user.php";
        $model = new user();
        $user = $model->checkuser($email, $matkhau);
        
        if (!is_array($user)) {
            $this->jsonResponse(['status' => 'error', 'message' => $user], 401);
        }
        
        // Loại bỏ mật khẩu trước khi trả về
        unset($user['matkhau']);
        $this->jsonResponse(['status' => 'success', 'data' => $user]);
    }

    public function register() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) $data = $_POST;
        
        $hoten = trim(strip_tags($data['hoten'] ?? ''));
        $email = strtolower(trim($data['email'] ?? ''));
        $matkhau = $data['matkhau'] ?? '';
        
        if (mb_strlen($hoten) < 2) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Họ tên phải có ít nhất 2 ký tự.'], 400);
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Email không hợp lệ.'], 400);
        }
        if (strlen($matkhau) < 6) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Mật khẩu phải có ít nhất 6 ký tự.'], 400);
        }
        
        require_once "models/user.php";
        $model = new user();
        
        if ($model->emailExists($email)) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Email này đã được sử dụng.'], 400);
        }
        
        $id = $model->luuuser($hoten, $email, password_hash($matkhau, PASSWORD_BCRYPT));
        $this->jsonResponse(['status' => 'success', 'message' => 'Đăng ký thành công', 'user_id' => $id]);
    }
}
