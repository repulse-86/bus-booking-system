<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## Deploying to Render

This project ships with a Docker + Render Blueprint setup on the `render` branch. Two free web services are created from the same image:

| Service | Purpose |
|---------|---------|
| `bus-booking` | Nginx + PHP-FPM, SQLite database, `https://bus-booking.onrender.com` |
| `bus-booking-reverb` | Laravel Reverb WebSocket server, `wss://bus-booking-reverb.onrender.com` |

PHP-FPM cannot hold long-lived WebSocket connections, so Reverb runs as its own process. The main service broadcasts to Reverb over its public HTTPS URL; browsers connect with Echo over WSS.

### How it works

- `Dockerfile` builds the PHP runtime, Composer vendor directory, and Vite assets in separate stages.
- `docker/entrypoint.sh` caches config, runs migrations, and starts PHP-FPM + Nginx on `$PORT`.
- `render.yaml` declares both services. The Reverb service overrides the image `CMD` with `php artisan reverb:start`.
- `database/database.sqlite` and `public/files/` are committed so every container starts with the baseline data.
- Realtime updates are used on the customer "My Bookings" page through the private `booking.update.{userId}` channel.

### Environment variables

`render.yaml` sets most values, including the Reverb credentials. `REVERB_APP_SECRET` is generated by Render and shared with the main service. `APP_KEY` is generated by Render as well.

The `VITE_REVERB_*` values are **build-time** variables. They are baked into the JavaScript bundle through the `ARG` declarations in the Dockerfile, so changing them requires a rebuild.

The app derives `APP_URL` from `RENDER_EXTERNAL_URL` unless it is set explicitly.

### Data persistence

There is no persistent disk on the free plan. Every deploy, restart, or cold start restores the committed baseline database and uploads. New bookings, users, and uploaded receipts are lost when the service restarts. Login sessions are stored in SQLite and are also reset.

If the deployed URL differs from the service name, set `REVERB_HOST` and `VITE_REVERB_HOST` manually in the Render dashboard and redeploy the main service.

> The committed SQLite database contains real user records. Keep the GitHub repository **private**.

### Local Docker test

```bash
docker compose up --build
```

The app runs at `http://localhost:8000` and Reverb at `ws://localhost:8080`. Sign in as a customer in two tabs, approve one of their bookings as an admin, and the booking list updates without a refresh.

### Tests

`phpunit.xml` uses an in-memory SQLite database, so `php artisan test` never touches the committed baseline database.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
