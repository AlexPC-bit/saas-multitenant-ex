FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

FROM php:8.4-cli
WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zip libonig-dev libxml2-dev sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite zip mbstring xml bcmath

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .
COPY --from=assets /app/public/build ./public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN mkdir -p database && touch database/database.sqlite

EXPOSE 8080

CMD ["sh", "-c", "php artisan migrate:fresh --force && php artisan serve --host 0.0.0.0 --port ${PORT:-8080}"]