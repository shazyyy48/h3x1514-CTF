FROM node:18-alpine

# рабочая директория
WORKDIR /app

# копируем только package файлы
COPY package.json ./

# устанавливаем зависимости
RUN npm install --production

# копируем остальной код
COPY server.js .
COPY public ./public

# порт приложения
EXPOSE 3000

# запуск
CMD ["node", "server.js"]
