<script setup>
import JoanLayout from '@/layouts/joanpaneque.dev/JoanLayout.vue';
import NewsletterSubscription from '@/components/joanpaneque.dev/NewsletterSubscription.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

// Props que vendrían del backend
const props = defineProps({
    article: {
        type: Object,
        default: () => ({
            id: 1,
            title: "El Futuro del Desarrollo Web: Tendencias 2024",
            excerpt: "Exploramos las tecnologías emergentes que están transformando el desarrollo web, desde WebAssembly hasta los frameworks de nueva generación.",
            content: `
                <p>El desarrollo web está evolucionando a un ritmo acelerado, y 2024 promete ser un año revolucionario para nuestra industria. En este artículo, exploraremos las tendencias más importantes que están moldeando el futuro del desarrollo web.</p>

                <h2>WebAssembly: El Futuro de la Performance Web</h2>
                <p>WebAssembly (WASM) está cambiando las reglas del juego. Esta tecnología permite ejecutar código de alto rendimiento directamente en el navegador, abriendo nuevas posibilidades para aplicaciones web complejas.</p>

                <blockquote>
                    "WebAssembly no reemplaza a JavaScript, sino que lo complementa para casos de uso específicos donde la performance es crítica."
                </blockquote>

                <h3>Casos de Uso Principales:</h3>
                <ul>
                    <li>Aplicaciones de edición de imagen y video</li>
                    <li>Juegos web de alta performance</li>
                    <li>Simulaciones científicas</li>
                    <li>Herramientas de desarrollo en el navegador</li>
                </ul>

                <h2>Frameworks de Nueva Generación</h2>
                <p>Los frameworks están evolucionando hacia arquitecturas más eficientes y developer-friendly. Vemos el surgimiento de herramientas como:</p>

                <h3>Astro y la Arquitectura de Islas</h3>
                <p>Astro introduce el concepto de "arquitectura de islas", donde solo las partes interactivas de la página se hidratan con JavaScript, mejorando significativamente la performance.</p>

                <pre><code>---
// Astro component example
const posts = await fetch('/api/posts').then(r => r.json());
---

&lt;Layout title="Blog"&gt;
  {posts.map(post =&gt; (
    &lt;PostCard post={post} client:visible /&gt;
  ))}
&lt;/Layout&gt;</code></pre>

                <h3>SvelteKit y la Simplicidad</h3>
                <p>SvelteKit continúa ganando tracción por su enfoque minimalista y su excelente performance out-of-the-box.</p>

                <h2>Edge Computing y Serverless</h2>
                <p>El edge computing está acercando la computación a los usuarios finales, reduciendo la latencia y mejorando la experiencia del usuario.</p>

                <h3>Ventajas del Edge Computing:</h3>
                <ol>
                    <li><strong>Menor latencia:</strong> Procesamiento más cerca del usuario</li>
                    <li><strong>Mejor escalabilidad:</strong> Distribución automática de carga</li>
                    <li><strong>Costos optimizados:</strong> Pago por uso real</li>
                </ol>

                <h2>Inteligencia Artificial en el Desarrollo</h2>
                <p>Las herramientas de IA están transformando cómo escribimos código. GitHub Copilot, ChatGPT y otras herramientas están acelerando el desarrollo y mejorando la calidad del código.</p>

                <h3>Mejores Prácticas con IA:</h3>
                <ul>
                    <li>Usar IA como asistente, no como reemplazo</li>
                    <li>Revisar y entender todo el código generado</li>
                    <li>Mantener las mejores prácticas de seguridad</li>
                    <li>Documentar las decisiones de diseño</li>
                </ul>

                <h2>Conclusión</h2>
                <p>El futuro del desarrollo web es emocionante y lleno de oportunidades. Las tecnologías emergentes nos permiten crear experiencias más rápidas, más accesibles y más poderosas que nunca.</p>

                <p>Como desarrolladores, es crucial mantenerse actualizado con estas tendencias mientras se mantiene un enfoque en los fundamentos sólidos del desarrollo web.</p>
            `,
            author: "Joan Paneque",
            date: "2024-01-15",
            readTime: "5 min",
            category: "Tecnología",
            image: "/images/blog/web-development.jpg",
            tags: ["JavaScript", "WebAssembly", "React", "Vue.js", "Astro", "SvelteKit"]
        })
    }
});

const title = `${props.article.title} - Blog`;

const routes = [
    {
        name: 'Blog',
        href: '/blog',
    },
    {
        name: 'Portfolio',
        href: '/',
    }
];

const buttons = [
    {
        name: 'Contacto',
        href: '/#contact',
    }
];

