<template>
  <div class="p-4 relative overflow-hidden flex flex-col-reverse md:flex-row items-center gap-8" :class="{ 'md:flex-row-reverse': reverse }">
    <div class="flex items-center pt-4" :class="reverse ? 'md:text-start' : 'md:text-end'">
      <div class="flex flex-col gap-2">
        <h3 class="text-lg font-bold transition-colors duration-300 text-black">{{ title }}</h3>
        <p class="text-gray-600 transition-colors duration-300 max-w-sm text-balance">
          {{ description }}
        </p>
        <a 
          class="inline-flex items-center justify-center gap-2 whitespace-nowrap text-sm font-medium border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 rounded-md px-3 mt-2" 
          :class="reverse ? 'md:self-start' : 'md:self-end'"
          :href="link" 
          target="_blank"
        >
          Ver proyecto
        </a>
      </div>
    </div>
    <img 
      :alt="title" 
      loading="lazy" 
      width="350" 
      height="350" 
      class="rounded-xl shadow-lg object-cover" 
      :src="image" 
    />
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  },
  image: {
    type: String,
    required: true
  },
  link: {
    type: String,
    required: true
  },
  reverse: {
    type: Boolean,
    default: false
  }
})
</script> 