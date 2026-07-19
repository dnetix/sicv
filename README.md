# SICV — Sistema de Información Compraventa

Laravel rewrite of the legacy CodeIgniter pawnshop system (see repository root).
Manages pawn contracts, extensions, redemptions, store inventory, POS sales,
expenses, and reports for Compraventa El Diamante.

## Stack

- Laravel 13 / PHP 8.4 (`serversideup/php` fpm-nginx, Docker, unprivileged)
- MySQL 8.4 (external `shared` Docker network, database `sicv`)
- Blade + Alpine.js + Tailwind CSS 4 (Vite)

## Local development

```bash
docker compose up -d             # app at http://sicv.test (Traefik) or http://localhost:8002
npm install && npm run build     # frontend assets (or `npm run dev` for HMR)
docker exec sicv-laravel php artisan migrate --seed
```

Dev login: `admin` / `secret`.

The container runs entirely as `www-data`, so plain `docker exec` is already
the right user. `storage/` and `bootstrap/cache` live on named volumes
(`laravel_storage`, `laravel_bootstrap-cache`) because the Windows bind mount
is not writable by an unprivileged container. **One-time setup whenever those
volumes are (re)created** (e.g. after `docker compose down -v`):

```bash
docker exec -u root sicv-laravel chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
docker restart sicv-laravel      # re-runs the storage-skeleton init script
```

The `legacy` database connection points read-only at the old CodeIgniter
database (`sicv-ci`) and is used by the `legacy:import` artisan command to
migrate production data (it also copies the company logo from the legacy
`assets/` mount):

```bash
docker exec sicv-laravel php artisan legacy:import --force
```

## Tests & style

```bash
docker exec sicv-laravel php artisan test
docker exec sicv-laravel ./vendor/bin/pint
```
