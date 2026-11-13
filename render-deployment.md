# Render Deployment Guide — Laravel + Docker + SQLite

Reusable instructions for deploying a Laravel app to Render using Docker and SQLite with a committed baseline database.

## Overview

This setup deploys a Laravel application to Render's free plan using:

- **Docker** multi-stage build (PHP 8.4 FPM Alpine + Nginx)
- **SQLite** database baked into the image (committed baseline data)
- **Nginx** + **PHP-FPM** serving the app
- **Render Blueprint** (`render.yaml`) for declarative deployment
- **No persistent disk**, no `storage:link`, no symlinked uploads

> **Warning:** Committing `database/database.sqlite` and `public/files` to Git means real user data (emails, password hashes, uploads) is in the repository. **Only use this if your GitHub repository is private.**

---

## Phase 1: Create the Render Branch

Create a dedicated branch so Render deploys from a known state:

```bash
git switch master
git switch -c render
```

> If you are already on a feature branch (e.g., `upgrade/laravel-13`), you can branch from there instead, but `master` is the cleanest starting point.

---

## Phase 2: Prepare Baseline Data (SQLite + Uploads)

Before building, ensure the production SQLite database exists and is populated:

1. Rebuild the SQLite schema from Laravel migrations:

   ```bash
   php artisan migrate:fresh
   ```

2. Import production data into `database/database.sqlite` (if migrating from MySQL):

   ```bash
   python scripts/mysql_dump_to_sqlite.py \
     --dump database/u428891925_reddit2.sql \
     --sqlite database/database.sqlite
   ```

3. Validate:

   ```bash
   php artisan migrate:status
   sqlite3 database/database.sqlite "PRAGMA integrity_check;"
   sqlite3 database/database.sqlite "SELECT COUNT(*) FROM users;"
   sqlite3 database/database.sqlite "SELECT COUNT(*) FROM posts;"
   ```

4. Copy uploaded files into `public/files/` so they are committed:

   ```
   public/files/posts-photos/...
   public/files/notes-photos/...
   public/files/profile-photos/...
   ```

5. Clean sidecar files (do not commit these):

   ```
   database/database.sqlite-wal
   database/database.sqlite-shm
   database/database.sqlite-journal
   database/*.sql
   database/*.bak
   ```

6. Verify the SQLite file is tracked by Git:

   ```bash
   git ls-files database/database.sqlite
   git ls-files public/files | head -5
   ```

---

## Phase 3: Gitignore Changes

### Root `.gitignore`

Ensure `/public/files` is **not** ignored (remove it if present):

```
# REMOVE this line if it exists:
# /public/files
```

### `database/.gitignore`

Replace contents with:

```gitignore
*.sqlite-wal
*.sqlite-shm
*.sqlite-journal
*.sqlite.bak
*.sql
```

This tracks `database/database.sqlite` while ignoring transient SQLite sidecar files and MySQL dumps.

### Do not track

- `.env`
- `database/*.sql`
- `database/database.sqlite-wal`
- `database/database.sqlite-shm`
- `database/database.sqlite-journal`
- `bootstrap/cache/*.php`
- `storage/logs/*`

---

## Phase 4: Create Docker & Render Files

Create the following files in the project root:

### `Dockerfile`

```dockerfile
# syntax=docker/dockerfile:1

FROM php:8.4-fpm-alpine AS php-base

RUN apk add --no-cache nginx gettext su-exec sqlite-dev build-base \
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

ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
```

### `.dockerignore`

```
.git
.github
node_modules
vendor
.env
.env.*
! .env.example
public/build
bootstrap/cache/*.php
database/*.sqlite-wal
database/*.sqlite-shm
database/*.sqlite-journal
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
.phpunit.cache
.phpunit.result.cache
tests/reports
.idea
.vscode
.DS_Store
```

> **Do not** exclude `database/database.sqlite` or `public/files` — they must be in the build context.

### `docker/entrypoint.sh`

```sh
#!/bin/sh
set -eu

: "${PORT:=10000}"

if [ -z "${APP_URL:-}" ]; then
    if [ -n "${RENDER_EXTERNAL_URL:-}" ]; then
        export APP_URL="${RENDER_EXTERNAL_URL}"
    fi
fi

if [ ! -f database/database.sqlite ]; then
    echo "ERROR: database/database.sqlite not found. Commit the baseline database before deploying." >&2
    exit 1
fi

chown -R www-data:www-data database database/database.sqlite public/files storage bootstrap/cache 2>/dev/null || true

su-exec www-data php artisan config:cache
su-exec www-data php artisan migrate --force
su-exec www-data php artisan cache:clear
su-exec www-data php artisan view:cache

envsubst '${PORT}' < docker/nginx.conf > /etc/nginx/http.d/default.conf

php-fpm -D

nginx -g 'daemon off;'
```

