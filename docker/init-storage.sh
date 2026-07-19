#!/bin/sh
# Seeds the Laravel writable skeleton inside the storage named volume, which
# starts empty (the real storage/ tree is shadowed by the volume mount).
# Runs as www-data via serversideup's /etc/entrypoint.d hook, so the volumes
# must already be owned by www-data — see the one-time chown documented in
# docker-compose.yml.

set -e

for dir in \
    /var/www/html/storage/app/public \
    /var/www/html/storage/framework/cache/data \
    /var/www/html/storage/framework/sessions \
    /var/www/html/storage/framework/testing \
    /var/www/html/storage/framework/views \
    /var/www/html/storage/logs; do
    mkdir -p "$dir" 2>/dev/null || echo "init-storage: cannot create $dir (run the one-time chown from docker-compose.yml)"
done
