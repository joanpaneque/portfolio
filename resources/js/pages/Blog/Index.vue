<script setup>
import JoanLayout from '@/layouts/joanpaneque.dev/JoanLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const title = $trans('joan_navbar.blog');

const routes = [
    {
        name: $trans('joan_navbar.portfolio'),
        href: route('portfolio.index'),
    }
];
const buttons = [
    {
        name: $trans('joan_navbar.contact_me'),
        href: route('portfolio.index') + "#contact",
    }
];

// Artículos del blog inventados
const articles = ref([
    {
        id: 1,
        title: "El Futuro del Desarrollo Web: Tendencias 2024",
        excerpt: "Exploramos las tecnologías emergentes que están transformando el desarrollo web, desde WebAssembly hasta los frameworks de nueva generación.",
        content: "El desarrollo web está evolucionando a un ritmo acelerado. En este artículo analizamos las principales tendencias que marcarán el 2024...",
        author: "Joan Paneque",
        date: "2024-01-15",
        readTime: "5 min",
        category: "Tecnología",
        image: "/images/blog/web-development.jpg",
        tags: ["JavaScript", "WebAssembly", "React", "Vue.js"]
    },
    {
        id: 2,
        title: "Optimización de Performance en Aplicaciones Vue.js",
        excerpt: "Técnicas avanzadas para mejorar el rendimiento de tus aplicaciones Vue.js, desde lazy loading hasta optimización de bundle.",
        content: "La performance es crucial en el desarrollo moderno. Aquí te mostramos cómo optimizar tus aplicaciones Vue.js...",
        author: "Joan Paneque",
        date: "2024-01-10",
        readTime: "8 min",
        category: "Frontend",
        image: "/images/blog/vue-performance.jpg",
        tags: ["Vue.js", "Performance", "Optimization", "JavaScript"]
    },
    {
        id: 3,
        title: "Arquitectura de Microservicios con Laravel",
        excerpt: "Cómo diseñar y implementar una arquitectura de microservicios escalable utilizando Laravel como base.",
        content: "Los microservicios ofrecen flexibilidad y escalabilidad. En este artículo exploramos cómo implementarlos con Laravel...",
        author: "Joan Paneque",
        date: "2024-01-05",
        readTime: "12 min",
        category: "Backend",
        image: "/images/blog/microservices.jpg",
        tags: ["Laravel", "Microservices", "PHP", "Architecture"]
    },
    {
        id: 4,
        title: "Inteligencia Artificial en el Desarrollo: ChatGPT y Copilot",
        excerpt: "Análisis del impacto de las herramientas de IA en el flujo de trabajo de los desarrolladores modernos.",
        content: "La IA está revolucionando cómo escribimos código. Exploramos las mejores prácticas para integrar estas herramientas...",
        author: "Joan Paneque",
        date: "2023-12-28",
        readTime: "6 min",
        category: "IA",
        image: "/images/blog/ai-development.jpg",
        tags: ["AI", "ChatGPT", "GitHub Copilot", "Productivity"]
    },
    {
        id: 5,
        title: "Seguridad en Aplicaciones Web: Guía Completa 2024",
        excerpt: "Todo lo que necesitas saber sobre seguridad web: desde OWASP Top 10 hasta implementación de autenticación moderna.",
        content: "La seguridad no es opcional. En esta guía completa cubrimos todos los aspectos esenciales...",
        author: "Joan Paneque",
        date: "2023-12-20",
        readTime: "15 min",
        category: "Seguridad",
        image: "/images/blog/web-security.jpg",
        tags: ["Security", "OWASP", "Authentication", "Web Development"]
    },
    {
        id: 6,
        title: "DevOps para Desarrolladores Frontend",
        excerpt: "Introducción práctica a DevOps desde la perspectiva del desarrollo frontend: CI/CD, Docker y deployment automatizado.",
        content: "DevOps ya no es solo para backend. Los desarrolladores frontend también necesitan estas habilidades...",
        author: "Joan Paneque",
        date: "2023-12-15",
        readTime: "10 min",
        category: "DevOps",
        image: "/images/blog/devops-frontend.jpg",
        tags: ["DevOps", "CI/CD", "Docker", "Frontend"]
    }
]);

const featuredArticle = ref(articles.value[0]);
const regularArticles = ref(articles.value.slice(1));

const categories = ref([
    "Todos",
    "Tecnología",
    "Frontend",
    "Backend",
    "IA",
    "Seguridad",
    "DevOps"
]);

const selectedCategory = ref("Todos");

const filteredArticles = ref(regularArticles.value);

const filterByCategory = (category) => {
    selectedCategory.value = category;
    if (category === "Todos") {
        filteredArticles.value = regularArticles.value;
    } else {
        filteredArticles.value = regularArticles.value.filter(article => article.category === category);
    }
};

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('es-ES', options);
};
</script>