// Artículos relacionados (simulados)
const relatedArticles = ref([
    {
        id: 2,
        title: "Optimización de Performance en Aplicaciones Vue.js",
        excerpt: "Técnicas avanzadas para mejorar el rendimiento de tus aplicaciones Vue.js.",
        category: "Frontend",
        readTime: "8 min",
        date: "2024-01-10"
    },
    {
        id: 3,
        title: "Arquitectura de Microservicios con Laravel",
        excerpt: "Cómo diseñar y implementar una arquitectura de microservicios escalable.",
        category: "Backend",
        readTime: "12 min",
        date: "2024-01-05"
    },
    {
        id: 4,
        title: "Inteligencia Artificial en el Desarrollo: ChatGPT y Copilot",
        excerpt: "Análisis del impacto de las herramientas de IA en el flujo de trabajo.",
        category: "IA",
        readTime: "6 min",
        date: "2023-12-28"
    }
]);

const formatDate = (dateString) => {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('es-ES', options);
};

const getCategoryIcon = (category) => {
    const icons = {
        'Tecnología': '🚀',
        'Frontend': '🎨',
        'Backend': '⚙️',
        'IA': '🤖',
        'Seguridad': '🔒',
        'DevOps': '🔧'
    };
    return icons[category] || '📝';
};

// Funcionalidad de compartir
const shareArticle = (platform) => {
    if (typeof window === 'undefined') return;

    const url = window.location.href;
    const text = `${props.article.title} - ${props.article.excerpt}`;

    const shareUrls = {
        twitter: `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`,
        linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`,
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`
    };

    if (shareUrls[platform]) {
        window.open(shareUrls[platform], '_blank', 'width=600,height=400');
    }
};
</script>

