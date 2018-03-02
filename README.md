# Banco local de receitas

Este projeto proporciona uma interface web e uma API para criação, leitura, update, busca e deleção de receitas a partir de um banco mongoDB local

## Dependências

  - npm && yarn
  - php
  - mongoDB
  
## Uso

Instale os pacotes com

`$ yarn install`

Para subir a API, rode:

`$ npm run back`

Para subir o front-end, rode:

`$ php -S 0.0.0.0:3001 -t front/`

ou

`$ npm run front`

### Rotas da API

  - /r [GET]        - retorna todas as receitas
  - /r [POST]       - cria uma nova receita
  - /r/:id [GET]    - retorna uma receita específica
  - /r/:id [PUT]    - atualiza uma receita específica
  - /r/:id [DELETE] - remove uma receita específica
  - /s [POST]       - busca uma receita

### GET

`$ curl <ip>:<port>/<path>`

### POST

`$ curl -X POST -H "Content-Type: application/json" -d @<arquivo.json> <ip>:<port>/<path>`

### PUT

`$ curl -X PUT -H "Content-Type: application/json" -d @<arquivo.json> <ip>:<port>/<path>`

### DELETE

`$ curl -X DELETE <ip>:<port>/<path>`

## License

GNU Affero General Public License v3 (AGPL-3.0)

