<template>
  <div class="contact-scheduler">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
      <!-- Calendario para seleccionar fecha y hora -->
      <div class="lg:col-span-1">
        <h3 class="text-xl font-bold mb-5 text-gray-800 dark:text-white">
          {{ selectDateLabel }}
        </h3>
        <Calendar
          :booked-times="bookedTimes"
          :no-times-available-text="noTimesAvailableText"
          :weekdays="weekdaysTranslated"
          :months="monthsTranslated"
          :availability="availability"
          :default-timezone="selectedTimezone"
          @date-selected="onDateSelected"
          @time-selected="onTimeSelected"
          @timezone-changed="onTimezoneChanged"
        />
      </div>

      <!-- Formulario de datos personales -->
      <div class="lg:col-span-1" v-if="selectedDateTime">
        <ContactForm
          :title="yourDetailsLabel"
          :name-label="nameLabel"
          :email-label="emailLabel"
          :project-details-label="projectDetailsLabel"
          :submit-button-label="submitButtonLabel"
          :success-message="successMessage"
          :error-message="errorMessage"
          :selected-date-time="selectedDateTime"
          @submit-form="onFormSubmit"
        />
      </div>

      <!-- Instrucciones iniciales (visible antes de seleccionar horario) -->
      <div class="lg:col-span-1" v-else>
        <div
          class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-200 dark:border-gray-700 h-full flex flex-col justify-center"
        >
          <svg
            class="mx-auto h-20 w-20 text-blue-500 dark:text-blue-400 mb-6"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="1.5"
              d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
            />
          </svg>
          <p class="text-center text-gray-600 dark:text-gray-300 text-lg leading-relaxed">
            {{ calendarInstructions }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from "vue";
import Calendar from "./Calendar.vue";
import ContactForm from "./ContactForm.vue";
import { useI18n } from "vue-i18n";

const { t } = useI18n();

// Props con traducciones y configuración
const props = defineProps({
  selectDateLabel: {
    type: String,
    default: "",
  },
  selectTimeLabel: {
    type: String,
    default: "",
  },
  noTimesAvailableText: {
    type: String,
    default: "",
  },
  yourDetailsLabel: {
    type: String,
    default: "",
  },
  nameLabel: {
    type: String,
    default: "",
  },
  emailLabel: {
    type: String,
    default: "",
  },
  projectDetailsLabel: {
    type: String,
    default: "",
  },
  submitButtonLabel: {
    type: String,
    default: "",
  },
  successMessage: {
    type: String,
    default: "",
  },
  errorMessage: {
    type: String,
    default: "",
  },
  calendarInstructions: {
    type: String,
    default: "",
  },
});

// Traducciones de días de la semana y meses
const weekdaysTranslated = computed(() => [
  t('calendar.weekdays.monday_short'),
  t('calendar.weekdays.tuesday_short'),
  t('calendar.weekdays.wednesday_short'),
  t('calendar.weekdays.thursday_short'),
  t('calendar.weekdays.friday_short'),
  t('calendar.weekdays.saturday_short'),
  t('calendar.weekdays.sunday_short')
]);

const monthsTranslated = computed(() => [
  t('calendar.months.january'),
  t('calendar.months.february'),
  t('calendar.months.march'),
  t('calendar.months.april'),
  t('calendar.months.may'),
  t('calendar.months.june'),
  t('calendar.months.july'),
  t('calendar.months.august'),
  t('calendar.months.september'),
  t('calendar.months.october'),
  t('calendar.months.november'),
  t('calendar.months.december')
]);

// Datos de reservas (simulados, en producción vendrían del backend)
const bookedTimes = ref({
  // Ejemplo: '2023-08-15': ['09:00', '10:00'] - Significa que esas horas ya están reservadas
});

// Estado para la fecha y hora seleccionadas
const selectedDate = ref(null);
const selectedTime = ref(null);
const selectedDateTime = ref(null);
const selectedTimezone = ref("Etc/GMT-1"); // UTC+1 para España

// Datos de disponibilidad hardcodeados
const availability = ref({
  weeklyHours: {},
  specificAvailability: {},
  blockedSlots: {},
});

// Simular obtención de datos del backend
function fetchBookedTimes() {
  // En una implementación real, esto sería una llamada a la API
  setTimeout(() => {
    // Simulamos algunas reservas ya realizadas
    bookedTimes.value = {
      // Ejemplo: una reserva ya hecha
      "2024-06-10": ["10:00"], // Un lunes con la hora de 10:00 ya reservada
      "2024-06-19": ["15:15"], // Un miércoles con la hora de 15:15 ya reservada
    };
  }, 500);
}

// Cargar disponibilidad desde la API
async function fetchAvailability() {
  try {
    const response = await fetch("/api/availability");
    const data = await response.json();

    // Actualizar la disponibilidad con los datos de la API
    availability.value = {
      weeklyHours: data.weekly_hours || {},
      specificAvailability: data.specific_availability || {},
      blockedSlots: data.blocked_slots || {},
    };
  } catch (error) {
    console.error("Error al cargar la disponibilidad:", error);
  }
}

// Cuando se carga el componente, cargamos datos de reservas y disponibilidad
onMounted(() => {
  // Cargar datos de reservas
  fetchBookedTimes();

  // Cargar configuración de disponibilidad
  fetchAvailability();
});

// Manejar la selección de fecha
function onDateSelected(date) {
  selectedDate.value = date;
  selectedDateTime.value = null; // Reiniciamos el valor combinado hasta que se seleccione hora
}

// Manejar la selección de hora
function onTimeSelected(data) {
  selectedTime.value = data.time;
  selectedDateTime.value = {
    date: data.date,
    time: data.time,
    originalDate: data.originalDate,
    displayTime: data.displayTime,
    timezone: data.timezone,
    serverDateTime: data.serverDateTime,
  };
}

// Manejar el cambio de zona horaria
function onTimezoneChanged(timezone) {
  selectedTimezone.value = timezone;
  // Cuando cambia la zona horaria, mantenemos la fecha seleccionada pero limpiamos la hora
  if (selectedDateTime.value) {
    selectedDateTime.value = null;
  }
}

// Manejar el envío del formulario
function onFormSubmit(formData) {
  console.log("Datos del formulario:", formData);

  // En una implementación real, aquí enviaríamos los datos al backend
  // y actualizaríamos las horas ocupadas

  // Simular actualización de horarios reservados
  const dateKey = selectedDateTime.value.originalDate;
  if (!bookedTimes.value[dateKey]) {
    bookedTimes.value[dateKey] = [];
  }

  bookedTimes.value[dateKey].push(selectedDateTime.value.time);

  // Añadimos información de zona horaria a los datos del formulario
  const completeFormData = {
    ...formData,
    date: selectedDateTime.value.date,
    time: selectedDateTime.value.time,
    originalDate: selectedDateTime.value.originalDate,
    displayTime: selectedDateTime.value.displayTime,
    timezone: selectedDateTime.value.timezone,
    serverDateTime: selectedDateTime.value.serverDateTime,
  };

  // En producción, enviaríamos completeFormData al servidor
  console.log("Datos completos con zona horaria:", completeFormData);

  // Reiniciar selección después de enviar
  selectedDate.value = null;
  selectedTime.value = null;
  selectedDateTime.value = null;
}

// Formatear fecha como clave para el objeto bookedTimes (YYYY-MM-DD)
function formatDateToKey(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, "0");
  const day = String(date.getDate()).padStart(2, "0");

  return `${year}-${month}-${day}`;
}
</script>

<style scoped>
.contact-scheduler {
  width: 100%;
  max-width: 1200px;
  margin: 0 auto;
}
</style>
