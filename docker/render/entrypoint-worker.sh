#!/bin/sh
set -e

echo "==> PHP version: $(php -r 'echo PHP_VERSION;')"

exec /usr/bin/supervisord -c /etc/supervisor/worker.conf