FROM php:8.4-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    libzip-dev unzip sqlite3 libsqlite3-dev curl gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_sqlite zip bcmath

RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install

RUN cp .env.example .env \
    && touch database/database.sqlite \
    && echo "\nDB_CONNECTION=sqlite" >> .env \
    && echo "DB_DATABASE=/var/www/html/database/database.sqlite" >> .env \
    && php artisan key:generate \
    && php artisan migrate --force

RUN npm ci
RUN npm run build

RUN rm -rf node_modules

RUN composer install --no-dev --optimize-autoloader

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 storage bootstrap/cache database

EXPOSE 80

CMD php artisan optimize:clear && \
    php artisan optimize && \
    php artisan migrate:fresh --seed --force && \
    apache2-foreground
