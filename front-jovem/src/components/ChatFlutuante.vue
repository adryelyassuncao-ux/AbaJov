<script setup>
import { ref } from 'vue'

const chatAberto = ref(false)
const texto = ref('')

const mensagens = ref([
  { autor: 'ia', texto: 'Olá! Como posso te ajudar com seu currículo ou com as vagas?' },
])

function enviar() {
  const conteudo = texto.value.trim()
  if (conteudo === '') return

  mensagens.value.push({ autor: 'jovem', texto: conteudo })
  texto.value = ''
}
</script>

<template>
  <div class="fixed bottom-4 right-4">
    <div
      v-if="chatAberto"
      class="mb-3 w-80 h-96 bg-white border rounded-lg shadow-lg flex flex-col"
    >
      <div class="flex items-center justify-between bg-blue-600 text-white px-4 py-3 rounded-t-lg">
        <span class="font-semibold">Assistente</span>
        <button @click="chatAberto = false">✕</button>
      </div>

      <div class="flex-1 overflow-y-auto p-3 space-y-2">
        <div
          v-for="(msg, i) in mensagens"
          :key="i"
          class="flex"
          :class="msg.autor === 'jovem' ? 'justify-end' : 'justify-start'"
        >
          <p
            class="px-3 py-2 rounded-lg text-sm max-w-[80%]"
            :class="msg.autor === 'jovem' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-800'"
          >
            {{ msg.texto }}
          </p>
        </div>
      </div>

      <div class="flex gap-2 border-t p-2">
        <input
          v-model="texto"
          @keyup.enter="enviar"
          type="text"
          placeholder="Digite sua mensagem..."
          class="flex-1 border rounded-lg px-3 py-2 text-sm"
        />
        <button
          @click="enviar"
          class="bg-blue-600 text-white px-3 rounded-lg text-sm"
        >
          Enviar
        </button>
      </div>
    </div>

    <button
      @click="chatAberto = !chatAberto"
      class="w-14 h-14 rounded-full bg-blue-600 text-white shadow-lg"
    >
      Chat
    </button>
  </div>
</template>