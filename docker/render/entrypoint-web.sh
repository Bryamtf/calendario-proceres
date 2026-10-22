#!/bin/sh
set -e

echo "==> PHP version: $(php -r 'echo PHP_VERSION;')"

export PORT=${PORT:-10000}
envsubst '${PORT}' < /etc/nginx/templates/default.conf.template > /etc/nginx/conf.d/default.conf

exec /usr/bin/supervisord -c /etc/supervisor/web.conf