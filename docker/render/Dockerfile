FROM php:8.4-fpm

# Dependencias del sistema + nginx + supervisor (Render exige un solo proceso por contenedor,
# por eso combinamos nginx+php-fpm aquí, a diferencia del docker-compose local que los separa)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    gettext-base \
    && rm -rf /var/lib/apt/lists/* \
    && rm -f /etc/nginx/sites-enabled/default

RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    intl

# Node.js — necesario para compilar los assets (npm run build) durante el build de Render
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN composer install --optimize-autoloader --no-dev --no-interaction
RUN npm install && npm run build && rm -rf node_modules

# Configuración de nginx (se genera en runtime con el $PORT que asigna Render) y supervisor
COPY docker/render/nginx.conf.template /etc/nginx/templates/default.conf.template
COPY docker/render/supervisord-web.conf /etc/supervisor/web.conf
COPY docker/render/supervisord-worker.conf /etc/supervisor/worker.conf
COPY docker/render/entrypoint-web.sh /entrypoint-web.sh
COPY docker/render/entrypoint-worker.sh /entrypoint-worker.sh
RUN chmod +x /entrypoint-web.sh /entrypoint-worker.sh

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN git config --global --add safe.directory /var/www/html

# Por defecto arranca como web; el servicio "worker" en Render sobreescribe esto
# con dockerCommand: /entrypoint-worker.sh (mismo Dockerfile, mismo build, dos roles).
CMD ["/entrypoint-web.sh"]
