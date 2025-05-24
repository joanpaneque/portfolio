<script setup>
import { ref } from 'vue';

const companies = ref([
  {
    id: 1,
    name: 'Empresa 1',
    logo: '/images/companies/renfe.svg',
    url: 'https://renfe.com'
  },
  {
    id: 2,
    name: 'Empresa 2',
    logo: '/images/companies/logirail.svg',
    url: 'https://logirail.com'
  },
  {
    id: 3,
    name: 'Empresa 3',
    logo: '/images/companies/gdp.svg',
    url: 'https://garciadepou.com'
  },
  {
    id: 4,
    name: 'Empresa 4',
    logo: '/images/companies/avantiam.png',
    url: 'https://avantiam.com'
  },
  {
    id: 5,
    name: 'Empresa 5',
    logo: '/images/companies/trueta.svg',
    url: 'https://icsgirona.cat/ca/hospitaltrueta'
  },
  {
    id: 6,
    name: 'Empresa 6',
    logo: '/images/companies/thehandbook.png',
    url: 'https://www.thehandbook.es/'
  }
]);
</script>

<template>
  <section id="companies" class="py-16 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-12">
        <h2 class="text-2xl md:text-3xl font-bold text-gray-900">
          Empresas que han confiado en mi trabajo
        </h2>
      </div>

      <div class="flex flex-wrap justify-center items-center gap-8 md:gap-12">
        <a 
          v-for="company in companies" 
          :key="company.id"
          :href="company.url"
          target="_blank"
          rel="noopener noreferrer"
          class="group transition-all duration-300 hover:scale-110"
        >
          <div class="w-24 h-24 md:w-32 md:h-32 flex items-center justify-center p-4 transition-all duration-300">
            <img 
              v-if="company.logo" 
              :src="company.logo" 
              :alt="company.name"
              class="max-w-full max-h-full object-contain filter grayscale group-hover:grayscale-0 transition-all duration-300"
              @error="$event.target.style.display = 'none'"
            >
            <div 
              v-else
              class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-xl md:text-2xl"
            >
              {{ company.name.charAt(0) }}
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>
</template>

<style scoped>
/* Animaciones adicionales si las necesitas */
.company-card {
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.company-card:hover {
  transform: translateY(-5px);
}
</style> 