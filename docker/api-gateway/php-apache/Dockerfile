FROM php:7.2-apache

# Run update
RUN apt-get update -yqq && \
    apt-get install -y apt-utils \
        curl \
        git \
        libicu-dev \ 
    && pecl channel-update pecl.php.net
    
# Extensions PHP
RUN docker-php-ext-install pdo_mysql intl

# Run install php mongo extension
RUN apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Run install php redis extension
RUN printf "\n" | pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis

# Run install xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Copy xdebug configuration for remote debugging
COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN sed -i "s/xdebug.remote_autostart=0/xdebug.remote_autostart=1/" /usr/local/etc/php/conf.d/xdebug.ini && \
    sed -i "s/xdebug.remote_enable=0/xdebug.remote_enable=1/" /usr/local/etc/php/conf.d/xdebug.ini && \
    sed -i "s/xdebug.cli_color=0/xdebug.cli_color=1/" /usr/local/etc/php/conf.d/xdebug.ini

COPY php.ini /usr/local/etc/php/

# Conf apache
RUN a2enmod rewrite
COPY vhost.conf /etc/apache2/sites-enabled/000-default.conf
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# Run clean up
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    rm /var/log/lastlog /var/log/faillog
    
WORKDIR /var/www/html

EXPOSE 80

ENTRYPOINT ["entrypoint.sh"]

CMD ["apache2-foreground"]