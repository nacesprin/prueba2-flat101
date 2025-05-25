#!/bin/sh
set -e

if [ "$1" = 'frankenphp' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
    if [ -z "$(ls -A 'vendor/' 2>/dev/null)" ]; then
        echo "🧩 Instalando dependencias de Composer..."
        composer install --prefer-dist --no-progress --no-interaction
    fi

    php bin/console -V

    # Cargar la base de datos
    if grep -q 'sqlite' .env && [ ! -f var/sqlite2.db ]; then
        echo "Creando base de datos SQLite en data/sqlite2.db..."
        # Crear la base de datos SQLite
        php bin/console doctrine:schema:update --force
    fi

    # Permisos para Symfony
    setfacl -R -m u:www-data:rwX -m u:1000:rwX var
    setfacl -dR -m u:www-data:rwX -m u:1000:rwX var

    echo 'Symfony con SQLite listo para usar.'
fi

exec docker-php-entrypoint "$@"
