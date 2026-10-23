require('dotenv').config();
const express = require('express');
const cors = require('cors');
const db = require('./db');

const app = express();

// Restringe o CORS ao endereço do front-end (5173 é a porta padrão do Vite)
app.use(cors({ origin: 'http://localhost:5173' }));
app.use(express.json());

// Rota de status do Banco de Dados (a sua, sem mudanças)
app.get('/status', async (req, res) => {
  try {
    const [resultado] = await db.query('SELECT 1 + 1 AS teste');
    res.json({
      mensagem: 'Servidor e Banco de Dados rodando perfeitamente!',
      banco: resultado,
    });
  } catch (erro) {
    console.error('Erro na conexão', erro);
    res.status(500).json({ erro: 'Falha ao conectar no banco de dados' });
  }
});

// Rota do chatbot
app.use('/api/chat', require('./routes/chat'));

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Servidor rodando em http://localhost:${PORT}`);
});