# AbaJov

## Configuração em uma nova máquina

Pré-requisitos:

- Node.js instalado;
- MySQL instalado e em execução;
- um usuário MySQL com permissão para criar o banco de dados.

1. Copie `.env.example` para `.env`.
2. Preencha no `.env` as credenciais do MySQL e a chave da API.
3. Execute:

```bash
npm run setup
```

Esse comando instala as dependências e executa todas as migrations pendentes.

Para executar somente as migrations depois que as dependências já estiverem instaladas:

```bash
npm run migrate
```

O esquema do banco fica em `migrate/schema.sql`. Ele é executado uma única vez
e o banco definido em `DB_NAME` é criado automaticamente, caso não exista.

Nunca versionar o arquivo `.env`. Use `.env.example` como modelo.
