# Используем официальный образ PHP с Apache
FROM php:8.0-apache

# Устанавливаем необходимые расширения PHP
RUN docker-php-ext-install pdo pdo_mysql

# Копируем файлы проекта в контейнер
COPY ./backend /var/www/html/

# Открываем порт для веб-сервера
EXPOSE 80
