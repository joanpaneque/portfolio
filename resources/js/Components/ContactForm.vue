<template>
    <div class="contact-form-container">
        <form
            @submit.prevent="submitForm"
            class="h-full rounded-xl border border-gray-200 bg-white p-6 shadow-lg dark:border-gray-700 dark:bg-gray-800"
        >
            <h4 class="mb-6 text-xl font-bold text-gray-800 dark:text-white">
                {{ title }}
            </h4>

            <!-- Datos personales -->
            <div class="mb-6 space-y-5">
                <div>
                    <label
                        for="name"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        {{ nameLabel }} <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="name"
                        v-model="formData.name"
                        type="text"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-base text-gray-800 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <div>
                    <label
                        for="email"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        {{ emailLabel }} <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="email"
                        v-model="formData.email"
                        type="email"
                        required
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-base text-gray-800 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    />
                </div>

                <div>
                    <label
                        for="project-details"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        {{ projectDetailsLabel }}
                    </label>
                    <textarea
                        id="project-details"
                        v-model="formData.projectDetails"
                        rows="4"
                        class="w-full resize-none rounded-lg border border-gray-300 bg-white px-4 py-3 text-base text-gray-800 transition-colors focus:border-blue-500 focus:ring-2 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                    ></textarea>
                </div>
            </div>

            <!-- Resumen de la reserva -->
            <div
                v-if="selectedDateTime"
                class="mb-8 rounded-lg border border-blue-100 bg-blue-50 p-5 dark:border-blue-800/30 dark:bg-blue-900/20"
            >
                <h5
                    class="mb-3 text-base font-medium text-blue-800 dark:text-blue-300"
                >
                    Detalles de la reserva
                </h5>
                <div class="space-y-2 text-sm text-blue-700 dark:text-blue-200">
                    <p class="flex justify-between">
                        <span class="font-medium">Fecha:</span>
                        <span>{{ formatDate(selectedDateTime.date) }}</span>
                    </p>
                    <p class="flex justify-between">
                        <span class="font-medium">Hora:</span>
                        <span>{{
                            selectedDateTime.displayTime
                                ? formatDisplayTime(
                                      selectedDateTime.displayTime,
                                  )
                                : formatTime(selectedDateTime.time)
                        }}</span>
                    </p>
                    <p
                        v-if="selectedDateTime.timezone"
                        class="flex justify-between"
                    >
                        <span class="font-medium">Zona horaria:</span>
                        <span>{{
                            formatTimezone(selectedDateTime.timezone)
                        }}</span>
                    </p>
                </div>
            </div>

            <!-- Botón de envío -->
            <button
                type="submit"
                :disabled="isSubmitting || !selectedDateTime"
                :class="[
                    'w-full rounded-lg px-4 py-3 text-base font-medium text-white transition-all duration-300',
                    isSubmitting || !selectedDateTime
                        ? 'cursor-not-allowed bg-gray-400'
                        : 'bg-gradient-to-r from-blue-500 to-purple-500 shadow-md hover:from-blue-600 hover:to-purple-600 hover:shadow-lg',
                ]"
            >
                <span
                    v-if="isSubmitting"
                    class="flex items-center justify-center"
                >
                    <svg
                        class="-ml-1 mr-2 h-4 w-4 animate-spin text-white"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                    Procesando...
                </span>
                <span v-else>{{ submitButtonLabel }}</span>
            </button>

            <!-- Mensaje de éxito -->
            <div
                v-if="formSuccess"
                class="mt-6 rounded-lg border border-green-100 bg-green-50 p-4 text-green-700 dark:border-green-800/30 dark:bg-green-900/20 dark:text-green-300"
            >
                <div class="flex items-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="mr-2 h-5 w-5 text-green-500"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    {{ successMessage }}
                </div>
            </div>

            <!-- Mensaje de error -->
            <div
                v-if="formError"
                class="mt-6 rounded-lg border border-red-100 bg-red-50 p-4 text-red-700 dark:border-red-800/30 dark:bg-red-900/20 dark:text-red-300"
            >
                <div class="flex items-center">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="mr-2 h-5 w-5 text-red-500"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    {{ errorMessage }}
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { defineEmits, defineProps, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t: $t } = useI18n();

