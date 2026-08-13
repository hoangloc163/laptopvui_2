# Laptop Vui

Ứng dụng bán laptop trực tuyến gồm:

- **Backend PHP** (MVC + SQLite) — trang web bán hàng cho khách và trang quản trị cho admin.
- **Mobile app Expo** (React Native) — app di động gọi API từ backend, chạy trên Expo Go.

Dự án được thiết kế để **chạy được ngay không cần cài MySQL**. Backend dùng SQLite và tự sinh dữ liệu demo trong lần chạy đầu tiên.

---

## Mục lục

- [Tính năng](#tính-năng)
- [Cấu trúc thư mục](#cấu-trúc-thư-mục)
- [Yêu cầu hệ thống](#yêu-cầu-hệ-thống)
- [Chạy backend cục bộ](#chạy-backend-cục-bộ)
- [Đưa code lên GitHub](#đưa-code-lên-github)
- [Deploy backend lên Render.com](#deploy-backend-lên-rendercom)
- [Chạy mobile app trên Expo Go](#chạy-mobile-app-trên-expo-go)
- [Tài khoản demo](#tài-khoản-demo)
- [Danh sách API endpoint](#danh-sách-api-endpoint)
- [Chuyển sang MySQL](#chuyển-sang-mysql)
- [Xử lý lỗi thường gặp](#xử-lý-lỗi-thường-gặp)
- [Ghi chú bảo mật](#ghi-chú-bảo-mật)

---

## Tính năng

**Trang khách hàng (web)**

- Xem sản phẩm, phân trang theo danh mục, tìm kiếm theo từ khóa
- Giỏ hàng lưu bằng session (thêm / cập nhật số lượng / xoá / làm trống)
- Đặt hàng với thông tin nhận hàng
- Đăng ký / đăng nhập tài khoản

**Trang quản trị**

- Dashboard: tổng sản phẩm, danh mục, đơn hàng, doanh thu
- CRUD sản phẩm (kèm upload ảnh JPG/PNG/WEBP tối đa 5 MB)
- CRUD danh mục
- Xem danh sách và chi tiết đơn hàng

**Mobile app (Expo)**

- Duyệt sản phẩm, lọc theo danh mục
- Xem chi tiết, thêm vào giỏ (giỏ hàng ở client, độc lập với web)
- Đăng ký / đăng nhập / đăng xuất (lưu phiên bằng `expo-secure-store`)
- Đặt hàng qua API
- Mở panel admin trong WebView cho tài khoản có `vaitro = 1`

---

## Cấu trúc thư mục

```
laptop-vui/
├── backend/                 ← PHP + SQLite
│   ├── Dockerfile           ← image cho Render (PHP 8.2 + Apache)
│   ├── .htaccess            ← rewrite mọi request về index.php
│   ├── config.php           ← ROOT_URL, cấu hình DB
│   ├── index.php            ← router chính (web + API)
│   ├── dev-router.php       ← chỉ dùng cho `php -S` khi dev local
│   ├── controllers/
│   │   ├── AdminController.php
│   │   ├── AdminLoaiController.php
│   │   ├── AdminOrderController.php
│   │   ├── AdminSPController.php
│   │   ├── ApiController.php      ← endpoint cho mobile app
│   │   ├── SanphamController.php
│   │   └── UserController.php
│   ├── models/
│   │   ├── database.php     ← kết nối PDO + tự tạo bảng + seed demo
│   │   ├── donhang.php
│   │   ├── loai.php
│   │   ├── sanpham.php
│   │   └── user.php
│   ├── views/               ← template PHP (Bootstrap 5)
│   ├── public/css/          ← CSS
│   ├── upload/              ← ảnh sản phẩm
│   └── data/demo.sqlite     ← DB SQLite dùng cho demo
├── mobile_app/              ← Expo React Native
│   ├── App.js               ← navigator chính
│   ├── app.json             ← cấu hình Expo
│   ├── package.json
│   └── src/
│       ├── api/config.js    ← 👉 URL backend đặt ở đây
│       ├── context/         ← AuthContext, CartContext
│       └── screens/         ← Home, ProductDetail, Cart, Checkout, ...
├── .gitignore
└── README.md
```

---

## Yêu cầu hệ thống

Để chạy đầy đủ backend + mobile app:

| Thành phần    | Phiên bản                        | Ghi chú                                      |
| ------------- | -------------------------------- | -------------------------------------------- |
| PHP           | ≥ 8.1                            | cần các extension: `pdo_sqlite`, `mbstring`  |
| Node.js       | ≥ 20 (LTS)                       | để chạy Expo                                 |
| Expo Go       | mới nhất                         | cài trên điện thoại (Android / iOS)          |
| Git           | mới nhất                         | để push lên GitHub                           |
| Tài khoản     | GitHub + Render.com              | để deploy backend                            |

---

## Chạy backend cục bộ

### Cách 1 — Dùng PHP built-in server (nhanh nhất)

Yêu cầu: đã cài PHP 8.1+ với extension `pdo_sqlite` và `mbstring`.

Trên **Windows (PowerShell)**:

```powershell
cd backend
$env:APP_ROOT_URL = "/"
php -S 127.0.0.1:8080 dev-router.php
```

Trên **macOS / Linux**:

```bash
cd backend
APP_ROOT_URL=/ php -S 127.0.0.1:8080 dev-router.php
```

Mở trình duyệt tại `http://127.0.0.1:8080/`.

### Cách 2 — Dùng Laragon / XAMPP

Copy toàn bộ thư mục `backend/` vào `www/laptop-vui/` (Laragon) hoặc `htdocs/laptop-vui/` (XAMPP). Vì app nằm trong thư mục con, bạn cần đặt `ROOT_URL` phù hợp:

- **Sửa trực tiếp** `backend/config.php`: đổi dòng cuối thành `define('ROOT_URL', '/laptop-vui/backend/');`, hoặc
- **Đặt biến môi trường** `APP_ROOT_URL=/laptop-vui/backend/` trong cấu hình Apache.

Truy cập `http://localhost/laptop-vui/backend/`.

### Cách 3 — Dùng Docker cục bộ (giống môi trường Render)

```bash
cd backend
docker build -t laptop-vui .
docker run -p 8080:8080 laptop-vui
```

Mở `http://localhost:8080/`.

### Kiểm tra nhanh

Nếu chạy đúng, các URL sau đều trả HTTP 200:

- `/` — trang chủ
- `/api/categories` — JSON danh sách danh mục
- `/api/products` — JSON danh sách sản phẩm
- `/admin/login` — form đăng nhập admin

---

## Đưa code lên GitHub

Sau khi bạn giải nén `laptop-vui.zip`, mở terminal (macOS/Linux) hoặc Git Bash (Windows) trong thư mục `laptop-vui/`:

```bash
git init
git add .
git commit -m "Initial commit: Laptop Vui e-commerce"
git branch -M main
```

Vào **github.com**, đăng nhập, bấm dấu **+** góc phải → **New repository**. Đặt tên (ví dụ `laptop-vui`), **không tick** "Add a README file". Bấm **Create repository**.

GitHub sẽ hiện lệnh push. Copy URL và chạy:

```bash
git remote add origin https://github.com/<username>/laptop-vui.git
git push -u origin main
```

Sau khi push xong, mở repo trên GitHub và **kiểm tra**:

- Có nhìn thấy 2 thư mục con `backend/` và `mobile_app/`?
- Vào `backend/` → có đầy đủ file: `index.php`, `.htaccess`, `Dockerfile`, `config.php`, `dev-router.php` và các thư mục `controllers/`, `models/`, `views/`, `public/`, `upload/`, `data/`?

Nếu thiếu file (đặc biệt `.htaccess` — file ẩn hay bị sót khi upload thủ công), hãy dùng git command line như trên thay vì kéo thả trên GitHub UI. **Đây là nguyên nhân phổ biến làm deploy Render fail.**

---

## Deploy backend lên Render.com

Render là dịch vụ hosting có gói **free** hỗ trợ Docker + HTTPS sẵn — phù hợp cho demo.

### Bước 1. Tạo Web Service trên Render

1. Đăng ký tài khoản tại [render.com](https://render.com) (đăng nhập bằng GitHub tiện nhất).
2. Vào Dashboard → bấm **New +** → chọn **Web Service**.
3. Chọn repo `laptop-vui` vừa push.
4. Điền các thông số:
    - **Name**: `laptop-vui` (hoặc tên bạn thích)
    - **Region**: chọn Singapore (gần Việt Nam nhất)
    - **Branch**: `main`
    - **Root Directory**: `backend` ⚠️ **RẤT QUAN TRỌNG** — nếu bỏ trống, Render sẽ tìm Dockerfile ở gốc repo và không thấy → deploy fail
    - **Runtime**: `Docker` (Render tự nhận Dockerfile)
    - **Instance Type**: `Free`
5. Bấm **Create Web Service**.

Render sẽ build image và deploy trong ~3–5 phút. Sau đó bạn có URL dạng `https://laptop-vui-XXXX.onrender.com`.

### Bước 2. Kiểm tra deploy

Mở các URL sau trên trình duyệt:

- `https://laptop-vui-XXXX.onrender.com/` — trang chủ hiện danh sách laptop
- `https://laptop-vui-XXXX.onrender.com/api/categories` — JSON `{"status":"success",...}`
- `https://laptop-vui-XXXX.onrender.com/admin/login` — đăng nhập bằng `admin@demo.local` / `admin123`

### Đặc điểm của gói Free trên Render

- Server **ngủ sau 15 phút** không có request; request đầu tiên "đánh thức" mất ~30–50 giây.
- **File SQLite reset** mỗi lần deploy lại (do container không có persistent disk). Với demo thì tốt — dữ liệu luôn về mẫu ban đầu. Nếu cần lưu vĩnh viễn, dùng gói trả phí + persistent disk, hoặc chuyển sang MySQL (xem phần bên dưới).
- Free instance có 512 MB RAM và 0.1 CPU — đủ cho demo, không đủ cho production.

---

## Chạy mobile app trên Expo Go

### Bước 1. Cài Expo Go trên điện thoại

- **Android**: [Google Play](https://play.google.com/store/apps/details?id=host.exp.exponent)
- **iOS**: App Store, tìm "Expo Go"

### Bước 2. Chỉ URL API sang backend của bạn

Mở `mobile_app/src/api/config.js` và sửa dòng `API_URL`:

```js
// Sau khi backend đã deploy lên Render, dùng URL đó:
export const API_URL = 'https://laptop-vui-XXXX.onrender.com/api';
```

> **Lưu ý**: URL phải kết thúc bằng `/api`, KHÔNG có dấu `/` cuối. Ví dụ đúng: `https://laptop-vui.onrender.com/api`.

Nếu test local (backend chạy trên máy tính, chưa deploy):
- **Máy thật cùng WiFi**: dùng IP LAN, ví dụ `http://192.168.1.10:8080/api` (chạy `ipconfig` trên Windows / `ifconfig` trên macOS/Linux để xem IP).
- **Android Emulator**: `http://10.0.2.2:8080/api`
- **iOS Simulator**: `http://localhost:8080/api`

### Bước 3. Cài dependencies và chạy

Trong thư mục `mobile_app/`:

```bash
npm install
npx expo start
```

Terminal sẽ hiện QR code cùng URL dạng `exp://...`.

### Bước 4. Quét QR

- **Android**: mở Expo Go → tab **Scan QR code** → quét.
- **iOS**: mở **Camera** thường của iPhone → quét → bấm banner mở trong Expo Go.

App load trong ~15–30 giây. Nếu backend đang ngủ trên Render, request đầu tiên có thể mất thêm ~30–50 giây để "đánh thức" server.

---

## Tài khoản demo

| Vai trò | Email               | Mật khẩu   |
| ------- | ------------------- | ---------- |
| Admin   | `admin@demo.local`  | `admin123` |

Tài khoản này được tạo tự động khi backend khởi tạo `demo.sqlite`. Bạn có thể tạo tài khoản khách hàng thường bằng form đăng ký trong app hoặc trên web.

---

## Danh sách API endpoint

Tất cả endpoint mobile bắt đầu bằng `/api`. Base URL: `https://<domain>/api`.

| Method | Endpoint          | Body / Query                                                                          | Mô tả                                              |
| ------ | ----------------- | ------------------------------------------------------------------------------------- | -------------------------------------------------- |
| GET    | `/categories`     | —                                                                                     | Danh sách danh mục (chỉ hiện `anhien = 1`).        |
| GET    | `/products`       | `?limit=20`                                                                           | Danh sách sản phẩm, sắp theo số lượt xem.          |
| GET    | `/product`        | `?id=1`                                                                               | Chi tiết một sản phẩm.                             |
| POST   | `/login`          | `{ "email": "...", "matkhau": "..." }`                                                | Đăng nhập, trả về thông tin user (không có mật khẩu). |
| POST   | `/register`       | `{ "hoten": "...", "email": "...", "matkhau": "..." }`                                | Tạo tài khoản mới (vaitro mặc định 0).             |
| POST   | `/checkout`       | `{ "hoten": "...", "email": "...", "diachi": "...", "dienthoai": "...", "cart": {"1": 2, "3": 1} }` | Đặt hàng. `cart` là map `id_sp → số lượng`.        |

Response luôn có shape:

```json
{ "status": "success" | "error", "data"?: ..., "message"?: "..." }
```

Ảnh sản phẩm được backend tự dựng URL tuyệt đối trong field `hinh_url` (dựa vào `HTTP_HOST` của request), nên mobile app có thể hiển thị trực tiếp mà không cần ghép URL.

---

## Chuyển sang MySQL

Nếu bạn có MySQL và muốn dữ liệu persistent:

1. Tạo database (ví dụ `laptop_vui`) và import schema — có thể dịch từ `initializeDemoDatabase()` trong `backend/models/database.php`, đổi `AUTOINCREMENT` thành `AUTO_INCREMENT` và điều chỉnh kiểu dữ liệu cho khớp MySQL.
2. Sửa `backend/config.php`:

    ```php
    const DB_DRIVER = "mysql";
    const DB_HOST = "your-mysql-host";
    const DB_NAME = "laptop_vui";
    const DB_USER = "your-user";
    const DB_PASS = "your-password";
    ```

3. Trên Render, thêm **Environment Variables** thay vì hardcode để không lộ mật khẩu:
    - Dashboard service → tab **Environment** → **Add Environment Variable**.
    - Sửa `config.php` đọc từ `getenv('DB_HOST')` v.v. (đã có sẵn pattern giống `APP_ROOT_URL`).

---

## Xử lý lỗi thường gặp

### Deploy Render

**Build fail: `Cannot serve directory /var/www/html/: No matching DirectoryIndex`**
→ File `index.php` không có trong `/var/www/html/`. Nghĩa là repo GitHub thiếu file. Dockerfile trong repo này đã có sanity check tự dò các file quan trọng và fail sớm với message rõ ràng nếu thiếu. Cách sửa: dùng `git push` (không kéo thả UI) để đảm bảo tất cả file được đẩy lên đầy đủ, đặc biệt là `.htaccess` (file ẩn).

**Build fail ở bước `chown: cannot access '/var/www/html/data'`**
→ Dockerfile trong repo này đã fix bằng cách `mkdir -p` trước `chown`. Nếu bạn dùng Dockerfile cũ, cập nhật lại theo bản trong `backend/Dockerfile`.

**Build fail ở `docker-php-ext-install pdo_sqlite` với thông báo `SQLITE_LIBS ... pkg-config`**
→ Thiếu `libsqlite3-dev` và `pkg-config`. Dockerfile trong repo này đã có sẵn hai package đó.

**Build fail vì không tìm thấy Dockerfile**
→ Trên Render, kiểm tra **Root Directory** phải là `backend` (không phải rỗng, không phải `/backend`, không có dấu `/`).

**Deploy pass nhưng service không lên (log báo lỗi port)**
→ Dockerfile trong repo này dùng `sed` lúc runtime để Apache listen đúng `$PORT` do Render cấp. Nếu bạn tùy chỉnh Dockerfile, giữ nguyên phần `CMD` cuối file.

### Backend

**`Call to undefined function mb_strlen()`**
→ Thiếu extension `mbstring`. Image `php:8.2-apache` mới đã có sẵn. Nếu chạy local: Windows/Laragon thường có sẵn; Ubuntu chạy `sudo apt-get install php-mbstring`.

**`SQLSTATE[HY000] [14] unable to open database file`**
→ Thư mục `backend/data/` không có quyền ghi. Chạy `chmod -R 775 backend/data backend/upload`.

**Trang trắng, không có nội dung**
→ Xem PHP error log. Với `php -S`, log hiện ngay trên terminal. Với Render, mở Dashboard service → tab **Logs**.

**404 "Không tìm thấy đường dẫn"**
→ `ROOT_URL` không khớp với URL bạn đang truy cập. Nếu deploy Render mà đặt sai `APP_ROOT_URL`, mọi route sẽ 404. Không đặt biến này khi deploy lên Render (mặc định `/` là đúng).

**Ảnh sản phẩm không hiển thị**
→ Kiểm tra `backend/upload/` có tồn tại trong repo và có ảnh không. Trên Render, ảnh upload sau khi deploy sẽ mất mỗi lần build lại (do container ephemeral).

### Mobile app (Expo)

**`Network request failed`**
→ 99% do `API_URL` sai. Kiểm tra:

- URL kết thúc bằng `/api`, không có `/` cuối.
- Nếu test local: điện thoại phải cùng WiFi với máy tính, và `API_URL` phải là IP LAN (không phải `localhost` hay `127.0.0.1`).
- Nếu dùng Android Emulator: URL phải là `http://10.0.2.2:8080/api` (10.0.2.2 là "localhost của máy host" theo góc nhìn emulator).

**`expo start` báo lỗi version mismatch**
→ Chạy `npx expo install --fix` để Expo tự sửa version dependencies.

**Expo Go báo "Something went wrong" khi load app**
→ Trong terminal Expo, bấm `r` để reload. Nếu vẫn lỗi, kill Metro (Ctrl+C) và chạy `npx expo start -c` (xóa cache).

**iPhone quét QR không mở Expo Go**
→ Trên iOS, luôn quét bằng app **Camera** thường, không phải trong Expo Go. Sau khi quét, iOS hỏi "Open in Expo Go?" thì bấm OK.

**Ảnh sản phẩm không hiện trên app**
→ Kiểm tra `hinh_url` trong response API bằng cách mở `<API_URL>/products` trên trình duyệt. URL trong `hinh_url` phải mở được ảnh.

---

## Ghi chú bảo mật

**Backdoor `mobile_auth`**: file `backend/controllers/AdminController.php` có đoạn cho phép bỏ qua mật khẩu admin khi request có query `?mobile_auth=<base64_email>`. Đây là cách mobile app mở panel admin trong WebView mà không cần nhập lại mật khẩu. **Bất kỳ ai biết email admin đều có thể dùng token này để đăng nhập.** Với bản demo là chấp nhận được, nhưng **KHÔNG DÙNG CHO PRODUCTION**. Nếu deploy public, hãy thay bằng cơ chế xác thực đúng chuẩn (JWT / session token gắn với mật khẩu).

**Mật khẩu người dùng**: được hash bằng `password_hash()` với `PASSWORD_BCRYPT` — tốt, không cần đổi.

**HTTPS**: Render cấp HTTPS miễn phí ngay khi deploy. Session cookie đã cấu hình `Secure`, `HttpOnly`, `SameSite=Lax` trong `index.php` khi request đến qua HTTPS.

**CORS**: hiện đang mở `*` (mọi origin) để mobile app gọi thoải mái. Với production nên siết lại thành domain cụ thể.

---

## Giấy phép

Dự án dùng cho mục đích học tập / demo. Không có ràng buộc thương mại.
