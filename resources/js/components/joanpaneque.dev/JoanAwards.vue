<template>
    <div class="py-16 bg-white" id="awards">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-8 lg:px-8">
            <div class="text-center mb-12 pt-8">
                <h2 class="text-3xl md:text-5xl font-bold text-black">
                    Premios y reconocimientos
                </h2>
                <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
                    Reconocimientos y logros obtenidos a lo largo de mi carrera profesional.
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

const awards = ref([
    {
        title: 'La nota más alta de Figueres en grado superior',
        organization: 'Ajuntament de Figueres',
        date: 'Noviembre 2023',
        description: 'Reconocimiento por tener la nota más alta en grado superior de la ciudad de Figueres, Catalunya.',
        image: '/images/awards/premis-talent.webp',
        newsSources: [
            {
                headline: 'Les tres millors notes de la Formació Professional de Grau Superior',
                image: '/images/news-logos/diari-emporda.png',
                link: 'https://www.emporda.info/comarca/2024/10/19/els-premis-figueres-talent-reconeixen-esforc-i-la-trajectoria-formativa-39-estudiants-109626111.html',
                publication: 'Diari Empordà'
            },
            {
                headline: 'Figueres lliura els premis a l\'Excel·lència Acadèmica a 39 alumnes',
                image: '/images/news-logos/diari-de-girona.svg',
                link: 'https://www.diariodegironella.cat/2024/10/19/figueres-lliura-els-premis-a-l-excelencia-academica-a-39-alumnes/',
                publication: 'Diari de Girona'
            },
            {
                headline: 'Figueres lliura els premis a l\'Excel·lència Acadèmica a 39 alumnes',
                image: '/images/news-logos/diari-de-figueres.png',
                link: 'https://www.figueres.cat/actualitat/noticies/figueres-lliura-els-premis-a-l-excel-lencia-academica-23620',
                publication: 'Diari de Figueres'
            },
            {
                headline: 'Los premios Figueres Talent reconocen el esfuerzo y la buena trayectoria formativa de 39 estudiantes de la ciudad',
                image: '/images/news-logos/la-ciutat.png',
                link: 'https://laciutat.cat/es/laciutatdegirona-es/figueres-es/premios-figueres-talent-esforc-trayectoria-formativa-39-estudiantes',
                publication: 'La Ciutat'
            }
        ]
    },
    {
        title: 'Matrícula de Honor',
        organization: 'Institut Cendrassos',
        date: 'Junio 2023',
        description: 'Reconocimiento por la excelencia académica en el Instituto Cendrassos.',
        image: '/images/awards/graduacio.png',
    },
    {
        title: 'Premi Bonaplata Joves 2024',
        organization: 'Associació del Museu de la Ciència i la Tècnica i d\'Arqueologia Industrial de Catalunya',
        date: 'Junio 2023',
        description: 'El projecte guanyador és una aplicació desenvolupada en col·laboració amb l’Hospital Trueta de Girona. Aquesta eina facilita el procés de selecció de l’antibiòtic més adequat per a cada intervenció quirúrgica, optimitzant-ne l’ús i ajudant a millorar la gestió en l’àmbit hospitalari. Actualment, l’aplicació ja s’està utilitzant en un entorn real, demostrant la seva utilitat i impacte positiu.',
        image: '/images/awards/bonaplata.webp',
        newsSources: [
            {
                headline: 'Alumnes de 2n DAW guanyen els premis Bonaplata Joves 2024',
                image: '/images/news-logos/cendrassos.webp',
                link: 'https://cendrassos.net/alumnes-de-2n-daw-guanyen-els-premis-bonaplata-joves-2024/',
                publication: 'Institut Cendrassos'
            }
        ]
    },
    {
        title: 'Premio al mejor proyecto tecnológico en ámbito STEAM del año',
        organization: 'Universitat de Girona',
        date: 'Diciembre 2022',
        description: 'Premio al mejor proyecto tecnológico en ámbito STEAM del año',
        image: '/images/awards/premis-consell-social.jpg',
        newsSources: [
            {
                headline: 'El Consell Social de la UdG premia quatre projectes d’estudiants de cicles',
                image: '/images/news-logos/el-punt-avui.png',
                link: 'https://www.elpuntavui.cat/societat/article/16-educacio/2462135-el-consell-social-de-la-udg-premia-quatre-projectes-d-estudiants-de-cicles.html',
                publication: 'El Punt Avui'
            },
            {
                headline: 'El Consell Social de la UdG premia quatre projectes d’estudiants de cicles',
                image: '/images/news-logos/patronat-udg.svg',
                link: 'https://patronateps.udg.edu/el-consell-social-de-la-udg-premia-quatre-projectes-destudiants-de-cicles/',
                publication: 'Universitat de Girona'
            }
        ]
    }
])
</script>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>