### `docker/nginx.conf`

```nginx
server {
    listen ${PORT} default_server;
    root /var/www/html/public;
    index index.php;

    client_max_body_size 10m;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location /files {
        alias /var/www/html/public/files;
    }

    location ~ /\. {
        deny all;
    }

    location = /up {
        access_log off;
        return 200 "OK";
        add_header Content-Type text/plain;
    }

    access_log /dev/stdout;
    error_log /dev/stderr;

    server_tokens off;

    gzip on;
    gzip_types text/plain text/css application/json application/javascript text/xml application/xml;
}
```

### `docker/php.ini`

```ini
memory_limit = 256M
upload_max_filesize = 8M
post_max_size = 10M
max_execution_time = 60
expose_php = Off

[opcache]
opcache.enable = 1
opcache.memory_consumption = 128
opcache.max_accelerated_files = 10000
opcache.validate_timestamps = 0

realpath_cache_size = 4096K
realpath_cache_ttl = 600
```

### `docker/php-fpm.d/zz-render.conf`

```ini
[www]
clear_env = no

listen = 127.0.0.1:9000
listen.owner = www-data
listen.group = www-data

user = www-data
group = www-data

pm = dynamic
pm.max_children = 10
pm.start_servers = 2
pm.min_spare_servers = 1
pm.max_spare_servers = 3
pm.max_requests = 500
```

### `docker-compose.yml` (local testing)

```yaml
services:
  app:
    build:
      context: .
      dockerfile: ./Dockerfile
    ports:
      - "8000:10000"
    environment:
      APP_ENV: local
      APP_DEBUG: "true"
      APP_URL: http://localhost:8000
      DB_CONNECTION: sqlite
      DB_BUSY_TIMEOUT: "5000"
      DB_JOURNAL_MODE: WAL
      DB_SYNCHRONOUS: NORMAL
      SESSION_DRIVER: database
      SESSION_SECURE_COOKIE: "false"
      CACHE_STORE: database
      QUEUE_CONNECTION: sync
      FILESYSTEM_DISK: public
      LOG_CHANNEL: stderr
      LOG_LEVEL: info
      PULSE_ENABLED: "false"
      MAIL_MAILER: log
```

### `render.yaml`

```yaml
services:
  - type: web
    name: reddit
    runtime: docker
    plan: free
    dockerfilePath: ./Dockerfile
    dockerContext: .
    healthCheckPath: /up
    autoDeploy: true
    envVars:
      - key: APP_NAME
        value: Reddit
      - key: APP_ENV
        value: production
      - key: APP_DEBUG
        value: "false"
      - key: APP_KEY
        sync: false
      - key: APP_URL
        sync: false
      - key: DB_CONNECTION
        value: sqlite
      - key: DB_BUSY_TIMEOUT
        value: "5000"
      - key: DB_JOURNAL_MODE
        value: WAL
      - key: DB_SYNCHRONOUS
        value: NORMAL
      - key: SESSION_DRIVER
        value: database
      - key: SESSION_SECURE_COOKIE
        value: "true"
      - key: CACHE_STORE
        value: database
      - key: QUEUE_CONNECTION
        value: sync
      - key: FILESYSTEM_DISK
        value: public
      - key: LOG_CHANNEL
        value: stderr
      - key: LOG_LEVEL
        value: info
      - key: PULSE_ENABLED
        value: "false"
      - key: MAIL_MAILER
        value: log
```

### `.env.render.example`

```env
APP_NAME=Reddit
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:ORIGINAL_HOSTINGER_KEY
APP_URL=https://your-service.onrender.com

DB_CONNECTION=sqlite
DB_BUSY_TIMEOUT=5000
DB_JOURNAL_MODE=WAL
DB_SYNCHRONOUS=NORMAL

SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true
CACHE_STORE=database
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

LOG_CHANNEL=stderr
LOG_LEVEL=info
PULSE_ENABLED=false

MAIL_MAILER=log
```

---

## Phase 5: Modify Existing Laravel Files

### `bootstrap/app.php`

Add inside `withMiddleware()`:

```php
$middleware->trustProxies(at: '*');
```

Render terminates TLS and forwards client IPs through `X-Forwarded-*` headers. Without this, IP-based rate limiting keys on the Render proxy IP.

### `config/database.php`

In the `sqlite` connection, replace the null pragmas with env-configurable values:

```php
'sqlite' => [
    'driver' => 'sqlite',
    'url' => env('DB_URL'),
    'database' => env('DB_DATABASE', database_path('database.sqlite')),
    'prefix' => '',
    'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
    'busy_timeout' => env('DB_BUSY_TIMEOUT'),
    'journal_mode' => env('DB_JOURNAL_MODE'),
    'synchronous' => env('DB_SYNCHRONOUS'),
],
```

