FROM php:8.2.17-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    libicu-dev \
    && docker-php-ext-install zip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

COPY assets/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

EXPOSE 80

COPY assets/docker_entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker_entrypoint.sh

RUN a2enmod rewrite

RUN rm -rf /var/log/apache2/*

COPY app /var/www/html/app
COPY public /var/www/html/public
COPY vendor /var/www/html/vendor
COPY writable /var/www/html/writable
COPY .env.prod /var/www/html/.env
COPY builds /var/www/html/builds
COPY composer.json /var/www/html/composer.json
COPY composer.lock /var/www/html/composer.lock
COPY preload.php /var/www/html/preload.php

RUN chown -R www-data:www-data /var/www/html

CMD ["apache2-foreground"]