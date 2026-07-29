require('dotenv').config();
const express = require('express');
const cors = require('cors');
const db = require('./db');

const app = express();

app.use(cors());
app.use(express.json());

app.get('/status', async (req, res) => {

    try{
        const [resultado] = await db.query('SELECT 1 + 1 AS teste');
        res.json({mensagem:'Servidor e Banco de Dados rodando perfeitamente!', 
            banco: resultado
            });

        
    }  catch(erro)
  
    {
        console.error('Error na conexão', erro);
        res.status(500).json({ erro: 'Falha ao conectar no banco de dados'});
    }
}
);

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
    console.log(`Servidor rodando em http://localhost:${PORT}`);
})