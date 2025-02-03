FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev

RUN apt-get update -y && apt-get upgrade -y && apt-get install git libssl-dev -y
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-install zip
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install exif
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install bcmath

RUN echo "upload_max_filesize = 1G" > /usr/local/etc/php/conf.d/uploads.ini \
 && echo "post_max_size = 1G" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "memory_limit = 1G" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "max_execution_time = 6000" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "default_socket_timeout = 6000" >> /usr/local/etc/php/conf.d/uploads.ini

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app

EXPOSE 9000

CMD ["php-fpm", "--nodaemonize"]
