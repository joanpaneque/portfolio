<template>
  <div class="admin-availability-page p-8">
    <h1 class="text-2xl font-bold mb-6">Panel de Administración de Disponibilidad</h1>
    
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
      <!-- Configuración de disponibilidad semanal -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-semibold mb-4">Disponibilidad semanal</h2>
        <p class="text-gray-600 dark:text-gray-300 mb-6">Define los horarios disponibles para cada día de la semana.</p>
        
        <div class="space-y-6">
          <div v-for="(dayNumber, index) in Object.keys(weeklyHours)" :key="dayNumber" class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
            <h3 class="font-semibold mb-3">{{ weekdays[dayNumber] }}</h3>
            
            <div class="mb-3 flex items-center">
              <span class="mr-3 text-gray-600 dark:text-gray-300">Estado:</span>
              <button 
                @click="toggleDayAvailability(dayNumber)"
                :class="[
                  'px-4 py-1 rounded-full text-sm font-medium',
                  weeklyHours[dayNumber].length > 0 
                    ? 'bg-green-100 text-green-800 dark:bg-green-800/20 dark:text-green-400'
                    : 'bg-red-100 text-red-800 dark:bg-red-800/20 dark:text-red-400'
                ]"
              >
                {{ weeklyHours[dayNumber].length > 0 ? 'Disponible' : 'No disponible' }}
              </button>
            </div>
            
            <div v-if="weeklyHours[dayNumber].length > 0" class="space-y-3">
              <div v-for="(time, timeIndex) in weeklyHours[dayNumber]" :key="timeIndex" class="flex items-center">
                <input 
                  type="time" 
                  v-model="weeklyHours[dayNumber][timeIndex]" 
                  class="p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-white" 
                />
                <button 
                  @click="removeTimeFromDay(dayNumber, timeIndex)" 
                  class="ml-2 p-2 text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/20 rounded-full"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
              
              <button 
                @click="addTimeToDay(dayNumber)" 
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center text-sm"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                </svg>
                Añadir horario
              </button>
            </div>
            
            <button 
              v-if="weeklyHours[dayNumber].length === 0"
              @click="addTimeToDay(dayNumber)" 
              class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center text-sm"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
              </svg>
              Habilitar día
            </button>
          </div>
        </div>
      </div>
      
      <!-- Configuración de excepciones y bloqueos -->
      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
        <div class="mb-8">
          <h2 class="text-xl font-semibold mb-4">Excepciones de disponibilidad</h2>
          <p class="text-gray-600 dark:text-gray-300 mb-4">Añade horas disponibles para fechas específicas incluso si normalmente no lo están.</p>
          
          <div class="space-y-4">
            <div v-for="(times, date) in specificAvailability" :key="date" class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="flex items-center justify-between mb-3">
                <span class="font-semibold">{{ formatDate(date) }}</span>
                <button 
                  @click="removeSpecificDate(date)" 
                  class="p-1 text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/20 rounded-full"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
              
              <div class="space-y-2">
                <div v-for="(time, timeIndex) in times" :key="timeIndex" class="flex items-center">
                  <input 
                    type="time" 
                    v-model="specificAvailability[date][timeIndex]" 
                    class="p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-white" 
                  />
                  <button 
                    @click="removeTimeFromSpecificDate(date, timeIndex)" 
                    class="ml-2 p-2 text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/20 rounded-full"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </div>
                
                <button 
                  @click="addTimeToSpecificDate(date)" 
                  class="px-4 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center text-sm"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  Añadir horario
                </button>
              </div>
            </div>
            
            <div class="mt-4">
              <div class="flex items-end gap-2">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha</label>
                  <input 
                    type="date" 
                    v-model="newSpecificDate" 
                    class="p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-white" 
                  />
                </div>
                <button 
                  @click="addSpecificDate" 
                  class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 flex items-center text-sm"
                  :disabled="!newSpecificDate"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  Añadir excepción
                </button>
              </div>
            </div>
          </div>
        </div>
        
        <div>
          <h2 class="text-xl font-semibold mb-4">Bloqueos temporales</h2>
          <p class="text-gray-600 dark:text-gray-300 mb-4">Bloquea horas o días completos en fechas específicas.</p>
          
          <div class="space-y-4">
            <div v-for="(times, date) in blockedSlots" :key="date" class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
              <div class="flex items-center justify-between mb-3">
                <span class="font-semibold">{{ formatDate(date) }}</span>
                <button 
                  @click="removeBlockedDate(date)" 
                  class="p-1 text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/20 rounded-full"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
              
              <div v-if="blockedSlots[date].includes('*')" class="text-red-600 dark:text-red-400 font-medium">
                Día completo bloqueado
                <button 
                  @click="unblockFullDay(date)" 
                  class="ml-2 px-2 py-1 bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300 rounded-lg text-xs"
                >
                  Desbloquear
                </button>
              </div>
              
              <div v-else class="space-y-2">
                <div v-for="(time, timeIndex) in blockedSlots[date]" :key="timeIndex" class="flex items-center">
                  <input 
                    type="time" 
                    v-model="blockedSlots[date][timeIndex]" 
                    class="p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-white" 
                  />
                  <button 
                    @click="removeBlockedTime(date, timeIndex)" 
                    class="ml-2 p-2 text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/20 rounded-full"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </div>
                
                <div class="flex gap-2">
                  <button 
                    @click="addBlockedTime(date)" 
                    class="px-4 py-1 bg-orange-500 text-white rounded-lg hover:bg-orange-600 flex items-center text-sm"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Añadir hora
                  </button>
                  
                  <button 
                    @click="blockFullDay(date)" 
                    class="px-4 py-1 bg-red-500 text-white rounded-lg hover:bg-red-600 flex items-center text-sm"
                  >
                    Bloquear día completo
                  </button>
                </div>
              </div>
            </div>
            
            <div class="mt-4">
              <div class="flex items-end gap-2">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha</label>
                  <input 
                    type="date" 
                    v-model="newBlockedDate" 
                    class="p-2 border border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-700 text-gray-800 dark:text-white" 
                  />
                </div>
                <button 
                  @click="addBlockedDate" 
                  class="px-4 py-2 bg-orange-500 text-white rounded-lg hover:bg-orange-600 flex items-center text-sm"
                  :disabled="!newBlockedDate"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                  </svg>
                  Añadir bloqueo
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Botones de acciones -->
    <div class="mt-8 flex justify-end space-x-4">
      <button 
        @click="resetChanges" 
        class="px-6 py-3 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
      >
        Cancelar cambios
      </button>
      <button 
        @click="saveChanges" 
        class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
      >
        Guardar configuración
      </button>
    </div>

    <!-- Vista previa del calendario -->
    <div class="mt-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
      <h2 class="text-xl font-semibold mb-4">Vista previa del calendario</h2>
      <p class="text-gray-600 dark:text-gray-300 mb-6">Así se verá el calendario para los usuarios:</p>
      
      <Calendar 
        :booked-times="{}" 
        :no-times-available-text="'No hay horarios disponibles para esta fecha'"
        :weekdays="weekdays"
        :months="months"
        :availability="{
          weeklyHours,
          specificAvailability,
          blockedSlots
        }"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import Calendar from '@/Components/Calendar.vue';
