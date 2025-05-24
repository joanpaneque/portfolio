<script setup>
const currentYear = new Date().getFullYear();

</script>

<template>
  <footer class="bg-[#002626] border-t border-dotted border-white/30 mt-8">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-between h-20">
        <p class="text-white/80">© {{ currentYear }} Joan Paneque Domingo. Todos los derechos reservados.</p>
      </div>
    </div>
  </footer>
</template> 