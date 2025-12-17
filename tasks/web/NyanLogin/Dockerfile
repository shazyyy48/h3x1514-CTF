FROM node:18-alpine

WORKDIR /app

COPY package*.json ./

RUN npm install --only=production

COPY . .

EXPOSE 4545

ENV FLAG=h3x1514{jwt_ny4shny_br3aker_uwu}

USER node

CMD ["node", "server.js"]