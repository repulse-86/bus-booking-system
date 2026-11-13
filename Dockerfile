# syntax=docker/dockerfile:1

FROM php:8.4-fpm-alpine AS php-base

# nginx serves HTTP, gettext provides envsubst, su-exec drops privileges in the entrypoint.
# pdo_sqlite, sqlite3, mbstring and opcache already ship with the official image.
RUN apk add --no-cache nginx gettext su-exec \
    && docker-php-ext-install bcmath

COPY docker/php.ini /usr/local/etc/php/
COPY docker/php-fpm.d/zz-render.conf /usr/local/etc/php-fpm.d/

FROM php-base AS vendor

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install --no-dev --no-interaction --prefer-dist --no-scripts --optimize-autoloader

FROM node:22-alpine AS assets

WORKDIR /var/www/html

COPY package.json package-lock.json ./

RUN npm ci

COPY resources resources
COPY public public
COPY vite.config.js tailwind.config.js postcss.config.js ./

COPY --from=vendor /var/www/html/vendor vendor

# Render injects the service's environment variables as Docker build args, so
# these values end up in the compiled Vite bundle (import.meta.env).
ARG VITE_APP_NAME="Bus Ticketing System"
ARG VITE_REVERB_APP_KEY
ARG VITE_REVERB_HOST
ARG VITE_REVERB_PORT=443
ARG VITE_REVERB_SCHEME=https

RUN npm run build

FROM php-base AS app

WORKDIR /var/www/html

COPY --from=vendor /var/www/html/vendor vendor
COPY --from=assets /var/www/html/public/build public/build

COPY . .

RUN chmod +x docker/entrypoint.sh \
    && sed -i 's/\r$//' docker/entrypoint.sh \
    && php artisan package:discover \
    && chown -R www-data:www-data storage bootstrap/cache database public/files \
    && chmod -R u+w storage bootstrap/cache database public/files

EXPOSE 10000

# CMD (not ENTRYPOINT) so Render's dockerCommand can override it for the
# Reverb service without changing the image.
CMD ["/var/www/html/docker/entrypoint.sh"]
