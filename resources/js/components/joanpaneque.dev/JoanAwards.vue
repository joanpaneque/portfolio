<template>
    <div class="py-16 bg-white" id="awards">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 lg:px-8">
            <div class="text-center mb-12 pt-8">
                <h2 class="text-3xl md:text-5xl font-bold text-black">
                    {{ $trans("joan_awards.title") }}
                </h2>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
                    {{ $trans("joan_awards.description") }}
                </p>
            </div>

            <div class="space-y-8">
                <div v-for="(award, index) in awards" :key="index" class="group">
                    <!-- Award Info -->
                    <div class="text-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ award.title }}</h3>
                        <p class="text-gray-600 mb-1">{{ award.organization }}</p>
                        <p class="text-gray-700 max-w-3xl mx-auto mb-6">{{ award.description }}</p>

                        <!-- Award Image -->
                        <div v-if="award.image" class="mb-6">
                            <img :src="award.image" :alt="award.title"
                                class="w-full max-w-2xl h-auto object-cover rounded-lg mx-auto shadow-lg"
                                loading="lazy" />
                        </div>
                    </div>

                    <!-- News Sources -->
                    <div v-if="award.newsSources && award.newsSources.length > 0"
                        class="flex flex-wrap justify-center items-center gap-6 md:gap-8">
                        <a v-for="(source, sourceIndex) in award.newsSources" :key="sourceIndex" :href="source.link"
                            target="_blank" rel="noopener noreferrer"
                            class="group/source transition-all duration-300 hover:scale-105">
                            <div class="flex flex-col items-center gap-3 p-4 max-w-xs">
                                <div
                                    class="h-16 md:h-20 flex items-center justify-center overflow-hidden rounded-lg px-4">
                                    <img v-if="source.image" :src="source.image" :alt="source.headline"
                                        class="h-full w-auto object-contain filter grayscale group-hover/source:grayscale-0 transition-all duration-300"
                                        loading="lazy" />
                                    <div v-else
                                        class="h-full w-16 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                        📰
                                    </div>
                                </div>
                                <div class="text-center">
                                    <h4
                                        class="text-sm font-medium text-gray-900 group-hover/source:text-blue-600 transition-colors duration-300 line-clamp-2 mb-1">
                                        {{ source.headline }}
                                    </h4>
                                    <p v-if="source.publication" class="text-xs text-gray-500">
                                        {{ source.publication }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Separator line (except for last item) -->
                    <div v-if="index < awards.length - 1" class="border-b border-gray-200 mt-12"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const awards = $trans("joan_awards.awards");
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>