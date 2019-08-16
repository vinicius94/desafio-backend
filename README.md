# Desafio Backend

Para desenvolver o desafio foi utilizado o PHP na versão 7.2.18, usando o composer como gerenciador de dependência. Então após clonar as alterações, execute o composer para baixar as dependências

```bat
composer update
```
Para persistir os dados foi utilizado o banco MySQL, e para conectar ao banco é necessário criar uma base nova no MySQL. Depois no arquivo[Config.php](app/Config.php) os dados de conexão como o host, a base que foi criada e o usuário e a senha.

Para rodar o ambiente foi utilizado o Apache 2.4 e é aconselhado a configuração de um virtual host para o diretório da aplicação

Para ter acesso a Api pode ser usado qualquer cliente REST, mas eu aconselho a usar o [PostMan](https://www.getpostman.com/downloads/). É basicamente instalar o Postman, e importar o arquivo [CredPago.postman_collection.json](CredPago.postman_collection.json). Já está configurado os testes automáticos, mas da para criar novas requisições

PS: Ainda há algumas melhorias a ser feita de unificação de código