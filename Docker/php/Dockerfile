FROM php:8.3-fpm-alpine as app

# Useful PHP extension installer image, copy binary into your container
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/


# old way of installing plugins
# RUN docker-php-ext-install pdo pdo_mysql

# Install php extensions
# exit on errors, exit on unset variables, print every command as it is executed
RUN set -eux; \
    install-php-extensions \
     zip \
     pdo \
     pdo_mysql;



# Allow super user for Composer
# ENV COMPOSER_ALLOW_SUPERUSER=1

# Obtain composer inside this container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy composer.json and .lock files (instead of entire source)
COPY ./app/composer.* ./

# composer install dependencies
RUN composer i --prefer-dist --no-dev --no-scripts --no-progress --no-interaction

# This assumes that the user running PHP-FPM is www-data
# RUN chown -R www-data:www-data /var/www/html
RUN chown -R www-data:www-data /var/www/html/vendor

# copy application files to working dir
COPY ./app .

# run composer du --optimize
RUN composer dump-autoload --optimize

FROM app as app_dev

ENV XDEBUG_MODE=off

# Copy xdebug config file into container
COPY ./Docker/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Install xdebug
RUN set -eux; \
	install-php-extensions xdebug