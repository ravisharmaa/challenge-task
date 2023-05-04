FROM node:current-alpine
WORKDIR /app

COPY package.json .
COPY --chown=node:node . /app
RUN npm install
USER node
CMD [ "npm", "dev"]
