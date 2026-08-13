<?php
require_once "config.php";

// ---------- Cấu hình session bảo mật ----------
// Bật cookie_secure khi request đến qua HTTPS (Render tự thêm HTTPS + header
// X-Forwarded-Proto). SameSite=Lax để tránh gửi cookie kèm cross-site request.
$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// ---------- CORS cho mobile app ----------
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// ---------- Autoload controllers ----------
spl_autoload_register(function ($class) {
    $file = BASE_DIR . "/controllers/" . $class . ".php";
    if (is_file($file)) {
        require_once $file;
    }
});

// ---------- Bảng route ----------
$router = [
    'get' => [
        '' => [new SanphamController(), 'index'],
        'sp' => [new SanphamController(), 'detail'],
        'loai' => [new SanphamController(), 'cat'],
        'addtocart' => [new SanphamController(), 'addtocart'],
        'showcart' => [new SanphamController(), 'showcart'],
        'removefromcart' => [new SanphamController(), 'removefromcart'],
        'clearcart' => [new SanphamController(), 'clearcart'],
        'checkout' => [new SanphamController(), 'checkout'],
        'tk' => [new SanphamController(), 'searchForm'],
        'register' => [new UserController(), 'register'],
        'login' => [new UserController(), 'login'],
        'logout' => [new UserController(), 'logout'],

        'admin' => [new AdminController(), 'index'],
        'admin/login' => [new AdminController(), 'login'],
        'admin/logout' => [new AdminController(), 'logout'],

        'admin/sp' => [new AdminSPController(), 'index'],
        'admin/addsp' => [new AdminSPController(), 'add'],
        'admin/editsp' => [new AdminSPController(), 'edit'],
        'admin/deletesp' => [new AdminSPController(), 'delete'],

        'admin/loai' => [new AdminLoaiController(), 'index'],
        'admin/addloai' => [new AdminLoaiController(), 'add'],
        'admin/editloai' => [new AdminLoaiController(), 'edit'],
        'admin/deleteloai' => [new AdminLoaiController(), 'delete'],

        'admin/orders' => [new AdminOrderController(), 'index'],
        'admin/order' => [new AdminOrderController(), 'detail'],

        'api/products' => [new ApiController(), 'products'],
        'api/product' => [new ApiController(), 'productDetail'],
        'api/categories' => [new ApiController(), 'categories'],
    ],
    'post' => [
        'tk' => [new SanphamController(), 'searchResult'],
        'updatecart' => [new SanphamController(), 'updatecart'],
        'checkout_' => [new SanphamController(), 'checkout_'],
        'register_' => [new UserController(), 'register_'],
        'login_' => [new UserController(), 'login_'],

        'admin/login_' => [new AdminController(), 'login_'],
        'admin/addsp_' => [new AdminSPController(), 'add_'],
        'admin/editsp_' => [new AdminSPController(), 'edit_'],
        'admin/addloai_' => [new AdminLoaiController(), 'add_'],
        'admin/editloai_' => [new AdminLoaiController(), 'edit_'],

        'api/checkout' => [new ApiController(), 'checkout'],
        'api/login' => [new ApiController(), 'login'],
        'api/register' => [new ApiController(), 'register'],
    ],
];

// ---------- Phân giải route ----------
$method = strtolower($_SERVER['REQUEST_METHOD'] ?? 'get');
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$basePath = rtrim(parse_url(ROOT_URL, PHP_URL_PATH) ?: '/', '/');

if ($basePath !== '' && $basePath !== '/' && str_starts_with($requestPath, $basePath)) {
    $requestPath = substr($requestPath, strlen($basePath));
}

$route = strtolower(trim($requestPath, '/'));
$params = array_merge($_GET, $_POST);

if (!isset($router[$method])) {
    http_response_code(405);
    die("Phương thức không được hỗ trợ: " . htmlspecialchars($method));
}

if (!isset($router[$method][$route])) {
    http_response_code(404);
    die("Không tìm thấy đường dẫn: " . htmlspecialchars($route));
}

call_user_func($router[$method][$route]);
