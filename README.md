<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
</p>

# Movies Reviews

## Tabela de conteúdos

- ### [Sobre o projeto](#sobre-o-projeto)
- ### [Como rodar o projeto](#como-rodar-o-projeto)
- ### [Como testar](#como-testar)
- ### [Obrigado!](#obrigado)

<br>

## Sobre o projeto 📚

A ideia do projeto é uma simples API que dá aos seus usuários a possibilidade de criar, editar e deletar reviews de filmes.

Existem algumas regras de negócio padrão a serem observadas:
- Um usuário deve estar logado para criar, editar e deletar uma review.
- Um usuário só pode editar e deletar as próprias reviews.
- Um usuário só pode editar e deletar a própria conta.
- Qualquer usuário (inclusive deslogado) pode realizar um CRUD sobre os filmes.
- Qualquer usuário (inclusive deslogado) pode criar uma nova conta.

Toda a API foi criada utilizando o [Laravel 10](https://laravel.com/docs) (versão mais recente do framework no momento) e suas ferramentas.

<br>

## Como rodar o projeto 🚀

Após clonar o projeto, entre na pasta ´MoviesReviews´ e rode o comando ´composer install´. Isso irá instalar as dependências da aplicação.

Uma vez que as dependências forem instaladas, você poderá rodar o comando ´./vendor/bin/sail up´ para que o docker-composer em que essa aplicação está baseada comece a rodar.

Espere até que todos os contêineres estejam rodando para que comece a testar livremente.

<br>

## Como testar 🧪

Uma collection do Postman pode ser encontrada neste link:

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/20223915-efb687f4-754f-4572-b6ba-528fedd9156e?action=collection%2Ffork&collection-url=entityId%3D20223915-efb687f4-754f-4572-b6ba-528fedd9156e%26entityType%3Dcollection%26workspaceId%3D9252cb61-8d29-4c37-b780-b927e1e7a264#?env%5BMovies%20reviews%20(Teste%20-%20Pontue)%5D=W3sia2V5IjoiYXBwX3VybCIsInZhbHVlIjoiaHR0cDovL2xvY2FsaG9zdDo4MCIsImVuYWJsZWQiOnRydWUsInR5cGUiOiJkZWZhdWx0Iiwic2Vzc2lvblZhbHVlIjoiaHR0cDovL2xvY2FsaG9zdDo4MCIsInNlc3Npb25JbmRleCI6MH0seyJrZXkiOiJiZWFyZXJfdG9rZW4iLCJ2YWx1ZSI6IiIsImVuYWJsZWQiOnRydWUsInR5cGUiOiJkZWZhdWx0Iiwic2Vzc2lvblZhbHVlIjoiIiwic2Vzc2lvbkluZGV4IjoxfV0=)

Além disso, o arquivo ´.env´ necessário para rodar o projeto foi enviado para o e-mail, junto com esse teste.

<br>

## Obrigado!

Obrigado pela oportunidade, espero que se divirta testando esta API. Até a próxima!
