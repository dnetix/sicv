# Production cutover runbook — SICV legacy → Laravel

Goal: replace the CodeIgniter app (PHP 7.4) with the Laravel app (PHP 8.3) on
the DigitalOcean droplet (or a fresh one), migrating all production data.
The import is idempotent and reads the legacy database directly, so the whole
cutover can be rehearsed as many times as needed before the real switch.

## 0. Prerequisites

- Docker + Docker Compose on the target machine (or PHP 8.3-fpm/Apache with
  `pdo_mysql gd zip intl opcache` if deploying without Docker).
- MySQL 8.x reachable from the app with the production `sicv-ci` database
  loaded (either the live one or a fresh dump: `mysqldump sicv-ci | mysql sicv-ci`).
- The legacy app's `assets/img/` folder available on disk (for the logo copy).

## 1. Prepare (no downtime)

```bash
# Database and user
mysql -e "CREATE DATABASE sicv CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
          CREATE USER 'sicv'@'%' IDENTIFIED BY '<strong-password>';
          GRANT ALL PRIVILEGES ON sicv.* TO 'sicv'@'%';
          GRANT SELECT ON \`sicv-ci\`.* TO 'sicv'@'%';"

# App
git clone <repo> && cd sicv-ci/laravel
cp .env.example .env    # then edit:
#   APP_ENV=production, APP_DEBUG=false, APP_URL=https://<domain>
#   APP_KEY: php artisan key:generate
#   DB_* -> the sicv database; LEGACY_DB_* -> sicv-ci (SELECT-only user is enough)
#   LEGACY_ASSETS_PATH -> path where the legacy assets/ folder is mounted/copied

composer install --no-dev --optimize-autoloader
npm ci && npm run build
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

With the Docker setup, mount the legacy `assets/` folder read-only (see
`docker-compose.yml`) so the import can copy the company logo.

## 2. Rehearsal (any time before cutover)

```bash
php artisan legacy:import --force   # in Docker: docker exec -u www-data sicv php artisan legacy:import --force
```

The command ends with a verification table (row counts, per-status contract
counts, amount sums, all compared against the legacy database). Every row must
say OK. Then:

```bash
php artisan tinker parity-check.php  # financial report components vs legacy, 3 date ranges — all must MATCH
```

Log in and spot-check: a known client's history, an active contract's payoff
against the old app's screen, the expired report count, a contract print.

## 3. Cutover (short maintenance window)

1. Put the legacy app in maintenance / block writes (stop its Apache vhost).
2. Re-run `php artisan legacy:import --force` against the now-frozen legacy
   database (seconds — it reloads everything from scratch).
3. Confirm the verification table is all OK and `parity-check.php` matches.
4. Switch the web server / DNS / Traefik router to the Laravel app.
5. Users log in with their EXISTING usernames and passwords — legacy hashes
   are upgraded to bcrypt transparently on each user's first login.

## 4. Rollback

The legacy database `sicv-ci` is never written by the new app. Rolling back is
just re-enabling the old vhost. (Any data entered into the new app after
cutover would need manual reconciliation — keep the window short and verify
immediately.)

## 5. After cutover

- Keep `sicv-ci` as a frozen archive (the import source is read-only).
- The old droplet/PHP 7.4 stack can be retired once a full business cycle
  (contracts, extensions, redemptions, pulls, sales, reports, printing) has
  been exercised on the new app.

## Intentional behavior changes (agreed during the rewrite)

- All reports require login (legacy financial reports were public).
- Self-updater and gold-price widget removed; `ingreso` tables not migrated (unused).
- Operator-editable amounts (redemption payoff, forfeit price, POS line price)
  remain editable but differences vs the computed value are recorded in
  `amount_overrides` and reviewable at `/admin/overrides`.
- Voided contracts keep their original amount in `contract_voids.original_amount`.
- Multi-write operations run in DB transactions; duplicate/out-of-stock POS
  lines are rejected instead of silently skipped; queueing an already-queued
  contract is skipped instead of aborting the batch.
- The sold-items report default date filter works; yearly stats include Dec 31.
- Foreclosed store items store their real cost (the loan amount) instead of 0.
- NEW: operators can flag clients with notes (`client_notes` — aviso/alerta,
  author and date recorded; deletion admin-only). Notes are managed from the
  client page and shown wherever the client is picked, most importantly on
  the new-contract screen.

## Known numeric note

Extension months are stored as DECIMAL(8,4) (legacy used 32-bit FLOAT). Totals
computed over months can differ from the legacy app by well under one peso in
aggregate (verified: $0.52 across $27.6M of expired-contract payoffs).
