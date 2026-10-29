import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite' // <-- Adicione esta linha

export default defineConfig({
  plugins: [
    vue(),
    tailwindcss(), // <-- Adicione esta linha
  ],
})