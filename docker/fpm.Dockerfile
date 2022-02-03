FROM php:7.4-fpm


RUN docker-php-ext-install pdo pdo_mysql


# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to run Composer and Artisan Commands
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

RUN mkdir -p /home/www/.composer && \
    chown -R www:www /home/www