<template>
    <Head :title="title" />
    <JoanLayout :title="title" :routes="routes" :buttons="buttons">
        <!-- Hero Section -->
        <div class="bg-[#002626] text-white py-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-4">
                        Blog de Desarrollo
                    </h1>
                    <p class="text-xl text-white/80 max-w-2xl mx-auto">
                        Artículos sobre desarrollo web, tecnología y las últimas tendencias en programación
                    </p>
                </div>
            </div>
        </div>

        <!-- Featured Article -->
        <div class="bg-white py-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Artículo Destacado</h2>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                    <div class="md:flex">
                        <div class="md:w-1/2">
                            <div class="h-64 md:h-full bg-gradient-to-br from-[#002626] to-emerald-600 flex items-center justify-center">
                                <div class="text-white text-6xl">📝</div>
                            </div>
                        </div>
                        <div class="md:w-1/2 p-8">
                            <div class="flex items-center gap-4 mb-4">
                                <span class="bg-[#002626] text-white px-3 py-1 rounded-full text-sm font-medium">
                                    {{ featuredArticle.category }}
                                </span>
                                <span class="text-gray-500 text-sm">{{ formatDate(featuredArticle.date) }}</span>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ featuredArticle.title }}</h3>
                            <p class="text-gray-600 mb-6">{{ featuredArticle.excerpt }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-500">Por {{ featuredArticle.author }}</span>
                                    <span class="text-gray-300">•</span>
                                    <span class="text-sm text-gray-500">{{ featuredArticle.readTime }} lectura</span>
                                </div>
                                <button class="bg-[#002626] text-white px-6 py-2 rounded-full font-medium hover:bg-[#003333] transition-colors">
                                    Leer más
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Filter -->
        <div class="bg-gray-50 py-8">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap gap-3 justify-center">
                    <button
                        v-for="category in categories"
                        :key="category"
                        @click="filterByCategory(category)"
                        :class="[
                            'px-4 py-2 rounded-full font-medium transition-colors',
                            selectedCategory === category
                                ? 'bg-[#002626] text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-100'
                        ]"
                    >
                        {{ category }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Articles Grid -->
        <div class="bg-white py-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Últimos Artículos</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <article
                        v-for="article in filteredArticles"
                        :key="article.id"
                        class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 group cursor-pointer"
                    >
                        <!-- Article Image -->
                        <div class="h-48 bg-gradient-to-br from-[#002626] to-emerald-600 flex items-center justify-center">
                            <div class="text-white text-4xl">
                                {{ article.category === 'Tecnología' ? '🚀' :
                                   article.category === 'Frontend' ? '🎨' :
                                   article.category === 'Backend' ? '⚙️' :
                                   article.category === 'IA' ? '🤖' :
                                   article.category === 'Seguridad' ? '🔒' :
                                   article.category === 'DevOps' ? '🔧' : '📝' }}
                            </div>
                        </div>

                        <!-- Article Content -->
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium">
                                    {{ article.category }}
                                </span>
                                <span class="text-gray-400 text-xs">{{ formatDate(article.date) }}</span>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-[#002626] transition-colors">
                                {{ article.title }}
                            </h3>

                            <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                {{ article.excerpt }}
                            </p>

                            <!-- Tags -->
                            <div class="flex flex-wrap gap-1 mb-4">
                                <span
                                    v-for="tag in article.tags.slice(0, 3)"
                                    :key="tag"
                                    class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs"
                                >
                                    #{{ tag }}
                                </span>
                            </div>

                            <!-- Article Footer -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs text-gray-500">{{ article.readTime }} lectura</span>
                                </div>
                                <button class="text-[#002626] font-medium text-sm hover:underline">
                                    Leer más →
                                </button>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- No articles message -->
                <div v-if="filteredArticles.length === 0" class="text-center py-12">
                    <div class="text-gray-400 text-6xl mb-4">📝</div>
                    <h3 class="text-xl font-medium text-gray-900 mb-2">No hay artículos en esta categoría</h3>
                    <p class="text-gray-600">Prueba seleccionando otra categoría o vuelve a "Todos"</p>
                </div>
            </div>
        </div>

        <!-- Newsletter Section -->
        <div class="bg-[#002626] text-white py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-3xl font-bold mb-4">Mantente al día</h2>
                <p class="text-white/80 mb-8 max-w-2xl mx-auto">
                    Suscríbete para recibir los últimos artículos sobre desarrollo web y tecnología directamente en tu email
                </p>
                <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                    <input
                        type="email"
                        placeholder="tu@email.com"
                        class="flex-1 px-4 py-3 rounded-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-white/20"
                    >
                    <button class="bg-white text-[#002626] px-6 py-3 rounded-full font-medium hover:bg-gray-100 transition-colors">
                        Suscribirse
                    </button>
                </div>
            </div>
        </div>
    </JoanLayout>
</template>

<style scoped>
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
