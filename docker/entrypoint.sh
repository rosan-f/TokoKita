#!/bin/sh
set -e

# Ensure .env exists inside container
if [ ! -f /var/www/html/.env ]; then
    if [ -f /var/www/html/.env.example ]; then
        cp /var/www/html/.env.example /var/www/html/.env
    else
        touch /var/www/html/.env
    fi
fi

# Ensure APP_KEY exists
if ! grep -q "^APP_KEY=base64:" /var/www/html/.env; then
    php artisan key:generate --force
fi

# Wait for MySQL database container to be ready
echo "Menunggu database MySQL (db:3306) siap..."
until php -r "try { new PDO('mysql:host=' . (getenv('DB_HOST') ?: 'db') . ';port=3306;dbname=' . (getenv('DB_DATABASE') ?: 'tokokita'), getenv('DB_USERNAME') ?: 'tokokita', getenv('DB_PASSWORD')); exit(0); } catch (Exception \$e) { exit(1); }"; do
    sleep 2
done
echo "Database MySQL terhubung!"

# Run migrations and seeders
php artisan migrate --force
php artisan db:seed --force

# Ensure backup and storage directories have correct permissions
mkdir -p storage/backup
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

echo "Menjalankan aplikasi tokoKita di port 80..."
exec apache2-foreground
