<script setup>

</script>

<template>
  <div id="about" class="scroll-mt-20">
    <h1 class="font-semibold text-4xl md:text-5xl text-balance text-black mt-16 text-center">
      {{ $trans("joan_about.title") }}
    </h1>
    <div class="flex flex-col md:flex-row justify-center mt-8 gap-8 bg-white p-16 rounded-3xl max-w-4xl mx-auto">
      <img alt="Joan" loading="lazy" width="300" height="300" class="rounded-xl object-cover shadow-lg" src="/images/joan2.webp" />
      <div class="flex flex-col gap-4">
        <p class="text-lg text-gray-700">
          {{ $trans("joan_about.description_1") }}
        </p>
        <p class="text-lg text-gray-700">
          {{ $trans("joan_about.description_2") }}
        </p>
        <p class="text-lg text-gray-700">
          {{ $trans("joan_about.description_3") }}
        </p>
        <div class="flex flex-row gap-4">
          <a href="mailto:joanpd0@gmail.com" class="text-[#002626] font-bold underline">joanpd0@gmail.com</a>
          <a href="https://github.com/joanpaneque" target="_blank" class="text-[#002626] font-bold underline">GitHub</a>
          <a href="https://www.linkedin.com/in/joan-paneque-domingo-906340237/" target="_blank" class="text-[#002626] font-bold underline">LinkedIn</a>
        </div>
      </div>
    </div>
  </div>
</template> 