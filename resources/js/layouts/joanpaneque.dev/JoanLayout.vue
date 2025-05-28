<script setup>
import JoanNavbar from '../../components/joanpaneque.dev/JoanNavbar.vue';
import JoanFooter from '../../components/joanpaneque.dev/JoanFooter.vue';


const props = defineProps({
  title: {
    type: String,
    required: true
  },
  routes: {
    type: Object,
    required: true
  },
  buttons: {
    type: Object,
    required: true
  },
});
</script>

<template>
      <div class="flex flex-col min-h-screen">
        <JoanNavbar :title="title" :routes="routes" :buttons="buttons" />
        <div class="mt-[85px]"></div>
        <slot />
        <JoanFooter />
      </div>
</template>