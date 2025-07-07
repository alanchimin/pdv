#!/bin/bash
set -e

echo "Esperando banco MySQL ficar disponível..."
until mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" -e 'select 1' &> /dev/null; do
  sleep 2
done
echo "Banco disponível! Rodando migrations..."

for file in /var/www/html/migrations/*.sql; do
  echo "Executando $file"
  mysql -h"$DB_HOST" -u"$DB_USER" -p"$DB_PASS" "$DB_NAME" < "$file"
done

echo "Migrations concluídas."

# Executa o comando padrão do container (Apache)
exec apache2-foreground
