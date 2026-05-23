FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

COPY . .

ENV WEBROOT=/var/www/html/public
ENV SKIP_COMPOSER=1
ENV RUN_SCRIPTS=1git
ENV REAL_IP_HEADER=1

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

RUN composer install --optimize-autoloader --ignore-platform-reqs --no-interaction

RUN php artisan optimize:clear || true
RUN chmod -R 775 storage bootstrap/cache || true

CMD ["/start.sh"]