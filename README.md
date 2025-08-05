# Meta_CrudUser

> Sistema completo de gerenciamento de usuários com arquitetura moderna, backend Laravel e frontend Vue 3, utilizando MongoDB como banco de dados.

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.x-green.svg)](https://vuejs.org)
[![MongoDB](https://img.shields.io/badge/MongoDB-6.x-brightgreen.svg)](https://mongodb.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-blue.svg)](https://docker.com)

## 📋 Índice

- [Sobre o Projeto](#-sobre-o-projeto)
- [Tecnologias](#-tecnologias)
- [Arquitetura](#-arquitetura)
- [Instalação e Configuração](#-instalação-e-configuração)
  - [Docker (Recomendado)](#docker-recomendado)
  - [Instalação Local](#instalação-local)
- [Funcionalidades](#-funcionalidades)
- [API Documentation](#-api-documentation)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Desenvolvimento](#-desenvolvimento)
- [Troubleshooting](#-troubleshooting)
- [Contribuição](#-contribuição)
- [Licença](#-licença)

## 🎯 Sobre o Projeto

O **Meta_CrudUser** é um sistema robusto de gerenciamento de usuários desenvolvido com tecnologias modernas e seguindo os princípios de Clean Architecture e SOLID. O projeto oferece uma solução completa para autenticação, autorização e operações CRUD de usuários, com interface responsiva e API RESTful.

### Características Principais

- ✅ **Autenticação JWT** com Laravel Sanctum
- ✅ **CRUD Completo** de usuários
- ✅ **Interface Moderna** com Vue 3 Composition API
- ✅ **Banco NoSQL** com MongoDB
- ✅ **Arquitetura Limpa** seguindo princípios SOLID
- ✅ **Docker Ready** para desenvolvimento e produção
- ✅ **Logs Detalhados** para debugging
- ✅ **CORS Configurado** para desenvolvimento

## 🛠 Tecnologias

### Backend
| Tecnologia | Versão | Propósito |
|------------|--------|-----------|
| **Laravel** | 10.x | Framework PHP |
| **PHP** | 8.1+ | Linguagem de programação |
| **MongoDB** | 6.x | Banco de dados NoSQL |
| **Laravel Sanctum** | 3.x | Autenticação JWT |
| **jenssegers/mongodb** | 4.x | Driver MongoDB para Laravel |

### Frontend
| Tecnologia | Versão | Propósito |
|------------|--------|-----------|
| **Vue.js** | 3.x | Framework JavaScript |
| **Vite** | 4.x | Build tool e dev server |
| **Vue Router** | 4.x | Roteamento SPA |
| **Axios** | 1.x | HTTP client |
| **Composition API** | 3.x | Sistema de reatividade |

### DevOps & Ferramentas
| Tecnologia | Versão | Propósito |
|------------|--------|-----------|
| **Docker** | 20.x+ | Containerização |
| **Docker Compose** | 2.x+ | Orquestração |
| **Mongo Express** | 1.x | Interface web MongoDB |
| **ESLint** | 8.x | Linting JavaScript |

## 🏗 Arquitetura

O projeto segue os princípios de **Clean Architecture** e **SOLID**, organizando o código em camadas bem definidas:

### Backend (Laravel)
```
app/
├── Http/
│   ├── Controllers/     # Controllers (HTTP requests/responses)
│   ├── Middleware/      # Middleware customizado
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
├── composables/        # Lógica de estado (Composition API)
├── services/           # Serviços de API
├── config/             # Configurações
└── assets/             # Recursos estáticos
```

### Princípios SOLID Aplicados

- **SRP**: Cada classe tem uma única responsabilidade
- **OCP**: Aberto para extensão, fechado para modificação
- **LSP**: Implementações intercambiáveis
- **ISP**: Interfaces coesas e focadas
- **DIP**: Dependência de abstrações

## 🚀 Instalação e Configuração

### Docker (Recomendado)

#### Pré-requisitos
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado
- Windows 10/11 ou macOS

#### Instalação Rápida

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/seu-usuario/meta-cruduser.git
   cd meta-cruduser
   ```

2. **Execute o script de setup (Windows PowerShell):**
   ```powershell
   .\docker-setup.ps1
   ```

3. **Ou execute manualmente:**
   ```bash
   # Build e start dos containers
   docker-compose up --build -d
   
   # Verificar status
   docker-compose ps
   ```

4. **Acesse os serviços:**
   - 🌐 **Frontend**: [http://localhost:5173](http://localhost:5173)
   - 🔧 **Backend**: [http://localhost:8000](http://localhost:8000)
   - 🗄️ **MongoDB**: localhost:27017
   - 📊 **Mongo Express**: [http://localhost:8081](http://localhost:8081) (admin/admin123)

#### Comandos Docker Úteis

```bash
# Logs em tempo real
docker-compose logs -f backend
docker-compose logs -f frontend
docker-compose logs -f mongodb

# Parar containers
docker-compose down

# Reiniciar
docker-compose restart

# Remover tudo (incluindo volumes)
docker-compose down -v

# Rebuild específico
docker-compose build backend
```

### Instalação Local

#### Pré-requisitos
- PHP 8.1+
- Composer 2.x+
- Node.js 16+
- MongoDB 6.x
- XAMPP (opcional)

#### Backend (Laravel)

1. **Instale as dependências:**
   ```bash
   composer install
   ```

2. **Configure o ambiente:**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure o MongoDB no `.env`:**
   ```env
   # Configuração do MongoDB
   DB_CONNECTION=mongodb
   DB_HOST=127.0.0.1
   DB_PORT=27017
   DB_DATABASE=to_do
   DB_USERNAME=
   DB_PASSWORD=
   
   # Outras configurações importantes
   APP_NAME="Meta_CrudUser"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://localhost:8000
   
   # Configurações de log
   LOG_CHANNEL=stack
   LOG_DEPRECATIONS_CHANNEL=null
   LOG_LEVEL=debug
   ```

4. **Inicie o servidor:**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```

#### Frontend (Vue 3)

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

## ✨ Funcionalidades

### 🔐 Autenticação
- **Login/Logout** com tokens JWT personalizados
- **Middleware customizado** para MongoDB
- **Revogação de tokens** no logout
- **Formato de token**: `ID|TOKEN`

### 👥 Gerenciamento de Usuários
- ✅ **CRUD Completo** (Create, Read, Update, Delete)
- ✅ **Paginação** de usuários
- ✅ **Busca** por nome
- ✅ **Modal** para criação/edição
- ✅ **Feedback visual** (sucesso/erro)
- ✅ **Interface responsiva**

### 🛡️ Controle de Acesso
- **Todos os usuários** são criados como admin (`role: 'isAdmin'`)
- **Autenticação obrigatória** para todas as operações
- **Middleware `auth.mongodb`** para proteção de rotas

## 📚 API Documentation

### Autenticação
| Método | Endpoint | Descrição | Autenticação |
|--------|----------|-----------|--------------|
| `POST` | `/api/login` | Login de usuário | ❌ |
| `POST` | `/api/logout` | Logout | ✅ |

### Usuários
| Método | Endpoint | Descrição | Autenticação |
|--------|----------|-----------|--------------|
| `GET` | `/api/users` | Listar todos os usuários | ✅ |
| `GET` | `/api/users/{id}` | Buscar usuário específico | ✅ |
| `POST` | `/api/users` | Criar novo usuário | ✅ |
| `PUT` | `/api/users/{id}` | Atualizar usuário | ✅ |
| `DELETE` | `/api/users/{id}` | Deletar usuário | ✅ |

### Utilitários
| Método | Endpoint | Descrição | Autenticação |
|--------|----------|-----------|--------------|
| `GET` | `/api/health` | Health check | ❌ |
| `GET` | `/api/csrf-token` | Token CSRF | ❌ |

### Exemplos de Uso

#### Login
```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password"
  }'
```

#### Listar Usuários
```bash
curl -X GET http://localhost:8000/api/users \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 📁 Estrutura do Projeto

### Estrutura Docker
```
Meta_CrudUser/
├── docker-compose.yml          # Configuração dos serviços
├── Dockerfile.backend          # Imagem do backend Laravel
├── .dockerignore              # Arquivos ignorados no build
├── docker-setup.ps1           # Script de setup (Windows)
├── docker/
│   └── mongo-init/
│       └── init.js            # Script de inicialização MongoDB
└── PerfilUser-FrontEnd/
    └── perfil-user-front/
        └── Dockerfile.frontend # Imagem do frontend Vue
```

### Estrutura da Aplicação
```
Meta_CrudUser/
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

### Serviços Docker

| Serviço | Porta | Descrição |
|----------|-------|-----------|
| **backend** | 8000 | Laravel API com MongoDB |
| **frontend** | 5173 | Vue 3 com Vite |
| **mongodb** | 27017 | Banco de dados MongoDB |
| **mongo-express** | 8081 | Interface web para MongoDB |

**Credenciais Mongo Express:**
- Usuário: `admin`
- Senha: `admin123`

## 🛠 Desenvolvimento

### Variáveis de Ambiente

O projeto utiliza as seguintes variáveis de ambiente para configuração do MongoDB:

| Variável | Valor Padrão | Descrição |
|----------|---------------|-----------|
| `DB_CONNECTION` | `mongodb` | Driver de conexão (sempre mongodb) |
| `DB_HOST` | `127.0.0.1` | Host do MongoDB |
| `DB_PORT` | `27017` | Porta do MongoDB |
| `DB_DATABASE` | `to_do` | Nome do banco de dados |
| `DB_USERNAME` | `` | Usuário do MongoDB (vazio para local) |
| `DB_PASSWORD` | `` | Senha do MongoDB (vazio para local) |

### Comandos de Desenvolvimento

```bash
# Backend
php artisan serve                    # Iniciar servidor Laravel
php artisan migrate                 # Executar migrations
php artisan db:seed                # Executar seeders
php artisan route:list             # Listar rotas
php artisan config:cache           # Cache de configuração

# Frontend
npm run dev                        # Servidor de desenvolvimento
npm run build                      # Build para produção
npm run lint                       # Linting
npm run preview                    # Preview do build

# Docker
docker-compose logs -f backend     # Logs do backend
docker-compose logs -f frontend    # Logs do frontend
docker-compose exec backend bash   # Acessar container backend
```

## 🔧 Troubleshooting

### Problemas comuns do MongoDB

1. **Erro de conexão com MongoDB:**
   ```bash
   # Verifique se o MongoDB está rodando
   mongod --version
   
   # Para XAMPP, verifique se o serviço está ativo
   # Para Windows, verifique no Gerenciador de Serviços
   ```

2. **Erro de autenticação:**
   - Para desenvolvimento local, deixe `DB_USERNAME` e `DB_PASSWORD` vazios
   - Para produção, configure usuário e senha no MongoDB

3. **Erro de banco não encontrado:**
   - O banco `to_do` será criado automaticamente na primeira operação
   - Verifique se o MongoDB tem permissões de escrita

4. **Logs detalhados:**
   - Verifique os logs em `storage/logs/laravel.log`
   - Use `LOG_LEVEL=debug` no `.env` para mais detalhes

### Problemas comuns do Docker

1. **Porta já em uso:**
   ```bash
   # Verifique processos nas portas
   netstat -ano | findstr :8000
   netstat -ano | findstr :5173
   
   # Mate o processo se necessário
   taskkill /PID <PID> /F
   ```

2. **Container não inicia:**
   ```bash
   # Verifique logs
   docker-compose logs backend
   docker-compose logs frontend
   
   # Rebuild
   docker-compose build --no-cache
   ```

3. **Problemas de permissão (Linux/macOS):**
   ```bash
   # Ajuste permissões
   sudo chown -R $USER:$USER .
   chmod -R 755 storage
   ```

## 🤝 Contribuição

1. **Fork** o projeto
2. **Crie** uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. **Commit** suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. **Push** para a branch (`git push origin feature/AmazingFeature`)
5. **Abra** um Pull Request

### Padrões de Contribuição

- Siga os princípios SOLID
- Mantenha a arquitetura limpa
- Adicione testes quando possível
- Documente mudanças na API
- Use commits semânticos


## 📞 Suporte

- **Documentação**: Este README
- **Email**: felipe_ol@outlook.com

---

**Desenvolvido com ❤️ usando Laravel, Vue.js e MongoDB**
