<template>
  <div class="bg-white flex flex-col items-center px-4 py-8 md:p-16 scroll-mt-20" id="projects">
    <div class="max-w-6xl flex flex-col gap-4 pt-8">
      <h2 class="text-3xl md:text-5xl font-bold text-black">
        {{ $trans("joan_projects.title") }}
      </h2>
      <p class="text-base md:text-xl text-gray-600 text-balance max-w-3xl mb-12">
        {{ $trans("joan_projects.description") }}
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
          {{ $trans("joan_projects.button_expand_projects") }}
        </button>
      </div>

      <!-- Botón Contraer -->
      <div v-if="canCollapse" class="flex justify-center mt-4">
        <button 
          @click="collapseProjects"
          class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 rounded-md px-6 transition-colors duration-300"
        >
          {{ $trans("joan_projects.button_collapse_projects") }}
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

const projects = $trans("joan_projects.projects");

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