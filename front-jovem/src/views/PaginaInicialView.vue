<template>
  <main class="w-full min-h-screen">
    <!-- SEÇÃO Superior-->
   <section class="bg-blue-950 px-4 md:px-8 py-2 md:py-4 flex items-center justify-between h-20 md:h-32 overflow-hidden">
      <div class="flex items-center">
       <img 
       src="../assets/fundojovem.png" 
      alt="Ilustração do Jovem" 
      class="w-12 md:w-20 h-auto object-contain rotate-90 translate-x-12 md:translate-x-24"
      />
      </div>

      <div class="flex items-center">
       <img 
       src="../assets/logo.png" 
      alt="logo" 
      class="w-12 md:w-30 h-auto object-contain"
      />
      </div>
   </section>
   
   <!-- SEÇÃO meio -->
<section class="relative bg-blue-50 w-full py-12 px-6 flex flex-col md:flex-row items-center justify-center gap-8 h-112 overflow-hidden">
  
  <img 
    src="../assets/figura.png" 
    alt="Decoração" 
    class="absolute bottom-0 left-0 w-32 md:w-120 h-auto object-contain pointer-events-none"
  />

  <!-- Bloco de Texto e Botão -->
  <div class="flex flex-col items-center gap-6 text-center z-10">
    <h1 class="text-3xl md:text-5xl font-extrabold text-blue-950">
      Seja Bem Vindo!
    </h1>
    <button class="bg-blue-200 text-blue-950 font-bold text-xl md:text-2xl px-8 py-3 rounded-md hover:bg-blue-300 transition-colors">
      cadastrar
    </button>
  </div>

</section>

    <!-- SEÇÃO INFERIOR -->
    <section class="bg-blue-950 text-white w-full py-3 px-8 flex items-center justify-start">
      <p class="text-lg md:text-xl font-bold">
        Inicio
      </p>
    </section>
  </main>
</template>

<script setup>
</script>