import axios from 'axios';

// Datos de días y meses
const weekdays = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
const months = ['Enero', 'Febrero', 'Marzo', 'AbrilXDD', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

// Estado para la configuración de disponibilidad
const weeklyHours = reactive({
  '0': [], // Lunes
  '1': [], // Martes
  '2': [], // Miércoles
  '3': [], // Jueves
  '4': [], // Viernes
  '5': [], // Sábado
  '6': []  // Domingo
});

// Estados para excepciones y bloqueos
const specificAvailability = reactive({});
const blockedSlots = reactive({});

// Nuevos elementos
const newSpecificDate = ref('');
const newBlockedDate = ref('');

// Variables de estado original para poder cancelar cambios
let originalWeeklyHours = {};
let originalSpecificAvailability = {};
let originalBlockedSlots = {};

// Cargar datos al iniciar
onMounted(async () => {
  try {
    const response = await axios.get('/api/availability');
    const data = response.data;
    
    // Guardar copia de los datos originales
    originalWeeklyHours = JSON.parse(JSON.stringify(data.weekly_hours || {}));
    originalSpecificAvailability = JSON.parse(JSON.stringify(data.specific_availability || {}));
    originalBlockedSlots = JSON.parse(JSON.stringify(data.blocked_slots || {}));
    
    // Asignar datos a los reactivos
    Object.assign(weeklyHours, data.weekly_hours || {});
    Object.assign(specificAvailability, data.specific_availability || {});
    Object.assign(blockedSlots, data.blocked_slots || {});
  } catch (error) {
    console.error('Error al cargar la disponibilidad:', error);
  }
});

// Métodos para gestionar la disponibilidad semanal
function toggleDayAvailability(dayNumber) {
  if (weeklyHours[dayNumber].length > 0) {
    weeklyHours[dayNumber] = [];
  } else {
    weeklyHours[dayNumber] = ['09:00'];
  }
}

function addTimeToDay(dayNumber) {
  if (!weeklyHours[dayNumber]) {
    weeklyHours[dayNumber] = [];
  }
  weeklyHours[dayNumber].push('09:00');
}

function removeTimeFromDay(dayNumber, timeIndex) {
  weeklyHours[dayNumber].splice(timeIndex, 1);
}

// Métodos para gestionar excepciones de disponibilidad
function addSpecificDate() {
  if (!newSpecificDate.value) return;
  
  const dateStr = newSpecificDate.value;
  if (!specificAvailability[dateStr]) {
    specificAvailability[dateStr] = ['09:00'];
  }
  
  newSpecificDate.value = '';
}

function addTimeToSpecificDate(date) {
  if (!specificAvailability[date]) {
    specificAvailability[date] = [];
  }
  specificAvailability[date].push('09:00');
}

function removeTimeFromSpecificDate(date, timeIndex) {
  specificAvailability[date].splice(timeIndex, 1);
  if (specificAvailability[date].length === 0) {
    removeSpecificDate(date);
  }
}

function removeSpecificDate(date) {
  delete specificAvailability[date];
}

// Métodos para gestionar bloqueos
function addBlockedDate() {
  if (!newBlockedDate.value) return;
  
  const dateStr = newBlockedDate.value;
  if (!blockedSlots[dateStr]) {
    blockedSlots[dateStr] = ['09:00'];
  }
  
  newBlockedDate.value = '';
}

function addBlockedTime(date) {
  if (!blockedSlots[date]) {
    blockedSlots[date] = [];
  }
  blockedSlots[date].push('09:00');
}

function removeBlockedTime(date, timeIndex) {
  blockedSlots[date].splice(timeIndex, 1);
  if (blockedSlots[date].length === 0) {
    removeBlockedDate(date);
  }
}

function removeBlockedDate(date) {
  delete blockedSlots[date];
}

function blockFullDay(date) {
  blockedSlots[date] = ['*'];
}

function unblockFullDay(date) {
  blockedSlots[date] = ['09:00'];
}

// Formatear fecha para mostrar
function formatDate(dateStr) {
  const [year, month, day] = dateStr.split('-');
  const date = new Date(parseInt(year), parseInt(month) - 1, parseInt(day));
  const dayOfWeek = weekdays[date.getDay()];
  return `${dayOfWeek}, ${day} de ${months[parseInt(month) - 1]} de ${year}`;
}

// Guardar cambios en el servidor
async function saveChanges() {
  try {
    await axios.post('/admin/availability', {
      weekly_hours: weeklyHours,
      specific_availability: specificAvailability,
      blocked_slots: blockedSlots
    });
    
    // Actualizar copias originales
    originalWeeklyHours = JSON.parse(JSON.stringify(weeklyHours));
    originalSpecificAvailability = JSON.parse(JSON.stringify(specificAvailability));
    originalBlockedSlots = JSON.parse(JSON.stringify(blockedSlots));
    
    alert('Configuración guardada correctamente');
  } catch (error) {
    console.error('Error al guardar la configuración:', error);
    alert('Error al guardar la configuración');
  }
}

// Cancelar cambios y restaurar valores originales
function resetChanges() {
  Object.assign(weeklyHours, JSON.parse(JSON.stringify(originalWeeklyHours)));
  Object.assign(specificAvailability, JSON.parse(JSON.stringify(originalSpecificAvailability)));
  Object.assign(blockedSlots, JSON.parse(JSON.stringify(originalBlockedSlots)));
}
</script> 