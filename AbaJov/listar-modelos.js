// listar-modelos.js
require('dotenv').config();
const { GoogleGenAI } = require('@google/genai');

const ai = new GoogleGenAI({ apiKey: process.env.GEMINI_API_KEY });

(async () => {
  const lista = await ai.models.list();
  for await (const modelo of lista) {
    console.log(modelo.name); // ex: models/nome-do-modelo
  }
})();