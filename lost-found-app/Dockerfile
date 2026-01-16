FROM php:8.2-apache

# ติดตั้งสิ่งที่จำเป็นสำหรับ Laravel และ PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql

# เปิดใช้งาน Mod Rewrite ของ Apache
RUN a2enmod rewrite

# ตั้งค่าให้ Apache ชี้ไปที่ folder public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ก๊อปปี้ไฟล์โปรเจกต์เข้า Server
WORKDIR /var/www/html
COPY . .

# สั่งลง Library และตั้งค่าสิทธิ์ไฟล์
RUN composer install --no-dev --optimize-autoloader
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
# สั่งล้าง Config และ Cache เก่าทิ้ง ก่อนจะเริ่มรันเว็บ
CMD bash -c "php artisan config:clear && php artisan cache:clear && apache2-foreground"