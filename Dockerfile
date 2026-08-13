# =====================================================================
# Dockerfile cho backend Laptop Vui (PHP 8.2 + Apache + SQLite)
# Dùng cho Render.com hoặc bất kỳ nền tảng chạy Docker nào.
# =====================================================================
FROM php:8.2-apache

# ---------- Cài extension cần thiết ----------
# Extensions bổ sung (mbstring đã có sẵn trong image php:8.2-apache):
# - pdo_sqlite: kết nối SQLite (mặc định của app khi DB_DRIVER = "sqlite")
# - pdo_mysql:  sẵn sàng khi bạn đổi DB_DRIVER sang "mysql"
#
# Build dependencies:
# - libsqlite3-dev: headers để biên dịch pdo_sqlite
# - pkg-config:     configure script dùng để tìm thư viện sqlite
RUN apt-get update && apt-get install -y --no-install-recommends \
        libsqlite3-dev \
        pkg-config \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# ---------- Copy source ----------
COPY . /var/www/html/

# ---------- Tạo thư mục runtime + cấp quyền ghi ----------
# Dùng mkdir -p để build không fail nếu repo thiếu thư mục data/ hoặc upload/
# (Git bỏ qua thư mục rỗng, nên các folder này có thể vắng mặt sau khi push
# repo mới). App tự tạo demo.sqlite khi lần đầu chạy nếu file chưa có.
RUN mkdir -p /var/www/html/data /var/www/html/upload \
    && chown -R www-data:www-data /var/www/html/data /var/www/html/upload \
    && chmod -R 775 /var/www/html/data /var/www/html/upload

# ---------- Cho phép .htaccess override để mod_rewrite hoạt động ----------
# Image php:apache mặc định để AllowOverride None. Phải bật All thì .htaccess
# trong /var/www/html mới có hiệu lực.
RUN printf '<Directory /var/www/html>\n\
    Options -Indexes +FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>\n' > /etc/apache2/conf-available/app.conf \
    && a2enconf app

# ---------- Cấu hình port lúc runtime ----------
# Render truyền biến $PORT vào container khi start (thường là 10000). Ta sed
# lúc runtime để Apache luôn listen đúng port do platform cấp, không phụ thuộc
# hành vi expand ${VAR} trong config của httpd. Nếu chạy local mà không set
# PORT, mặc định 8080.
EXPOSE 8080

CMD : ${PORT:=8080} && \
    sed -i "s/^Listen 80$/Listen ${PORT}/" /etc/apache2/ports.conf && \
    sed -i "s/*:80>/*:${PORT}>/" /etc/apache2/sites-available/000-default.conf && \
    exec apache2-foreground
