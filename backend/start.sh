
set -euo pipefail

if [ ! -d vendor ]; then
  echo "Installing PHP dependencies..."
  composer install --no-interaction --optimize-autoloader --no-dev
fi

if [ -z "${APP_KEY:-}" ]; then
  echo "Generating APP_KEY..."
  php artisan key:generate --force
fi


echo "Running migrations..."
php artisan migrate --force || true


php artisan storage:link || true


PORT="${PORT:-8080}"
echo "Starting Laravel server on 0.0.0.0:${PORT}"
exec php artisan serve --host=0.0.0.0 --port=${PORT}