const props = defineProps({
    title: {
        type: String,
        default: 'Tus datos',
    },
    nameLabel: {
        type: String,
        default: 'Nombre completo',
    },
    emailLabel: {
        type: String,
        default: 'Correo electrónico',
    },
    projectDetailsLabel: {
        type: String,
        default: 'Detalles del proyecto (opcional)',
    },
    submitButtonLabel: {
        type: String,
        default: 'Confirmar reunión',
    },
    successMessage: {
        type: String,
        default:
            '¡Reunión agendada con éxito! Recibirás un correo de confirmación.',
    },
    errorMessage: {
        type: String,
        default: 'Ha ocurrido un error. Por favor, intenta nuevamente.',
    },
    selectedDateTime: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(['submit-form']);

// Estado del formulario
const formData = ref({
    name: '',
    email: '',
    projectDetails: '',
});

const isSubmitting = ref(false);
const formSuccess = ref(false);
const formError = ref(false);

// Enviar el formulario
async function submitForm() {
    if (!props.selectedDateTime) return;

    isSubmitting.value = true;
    formSuccess.value = false;
    formError.value = false;

    try {
        // Simulamos una petición al backend
        await new Promise((resolve) => setTimeout(resolve, 1500));

        // Enviar datos al componente padre
        emit('submit-form', {
            ...formData.value,
            date: props.selectedDateTime.date,
            time: props.selectedDateTime.time,
            // Incluimos la información adicional si está disponible
            ...(props.selectedDateTime.originalDate && {
                originalDate: props.selectedDateTime.originalDate,
            }),
            ...(props.selectedDateTime.displayTime && {
                displayTime: props.selectedDateTime.displayTime,
            }),
            ...(props.selectedDateTime.timezone && {
                timezone: props.selectedDateTime.timezone,
            }),
        });

        // Mostrar mensaje de éxito
        formSuccess.value = true;

        // Limpiar formulario
        formData.value = {
            name: '',
            email: '',
            projectDetails: '',
        };
    } catch (error) {
        console.error('Error al enviar el formulario:', error);
        formError.value = true;
    } finally {
        isSubmitting.value = false;
    }
}

// Formatear fecha (2023-05-15 -> 15 de Mayo de 2023)
function formatDate(date) {
    if (!date) return '';

    const months = [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre',
    ];

    const day = date.getDate();
    const month = date.getMonth();
    const year = date.getFullYear();

    return `${day} de ${months[month]} de ${year}`;
}

// Formatear hora (09:00 -> 9:00 AM)
function formatTime(time) {
    if (!time) return '';

    const [hours, minutes] = time.split(':');
    const hour = parseInt(hours);

    if (hour < 12) {
        return `${hour}:${minutes} AM`;
    } else if (hour === 12) {
        return `12:${minutes} PM`;
    } else {
        return `${hour - 12}:${minutes} PM`;
    }
}

// Formatear hora para mostrar (convertida según zona horaria)
function formatDisplayTime(displayTime) {
    if (!displayTime) return '';

    const [hours, minutes] = displayTime.split(':');
    const hour = parseInt(hours);

    if (hour < 12) {
        return `${hour}:${minutes} AM`;
    } else if (hour === 12) {
        return `12:${minutes} PM`;
    } else {
        return `${hour - 12}:${minutes} PM`;
    }
}

// Formatear zona horaria para mostrar de forma amigable
function formatTimezone(timezone) {
    if (!timezone) return '';

    try {
        // Utilizar el sistema de traducción i18n
        return $t(`timezones.${timezone}`);
    } catch (error) {
        // Fallback por si la traducción no está disponible
        return timezone;
    }
}
</script>

<style scoped>
.contact-form-container {
    width: 100%;
    max-width: 550px;
    margin: 0 auto;
}
</style>