<template>
    <Head :title="title" />
    <JoanLayout :title="'Blog'" :routes="routes" :buttons="buttons">
        <!-- Article Header -->
        <div class="bg-[#002626] text-white py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="mb-8">
                    <ol class="flex items-center space-x-2 text-sm text-white/60">
                        <li><a href="/blog" class="hover:text-white transition-colors">Blog</a></li>
                        <li class="text-white/40">/</li>
                        <li class="text-white/80">{{ article.category }}</li>
                    </ol>
                </nav>

                <!-- Article Meta -->
                <div class="flex items-center gap-4 mb-6">
                    <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-medium">
                        {{ article.category }}
                    </span>
                    <span class="text-white/80 text-sm">{{ formatDate(article.date) }}</span>
                    <span class="text-white/60">•</span>
                    <span class="text-white/80 text-sm">{{ article.readTime }} de lectura</span>
                </div>

                <!-- Title -->
                <h1 class="text-4xl md:text-5xl font-bold mb-6 leading-tight">
                    {{ article.title }}
                </h1>

                <!-- Excerpt -->
                <p class="text-xl text-white/90 leading-relaxed max-w-3xl">
                    {{ article.excerpt }}
                </p>

                <!-- Author & Share -->
                <div class="flex items-center justify-between mt-8 pt-8 border-t border-white/20">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-lg">{{ article.author.charAt(0) }}</span>
                        </div>
                        <div>
                            <p class="text-white font-medium">{{ article.author }}</p>
                            <p class="text-white/60 text-sm">Desarrollador Full Stack</p>
                        </div>
                    </div>

                    <!-- Share Buttons -->
                    <div class="flex items-center gap-3">
                        <span class="text-white/60 text-sm mr-2">Compartir:</span>
                        <button
                            @click="shareArticle('twitter')"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors"
                            title="Compartir en Twitter"
                        >
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </button>
                        <button
                            @click="shareArticle('linkedin')"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors"
                            title="Compartir en LinkedIn"
                        >
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </button>
                        <button
                            @click="shareArticle('facebook')"
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-full flex items-center justify-center transition-colors"
                            title="Compartir en Facebook"
                        >
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Article Content -->
        <div class="bg-white py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Featured Image -->
                <div class="mb-12">
                    <div class="h-96 bg-gradient-to-br from-[#002626] to-emerald-600 rounded-2xl flex items-center justify-center">
                        <div class="text-white text-8xl">{{ getCategoryIcon(article.category) }}</div>
                    </div>
                </div>

                <!-- Article Body -->
                <div class="article-content prose prose-lg max-w-none">
                    <div v-html="article.content" class="article-text"></div>
                </div>

                <!-- Tags -->
                <div class="mt-12 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Tags</h3>
                    <div class="flex flex-wrap gap-2">
                        <span
                            v-for="tag in article.tags"
                            :key="tag"
                            class="bg-gray-100 hover:bg-[#002626] hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm font-medium transition-colors cursor-pointer"
                        >
                            #{{ tag }}
                        </span>
                    </div>
                </div>

                <!-- Author Bio -->
                <div class="mt-12 p-8 bg-gray-50 rounded-2xl">
                    <div class="flex items-start gap-6">
                        <div class="w-20 h-20 bg-[#002626] rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-2xl">{{ article.author.charAt(0) }}</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ article.author }}</h3>
                            <p class="text-gray-600 mb-4">
                                Desarrollador Full Stack con más de 5 años de experiencia en tecnologías web modernas.
                                Especializado en Vue.js, Laravel y arquitecturas escalables.
                            </p>
                            <div class="flex gap-4">
                                <a href="https://github.com/joanpaneque" target="_blank" class="text-[#002626] hover:underline font-medium">
                                    GitHub
                                </a>
                                <a href="https://www.linkedin.com/in/joan-paneque-domingo-906340237/" target="_blank" class="text-[#002626] hover:underline font-medium">
                                    LinkedIn
                                </a>
                                <a href="mailto:joanpd0@gmail.com" class="text-[#002626] hover:underline font-medium">
                                    Email
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Newsletter Subscription Component -->
                <div class="mt-12">
                    <NewsletterSubscription />
                </div>
            </div>
        </div>

        <!-- Related Articles -->
        <div class="bg-gray-50 py-16">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Artículos Relacionados</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <article
                        v-for="relatedArticle in relatedArticles"
                        :key="relatedArticle.id"
                        class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 group cursor-pointer"
                    >
                        <!-- Article Image -->
                        <div class="h-48 bg-gradient-to-br from-[#002626] to-emerald-600 flex items-center justify-center">
                            <div class="text-white text-4xl">{{ getCategoryIcon(relatedArticle.category) }}</div>
                        </div>

                        <!-- Article Content -->
                        <div class="p-6">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-medium">
                                    {{ relatedArticle.category }}
                                </span>
                                <span class="text-gray-400 text-xs">{{ formatDate(relatedArticle.date) }}</span>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-[#002626] transition-colors">
                                {{ relatedArticle.title }}
                            </h3>

                            <p class="text-gray-600 text-sm mb-4">
                                {{ relatedArticle.excerpt }}
                            </p>

                            <!-- Article Footer -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                <span class="text-xs text-gray-500">{{ relatedArticle.readTime }} lectura</span>
                                <button class="text-[#002626] font-medium text-sm hover:underline">
                                    Leer más →
                                </button>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Back to Blog -->
                <div class="text-center mt-12">
                    <a
                        href="/blog"
                        class="inline-flex items-center gap-2 bg-[#002626] text-white px-8 py-3 rounded-full font-medium hover:bg-[#003333] transition-colors"
                    >
                        ← Volver al Blog
                    </a>
                </div>
            </div>
        </div>
    </JoanLayout>
</template>

<style scoped>
/* Estilos para el contenido del artículo */
:deep(.article-text) {
    color: #1f2937;
    line-height: 1.75;
}

:deep(.article-text h2) {
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
    margin-top: 3rem;
    margin-bottom: 1.5rem;
}

:deep(.article-text h3) {
    font-size: 1.25rem;
    font-weight: 600;
    color: #111827;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

:deep(.article-text p) {
    margin-bottom: 1.5rem;
    font-size: 1.125rem;
    line-height: 1.75;
}

:deep(.article-text ul),
:deep(.article-text ol) {
    margin-bottom: 1.5rem;
    padding-left: 1.5rem;
}

:deep(.article-text li) {
    margin-bottom: 0.5rem;
    font-size: 1.125rem;
}

:deep(.article-text ul li) {
    list-style-type: disc;
}

:deep(.article-text ol li) {
    list-style-type: decimal;
}

:deep(.article-text blockquote) {
    border-left: 4px solid #002626;
    padding-left: 1.5rem;
    padding-top: 1rem;
    padding-bottom: 1rem;
    margin-top: 2rem;
    margin-bottom: 2rem;
    background-color: #f9fafb;
    font-style: italic;
    color: #374151;
    border-top-right-radius: 0.5rem;
    border-bottom-right-radius: 0.5rem;
}

:deep(.article-text pre) {
    background-color: #111827;
    color: #f3f4f6;
    padding: 1.5rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin-top: 2rem;
    margin-bottom: 2rem;
}

:deep(.article-text code) {
    background-color: #f3f4f6;
    color: #1f2937;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
    font-family: ui-monospace, SFMono-Regular, "SF Mono", Consolas, "Liberation Mono", Menlo, monospace;
}

:deep(.article-text pre code) {
    background-color: transparent;
    color: #f3f4f6;
    padding: 0;
}

:deep(.article-text strong) {
    font-weight: 600;
    color: #111827;
}

:deep(.article-text a) {
    color: #002626;
    font-weight: 500;
    text-decoration: none;
}

:deep(.article-text a:hover) {
    text-decoration: underline;
}
</style>
