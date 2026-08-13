<?php
/**
 * Cấu hình ứng dụng.
 *
 * ROOT_URL:
 *   - Mặc định "/" (dùng khi deploy trên Render.com hoặc bất kỳ hosting nào
 *     phục vụ app ở gốc domain).
 *   - Nếu chạy trong Laragon/XAMPP tại http://localhost/banhang/, hãy đặt
 *     biến môi trường APP_ROOT_URL="/banhang/" trước khi khởi động PHP,
 *     hoặc sửa trực tiếp giá trị mặc định bên dưới.
 *
 * DB_DRIVER:
 *   - "sqlite" (mặc định): tự tạo demo.sqlite, không cần cài MySQL.
 *   - "mysql": cần điền DB_HOST, DB_NAME, DB_USER, DB_PASS phù hợp.
 */

// ---------- Cấu hình cơ sở dữ liệu ----------
const DB_DRIVER = "sqlite"; // "sqlite" | "mysql"
const SQLITE_PATH = __DIR__ . "/data/demo.sqlite";

// Chỉ dùng khi DB_DRIVER = "mysql"
const DB_HOST = "localhost";
const DB_NAME = "db";
const DB_USER = "root";
const DB_PASS = "";

// ---------- Cấu hình URL ----------
const BASE_DIR = __DIR__;

// Cho phép override qua biến môi trường để chạy local và deploy dùng chung code.
$rootUrlEnv = getenv('APP_ROOT_URL');
define('ROOT_URL', ($rootUrlEnv !== false && $rootUrlEnv !== '') ? $rootUrlEnv : '/');
define('PUBLIC_URL', ROOT_URL . 'public/');
