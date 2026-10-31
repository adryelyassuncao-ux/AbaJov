require('dotenv').config();

const fs = require('fs');
const path = require('path');
const mysql = require('mysql2/promise');

const requiredVariables = ['DB_HOST', 'DB_USER', 'DB_NAME'];
const missingVariables = requiredVariables.filter((name) => !process.env[name]);

const connectionOptions = {
  host: process.env.DB_HOST,
  user: process.env.DB_USER,
  password: process.env.DB_PASSWORD || '',
  port: Number(process.env.DB_PORT || 3306),
  multipleStatements: true,
};

const databaseName = process.env.DB_NAME;
const schemaFile = path.join(__dirname, 'schema.sql');

const quoteIdentifier = (identifier) => `\`${identifier.replace(/`/g, '``')}\``;

async function migrate() {
  const connection = await mysql.createConnection(connectionOptions);

  try {
    await connection.query(
      `CREATE DATABASE IF NOT EXISTS ${quoteIdentifier(databaseName)} ` +
      'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
    );
    await connection.query(`USE ${quoteIdentifier(databaseName)}`);
    await connection.query(`
      CREATE TABLE IF NOT EXISTS migrations (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT,
        migration VARCHAR(255) NOT NULL,
        executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY migrations_migration_unique (migration)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    `);

    const [appliedRows] = await connection.query('SELECT migration FROM migrations');
    const applied = new Set(appliedRows.map((row) => row.migration));
    const migration = 'schema.sql';
    if (!applied.has(migration)) {
      const sql = fs.readFileSync(schemaFile, 'utf8').trim();
      await connection.beginTransaction();
      try {
        await connection.query(sql);
        await connection.query('INSERT INTO migrations (migration) VALUES (?)', [migration]);
        await connection.commit();
        console.log(`Migration aplicada: ${migration}`);
      } catch (error) {
        await connection.rollback();
        throw new Error(`Falha na migration ${migration}: ${error.message}`);
      }
    }

    console.log('Banco de dados pronto.');
  } finally {
    await connection.end();
  }
}

if (missingVariables.length > 0) {
  console.error(`Variáveis obrigatórias ausentes no .env: ${missingVariables.join(', ')}`);
  process.exitCode = 1;
} else {
  migrate().catch((error) => {
    console.error(`Erro ao preparar o banco: ${error.message}`);
    process.exitCode = 1;
  });
}
