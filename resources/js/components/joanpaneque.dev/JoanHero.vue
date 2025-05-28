<script setup>

</script>

<template>
  <div class="relative bg-[#002626] text-white scroll-mt-20" id="home">
    <div class="mx-auto max-w-6xl px-4 min-h-[80vh] flex flex-col justify-center">
      <div class="flex flex-col md:flex-row items-center my-8 md:my-0">
        <div class="text-left py-16 flex-1">
          <div class="flex py-4">
            <div class="bg-black/20 rounded-full px-4 py-1 flex items-center gap-2">
              <span>{{ $trans("joan_hero.label") }}</span>
            </div>
          </div>
          <h1 class="text-4xl md:text-6xl mb-4 font-semibold text-balance text-white pb-2">
            {{ $trans("joan_hero.title") }}
          </h1>
          <p class="text-xl mb-2 text-balance">
            {{ $trans("joan_hero.description_1") }}
          </p>
          <p class="text-xl mb-8 text-balance">
            {{ $trans("joan_hero.description_2") }}
          </p>
          <div class="flex flex-row items-center mb-4 gap-4">
            <a class="bg-white text-black px-8 py-3 rounded-full font-medium hover:bg-gray-200 transition-colors border-2 border-transparent" href="#projects">{{ $trans("joan_hero.button_view_projects") }}</a>
            <a class="border-2 border-white text-white px-8 py-3 rounded-full font-medium hover:bg-white hover:text-black transition-colors" href="#contact">{{ $trans("joan_hero.button_contact_me") }}</a>
          </div>
        </div>
        <div class="flex-1 relative flex flex-col items-center justify-center">
          <img src="/images/joan.webp" alt="Joan" class="rounded-full h-[500px] w-[500px] object-cover border border-emerald-900 shadow-lg bg-gradient-to-b from-emerald-900 to-transparent" />
        </div>
      </div>
    </div>
  </div>
</template>