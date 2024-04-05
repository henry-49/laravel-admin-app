FROM php:8.0-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- \
         --install-dir=/usr/local/bin --filename=composer

WORKDIR /app
COPY . .

# copying all the file
RUN composer install

# localhost inside the docker container
CMD php artisan serve --host=0.0.0.0
