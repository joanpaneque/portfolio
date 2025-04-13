<template>
  <div class="calendar-component">
    <!-- Selector de zona horaria -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 border border-gray-200 dark:border-gray-700">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
        {{ $t('timezones.label') }}
      </label>
      <div class="relative">
        <select 
          v-model="selectedTimezone" 
          @change="handleTimezoneChange"
          class="block w-full p-3 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-blue-500 focus:border-blue-500"
          :disabled="isLoadingTimezone"
        >
          <option v-for="tz in timezones" :key="tz.value" :value="tz.value">
            {{ $t(`timezones.${tz.value}`) }}
          </option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
          <svg v-if="isLoadingTimezone" class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <svg v-else class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </div>
      </div>
      <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
        <span v-if="isLoadingTimezone">{{ $t('timezones.detecting') }}</span>
        <span v-else>{{ $t('timezones.info') }} <span class="text-blue-500 font-medium">{{ $t('timezones.manualSelection') }}</span></span>
      </p>
    </div>

    <!-- Calendario para selección de fecha -->
    <div class="date-selection bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
      <div class="header bg-gradient-to-r from-blue-500 to-purple-500 p-5 text-white">
        <div class="flex justify-between items-center">
          <button 
            @click="previousMonth" 
            class="text-white hover:bg-white/20 p-3 rounded-full transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
          <h3 class="text-xl font-bold tracking-wide">{{ currentMonthName }} {{ currentYear }}</h3>
          <button 
            @click="nextMonth" 
            class="text-white hover:bg-white/20 p-3 rounded-full transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
        </div>
      </div>
      
      <div class="p-6">
        <!-- Días de la semana -->
        <div class="grid grid-cols-7 mb-4">
          <span v-for="day in weekdays" :key="day" class="text-center text-sm font-semibold text-gray-600 dark:text-gray-300 py-2">
            {{ day }}
          </span>
        </div>
        
        <!-- Días del mes -->
        <div class="grid grid-cols-7 gap-2">
          <button
            v-for="{ date, status } in calendarDays"
            :key="date.getTime()"
            :class="getDayClasses(date, status)"
            @click="selectDate(date, status)"
            :disabled="status === 'disabled'"
          >
            {{ date.getDate() }}
          </button>
        </div>
      </div>
    </div>

    <!-- Selección de hora (visible solo cuando se ha seleccionado una fecha) -->
    <div v-if="selectedDate" class="time-selection mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
      <h4 class="text-xl font-semibold mb-5 text-gray-800 dark:text-white">
        {{ formatSelectedDate }}
      </h4>
      
      <div v-if="availableTimesForSelectedDate.length > 0" class="grid grid-cols-3 gap-4">
        <button
          v-for="timeObj in availableTimesForSelectedDate"
          :key="timeObj.originalTime"
          :class="[
            'p-4 rounded-lg border font-medium transition-colors text-base',
            selectedTime && selectedTime.originalTime === timeObj.originalTime && selectedTime.originalDate === timeObj.originalDate
              ? 'bg-blue-500 text-white border-blue-500 shadow-md'
              : 'bg-white dark:bg-gray-700 border-gray-200 dark:border-gray-600 text-gray-800 dark:text-white hover:bg-blue-50 dark:hover:bg-blue-900/30'
          ]"
          @click="selectTime(timeObj)"
        >
          {{ formatTime(timeObj) }}
        </button>
      </div>
      
      <div v-else class="text-center p-6 bg-gray-50 dark:bg-gray-700/50 rounded-lg text-gray-600 dark:text-gray-300">
        {{ noTimesAvailableText }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import axios from 'axios';
import { useI18n } from 'vue-i18n';

const { t: $t } = useI18n();

const props = defineProps({
  bookedDates: {
    type: Array,
    default: () => []
  },
  bookedTimes: {
    type: Object,
    default: () => ({})
  },
  maxDaysInAdvance: {
    type: Number,
    default: 60
  },
  noTimesAvailableText: {
    type: String,
    default: 'No hay horarios disponibles para esta fecha'
  },
  weekdays: {
    type: Array,
    default: () => ['Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa', 'Do']
  },
  months: {
    type: Array,
    default: () => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre']
  },
  availability: {
    type: Object,
    default: () => ({
      weeklyHours: {},
      specificAvailability: {},
      blockedSlots: {}
    })
  },
  defaultTimezone: {
    type: String,
    default: 'Etc/GMT-1'
  }
});

const emit = defineEmits(['dateSelected', 'timeSelected', 'timezoneChanged']);

// Estado local del calendario
const currentDate = ref(new Date());
const selectedDate = ref(null);
const selectedTime = ref(null);
const selectedTimezone = ref(props.defaultTimezone);
const isLoadingTimezone = ref(false);

// Lista de zonas horarias comunes
const timezones = [
  { value: 'Etc/GMT+12' },
  { value: 'Etc/GMT+11' },
  { value: 'Etc/GMT+10' },
  { value: 'Etc/GMT+9' },
  { value: 'Etc/GMT+8' },
  { value: 'Etc/GMT+7' },
  { value: 'Etc/GMT+6' },
  { value: 'Etc/GMT+5' },
  { value: 'Etc/GMT+4' },
  { value: 'Etc/GMT+3' },
  { value: 'Etc/GMT+2' },
  { value: 'Etc/GMT+1' },
  { value: 'Etc/GMT+0' },
  { value: 'Etc/GMT-1' },
  { value: 'Etc/GMT-2' },
  { value: 'Etc/GMT-3' },
  { value: 'Etc/GMT-4' },
  { value: 'Etc/GMT-5' },
  { value: 'Etc/GMT-6' },
  { value: 'Etc/GMT-7' },
  { value: 'Etc/GMT-8' },
  { value: 'Etc/GMT-9' },
  { value: 'Etc/GMT-10' },
  { value: 'Etc/GMT-11' },
  { value: 'Etc/GMT-12' },
  { value: 'Etc/GMT-13' },
  { value: 'Etc/GMT-14' }
];

// Detectar zona horaria del usuario al cargar el componente
onMounted(async () => {
  isLoadingTimezone.value = true;
  try {
    // Primero intentamos usar el servicio API basado en IP
    const response = await axios.get('/api/timezone');
    const data = response.data;
    
    if (data && data.etcGmtFormat) {
      // Verificar si el formato está en nuestra lista de zonas horarias
      const validTimezone = timezones.find(tz => tz.value === data.etcGmtFormat);
      if (validTimezone) {
        selectedTimezone.value = data.etcGmtFormat;
        emit('timezoneChanged', data.etcGmtFormat);
        isLoadingTimezone.value = false;
        return;
      }
    }
    
    // Si no hay datos del servidor o la zona horaria no es válida,
    // usamos la detección por JavaScript como fallback
    detectTimezoneByJavaScript();
  } catch (error) {
    console.error('Error al obtener la zona horaria del servidor:', error);
    // Fallback a la detección por JavaScript
    detectTimezoneByJavaScript();
  } finally {
    isLoadingTimezone.value = false;
  }
});

// Método de detección de zona horaria usando JavaScript
function detectTimezoneByJavaScript() {
  try {
    // Primero verificamos si el navegador está configurado para España
    const userLang = navigator.language || navigator.userLanguage;
    
    // Si el idioma del navegador es español o catalán de España, asumimos España (UTC+1)
    if (userLang && (userLang === 'es-ES' || userLang === 'ca-ES')) {
      console.log('Detectado navegador configurado para España');
      selectedTimezone.value = 'Etc/GMT-1'; // España (UTC+1)
      emit('timezoneChanged', 'Etc/GMT-1');
      return;
    }
    
    // Si la detección por idioma no funciona, intentamos por offset
    const userOffsetMinutes = new Date().getTimezoneOffset();
    
    // Convertimos a horas (y negamos porque getTimezoneOffset() devuelve negativo para UTC+)
    const userOffsetHours = -userOffsetMinutes / 60;
    
    // En España, el offset es +1 (invierno) o +2 (verano)
    // Verificamos si estamos en ese rango como comprobación adicional
    if (userOffsetHours === 1 || userOffsetHours === 2) {
      console.log('Detectada zona horaria compatible con España (UTC+' + userOffsetHours + ')');
      // Si estamos en UTC+1 o UTC+2, es probable que estemos en Europa/España
      selectedTimezone.value = `Etc/GMT-${userOffsetHours}`;
      emit('timezoneChanged', selectedTimezone.value);
      return;
    }
    
    // Para otros casos, encontrar la zona horaria UTC más cercana
    let bestMatch = 'Etc/GMT-1'; // Valor por defecto (España, UTC+1)
    let smallestDiff = Infinity;
    
    // Para cada zona horaria en nuestra lista
    for (const tz of timezones) {
      // Extraer el offset UTC del valor de la zona horaria
      // Formato: 'Etc/GMT+X' o 'Etc/GMT-X'
      const offsetStr = tz.value.split('GMT')[1];
      const sign = offsetStr[0] === '+' ? -1 : 1; // Signo inverso por la convención Etc/GMT
      const offsetValue = sign * parseFloat(offsetStr.substring(1));
      
      // Calcular la diferencia con el offset del usuario
      const diff = Math.abs(offsetValue - userOffsetHours);
      
      // Si encontramos una coincidencia más cercana
      if (diff < smallestDiff) {
        smallestDiff = diff;
        bestMatch = tz.value;
      }
    }
    
    // Establecer la zona horaria seleccionada
    selectedTimezone.value = bestMatch;
    emit('timezoneChanged', bestMatch);
  } catch (error) {
    console.error('Error al detectar zona horaria por JavaScript:', error);
    // Si todo falla, usar el valor por defecto
    selectedTimezone.value = props.defaultTimezone;
    emit('timezoneChanged', props.defaultTimezone);
  }
}

// Calcular año y mes actuales
const currentYear = computed(() => currentDate.value.getFullYear());
const currentMonth = computed(() => currentDate.value.getMonth());
const currentMonthName = computed(() => props.months[currentMonth.value]);

// Día actual para comparaciones
const today = new Date();
today.setHours(0, 0, 0, 0);

// Fecha máxima para reservas
const maxDate = computed(() => {
  const date = new Date();
  date.setDate(date.getDate() + props.maxDaysInAdvance);
  return date;
});

// Calcular los días a mostrar en el calendario
const calendarDays = computed(() => {
  const year = currentYear.value;
  const month = currentMonth.value;
  
  // Primer día del mes
  const firstDay = new Date(year, month, 1);
  // Último día del mes
  const lastDay = new Date(year, month + 1, 0);
  
  // Días totales del mes
  const daysInMonth = lastDay.getDate();
  
  // Día de la semana del primer día (0 = Domingo, 1 = Lunes, etc.)
  let firstDayOfWeek = firstDay.getDay();
  
  // Ajustar para que la semana comience el lunes (0 = Lunes, 6 = Domingo)
  firstDayOfWeek = firstDayOfWeek === 0 ? 6 : firstDayOfWeek - 1;
  
  // Array para almacenar todos los días a mostrar en el calendario
  const days = [];
  
  // Añadir días del mes anterior para completar la primera semana
  for (let i = 0; i < firstDayOfWeek; i++) {
    const date = new Date(year, month, -firstDayOfWeek + i + 1);
    days.push({ date, status: 'disabled' });
  }
  
  // Añadir días del mes actual
  for (let i = 1; i <= daysInMonth; i++) {
    const date = new Date(year, month, i);
    date.setHours(0, 0, 0, 0);
    
    let status = 'available';
    
    // Verificar si la fecha es anterior a hoy o posterior al máximo permitido
    if (date < today || date > maxDate.value) {
      status = 'disabled';
    } 
    // Verificar si la fecha está reservada completamente
    else if (isDateFullyBooked(date)) {
      status = 'booked';
    } 
    // Verificar si la fecha tiene algunas horas disponibles
    else if (isDatePartiallyBooked(date)) {
      status = 'partially-booked';
    }
    
    days.push({ date, status });
  }
  
  // Añadir días del mes siguiente para completar la última semana
  const remainingCells = 42 - days.length; // 6 semanas x 7 días = 42 celdas
  for (let i = 1; i <= remainingCells; i++) {
    const date = new Date(year, month + 1, i);
    days.push({ date, status: 'disabled' });
  }
  
  return days;
});

// Formatear la fecha seleccionada
const formatSelectedDate = computed(() => {
  if (!selectedDate.value) return '';
  
  const date = selectedDate.value;
  const day = date.getDate();
  const month = date.getMonth();
  const year = date.getFullYear();
  
  return `${day} de ${props.months[month]} de ${year}`;
});

// Horarios disponibles para la fecha seleccionada, ajustados a la zona horaria del usuario
const availableTimesForSelectedDate = computed(() => {
  if (!selectedDate.value) return [];
  
  const selectedDateObj = selectedDate.value;
  const availableTimesWithMetadata = [];

  // Para cada día (anterior, actual y siguiente)
  for (let dayOffset = -1; dayOffset <= 1; dayOffset++) {
    const checkDate = new Date(selectedDateObj);
    checkDate.setDate(checkDate.getDate() + dayOffset);
    
    const dateStr = formatDateToKey(checkDate);
    const dayIndex = checkDate.getDay();
    const dayOfWeek = dayIndex === 0 ? '6' : (dayIndex - 1).toString();
    
    // Verificar si hay horas específicas bloqueadas para esta fecha
    const blockedHours = props.availability.blockedSlots[dateStr] || [];
    
    // Si el día está completamente bloqueado, saltamos este día
    if (blockedHours.includes('*')) {
      continue;
    }
    
    // Opciones por defecto según el día de la semana
    let availableHours = [...(props.availability.weeklyHours[dayOfWeek] || [])];
    
    // Añadir horas específicas adicionales para esta fecha
    if (props.availability.specificAvailability[dateStr]) {
      availableHours = [
        ...new Set([
          ...availableHours, 
          ...props.availability.specificAvailability[dateStr]
        ])
      ];
    }
    
    // Filtrar horas bloqueadas
    availableHours = availableHours.filter(hour => !blockedHours.includes(hour));
    
    // Verificar con las reservas existentes (ya realizadas)
    const bookedTimes = props.bookedTimes[dateStr] || [];
    availableHours = availableHours.filter(time => !bookedTimes.includes(time));
    
    // Procesar las horas disponibles
    for (const time of availableHours) {
      const [hours, minutes] = time.split(':').map(Number);
      
      try {
        // Las horas en la base de datos están en zona horaria de España (UTC+1 o UTC+2 en verano)
        // Para simplificar y trabajar con UTC, asumimos UTC+1 (la más común en España)
        const offsetHours = 1; // Offset de España respecto a UTC (puede ser 1 o 2 dependiendo de DST)
        
        // Convertimos la hora española a UTC
        // La hora UTC sería la hora española menos el offset
        const utcHours = hours - offsetHours;
        
        // Creamos un timestamp UTC que representa la hora
        const timestamp = Date.UTC(
          checkDate.getFullYear(),
          checkDate.getMonth(),
          checkDate.getDate(),
          utcHours,
          minutes
        );
        
        // Opciones para formatear en la zona horaria del usuario
        const userOptions = {
          timeZone: selectedTimezone.value,
          year: 'numeric',
          month: '2-digit',
          day: '2-digit',
          hour: '2-digit',
          minute: '2-digit',
          hour12: false
        };
        
        // Convertimos el timestamp a la zona horaria del usuario
        const userDateTime = new Intl.DateTimeFormat('es-ES', userOptions).format(new Date(timestamp));
        
        // Extraemos la fecha y hora
        const [userDatePart, userTimePart] = userDateTime.split(', ');
        const [userDay, userMonth, userYear] = userDatePart.split('/').map(Number);
        
        // Verificamos si la fecha coincide con la fecha seleccionada
        if (
          userDay === selectedDateObj.getDate() &&
          userMonth - 1 === selectedDateObj.getMonth() && // userMonth viene como 1-12, getMonth() devuelve 0-11
          userYear === selectedDateObj.getFullYear()
        ) {
          // Esta hora cae en el día seleccionado en la zona horaria del usuario
          availableTimesWithMetadata.push({
            originalTime: time,
            originalDate: dateStr,
            displayTime: userTimePart,
            serverDateTime: new Date(timestamp).toISOString()
          });
        }
      } catch (error) {
        console.error('Error al convertir zona horaria:', error, time);
      }
    }
  }
  
  // Ordenar por hora mostrada
  return availableTimesWithMetadata.sort((a, b) => {
    return a.displayTime.localeCompare(b.displayTime);
  });
});

// Verificar si una fecha está completamente reservada o sin disponibilidad
function isDateFullyBooked(date) {
  const dateStr = formatDateToKey(date);
  // Convertir día de la semana de formato JavaScript (0=domingo) a nuestro formato (0=lunes)
  const dayIndex = date.getDay();
  const dayOfWeek = dayIndex === 0 ? '6' : (dayIndex - 1).toString(); // 0=lunes, 6=domingo
  
  // Si el día está bloqueado completamente en blockedSlots
  if (props.availability.blockedSlots[dateStr]?.includes('*')) {
    return true;
  }
  
  // Obtener las horas disponibles por defecto para este día de la semana
  let availableHours = [...(props.availability.weeklyHours[dayOfWeek] || [])];
  
  // Añadir horas específicas adicionales para esta fecha
  if (props.availability.specificAvailability[dateStr]) {
    availableHours = [
      ...new Set([
        ...availableHours, 
        ...props.availability.specificAvailability[dateStr]
      ])
    ];
  }
  
  // Filtrar horas bloqueadas específicamente para esta fecha
  const blockedHours = props.availability.blockedSlots[dateStr] || [];
  availableHours = availableHours.filter(hour => !blockedHours.includes(hour));
  
  // Si no hay horas disponibles después de aplicar los filtros
  if (availableHours.length === 0) {
    return true;
  }
  
  // Verificar si todas las horas disponibles están ya reservadas
  const bookedTimes = props.bookedTimes[dateStr] || [];
  return availableHours.every(time => bookedTimes.includes(time));
}

// Verificar si una fecha está parcialmente reservada
function isDatePartiallyBooked(date) {
  const dateStr = formatDateToKey(date);
  // Convertir día de la semana de formato JavaScript (0=domingo) a nuestro formato (0=lunes)
  const dayIndex = date.getDay();
  const dayOfWeek = dayIndex === 0 ? '6' : (dayIndex - 1).toString(); // 0=lunes, 6=domingo
  
  // Si el día está completamente bloqueado
  if (props.availability.blockedSlots[dateStr]?.includes('*')) {
    return false; // No está parcialmente ocupado, sino completamente
  }
  
  // Obtener las horas disponibles para este día
  let availableHours = [...(props.availability.weeklyHours[dayOfWeek] || [])];
  
  // Añadir horas específicas adicionales para esta fecha
  if (props.availability.specificAvailability[dateStr]) {
    availableHours = [
      ...new Set([
        ...availableHours, 
        ...props.availability.specificAvailability[dateStr]
      ])
    ];
  }
  
  // Filtrar horas bloqueadas
  const blockedHours = props.availability.blockedSlots[dateStr] || [];
  availableHours = availableHours.filter(hour => !blockedHours.includes(hour));
  
  // Si no hay horas disponibles después de aplicar los filtros
  if (availableHours.length === 0) {
    return false;
  }
  
  // Verificar con las reservas existentes (ya realizadas)
  const bookedTimes = props.bookedTimes[dateStr] || [];
  
  // Horas que quedan disponibles después de filtrar las reservadas
  const remainingAvailableHours = availableHours.filter(time => !bookedTimes.includes(time));
  
  // Considerar "parcialmente reservado" si:
  // 1. Solo queda una hora disponible, o
  // 2. Algunas (pero no todas) las horas están reservadas
  return remainingAvailableHours.length === 1 || 
         (bookedTimes.length > 0 && remainingAvailableHours.length > 0);
}

// Formatear fecha como clave para el objeto bookedTimes (YYYY-MM-DD)
function formatDateToKey(date) {
  const year = date.getFullYear();
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const day = String(date.getDate()).padStart(2, '0');
  
  return `${year}-${month}-${day}`;
}

// Formatear hora para mostrar con información de zona horaria
function formatTime(timeObj) {
  const time = timeObj.displayTime;
  const [hours, minutes] = time.split(':');
  const hour = parseInt(hours);
  
  let formattedTime;
  if (hour < 12) {
    formattedTime = `${hour}:${minutes} AM`;
  } else if (hour === 12) {
    formattedTime = `12:${minutes} PM`;
  } else {
    formattedTime = `${hour - 12}:${minutes} PM`;
  }
  
  return formattedTime;
}

// Manejar cambio de zona horaria
function handleTimezoneChange() {
  emit('timezoneChanged', selectedTimezone.value);
  // Si hay una fecha seleccionada, mantenerla pero actualizar las horas disponibles
  if (selectedDate.value) {
    // Reiniciar la hora seleccionada
    selectedTime.value = null;
  }
}

// Clases para los días del calendario
function getDayClasses(date, status) {
  let baseClasses = 'w-11 h-11 rounded-full flex items-center justify-center text-base';
  
  // Verificar si es la fecha seleccionada
  const isSelected = selectedDate.value && 
                     date.getDate() === selectedDate.value.getDate() && 
                     date.getMonth() === selectedDate.value.getMonth() && 
                     date.getFullYear() === selectedDate.value.getFullYear();
  
  if (isSelected) {
    return `${baseClasses} bg-blue-500 text-white font-semibold shadow-md`;
  }
  
  if (status === 'disabled') {
    return `${baseClasses} text-gray-300 dark:text-gray-600 cursor-not-allowed`;
  }
  
  if (status === 'booked') {
    return `${baseClasses} bg-red-50 dark:bg-red-900/10 text-red-700 dark:text-red-400 cursor-not-allowed`;
  }
  
  if (status === 'partially-booked') {
    return `${baseClasses} bg-yellow-50 dark:bg-yellow-900/10 text-yellow-600 dark:text-yellow-300 hover:bg-yellow-100 dark:hover:bg-yellow-800/20 cursor-pointer`;
  }
  
  return `${baseClasses} bg-green-50 dark:bg-green-900/10 hover:bg-green-100 dark:hover:bg-green-800/20 text-green-700 dark:text-green-400 cursor-pointer`;
}

// Navegar al mes anterior
function previousMonth() {
  currentDate.value = new Date(currentYear.value, currentMonth.value - 1, 1);
}

// Navegar al mes siguiente
function nextMonth() {
  currentDate.value = new Date(currentYear.value, currentMonth.value + 1, 1);
}

// Seleccionar una fecha
function selectDate(date, status) {
  if (status === 'disabled' || status === 'booked') return;
  
  selectedDate.value = new Date(date);
  selectedTime.value = null;
  emit('dateSelected', selectedDate.value);
}

// Seleccionar una hora
function selectTime(timeObj) {
  selectedTime.value = timeObj;
  emit('timeSelected', { 
    date: selectedDate.value, 
    time: timeObj.originalTime, 
    originalDate: timeObj.originalDate,
    serverDateTime: timeObj.serverDateTime,
    displayTime: timeObj.displayTime,
    timezone: selectedTimezone.value
  });
}

// Reiniciar la hora seleccionada cuando cambia la fecha
watch(selectedDate, () => {
  selectedTime.value = null;
});

// Formatear zona horaria para mostrar de forma amigable
function formatTimezone(timezone) {
  if (!timezone) return '';
  
  // Usar el sistema de traducción i18n
  return $t(`timezones.${timezone}`);
}
</script>

<style scoped>
.calendar-component {
  width: 100%;
  max-width: 550px;
  margin: 0 auto;
}
</style> 