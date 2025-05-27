<script setup>
import { ref } from 'vue';

const scrollPosition = ref(0);
const isMenuOpen = ref(false);

window.addEventListener('scroll', () => {
  const pageY = document.body.getBoundingClientRect();
  scrollPosition.value = pageY.top < 0
    ? Math.min(100, Math.max(0, (window.scrollY / (document.body.scrollHeight - window.innerHeight)) * 100))
    : 0;
});

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

const closeMenu = () => {
  isMenuOpen.value = false;
};
</script>

<template>
  <nav class="fixed w-full z-50 bg-[#002626] border-b border-dotted border-white/30 pt-[200px] mt-[-200px]">
    <div class="w-full h-[5px] bg-emerald-900">
      <div class="h-full bg-emerald-600" :style="{ width: `${scrollPosition}%` }"></div>
    </div>
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between h-20">
        <div class="flex items-center">
          <a class="text-2xl font-bold text-white flex items-center gap-2" href="#home" @click="closeMenu">
            <img alt="Logo" width="45" height="45" src="/images/logo.svg" />
            <span class="ml-2">{{ $trans("joan_navbar.portfolio") }}</span>
          </a>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-8">
          <a class="text-white/80 hover:text-white transition-colors" href="#about">{{ $trans("joan_navbar.about") }}</a>
          <a class="text-white/80 hover:text-white transition-colors" href="#projects">{{ $trans("joan_navbar.projects") }}</a>
          <a class="text-white/80 hover:text-white transition-colors" href="#awards">{{ $trans("joan_navbar.awards") }}</a>
          <a class="text-white/80 hover:text-white transition-colors" href="#contact">{{ $trans("joan_navbar.contact") }}</a>
        </div>

        <div class="flex items-center space-x-4">
          <a class="hidden md:block bg-white text-black px-6 py-2 rounded-full font-medium hover:bg-gray-200 transition-colors" href="#contact">{{ $trans("joan_navbar.contact_me") }}</a>

          <!-- Hamburger Button -->
          <button
            class="md:hidden text-white p-2 focus:outline-none"
            @click="toggleMenu"
            aria-label="Toggle menu"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              class="h-6 w-6"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                v-if="!isMenuOpen"
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
              />
              <path
                v-else
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18L18 6M6 6l12 12"
              />
            </svg>
          </button>
        </div>
      </div>

      <!-- Mobile Menu -->
      <div
        v-if="isMenuOpen"
        class="md:hidden py-4 border-t border-dotted border-white/30 pb-6 animate-fadeIn"
      >
        <div class="flex flex-col space-y-4">
          <a class="text-white/80 hover:text-white transition-colors py-2" href="#about" @click="closeMenu">{{ $trans("joan_navbar.about") }}</a>
          <a class="text-white/80 hover:text-white transition-colors py-2" href="#projects" @click="closeMenu">{{ $trans("joan_navbar.projects") }}</a>
          <a class="text-white/80 hover:text-white transition-colors py-2" href="#awards" @click="closeMenu">{{ $trans("joan_navbar.awards") }}</a>
          <a class="text-white/80 hover:text-white transition-colors py-2" href="#contact" @click="closeMenu">{{ $trans("joan_navbar.contact") }}</a>
          <a class="bg-white text-black px-6 py-2 rounded-full font-medium hover:bg-gray-200 transition-colors w-full text-center mt-4" href="#contact" @click="closeMenu">{{ $trans("joan_navbar.contact_me") }}</a>
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.animate-fadeIn {
  animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>