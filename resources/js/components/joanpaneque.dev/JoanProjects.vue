<template>
  <div class="bg-white flex flex-col items-center px-4 py-8 md:p-16 scroll-mt-20" id="projects">
    <div class="max-w-6xl flex flex-col gap-4 pt-8">
      <h2 class="text-3xl md:text-5xl font-bold text-black">Proyectos destacados</h2>
      <p class="text-base md:text-xl text-gray-600 text-balance max-w-3xl mb-12">
        Algunos de los proyectos en los que he trabajado recientemente.
      </p>
      <div class="flex flex-col gap-4">
        <ProjectCard
          v-for="(project, index) in visibleProjects"
          :key="index"
          :title="project.title"
          :description="project.description"
          :image="project.image"
          :link="project.link"
          :reverse="index % 2 === 0"
        />
      </div>
      
      <!-- Botón Mostrar más -->
      <div v-if="hasMoreProjects" class="flex justify-center mt-8">
        <button 
          @click="showMoreProjects"
          class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 rounded-md px-6 transition-colors duration-300"
        >
          Mostrar más proyectos
        </button>
      </div>

      <!-- Botón Contraer -->
      <div v-if="canCollapse" class="flex justify-center mt-4">
        <button 
          @click="collapseProjects"
          class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 rounded-md px-6 transition-colors duration-300"
        >
          Mostrar menos proyectos
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import ProjectCard from './ProjectCard.vue'

const projectsPerPage = 3
const currentlyShowing = ref(projectsPerPage)

const projects = [
  {
    title: 'Asistente de profilaxis quirúrgica',
    description: 'Herramienta clínica diseñada para ayudar a cirujanos y anestesiólogos a seleccionar el antibiótico más adecuado antes de una intervención quirúrgica.',
    image: '/images/projects/trueta.png',
    link: 'https://github.com/joanpaneque/Trueta2.0'
  },
  {
    title: 'Otio App: Ticketing para degustaciones',
    description: 'Aplicación móvil para la gestión de ticketing para eventos de degustaciones.',
    image: '/images/projects/otio.png',
    link: 'https://otioapp.com'
  },
  {
    title: 'Sprine.js: Motor gráfico de videojuegos 2D',
    description: 'Motor gráfico de videojuegos 2D desarrollado en JavaScript.',
    image: '/images/projects/sprine.png',
    link: 'https://github.com/joanpaneque/Sprine'
  },
  {
    title: '+100 problemas resueltos en C++',
    description: 'Repositorio donde he resuelto más de 100 problemas de programación en C++.',
    image: '/images/projects/cpp.png',
    link: 'https://github.com/joanpaneque/AceptaElReto'
  },
  {
    title: 'BarbaQ: Red social para organizar barbacoas',
    description: 'Landing page moderna y responsiva para una startup tecnológica, optimizada para SEO y conversión.',
    image: '/images/projects/barbaq.png',
    link: 'https://github.com/joanpaneque/BarbaQ'
  },
  {
    title: 'Clon de la calculadora de iOS',
    description: 'Clon de la calculadora de iOS desarrollado con tecnologías web.',
    image: '/images/projects/ios.png',
    link: 'https://github.com/joanpaneque/iOS-Calculator'
  }
]

const visibleProjects = computed(() => {
  return projects.slice(0, currentlyShowing.value)
})

const hasMoreProjects = computed(() => {
  return currentlyShowing.value < projects.length
})

const showMoreProjects = () => {
  currentlyShowing.value = Math.min(currentlyShowing.value + projectsPerPage, projects.length)
  canCollapse.value = true
}

const canCollapse = computed(() => {
  return currentlyShowing.value > projectsPerPage
})

const collapseProjects = () => {
  currentlyShowing.value = projectsPerPage
}
</script> 