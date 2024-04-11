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

RUN pecl install xdebug && docker-php-ext-enable xdebug

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

COPY assets/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

EXPOSE 80

COPY assets/docker_entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker_entrypoint.sh

RUN a2enmod rewrite

RUN rm -rf /var/log/apache2/*

RUN adduser dev

USER dev

ENV XDEBUG_MODE=coverage

ENTRYPOINT ["docker_entrypoint.sh"]