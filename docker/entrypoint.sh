#!/bin/sh
set -e
cd /var/www/html

if [ ! -f .env ]; then
  cp .env.docker .env
fi

echo "Waiting for MySQL..."
i=0
until php -r "new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: 3306) . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));" 2>/dev/null; do
  i=$((i+1))
  if [ "$i" -gt 60 ]; then
    echo "MySQL not ready"
    exit 1
  fi
  sleep 2
done

php artisan migrate --force --no-interaction

# Seed only once (when no demo admin exists)
NEEDS_SEED=$(php -r "try { \$p=new PDO('mysql:host='.getenv('DB_HOST').';port='.(getenv('DB_PORT')?:3306).';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); \$c=\$p->query(\"SELECT COUNT(*) FROM users WHERE email='admin@plumbingquotecraft.test'\")->fetchColumn(); echo \$c>0?'0':'1'; } catch(Throwable \$e){ echo '1'; }")
if [ "$NEEDS_SEED" = "1" ]; then
  php artisan db:seed --force --no-interaction || true
fi

php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

exec php artisan serve --host=0.0.0.0 --port=8000
