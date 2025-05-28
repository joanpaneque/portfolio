<script setup>
import { ref } from 'vue';

// Props opcionales para personalizar el componente
const props = defineProps({
    title: {
        type: String,
        default: '¿Te gustó este artículo?'
    },
    description: {
        type: String,
        default: 'Suscríbete gratis y recibe notificaciones cada vez que publique un nuevo artículo'
    },
    buttonText: {
        type: String,
        default: 'Suscribirse gratis'
    },
    disclaimer: {
        type: String,
        default: 'Sin spam. Puedes cancelar en cualquier momento.'
    }
});

// Estado del formulario
const email = ref('');
const isLoading = ref(false);
const isSubscribed = ref(false);

// Función para manejar la suscripción
const handleSubscription = async () => {
    if (!email.value || !email.value.includes('@')) {
        alert('Por favor, introduce un email válido');
        return;
    }

    isLoading.value = true;

    try {
        // Aquí iría la lógica para enviar el email al backend
        // Por ahora simulamos una llamada a la API
        await new Promise(resolve => setTimeout(resolve, 1000));

        isSubscribed.value = true;
        email.value = '';

        // Opcional: mostrar mensaje de éxito
        setTimeout(() => {
            isSubscribed.value = false;
        }, 3000);

    } catch (error) {
        console.error('Error al suscribirse:', error);
        alert('Hubo un error al procesar tu suscripción. Inténtalo de nuevo.');
    } finally {
        isLoading.value = false;
    }
};
</script>

<template>
    <div class="p-8 bg-[#002626] rounded-2xl text-center">
        <div class="max-w-md mx-auto">
            <!-- Estado normal -->
            <div v-if="!isSubscribed">
                <h3 class="text-2xl font-bold text-white mb-3">{{ title }}</h3>
                <p class="text-white/80 mb-6">{{ description }}</p>

                <form @submit.prevent="handleSubscription" class="flex flex-col sm:flex-row gap-3">
                    <input
                        v-model="email"
                        type="email"
                        placeholder="tu@email.com"
                        required
                        :disabled="isLoading"
                        class="flex-1 px-4 py-3 rounded-full text-gray-900 focus:outline-none focus:ring-2 focus:ring-white/20 placeholder-gray-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                    <button
                        type="submit"
                        :disabled="isLoading"
                        class="bg-white text-[#002626] px-6 py-3 rounded-full font-medium hover:bg-gray-100 transition-colors whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                        <span v-if="isLoading">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Suscribiendo...
                        </span>
                        <span v-else>{{ buttonText }}</span>
                    </button>
                </form>

                <p class="text-white/60 text-sm mt-3">{{ disclaimer }}</p>
            </div>

            <!-- Estado de éxito -->
            <div v-else class="py-4">
                <div class="text-white text-4xl mb-3">✅</div>
                <h3 class="text-2xl font-bold text-white mb-2">¡Suscripción exitosa!</h3>
                <p class="text-white/80">
                    Te hemos enviado un email de confirmación. Revisa tu bandeja de entrada.
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Animación del spinner */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
