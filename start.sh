#!/bin/sh
echo "Waiting for database..."
for i in $(seq 1 30); do
  php -r "require 'db.php';" 2>/dev/null && break
  echo "Retrying in 2s... ($i)"
  sleep 2
done
php setup.php && php -S 0.0.0.0:$PORT