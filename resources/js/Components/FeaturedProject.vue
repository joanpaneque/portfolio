<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Link } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";

// Configuración de i18n
const { t } = useI18n();

const props = defineProps<{
  title: string;
  description: string;
  technologies: Array<{
    name: string;
    color: string;
  }>;
  projectUrl: string;
  images: Array<{
    type?: string;
    src: string;
    gradient?: string;
    title: string;
  }>;
  awards?: Array<{
    title: string;
    description: string;
  }>;
  buttonText?: string;
  gradientColors?: {
    from: string;
    via: string;
    to: string;
  };
}>();

// Define los slots
defineSlots<{
  metrics: (props: {}) => any;
  default: (props: {}) => any;
}>();

// Valores por defecto para el gradiente
const defaultGradient = {
  from: "from-cyan-500/40 dark:from-cyan-500/40",
  via: "via-blue-600/30 dark:via-blue-600/30",
  to: "to-indigo-700/40 dark:to-indigo-700/40"
};

// Combinar el gradiente proporcionado con los valores por defecto
const gradientClasses = computed(() => {
  const gradient = props.gradientColors || defaultGradient;
  return `${gradient.from} ${gradient.via} ${gradient.to}`;
});

// Control para mostrar/ocultar detalles del proyecto
const showProjectDetails = ref(false);

// Variables para el modal de imágenes
const showImageModal = ref(false);
const selectedImageIndex = ref(0);

// Función para abrir el modal con la imagen seleccionada
const openImageModal = (index: number) => {
  selectedImageIndex.value = index;
  showImageModal.value = true;
  
  // Prevenir el scroll del body mientras el modal está abierto
  document.body.style.overflow = 'hidden';
  
  // Aplicar la animación pulse al elemento que se ha clicado
  setTimeout(() => {
    const clickedImage = document.querySelector(`.gallery-image-${index}`);
    if (clickedImage) {
      clickedImage.classList.add('pulse-on-click');
      
      // Eliminar la clase después de la animación para que se pueda reproducir de nuevo
      setTimeout(() => {
        clickedImage.classList.remove('pulse-on-click');
      }, 300); // Duración de la animación
    }
  }, 0);
};

// Función para cerrar el modal
const closeImageModal = () => {
  showImageModal.value = false;
  // Restaurar el scroll del body
  document.body.style.overflow = '';
};

// Event listener para cerrar con Escape
const handleKeyDown = (e: KeyboardEvent) => {
  if (e.key === 'Escape' && showImageModal.value) {
    closeImageModal();
  }
};

// Añadir y eliminar el event listener
onMounted(() => {
  window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeyDown);
});
</script>

