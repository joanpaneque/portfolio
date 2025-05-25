<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { usePage } from '@inertiajs/vue3'

const skills = ref([
  { name: 'HTML5', src: '/images/stack/html.svg' },
  { name: 'CSS3', src: '/images/stack/css.svg' },
  { name: 'JavaScript (ES6+)', src: '/images/stack/javascript.svg' },
  { name: 'TypeScript', src: '/images/stack/typescript.svg' },
  { name: 'Vue.js', src: '/images/stack/vuejs.svg' },
  { name: 'React', src: '/images/stack/react.svg' },
  { name: 'Astro', src: '/images/stack/astro.svg' },
  { name: 'Redux', src: '/images/stack/redux.svg' },
  { name: 'Tailwind CSS', src: '/images/stack/tailwind.svg' },
  { name: 'Alpine.js', src: '/images/stack/alpinejs.svg' },
  { name: 'Bootstrap', src: '/images/stack/bootstrap.svg' },
  { name: 'Vite', src: '/images/stack/vitejs.svg' },
  { name: 'Inertia.js', src: '/images/stack/inertiajs.svg' },
  { name: 'PHP', src: '/images/stack/php.svg' },
  { name: 'Laravel', src: '/images/stack/laravel.svg' },
  { name: 'Node.js', src: '/images/stack/nodejs.svg' },
  { name: 'Express.js', src: '/images/stack/express.svg' },
  { name: 'MySQL', src: '/images/stack/mysql.svg' },
  { name: 'SQLite', src: '/images/stack/sqlite.svg' },
  { name: 'Redis', src: '/images/stack/redis.svg' },
  { name: 'REST APIs', src: '/images/stack/json.svg' },
  { name: 'Docker', src: '/images/stack/docker.svg' },
  { name: 'Git', src: '/images/stack/git.svg' },
  { name: 'GitHub Actions', src: '/images/stack/github-actions.svg' },
  { name: 'Nginx', src: '/images/stack/nginx.svg' },
  { name: 'Apache', src: '/images/stack/apache.svg' },
  { name: 'Linux Server (Ubuntu, etc.)', src: '/images/stack/linux.svg' },
  { name: 'Cloudflare', src: '/images/stack/cloudflare.svg' },
  { name: 'VPS', src: '/images/stack/digital-ocean.svg' },
  { name: 'Azure', src: '/images/stack/azure.svg' },
  { name: 'PHPUnit', src: '/images/stack/phpunit.svg' },
  { name: 'Pest', src: '/images/stack/pest.svg' },
  { name: 'Selenium', src: '/images/stack/selenium.svg' },
  { name: 'Web Scraping', src: '/images/stack/puppeteer.svg' },
  { name: 'After Effects', src: '/images/stack/after-effects.svg' },
  { name: 'C', src: '/images/stack/c.svg' },
  { name: 'C++', src: '/images/stack/cpp.svg' },
  { name: 'Figma', src: '/images/stack/figma.svg' },
  { name: 'Java', src: '/images/stack/java.svg' },
  { name: 'Jest', src: '/images/stack/jest.svg' },
  { name: 'Jira', src: '/images/stack/jira.svg' },
  { name: 'Livewire', src: '/images/stack/livewire.svg' },
  { name: 'Markdown', src: '/images/stack/markdown.svg' },
  { name: 'MariaDB', src: '/images/stack/mariadb.svg' },
  { name: 'Notion', src: '/images/stack/notion.svg' },
  { name: 'Photoshop', src: '/images/stack/photoshop.svg' },
  { name: 'Postman', src: '/images/stack/postman.svg' },
  { name: 'OpenAI API', src: '/images/stack/openai.svg' },
  { name: 'Anthropic API', src: '/images/stack/anthropic.svg' },
])

const sortedSkills = computed(() => {
  return [...skills.value].sort((a, b) => a.name.localeCompare(b.name))
})

const repeatedSkills = computed(() => {
  // Repetimos las skills suficientes veces para llenar la pantalla y crear el efecto continuo
  const repetitions = 8 // Ajusta este número según sea necesario
  const repeated = []
  for (let i = 0; i < repetitions; i++) {
    repeated.push(...sortedSkills.value)
  }
  return repeated
})

const filteredSkills = computed(() => {
  if (!recommendTechnologyForm.value.technology) {
    return repeatedSkills.value
  }
  
  const filtered = sortedSkills.value.filter(skill => 
    skill.name.toLowerCase().includes(recommendTechnologyForm.value.technology.toLowerCase())
  )
  
  // Estimamos si ocuparía más de una fila (aproximadamente 4-5 elementos por fila en pantallas pequeñas)
  const maxItemsPerRow = 4
  if (filtered.length > maxItemsPerRow) {
    // Si hay muchos resultados, usamos repeticiones para mantener la marquesina
    const repetitions = 4
    const repeated = []
    for (let i = 0; i < repetitions; i++) {
      repeated.push(...filtered)
    }
    return repeated
  }
  
  // Si son pocos resultados, los mostramos centrados sin repetir
  return filtered
})

