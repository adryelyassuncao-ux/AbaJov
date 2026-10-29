// routes/chat.js
const express = require('express');
const rateLimit = require('express-rate-limit');
const { GoogleGenAI } = require('@google/genai');
const SYSTEM_PROMPT = require('../prompts/assistente');

const router = express.Router();
const ai = new GoogleGenAI({ apiKey: process.env.GEMINI_API_KEY });

// Modelos a tentar, em ordem (o reserva só entra se estiver no .env)
const MODELOS = [
  process.env.GEMINI_MODELO || 'gemini-3.8-flash',
  process.env.GEMINI_MODELO_RESERVA,
].filter(Boolean);

const MAX_MENSAGENS = 10;    // quantas mensagens do histórico enviar
const MAX_CARACTERES = 1000; // tamanho máximo de cada mensagem

// Rate limit: cada IP pode fazer 10 perguntas por minuto
const limitador = rateLimit({
  windowMs: 60 * 1000,
  max: 10,
  message: { erro: 'Muitas mensagens em pouco tempo. Aguarde um instante.' },
});

const esperar = (ms) => new Promise((resolve) => setTimeout(resolve, ms));

// Só vale a pena tentar de novo em erro temporário (sobrecarga / limite de uso)
const ehErroTemporario = (err) => err.status === 429 || err.status === 503;

router.post('/', limitador, async (req, res) => {
  try {
    const { messages } = req.body; // [{ role: 'user' | 'assistant', content: '...' }]

    if (!Array.isArray(messages) || messages.length === 0) {
      return res.status(400).json({ erro: 'Mensagens inválidas.' });
    }

    // Últimas mensagens, validadas e convertidas pro formato do Gemini
    const contents = messages
      .slice(-MAX_MENSAGENS)
      .filter((m) => (m.role === 'user' || m.role === 'assistant') && typeof m.content === 'string')
      .map((m) => ({
        role: m.role === 'assistant' ? 'model' : 'user',
        parts: [{ text: m.content.slice(0, MAX_CARACTERES) }],
      }));

    // O histórico precisa começar por uma mensagem do usuário
    while (contents.length > 0 && contents[0].role !== 'user') contents.shift();

    if (contents.length === 0) {
      return res.status(400).json({ erro: 'Mensagens inválidas.' });
    }

    // Tenta cada modelo, com retry e espera crescente em erro temporário
    let response;
    let ultimoErro;

    for (const modelo of MODELOS) {
      for (let i = 0; i < 3; i++) {
        try {
          response = await ai.models.generateContent({
            model: modelo,
            contents,
            config: { systemInstruction: SYSTEM_PROMPT, maxOutputTokens: 500 },
          });
          break;
        } catch (err) {
          ultimoErro = err;
          if (!ehErroTemporario(err)) {
            console.error(`Modelo ${modelo} falhou (erro permanente):`, err.message);
            break; // passa para o próximo modelo
          }
          if (i < 2) await esperar(1000 * 2 ** i); // espera 1s, depois 2s
        }
      }
      if (response) break;
    }

    if (!response) throw ultimoErro;

    res.json({ resposta: response.text || 'Não consegui gerar uma resposta agora.' });
  } catch (erro) {
    console.error('Erro ao comunicar com o Gemini:', erro);
    if (ehErroTemporario(erro)) {
      return res.status(503).json({
        erro: 'O assistente está com muita procura agora. Tente de novo em alguns instantes.',
      });
    }
    res.status(500).json({ erro: 'Não foi possível responder agora.' });
  }
});

module.exports = router;