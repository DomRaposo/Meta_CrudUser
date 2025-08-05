# Petiko To Do - Sistema de Gerenciamento de Usuários

Sistema completo de gerenciamento de usuários com backend em Laravel e frontend em Vue 3, utilizando MongoDB como banco de dados.

---

## Sumário

- [Sobre o Projeto](#sobre-o-projeto)
- [Tecnologias](#tecnologias)
- [Arquitetura](#arquitetura)
- [Como rodar o projeto localmente](#como-rodar-o-projeto-localmente)
  - [Backend (Laravel)](#backend-laravel)
  - [Frontend (Vue 3)](#frontend-vue-3)
- [Funcionalidades](#funcionalidades)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [API Endpoints](#api-endpoints)
- [Princípios SOLID e Clean Code](#princípios-solid-e-clean-code)
- [Licença](#licença)

---

## Sobre o Projeto

Este projeto é um sistema de cadastro, edição e gerenciamento de usuários com autenticação JWT, controle de permissões (admin), paginação e interface moderna. O sistema utiliza MongoDB como banco de dados e segue os princípios SOLID e Clean Code.

---

## Tecnologias

### Backend
- **Framework:** Laravel 10+
- **PHP:** 8.1+
- **Banco de Dados:** MongoDB
- **Autenticação:** Laravel Sanctum com tokens personalizados
- **Pacote MongoDB:** jenssegers/laravel-mongodb

### Frontend
- **Framework:** Vue 3 (Composition API)
- **Roteamento:** Vue Router
- **Estado:** Composables (Vue 3)
- **HTTP Client:** Axios
- **Build Tool:** Vite
- **Linting:** ESLint

### Outros
- **CORS:** Configurado para desenvolvimento local
- **Logging:** Laravel Log com logs detalhados
- **Arquitetura:** Clean Architecture com separação de responsabilidades

---

## Arquitetura

O projeto segue os princípios de Clean Architecture e SOLID:

### Backend (Laravel)
```
app/
├── Http/
│   ├── Controllers/     # Controllers (apenas roteamento HTTP)
│   ├── Middleware/      # Middleware customizado (MongoDBAuth)
│   └── Responses/       # Classes de resposta padronizadas
├── Services/            # Lógica de negócio
├── Repositories/        # Acesso a dados
├── Models/              # Modelos Eloquent (MongoDB)
└── Enums/              # Enumerações
```

### Frontend (Vue 3)
```
src/
├── components/          # Componentes reutilizáveis
├── views/              # Páginas/Views
├── composables/        # Lógica de estado (Vue 3)
├── services/           # Serviços de API
├── config/             # Configurações
└── assets/             # Recursos estáticos
```

---

## Como rodar o projeto localmente

### Pré-requisitos
- PHP 8.1+
- Composer
- Node.js 16+
- MongoDB
- XAMPP (opcional, para ambiente local)

### Backend (Laravel)

1. **Instale as dependências:**
   ```bash
   composer install
   ```

2. **Configure o ambiente:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure o MongoDB no .env:**
   ```env
   DB_CONNECTION=mongodb
   DB_HOST=127.0.0.1
   DB_PORT=27017
   DB_DATABASE=petiko_todo
   DB_USERNAME=
   DB_PASSWORD=
   ```

4. **Inicie o servidor:**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
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
   npm run dev
   ```
   O frontend estará disponível em `http://localhost:5173`.

---

## Funcionalidades

### Autenticação
- Login com email e senha
- Tokens JWT personalizados (formato: `ID|TOKEN`)
- Middleware de autenticação customizado para MongoDB
- Logout com revogação de tokens

### Gerenciamento de Usuários
- ✅ **CRUD completo** (Create, Read, Update, Delete)
- ✅ **Paginação** de usuários
- ✅ **Busca** de usuários por nome
- ✅ **Modal** para criação/edição
- ✅ **Feedback visual** (mensagens de sucesso/erro)
- ✅ **Interface responsiva**

### Controle de Acesso
- Todos os usuários são criados como admin (`role: 'isAdmin'`)
- Autenticação obrigatória para todas as operações
- Middleware `auth.mongodb` para proteção de rotas

---

## Estrutura do Projeto

```
Petiko_To_Do/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   └── UserController.php
│   │   ├── Middleware/
│   │   │   └── MongoDBAuth.php
│   │   └── Responses/
│   │       └── UserResponse.php
│   ├── Services/
│   │   ├── AuthService.php
│   │   └── UserService.php
│   ├── Repositories/
│   │   └── UserRepository.php
│   ├── Models/
│   │   ├── User.php
│   │   └── PersonalAccessToken.php
│   └── Enums/
├── config/
│   ├── cors.php
│   └── sanctum.php
├── routes/
│   └── api.php
├── PerfilUser-FrontEnd/
│   └── perfil-user-front/
│       ├── src/
│       │   ├── components/
│       │   │   ├── Dashboard.vue
│       │   │   ├── Header.vue
│       │   │   ├── Footer.vue
│       │   │   └── UserList.vue
│       │   ├── views/
│       │   │   ├── LoginView.vue
│       │   │   ├── DashboardView.vue
│       │   │   └── UsersView.vue
│       │   ├── composables/
│       │   │   └── useAuth.js
│       │   ├── services/
│       │   │   ├── auth.js
│       │   │   └── ApiService.js
│       │   ├── config/
│       │   │   └── api.js
│       │   ├── router/
│       │   │   └── index.js
│       │   └── assets/
│       │       └── css/
│       └── package.json
└── README.md
```

---

## API Endpoints

### Autenticação
```
POST /api/login          # Login de usuário
POST /api/logout         # Logout (requer autenticação)
```

### Usuários (todos requerem autenticação)
```
GET    /api/users        # Listar todos os usuários
GET    /api/users/{id}   # Buscar usuário específico
POST   /api/users        # Criar novo usuário
PUT    /api/users/{id}   # Atualizar usuário
DELETE /api/users/{id}   # Deletar usuário
```

### Utilitários
```
GET /api/health          # Health check
GET /api/csrf-token      # Token CSRF
```

---

## Princípios SOLID e Clean Code

### Single Responsibility Principle (SRP)
- **Controllers**: Apenas roteamento HTTP
- **Services**: Lógica de negócio + orquestração
- **Repositories**: Acesso a dados
- **Responses**: Padronização de respostas HTTP

### Open/Closed Principle (OCP)
- Classes abertas para extensão, fechadas para modificação
- Uso de interfaces e abstrações

### Liskov Substitution Principle (LSP)
- Métodos retornam tipos consistentes (`JsonResponse`)
- Implementações intercambiáveis

### Interface Segregation Principle (ISP)
- Métodos específicos para cada operação
- Interfaces coesas e focadas

### Dependency Inversion Principle (DIP)
- Dependência de abstrações, não implementações
- Injeção de dependência via construtor

### Clean Code
- **Nomes descritivos**: `getAllUsersResponse()`, `createUserResponse()`
- **Métodos pequenos**: Cada método tem uma responsabilidade
- **Tratamento de erros**: Try/catch consistente
- **Documentação**: Comentários explicativos
- **Separação de responsabilidades**: Cada camada tem seu papel

---

## Licença

Este projeto é open-source e está sob a licença MIT.

---

## Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

---

## Status do Projeto

✅ **Concluído:**
- Sistema de autenticação com MongoDB
- CRUD completo de usuários
- Interface moderna e responsiva
- Arquitetura SOLID e Clean Code
- Logs detalhados para debugging
- Tratamento de erros robusto

🔄 **Em desenvolvimento:**
- Testes automatizados
- Documentação da API
- Deploy automatizado
