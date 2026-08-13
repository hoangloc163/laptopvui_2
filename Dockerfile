# =====================================================================
# Dockerfile cho backend Laptop Vui (PHP 8.2 + Apache + SQLite)
# Dùng cho Render.com hoặc bất kỳ nền tảng chạy Docker nào.
# =====================================================================
FROM php:8.2-apache

# ---------- Cài extension cần thiết ----------
# Extensions:
# - pdo_sqlite: kết nối SQLite (mặc định của app khi DB_DRIVER = "sqlite")
# - pdo_mysql:  sẵn sàng khi bạn đổi DB_DRIVER sang "mysql"
# - mbstring:   BẮT BUỘC vì code có dùng mb_strlen() (nếu thiếu, /register
#                sẽ crash với HTTP 500)
#
# Build dependencies (image php:apache KHÔNG có sẵn):
# - libsqlite3-dev: headers để biên dịch pdo_sqlite
# - libonig-dev:    dependency của mbstring
# - pkg-config:     configure script dùng để tìm thư viện sqlite
RUN apt-get update && apt-get install -y --no-install-recommends \
        libonig-dev \
        libsqlite3-dev \
        pkg-config \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql mbstring \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# ---------- Copy source ----------
COPY . /var/www/html/

# ---------- Quyền ghi cho SQLite và thư mục upload ----------
# Apache chạy user www-data; cần quyền ghi để tạo demo.sqlite và upload ảnh.
RUN chown -R www-data:www-data /var/www/html/data /var/www/html/upload \
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
