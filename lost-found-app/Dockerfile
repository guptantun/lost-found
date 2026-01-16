FROM php:8.2-apache

# 1. ติดตั้งโปรแกรมที่จำเป็น (Zip, Git, Postgres)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql

# 2. เปิดใช้งาน mod_rewrite (สำคัญมาก! แก้อาการ 404 หน้า Register/Login)
RUN a2enmod rewrite

# 3. ตั้งค่า Apache ให้ชี้ไปที่โฟลเดอร์ public และอนุญาตให้ใช้ .htaccess
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. ก๊อปปี้ไฟล์โปรเจกต์เข้า Docker
WORKDIR /var/www/html
COPY . .

# 5. ติดตั้ง Composer และ Libraries
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# 6. แก้สิทธิ์ไฟล์ให้ Laravel เขียนข้อมูลได้
RUN chown -R www-data:www-data storage bootstrap/cache

# 7. สั่งรัน Migration และเปิดเว็บ
# เพิ่มคำสั่ง 'php artisan storage:link' เพื่อสร้างทางเชื่อมรูปภาพ
CMD bash -c "php artisan storage:link && php artisan migrate --force && php artisan config:clear && apache2-foreground"