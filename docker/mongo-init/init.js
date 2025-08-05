// Script de inicialização do MongoDB
// Este script é executado quando o container MongoDB é iniciado pela primeira vez

print('Inicializando banco de dados Meta_CrudUser...');

// Conecta ao banco de dados
db = db.getSiblingDB('to_do');

// Cria coleções necessárias
db.createCollection('users');
db.createCollection('personal_access_tokens');

// Cria índices para melhor performance
db.users.createIndex({ "email": 1 }, { unique: true });
db.users.createIndex({ "fullName": 1 });
db.personal_access_tokens.createIndex({ "tokenable_id": 1 });
db.personal_access_tokens.createIndex({ "token": 1 });

print('Banco de dados Meta_CrudUser inicializado com sucesso!');
print('Coleções criadas: users, personal_access_tokens');
print('Índices criados para otimização de consultas'); 