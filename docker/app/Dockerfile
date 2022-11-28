FROM php:8.0.20-fpm


# Pre-requisites installation
RUN apt-get update \
   && apt-get install -y curl mcrypt zip git \
    && docker-php-ext-install pdo_mysql

COPY ./app/php.ini /usr/local/etc/php.ini

# Composer installation
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Permissions setup
RUN chown -R www-data:www-data /var/www

# Xdebug setup
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY ./app/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# Node & NPM setup
ENV NODE_VERSION=16.13.0
RUN apt install -y curl
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
ENV NVM_DIR=/root/.nvm
RUN . "$NVM_DIR/nvm.sh" && nvm install ${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm use v${NODE_VERSION}
RUN . "$NVM_DIR/nvm.sh" && nvm alias default v${NODE_VERSION}
ENV PATH="/root/.nvm/versions/node/v${NODE_VERSION}/bin/:${PATH}"
RUN node --version
RUN npm --version
