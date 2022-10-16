FROM php:7.3.9-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    mariadb-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/
RUN docker-php-ext-install gd

# Install composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && composer global require hirak/prestissimo --no-plugins --no-scripts

# Set working directory
ENV APP_ROOT /var/www/grs-backend
WORKDIR ${APP_ROOT}

# Copy copmoser files for cache optimizations
COPY composer.lock composer.json ${APP_ROOT}/

# Install composer packages
RUN composer install --prefer-dist --no-scripts --no-dev --no-autoloader && rm -rf /root/.composer

#override configuration php.ini
COPY php/local.ini /usr/local/etc/php/conf.d/

# Copy existing entrypoint script
COPY entrypoint.sh /usr/local/bin/entrypoint

# Add user for laravel application
#RUN groupadd -g 1000 www
#RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . ${APP_ROOT}

# Finish composer
RUN composer dump-autoload --no-scripts --no-dev --optimize

# Copy existing application directory permissions
#COPY --chown=www:www . ${APP_ROOT}

# Change current user to www
#USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
ENTRYPOINT ["entrypoint"]