const shouldUseMarquee = computed(() => {
  if (!recommendTechnologyForm.value.technology) {
    return true
  }
  
  const filtered = sortedSkills.value.filter(skill => 
    skill.name.toLowerCase().includes(recommendTechnologyForm.value.technology.toLowerCase())
  )
  
  // Usamos marquesina si hay más de 4 elementos filtrados para asegurar una sola línea
  // En pantallas pequeñas, incluso 4-5 elementos pueden ocupar múltiples líneas
  return filtered.length > 4
})

const recommendTechnologyForm = ref({
    technology: '',
});

const lastRecommendedTechnology = ref(usePage().props.lastRecommendedTechnology);
const canRecommendTechnology = ref(usePage().props.canRecommendTechnology);
const timeRemaining = ref(usePage().props.timeRemaining || 0);
const isLoading = ref(false);
const justRecommended = ref(false);
const isUndoing = ref(false);
let countdownInterval = null;
let successMessageTimeout = null;

// Función para formatear el tiempo restante
const formatTimeRemaining = computed(() => {
  if (timeRemaining.value <= 0) return '';
  
  const minutes = Math.floor(timeRemaining.value);
  const seconds = Math.floor((timeRemaining.value - minutes) * 60);
  
  if (minutes > 0) {
    return `${minutes}m ${seconds}s`;
  }
  return `${seconds}s`;
});

// Función para iniciar el contador regresivo
const startCountdown = () => {
  if (countdownInterval) {
    clearInterval(countdownInterval);
  }
  
  countdownInterval = setInterval(() => {
    if (timeRemaining.value > 0) {
      timeRemaining.value -= 1/60; // Decrementar en segundos (1/60 de minuto)
      
      if (timeRemaining.value <= 0) {
        timeRemaining.value = 0;
        canRecommendTechnology.value = true;
        clearInterval(countdownInterval);
      }
    }
  }, 1000); // Actualizar cada segundo
};

const recommendTechnology = async () => {
  if (!recommendTechnologyForm.value.technology.trim()) return;
  
  isLoading.value = true;
  justRecommended.value = false;
  
  // Limpiar timeout anterior si existe
  if (successMessageTimeout) {
    clearTimeout(successMessageTimeout);
  }
  
  try {
    const response = await axios.post(route('api.recommend-technology'), {
      technology: recommendTechnologyForm.value.technology
    });
    
    lastRecommendedTechnology.value = response.data.lastRecommendedTechnology;
    canRecommendTechnology.value = response.data.canRecommendTechnology;
    timeRemaining.value = response.data.timeRemaining || 0;
    
    // Si la recomendación fue exitosa, marcar como recomendado
    if (response.data.success) {
      justRecommended.value = true;
      
      // Configurar timeout para ocultar el mensaje después de 6 segundos
      successMessageTimeout = setTimeout(() => {
        justRecommended.value = false;
        recommendTechnologyForm.value.technology = ''; // Limpiar el texto del input
      }, 4000);
    }
    
    // Si hay tiempo restante, iniciar el contador
    if (timeRemaining.value > 0) {
      startCountdown();
    }
  } catch (error) {
    console.error('Error al recomendar tecnología:', error);
  } finally {
    isLoading.value = false;
  }
}

const undoRecommendation = async () => {
  isUndoing.value = true;
  
  try {
    const response = await axios.post(route('api.undo-recommendation'));
    
    lastRecommendedTechnology.value = response.data.lastRecommendedTechnology;
    canRecommendTechnology.value = response.data.canRecommendTechnology;
    timeRemaining.value = response.data.timeRemaining || 0;
    
    // Limpiar el contador si ya no hay tiempo restante
    if (timeRemaining.value <= 0) {
      if (countdownInterval) {
        clearInterval(countdownInterval);
      }
    }
  } catch (error) {
    console.error('Error al deshacer recomendación:', error);
  } finally {
    isUndoing.value = false;
  }
}

// Función para resetear el estado cuando cambie el input
const onInputChange = () => {
  justRecommended.value = false;
  
  // Limpiar timeout si existe
  if (successMessageTimeout) {
    clearTimeout(successMessageTimeout);
  }
};

// Inicializar el contador si hay tiempo restante al cargar la página
onMounted(() => {
  if (timeRemaining.value > 0) {
    startCountdown();
  }
});

// Limpiar el intervalo al desmontar el componente
onUnmounted(() => {
  if (countdownInterval) {
    clearInterval(countdownInterval);
  }
  if (successMessageTimeout) {
    clearTimeout(successMessageTimeout);
  }
});

</script>

