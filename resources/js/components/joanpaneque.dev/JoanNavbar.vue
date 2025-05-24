<script setup>
import { ref } from 'vue';

const scrollPosition = ref(0);

window.addEventListener('scroll', () => {
  const pageY = document.body.getBoundingClientRect();
  scrollPosition.value = pageY.top < 0
    ? Math.min(100, Math.max(0, (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100))
    : 0;
});
</script>

<template>
  <nav class="fixed w-full z-50 bg-[#002626] border-b border-dotted border-white/30">
    <div class="w-full h-[5px] bg-emerald-900">
      <div class="h-full bg-emerald-600" :style="{ width: `${scrollPosition}%` }"></div>
    </div>
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between h-20">
        <div class="flex items-center">
          <a class="text-2xl font-bold text-white flex items-center gap-2" href="#home">
            <img alt="Logo" width="60" height="60" src="/images/logo.svg" />
            Portfolio
          </a>
        </div>
        <div class="hidden md:flex items-center space-x-8">
          <a class="text-white/80 hover:text-white transition-colors" href="#about">Sobre mí</a>
          <a class="text-white/80 hover:text-white transition-colors" href="#projects">Proyectos</a>
          <a class="text-white/80 hover:text-white transition-colors" href="#awards">Premios</a>
          <a class="text-white/80 hover:text-white transition-colors" href="#contact">Contacto</a>
        </div>
        <div class="flex items-center space-x-4">
          <a class="bg-white text-black px-6 py-2 rounded-full font-medium hover:bg-gray-200 transition-colors" href="#contact">Contáctame</a>
        </div>
      </div>
    </div>
  </nav>
</template> 