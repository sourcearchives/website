FROM php:7.2-apache
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libxml2-dev \
    && docker-php-ext-install -j$(nproc) iconv xml mbstring \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd
ADD . /var/www/html
RUN cd /var/www/html \
    && ls -ali \
    && cp -r .0_sources 0_sources \
    && cp -r .data data \
    && cp -r .tmp tmp \
    && cp .gitignore.template .gitignore \
    && cp .htaccess.template .htaccess \
    && chown -R www-data tmp/plxcache \
    && chown -R www-data tmp/cache \
    && touch 0_sources/last_updated.txt \
    && chmod 666 0_sources/last_updated.txt \
    && a2enmod rewrite