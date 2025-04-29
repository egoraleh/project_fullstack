# Используем образ Node.js для сборки проекта
FROM node:22 AS build

WORKDIR /app

# Копируем package.json и устанавливаем зависимости
COPY ./package.json ./package-lock.json ./
RUN npm install

# Копируем все остальные файлы и собираем проект
COPY . .
RUN npm run build

# Используем Nginx для обслуживания собранного проекта
FROM nginx:alpine

# Копируем собранный фронтенд в директорию Nginx
COPY --from=build /app/dist /usr/share/nginx/html

# Копируем свой nginx конфиг
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
