FROM php:8.2-fpm

# Arguments defined in docker-compose.yml
ARG user
ARG uid
ARG group

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    cron \
    sendmail

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    gd \
    iconv \
    pdo_mysql \
    zip \
    bcmath

COPY /docker/php/php.ini /usr/local/etc/php/conf.d/

RUN touch /var/log/cron.log

RUN chown -R $user:$user /var/www/html
RUN chmod +x /var
# Change current user to $user
USER $user

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
