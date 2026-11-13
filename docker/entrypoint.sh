#!/bin/sh
set -eu

: "${PORT:=10000}"

if [ -z "${APP_URL:-}" ] && [ -n "${RENDER_EXTERNAL_URL:-}" ]; then
    export APP_URL="${RENDER_EXTERNAL_URL}"
fi

if [ -z "${APP_KEY:-}" ]; then
    echo "ERROR: APP_KEY is not set. Configure it in the Render dashboard before deploying." >&2
    exit 1
fi

if [ ! -f database/database.sqlite ]; then
    echo "ERROR: database/database.sqlite not found. Commit the baseline database before deploying." >&2
    exit 1
fi

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs storage/app/private public/files/payments

chown -R www-data:www-data database public/files storage bootstrap/cache 2>/dev/null || true

su-exec www-data php artisan config:cache
su-exec www-data php artisan migrate --force
su-exec www-data php artisan cache:clear
su-exec www-data php artisan view:cache

envsubst '${PORT}' < docker/nginx.conf > /etc/nginx/http.d/default.conf

php-fpm -D

nginx -g 'daemon off;'