<template>
  <div class="relative group overflow-hidden rounded-3xl shadow-xl transform transition-all duration-500 hover:scale-[1.02] h-auto backdrop-blur-sm border border-gray-200/60 dark:border-white/5">
    <!-- Fondo con gradiente interactivo -->
    <div class="absolute inset-0 bg-gradient-to-br opacity-95 dark:opacity-60 group-hover:opacity-100 dark:group-hover:opacity-80 transition-opacity duration-500 backdrop-blur-md" :class="gradientClasses"></div>
    
    <!-- Patrón geométrico decorativo -->
    <div class="absolute inset-0 opacity-15 dark:opacity-20 mix-blend-overlay">
      <div class="absolute top-0 left-0 right-0 h-full w-full" 
           style="background-image: radial-gradient(circle, rgba(20,20,20,0.7) 1px, transparent 1px); 
                  background-size: 20px 20px;"></div>
    </div>
    
    <!-- Formas decorativas -->
    <div class="absolute -right-20 -top-20 w-64 h-64 rounded-full bg-blue-300/15 dark:bg-blue-400/5 blur-3xl"></div>
    <div class="absolute -left-20 -bottom-20 w-64 h-64 rounded-full bg-indigo-300/15 dark:bg-indigo-400/5 blur-3xl"></div>
    
    <!-- Contenido -->
    <div class="relative z-10 p-8 flex flex-col">
      <!-- Cabecera con título del proyecto -->
      <div class="flex items-center mb-6">
        <div class="w-10 h-10 flex items-center justify-center rounded-full bg-white/30 backdrop-blur-md mr-4">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800 dark:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
          </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ title }}</h3>
      </div>
      
      <!-- Descripción -->
      <p class="text-gray-800 dark:text-white/90 mb-8 backdrop-blur-sm bg-white/60 dark:bg-white/5 p-4 rounded-xl border border-gray-300 dark:border-white/10">
        {{ description }}
      </p>
      
      <!-- Botón de acción que ahora actúa como toggle -->
      <button
        @click="showProjectDetails = !showProjectDetails"
        class="inline-flex items-center px-5 py-2.5 rounded-xl text-gray-800 dark:text-white font-medium border border-gray-400 dark:border-white/10 transition-all duration-300 group-hover:shadow-lg w-full justify-center bg-white/60 hover:bg-white/80 dark:bg-white/10 dark:hover:bg-white/20 backdrop-blur-md"
        :class="{'mb-8': images.length > 0 || showProjectDetails}"
      >
        <span>{{ showProjectDetails ? t('project.hideDetails') || "Ocultar detalles" : t('cta.viewDetails') || "Ver detalles" }}</span>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          class="h-5 w-5 ml-2 transition-transform duration-300"
          :class="showProjectDetails ? 'rotate-90' : ''"
          fill="none"
          viewBox="0 0 24 24"
          stroke="currentColor"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M14 5l7 7m0 0l-7 7m7-7H3"
          />
        </svg>
      </button>
      
      <!-- Contenido expandible con detalles -->
      <div
        v-show="showProjectDetails"
        class="mb-8 overflow-hidden transition-all duration-500 ease-in-out"
        :class="{ 'animate-slideDown': showProjectDetails }"
      >
        <!-- Dashboard de estadísticas - Aquí insertamos el slot para métricas personalizadas -->
        <div class="bg-white/70 dark:bg-white/10 backdrop-blur-md rounded-2xl p-5 mb-6 border border-gray-300 dark:border-white/20">
          <slot name="metrics"></slot>
        </div>
        
        <!-- Premios ganados, mostrado solo si existen awards -->
        <div v-if="awards && awards.length > 0" class="mb-6">
          <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-600 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
            {{ t('project.awardsWon') || "Premios ganados" }}
          </h4>
          <div class="space-y-3">
            <div v-for="(award, index) in awards" :key="index" class="bg-white/60 dark:bg-white/10 backdrop-blur-sm p-3 rounded-xl border border-gray-300 dark:border-white/10 flex items-start">
              <div class="w-8 h-8 flex-shrink-0 bg-yellow-500/30 dark:bg-yellow-500/20 rounded-full flex items-center justify-center mr-3 mt-0.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-700 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
              </div>
              <div class="text-gray-900 dark:text-white text-sm">
                <span class="font-medium">{{ award.title }}</span>
                <p class="text-gray-700 dark:text-white/80 text-xs mt-1">{{ award.description }}</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Tecnologías utilizadas -->
        <div class="flex flex-wrap gap-2 mb-6">
          <span 
            v-for="(tech, index) in technologies" 
            :key="index" 
            class="px-3 py-1.5 bg-white/60 dark:bg-white/10 backdrop-blur-md text-gray-800 dark:text-white text-sm rounded-full border border-gray-300 dark:border-white/10 flex items-center"
          >
            <span class="w-2 h-2 rounded-full mr-2" :class="tech.color"></span>
            {{ tech.name }}
          </span>
        </div>
        
        <!-- Enlace a página completa del proyecto -->
        <Link
          :href="projectUrl"
          class="inline-flex items-center px-5 py-2.5 rounded-xl text-gray-800 dark:text-white font-medium border border-gray-400 dark:border-white/10 transition-all duration-300 group-hover:shadow-lg w-full justify-center bg-white/60 hover:bg-white/80 dark:bg-white/10 dark:hover:bg-white/20 backdrop-blur-md"
        >
          {{ buttonText || t('cta.goToProject') || "Ir al proyecto" }}
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 ml-2 transition-transform duration-300 group-hover:translate-x-1"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M14 5l7 7m0 0l-7 7m7-7H3"
            />
          </svg>
        </Link>
      </div>
      
      <!-- Galería de imágenes estilo Bento -->
      <div v-if="images.length > 0" class="grid grid-cols-4 gap-3">
        <!-- Imagen 1 - Más grande a la izquierda, solo se muestra si hay al menos una imagen -->
        <div 
          v-if="images.length > 0"
          class="col-span-2 row-span-2 relative group/img overflow-hidden rounded-xl border border-gray-300 dark:border-white/20 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-lg hover:border-gray-400 dark:hover:border-white/40 cursor-pointer hover-glow gallery-image-0"
          @click="openImageModal(0)"
        >
          <img 
            :src="images[0].src" 
            :alt="images[0].title"
            class="w-full h-full object-cover min-h-[200px] transition-transform duration-500 group-hover/img:scale-110"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-800/30 to-transparent dark:from-blue-900/60 dark:via-blue-700/10 opacity-75 group-hover/img:opacity-85 transition-all duration-300 flex items-end">
            <span class="text-white text-sm font-medium p-3 translate-y-2 group-hover/img:translate-y-0 transition-transform duration-300">{{ images[0].title }}</span>
          </div>
        </div>
        
        <!-- Imagen 2 - Arriba a la derecha, solo se muestra si hay al menos dos imágenes -->
        <div 
          v-if="images.length > 1"
          class="col-span-2 relative group/img overflow-hidden rounded-xl border border-gray-300 dark:border-white/20 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-lg hover:border-gray-400 dark:hover:border-white/40 cursor-pointer hover-glow gallery-image-1"
          @click="openImageModal(1)"
        >
          <img 
            :src="images[1].src" 
            :alt="images[1].title"
            class="w-full h-full object-cover min-h-[95px] transition-transform duration-500 group-hover/img:scale-110"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-800/30 to-transparent dark:from-blue-900/60 dark:via-blue-700/10 opacity-75 group-hover/img:opacity-85 transition-all duration-300 flex items-end">
            <span class="text-white text-sm font-medium p-3 translate-y-2 group-hover/img:translate-y-0 transition-transform duration-300">{{ images[1].title }}</span>
          </div>
        </div>
        
        <!-- Imagen 3 - Abajo a la derecha, solo se muestra si hay al menos tres imágenes -->
        <div 
          v-if="images.length > 2"
          class="col-span-2 relative group/img overflow-hidden rounded-xl border border-gray-300 dark:border-white/20 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-lg hover:border-gray-400 dark:hover:border-white/40 cursor-pointer hover-glow gallery-image-2"
          @click="openImageModal(2)"
        >
          <img 
            :src="images[2].src" 
            :alt="images[2].title"
            class="w-full h-full object-cover max-h-[160px] transition-transform duration-500 group-hover/img:scale-110"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-800/30 to-transparent dark:from-blue-900/60 dark:via-blue-700/10 opacity-75 group-hover/img:opacity-85 transition-all duration-300 flex items-end">
            <span class="text-white text-sm font-medium p-3 translate-y-2 group-hover/img:translate-y-0 transition-transform duration-300">{{ images[2].title }}</span>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Modal de imágenes -->
    <Teleport to="body">
      <div v-if="showImageModal" class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/85 p-4 transition-opacity duration-300">
        <!-- Fondo del modal que permite cerrar al hacer clic -->
        <div class="absolute inset-0" @click="closeImageModal"></div>
        
        <div class="relative max-w-5xl w-full max-h-[95vh] overflow-hidden">
          <!-- Imagen actual -->
          <img 
            :src="images[selectedImageIndex].src" 
            :alt="images[selectedImageIndex].title" 
            class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl"
          />
          
          <!-- Título de la imagen -->
          <div class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-t from-black/70 to-transparent">
            <h4 class="text-white font-medium text-xl">
              {{ images[selectedImageIndex].title }}
            </h4>
          </div>
          
          <!-- Contador de imágenes -->
          <div class="absolute top-4 left-4 bg-black/60 px-3 py-1 rounded-full text-white text-sm">
            {{ selectedImageIndex + 1 }} / {{ images.length }}
          </div>
          
          <!-- Botón para cerrar -->
          <button 
            @click="closeImageModal" 
            class="absolute top-4 right-4 w-10 h-10 flex items-center justify-center rounded-full bg-black/60 text-white hover:bg-white/20 transition-colors duration-300"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
          </button>
          
          <!-- Controles de navegación, solo si hay más de una imagen -->
          <div v-if="images.length > 1" class="absolute inset-x-0 top-1/2 transform -translate-y-1/2 flex justify-between px-4">
            <button 
              @click.stop="selectedImageIndex = (selectedImageIndex - 1 + images.length) % images.length" 
              class="w-12 h-12 flex items-center justify-center rounded-full bg-black/60 text-white hover:bg-white/20 transition-colors duration-300"
              aria-label="Imagen anterior"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <button 
              @click.stop="selectedImageIndex = (selectedImageIndex + 1) % images.length" 
              class="w-12 h-12 flex items-center justify-center rounded-full bg-black/60 text-white hover:bg-white/20 transition-colors duration-300"
              aria-label="Imagen siguiente"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>

<style scoped>
.animate-slideDown {
  animation: slideDown 0.5s ease-in-out;
}

@keyframes slideDown {
  0% {
    max-height: 0;
    opacity: 0;
  }
  100% {
    max-height: 1000px;
    opacity: 1;
  }
}

.pulse-on-click {
  animation: pulse 0.3s ease-in-out;
}

@keyframes pulse {
  0% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.05);
  }
  100% {
    transform: scale(1);
  }
}

.hover-glow:hover {
  box-shadow: 0 0 20px rgba(255, 255, 255, 0.2);
}
</style> 