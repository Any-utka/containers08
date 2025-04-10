FROM php:7.4-fpm as base

# Установка SQLite и зависимостей для работы с SQLite в PHP
RUN apt-get update && \
    apt-get install -y sqlite3 libsqlite3-dev && \
    docker-php-ext-install pdo_sqlite

# Указываем, что контейнер будет использоваться для работы с базой данных
VOLUME ["/var/www/db"]

# Копируем схему базы данных
COPY sql/schema.sql /var/www/db/schema.sql

# Подготавливаем базу данных
RUN echo "prepare database" && \
    cat /var/www/db/schema.sql | sqlite3 /var/www/db/db.sqlite && \
    chmod 777 /var/www/db/db.sqlite && \
    rm -rf /var/www/db/schema.sql && \
    echo "database is ready"

# Копируем код приложения
COPY site /var/www/html
