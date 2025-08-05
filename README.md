# Petiko To Do

Sistema completo de gerenciamento de usuários e tarefas, com backend em Laravel e frontend em Vue 3.

---

## Sumário

- [Sobre o Projeto](#sobre-o-projeto)
- [Tecnologias](#tecnologias)
- [Como rodar o projeto localmente](#como-rodar-o-projeto-localmente)
  - [Backend (Laravel)](#backend-laravel)
  - [Frontend (Vue 3)](#frontend-vue-3)
- [Como rodar com Docker](#como-rodar-com-docker)
- [Funcionalidades](#funcionalidades)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Licença](#licença)

---

## Sobre o Projeto

Este projeto é um sistema de cadastro, edição e gerenciamento de usuários e tarefas, com autenticação, controle de permissões (admin/usuário), paginação e interface moderna.

---

## Tecnologias

- **Backend:** PHP 8+, Laravel
- **Frontend:** Vue 3, Vue Router, Axios
- **Banco de Dados:** MySQL (ou outro compatível com Laravel)
- **Outros:** ESLint, Babel, Vite/Vue CLI, Docker

---

## Como rodar o projeto localmente

### Backend (Laravel)

1. **Instale as dependências:**
   ```bash
   composer install
   ```

2. **Configure o ambiente:**
   - Copie `.env.example` para `.env` e ajuste as variáveis (DB, etc).
   - Gere a chave:
     ```bash
     php artisan key:generate
     ```

3. **Rode as migrations:**
   ```bash
   php artisan migrate
   ```

4. **Inicie o servidor:**
   ```bash
   php artisan serve
   ```
   O backend estará disponível em `http://localhost:8000`.

---

### Frontend (Vue 3)

1. **Acesse a pasta do frontend:**
   ```bash
   cd PerfilUser-FrontEnd/perfil-user-front
   ```

2. **Instale as dependências:**
   ```bash
   npm install
   ```

3. **Rode o servidor de desenvolvimento:**
   ```bash
   npm run serve
   ```
   O frontend estará disponível em `http://localhost:8080`.

---

## Como rodar com Docker

### Pré-requisitos
- Docker e Docker Compose instalados

### Passos

1. **Build e start dos containers:**
   ```bash
   docker-compose up --build
   ```

2. **Acesse os serviços:**
   - Frontend: [http://localhost:8080](http://localhost:8080)
   - Backend: [http://localhost:8000](http://localhost:8000)
   - MySQL: porta 3306 (usuário/senha: root/root, banco: petiko)

3. **Primeiro uso:**
   - Acesse o container backend para rodar as migrations:
     ```bash
     docker-compose exec backend php artisan migrate
     ```

### Estrutura dos serviços
- **backend:** Laravel rodando em PHP 8.1, com hot reload via volume.
- **frontend:** Vue 3 rodando em modo desenvolvimento, com hot reload via volume.
- **db:** MySQL 8, com persistência de dados em volume local.

### Parar os containers
```bash
docker-compose down
```

---

## Funcionalidades

- Cadastro, edição e remoção de usuários
- Cadastro, edição e remoção de tarefas
- Paginação de usuários
- Busca de usuários e tarefas
- Modal para criação/edição
- Controle de permissões (admin/usuário)
- Feedback visual (toasts, mensagens de sucesso/erro)
- Interface responsiva

---

## Estrutura do Projeto

```
Petiko_To_Do/
├── app/                  # Backend Laravel
├── PerfilUser-FrontEnd/
│   └── perfil-user-front/
│       ├── src/          # Código-fonte Vue
│       ├── public/
│       ├── package.json
│       └── ...
├── database/
├── routes/
├── public/
├── composer.json
├── docker-compose.yml
├── Dockerfile.backend
├── PerfilUser-FrontEnd/perfil-user-front/Dockerfile.frontend
└── ...
```

---

## Licença

Este projeto é open-source e está sob a licença MIT.
