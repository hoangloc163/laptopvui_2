# =====================================================================
# Dockerfile cho Laptop Vui — ĐẶT Ở ROOT CỦA REPO
# Cấu trúc repo:
#   /Dockerfile     ← file này
#   /backend/       ← code PHP
#   /mobile_app/    ← code Expo (không dùng khi deploy backend)
#
# Trên Render: để trống "Root Directory" (dùng root repo làm build context).
# Dockerfile này chỉ copy backend/ vào /var/www/html/ và tự tạo .htaccess
# nên không phụ thuộc việc file .htaccess có trong repo hay không (tránh
# lỗi upload UI làm mất dấu chấm đầu của file ẩn).
# =====================================================================
FROM php:8.2-apache

# ---------- Cài extension cần thiết ----------
# - pdo_sqlite: kết nối SQLite (mặc định của app)
# - pdo_mysql:  sẵn sàng nếu bạn đổi sang MySQL
# - libsqlite3-dev + pkg-config: build dependencies cho pdo_sqlite
RUN apt-get update && apt-get install -y --no-install-recommends \
        libsqlite3-dev \
        pkg-config \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# ---------- Copy source từ backend/ ----------
# Chỉ copy backend/ vào /var/www/html/ (không copy mobile_app/ hay README.md).
COPY backend/ /var/www/html/

# ---------- Sanity check ----------
# Kiểm tra các file bắt buộc thực sự tồn tại. Fail sớm với message rõ ràng
# nếu thiếu (thường do upload sai cấu trúc). KHÔNG check .htaccess vì bên
# dưới ta tự tạo nó.
RUN echo "=== Content of /var/www/html/ after COPY backend/ ===" && \
    ls -la /var/www/html/ && \
    echo "=== Verifying required files ===" && \
    for f in index.php config.php controllers/SanphamController.php models/database.php views/layout.php public/css/storefront.css; do \
        if [ ! -e "/var/www/html/$f" ]; then \
            echo ""; \
            echo "FATAL: /var/www/html/$f is missing from build context."; \
            echo "   - Kiem tra repo GitHub co day du file 'backend/$f' khong."; \
            echo "   - Neu upload qua GitHub UI, hay dam bao keo ca thu muc backend/"; \
            echo "     chu khong keo tung file le."; \
            exit 1; \
        fi; \
    done && \
    echo "OK: All required source files present."

# ---------- Tự tạo .htaccess (bulletproof với upload UI) ----------
# .htaccess là file ẩn, thường bị GitHub UI làm mất dấu chấm đầu khi upload.
# Ta embed nội dung vào đây để chắc chắn Apache luôn có URL rewriting đúng.
RUN printf '%s\n' \
    'RewriteEngine On' \
    '' \
    '# Nếu request khớp file hoặc thư mục có thật thì phục vụ trực tiếp.' \
    'RewriteCond %{REQUEST_FILENAME} -f [OR]' \
    'RewriteCond %{REQUEST_FILENAME} -d' \
    'RewriteRule ^ - [L]' \
    '' \
    '# Còn lại đưa hết về index.php để router của app xử lý.' \
    'RewriteRule ^ index.php [QSA,L]' \
    '' \
    '<FilesMatch "\.(sqlite|sqlite3|db|env|log)$">' \
    '    Require all denied' \
    '</FilesMatch>' \
    '' \
    '<Files "config.php">' \
    '    Require all denied' \
    '</Files>' \
    '' \
    '<Files "dev-router.php">' \
    '    Require all denied' \
    '</Files>' \
    '' \
    '<IfModule mod_headers.c>' \
    '    <FilesMatch "\.(jpg|jpeg|png|gif|webp|svg|css|js|ico)$">' \
    '        Header set Cache-Control "public, max-age=86400"' \
    '    </FilesMatch>' \
    '</IfModule>' \
    > /var/www/html/.htaccess

# ---------- Tạo thư mục runtime + cấp quyền ghi ----------
RUN mkdir -p /var/www/html/data /var/www/html/upload \
    && chown -R www-data:www-data /var/www/html/data /var/www/html/upload \
    && chmod -R 775 /var/www/html/data /var/www/html/upload

# ---------- Cho phép .htaccess override để mod_rewrite hoạt động ----------
RUN printf '<Directory /var/www/html>\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/app.conf \
    && a2enconf app

# ---------- Cấu hình port lúc runtime ----------
# Render truyền $PORT vào container. Ta sed lúc runtime để Apache listen
# đúng port đó. Default 8080 khi chạy local.
EXPOSE 8080

CMD : ${PORT:=8080} && \
    sed -i "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf && \
    sed -i "s/*:80>/*:${PORT}>/" /etc/apache2/sites-available/000-default.conf && \
    exec apache2-foreground