<template>
    <div class="mt-20 w-full flex flex-col items-center text-center pb-4 font-bold text-sm text-gray-200 px-8 md:px-16 bg-white">
      <span>{{ $trans("joan_skills.title") }}</span>

      <div class="overflow-hidden w-full m-8">
        <div 
          :class="shouldUseMarquee ? 'flex flex-row items-center gap-8 md:gap-16 animate-marquee' : 'flex flex-row items-center justify-center gap-8 md:gap-16 flex-wrap'"
          class="min-h-[100px]"
        >
          <!-- Mensaje cuando no hay resultados -->
          <div v-if="recommendTechnologyForm.technology && filteredSkills.length === 0" class="flex flex-col items-center justify-center w-full h-full gap-4">
            <!-- Mensaje de éxito cuando se ha recomendado -->
            <span v-if="justRecommended" class="text-emerald-400 text-sm font-medium flex items-center gap-2">
              {{ $trans("joan_skills.recommend_technology_success", { technology: recommendTechnologyForm.technology }) }}
              <!-- Círculo de progreso -->
              <div class="relative w-4 h-4">
                <svg class="w-4 h-4 transform -rotate-90" viewBox="0 0 24 24">
                  <!-- Círculo de fondo -->
                  <circle
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="2"
                    fill="none"
                    class="text-emerald-600/30"
                  />
                  <!-- Círculo de progreso -->
                  <circle
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="2"
                    fill="none"
                    stroke-linecap="round"
                    class="text-emerald-900 animate-progress-circle"
                    stroke-dasharray="62.83"
                    stroke-dashoffset="62.83"
                  />
                </svg>
              </div>
            </span>
            <!-- Mensaje cuando no se encuentra la tecnología -->
            <span v-else class="text-gray-400 text-sm italic">
              {{ $trans("joan_skills.not_found_technology", { technology: recommendTechnologyForm.technology }) }}
            </span>
            
            <!-- Contenedor de botones -->
            <div class="flex items-center gap-2">
              <button 
                @click="recommendTechnology"
                :disabled="isLoading || !canRecommendTechnology"
                class="min-w-[120px] bg-emerald-900 cursor-pointer hover:bg-emerald-800 disabled:bg-emerald-900/50 disabled:cursor-not-allowed text-white text-xs font-medium px-4 py-2 rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-50"
              >
                <span v-if="isLoading">Recomendando...</span>
                <span v-else-if="!canRecommendTechnology && timeRemaining > 0">
                  {{ $trans("joan_skills.wait_recommend_technology", { time_remaining: formatTimeRemaining }) }}
                </span>
                <span v-else-if="!canRecommendTechnology">No puedes recomendar más</span>
                <span v-else>{{ $trans("joan_skills.recommend_technology", { technology: recommendTechnologyForm.technology }) }}</span>
              </button>

              <!-- Botón de undo - solo aparece cuando hay tiempo restante -->
              <button 
                v-if="!canRecommendTechnology && timeRemaining > 0 && lastRecommendedTechnology"
                @click="undoRecommendation"
                :disabled="isUndoing"
                class="bg-emerald-900 cursor-pointer hover:bg-emerald-800 disabled:bg-emerald-900/50 disabled:cursor-not-allowed text-white text-xs font-medium px-3 py-2 rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-opacity-50"
                :title="$trans('joan_skills.recommend_technology_undo_title')"
              >
                <span v-if="isUndoing">...</span>
                <span v-else>{{ $trans("joan_skills.recommend_technology_undo") }}</span>
              </button>
            </div>

          </div>
          <!-- Tecnologías -->
          <div 
            v-for="(skill, index) in filteredSkills" 
            :key="`${skill.name}-${index}`"
            class="flex flex-col items-center gap-2 flex-shrink-0 group/skill"
          >
            <img 
              :src="skill.src" 
              :alt="skill.name" 
              class="w-[60px] h-[60px] object-contain grayscale group-hover/skill:grayscale-0 transition duration-300" 
            />
            <span class="text-xs text-gray-300 font-medium whitespace-nowrap">{{ skill.name }}</span>
          </div>
        </div>
      </div>
      
      <!-- Search Input -->
      <div class="mt-4 mb-8">
        <input 
          v-model="recommendTechnologyForm.technology"
          type="text" 
          :placeholder="$trans('joan_skills.placeholder_search_technology')"
          maxlength="15"
          class="bg-white/10 border border-emerald-900/20 text-black text-sm px-4 py-2 rounded-full focus:outline-none focus:border-emerald-500 focus:bg-white/15 transition-all duration-300 placeholder-gray-400 w-64"
          @input="onInputChange"
        />
      </div>
    </div>
  </template>

<style scoped>
@keyframes marquee {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(calc(-100% / 8));
  }
}

@keyframes progress-circle {
  0% {
    stroke-dashoffset: 62.83;
  }
  100% {
    stroke-dashoffset: 0;
  }
}

.animate-marquee {
  animation: marquee 60s linear infinite;
  width: max-content;
}

.animate-marquee:hover {
  animation-play-state: paused;
}

.animate-progress-circle {
  animation: progress-circle 4s linear forwards;
}
</style> 