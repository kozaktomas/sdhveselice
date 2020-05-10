FROM php:7.4-apache

RUN apt-get update && apt-get install -y zlib1g-dev git nano libzip-dev zip \
  && docker-php-ext-install zip

# composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

ADD docker/virtual_host.conf /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite
WORKDIR /var/www/html

ADD . /var/www/html
RUN mkdir -p /var/www/html/temp/cache \
    && mkdir -p /var/www/html/log \
    && chmod -R 777 /var/www/html/temp \
    && chmod -R 777 /var/www/html/log \
    && composer install --no-dev