Defaults remain `null`, so local development and tests are unchanged.

### `README.md`

Add a "Deploying to Render" section (see the full project README for an example).

---

## Phase 6: Render Dashboard Configuration

1. Push the `render` branch:

   ```bash
   git add -A
   git commit -m "Add Render Docker deployment files"
   git push origin render
   ```

2. In the Render dashboard:
   - Create a new **Web Service** or **Blueprint** from the repository.
   - Set the branch to `render`.
   - Use `render.yaml` as the blueprint.

3. Set **Render environment variables** (secrets — never commit these):
   - `APP_KEY` → `base64:ORIGINAL_HOSTINGER_KEY` (copy from original Hostinger `.env`)
   - `APP_URL` → `https://your-service.onrender.com`

4. Set `MAIL_MAILER=log` (Render free plan blocks SMTP ports 25/465/587).

---

## Phase 7: Fresh Deploy vs Restore Baseline

### Fresh Deploy (new Laravel project, no existing data)

If you are deploying a Laravel project for the first time and do **not** need to restore an old database:

1. Skip Phase 2 baseline import — let `php artisan migrate --force` create empty tables at boot.
2. Skip copying files into `public/files` — the directory can be empty.
3. In `docker/entrypoint.sh`, the `database/database.sqlite` check will fail because the file doesn't exist yet. Either:
   - Create an empty `database/database.sqlite` and commit it, **or**
   - Temporarily comment out the existence check in `docker/entrypoint.sh` until the first boot creates tables.

4. Deploy normally. The app starts with an empty database.

### Restore Baseline (existing production data)

If you are restoring an old database and uploaded files (as we did for the Reddit project):

1. **Phase 2** must be completed before building:
   - Rebuild `database/database.sqlite` from the old dump (MySQL → SQLite conversion script).
   - Copy old uploaded files into `public/files/`.
   - Validate row counts and image paths.

2. Commit `database/database.sqlite` and `public/files/**` to the `render` branch.

3. The Docker image contains the committed baseline. On every deploy/restart, Render starts a fresh container from the image, restoring the committed database and files automatically.

4. **Accepted consequence:** Any data written after publish (new users, posts, uploads) is lost on the next deploy/restart or cold start. This is the trade-off of the no-persistent-disk approach.

---

## Phase 8: Verification

### Local Docker

```bash
docker build -t myapp-render .
docker compose up
```

Check:
- `http://localhost:8000/up` returns 200
- Home page shows committed content
- Login works
- Uploaded images load from `/files/...`
- Recreate container (`docker compose down && docker compose up --build`) and confirm baseline is restored

### Render

After deploy:
- Build completes
- `/up` passes
- Migrations are no-ops (baseline already applied)
- Baseline users/posts/images are present
- Login works
- File uploads served from `/files/...`
- Logs appear in Render log stream

### Laravel Tests

```bash
php artisan test
```

Ensure `trustProxies` and database config changes do not break the test suite.

---

## Critical Notes

| Item | Detail |
|------|--------|
| Repository visibility | Must be **private** — committed SQLite DB contains emails, password hashes, 2FA secrets, and uploads. |
| APP_KEY | Must match the original environment. Set as Render secret, never commit it. |
| Mail on free plan | Render blocks SMTP ports. Use `MAIL_MAILER=log` or an HTTP mail API (Resend/Mailgun/Postmark). |
| Persistent data | No persistent disk. Data written after publish is lost on redeploy/cold start. Single instance only. |
| storage:link | Not used. Files served directly from `public/files`. |
| Entrypoint | Must point to `/var/www/html/docker/entrypoint.sh` (where `COPY . .` places it), not `/usr/local/bin/entrypoint`. |
| PHP version | Match `composer.lock` requirements. If Symfony/Laravel require PHP 8.4+, use `php:8.4-fpm-alpine`. |
| Extensions already built-in | `pdo_sqlite`, `sqlite3`, `opcache` are already in the `php:8.x-fpm-alpine` image. Only install `bcmath` (and any others not built-in). |

---

## Troubleshooting

| Symptom | Likely Cause | Fix |
|---------|--------------|-----|
| `ENTRYPOINT` file not found | Entry point path wrong | Use `/var/www/html/docker/entrypoint.sh` |
| `composer install` fails with `requires php >=8.4.1` | PHP version mismatch | Use `php:8.4-fpm-alpine` (or match lock file) |
| `pdo_sqlite`/`sqlite3` install fails | Already built-in | Remove from `docker-php-ext-install` |
| `config:cache` fails | `APP_KEY` missing | Set `APP_KEY` in Render dashboard |
| `/up` returns 500 | Nginx/PHP-FPM not started | Check entrypoint logs in Render |
| Uploaded images 404 | `public/files` not in image | Verify `.dockerignore` does not exclude it; commit files |
