# Используем образ Node.js для сборки проекта
FROM node:22 AS build

WORKDIR /app

# Копируем package.json и устанавливаем зависимости
COPY ./frontend/package.json ./frontend/package-lock.json ./
RUN npm install

# Копируем все остальные файлы и собираем проект
COPY ./frontend /app/
RUN npm run build

# Используем Nginx для обслуживания собранного проекта
FROM nginx:alpine

# Копируем собранный фронтенд в директорию Nginx
COPY --from=build /app/dist /usr/share/nginx/html

# Открываем порт для Nginx
EXPOSE 80