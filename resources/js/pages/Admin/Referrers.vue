<script setup>
import AdminLayout from '../../layouts/AdminLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// Props from backend
const props = defineProps({
  referrers: {
    type: Array,
    default: () => []
  }
});

const searchQuery = ref('');
const copiedId = ref(null);
const deletingId = ref(null);

// Helper functions to detect device and country from user agent and IP
const detectDevice = (userAgent) => {
  if (!userAgent) return 'Desktop';

  const ua = userAgent.toLowerCase();
  if (ua.includes('mobile') || ua.includes('android') || ua.includes('iphone')) {
    return 'Mobile';
  } else if (ua.includes('tablet') || ua.includes('ipad')) {
    return 'Tablet';
  }
  return 'Desktop';
};

const detectCountry = (ip) => {
  // En producción esto debería usar una API de geolocalización
  // Por ahora devolvemos un valor por defecto
  return 'N/A';
};

// Computed property to process referrers data
const processedReferrers = computed(() => {
  return props.referrers.map(referrer => ({
    ...referrer,
    device: detectDevice(referrer.user_agent),
    country: detectCountry(referrer.ip)
  }));
});

// Copy to clipboard functionality
const copyReferrerInfo = async (referrer) => {
  const referrerInfo = `
INFORMACIÓN DEL REFERRER
========================

ID: ${referrer.id}
From ID: ${referrer.from_id || 'N/A'}
IP: ${referrer.ip || 'N/A'}
País: ${referrer.country}
Dispositivo: ${referrer.device}
URL Destino: ${referrer.destination_url || 'N/A'}
User Agent: ${referrer.user_agent || 'N/A'}
Fecha de Creación: ${formatDate(referrer.created_at)}
Fecha de Actualización: ${formatDate(referrer.updated_at)}

========================
Copiado desde Panel de Admin - ${new Date().toLocaleString('es-ES')}
  `.trim();

  try {
    await navigator.clipboard.writeText(referrerInfo);
    copiedId.value = referrer.id;

    // Reset the copied state after 2 seconds
    setTimeout(() => {
      copiedId.value = null;
    }, 2000);
  } catch (err) {
    // Fallback for older browsers
    const textArea = document.createElement('textarea');
    textArea.value = referrerInfo;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
      document.execCommand('copy');
      copiedId.value = referrer.id;
      setTimeout(() => {
        copiedId.value = null;
      }, 2000);
    } catch (err) {
      console.error('Error al copiar al portapapeles:', err);
      alert('Error al copiar la información. Por favor, inténtalo de nuevo.');
    } finally {
      document.body.removeChild(textArea);
    }
  }
};

// Delete referrer functionality
const deleteReferrer = async (referrer) => {
  const fromIdText = referrer.from_id || `ID ${referrer.id}`;

  if (!confirm(`¿Estás seguro de que quieres eliminar el referrer "${fromIdText}"?\n\nEsta acción no se puede deshacer.`)) {
    return;
  }

  deletingId.value = referrer.id;

  try {
    const response = await fetch(`/admin/referrers/${referrer.id}`, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
        'Accept': 'application/json',
      },
    });

    const data = await response.json();

    if (data.success) {
      // Reload the page to refresh the data
      router.reload({ only: ['referrers'] });
    } else {
      alert(data.message || 'Error al eliminar el referrer');
    }
  } catch (error) {
    console.error('Error al eliminar referrer:', error);
    alert('Error de conexión al eliminar el referrer. Por favor, inténtalo de nuevo.');
  } finally {
    deletingId.value = null;
  }
};

const getDeviceColor = (device) => {
  const colors = {
    'Desktop': 'bg-blue-100 text-blue-800',
    'Mobile': 'bg-green-100 text-green-800',
    'Tablet': 'bg-purple-100 text-purple-800'
  };
  return colors[device] || 'bg-gray-100 text-gray-800';
};

const getDeviceIcon = (device) => {
  const icons = {
    'Desktop': 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
    'Mobile': 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z',
    'Tablet': 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'
  };
  return icons[device] || icons['Desktop'];
};

const formatDate = (dateString) => {
  if (!dateString) return 'N/A';
  const date = new Date(dateString);
  return date.toLocaleDateString('es-ES', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

const truncateUrl = (url, maxLength = 50) => {
  if (!url) return '';
  return url.length > maxLength ? url.substring(0, maxLength) + '...' : url;
};

const truncateUserAgent = (userAgent, maxLength = 60) => {
  if (!userAgent) return '';
  return userAgent.length > maxLength ? userAgent.substring(0, maxLength) + '...' : userAgent;
};

// Stats computed properties
const totalReferrers = computed(() => processedReferrers.value.length);
const mobileCount = computed(() => processedReferrers.value.filter(r => r.device === 'Mobile').length);
const desktopCount = computed(() => processedReferrers.value.filter(r => r.device === 'Desktop').length);
const todayCount = computed(() => {
  const today = new Date().toISOString().split('T')[0];
  return processedReferrers.value.filter(r => r.created_at && r.created_at.includes(today)).length;
});
</script>

<template>
  <Head title="Referrers - Admin" />

  <AdminLayout>
    <template #title>Gestión de Referrers</template>

    <!-- Header -->
    <div class="mb-8">
      <div class="sm:flex sm:items-center sm:justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Referrers</h1>
          <p class="mt-2 text-sm text-gray-700">Monitorea todas las conexiones de referencia del sistema</p>
        </div>
        <div class="mt-4 sm:mt-0">
          <div class="flex items-center gap-4">
            <div class="bg-white rounded-lg px-4 py-2 border border-gray-200">
              <div class="flex items-center gap-2">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="text-sm font-medium text-gray-700">{{ totalReferrers }} conexiones registradas</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Referrers</p>
            <p class="text-2xl font-bold text-gray-900">{{ totalReferrers }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Mobile</p>
            <p class="text-2xl font-bold text-gray-900">{{ mobileCount }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center">
          <div class="p-2 bg-purple-100 rounded-lg">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Desktop</p>
            <p class="text-2xl font-bold text-gray-900">{{ desktopCount }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center">
          <div class="p-2 bg-orange-100 rounded-lg">
            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Hoy</p>
            <p class="text-2xl font-bold text-gray-900">{{ todayCount }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Buscar por from_id, IP o URL..."
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
            >
            <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Dispositivo</label>
          <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            <option value="">Todos los dispositivos</option>
            <option value="desktop">Desktop</option>
            <option value="mobile">Mobile</option>
            <option value="tablet">Tablet</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">País</label>
          <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            <option value="">Todos los países</option>
            <option value="spain">España</option>
            <option value="mexico">México</option>
            <option value="argentina">Argentina</option>
            <option value="colombia">Colombia</option>
            <option value="chile">Chile</option>
          </select>
        </div>
        <div class="flex items-end">
          <button class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors">
            Limpiar Filtros
          </button>
        </div>
      </div>
    </div>

    <!-- Referrers Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
      <div v-if="processedReferrers.length === 0" class="p-8 text-center">
        <div class="flex flex-col items-center">
          <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
          </svg>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No hay referrers registrados</h3>
          <p class="text-gray-500">Los referrers aparecerán aquí cuando los usuarios accedan con parámetros 'from'.</p>
        </div>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP / País</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dispositivo</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">URL Destino</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="referrer in processedReferrers" :key="referrer.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="p-2 bg-emerald-100 rounded-lg mr-3">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                    </svg>
                  </div>
                  <div>
                    <div class="text-sm font-medium text-gray-900">{{ referrer.from_id || 'N/A' }}</div>
                    <div class="text-sm text-gray-500">ID: {{ referrer.id }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ referrer.ip || 'N/A' }}</div>
                <div class="text-sm text-gray-500">{{ referrer.country }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="['inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-full', getDeviceColor(referrer.device)]">
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getDeviceIcon(referrer.device)" />
                  </svg>
                  {{ referrer.device }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900" :title="referrer.destination_url">
                  {{ truncateUrl(referrer.destination_url) }}
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-500" :title="referrer.user_agent">
                  {{ truncateUserAgent(referrer.user_agent) }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(referrer.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex items-center justify-end gap-2">
                  <button class="text-emerald-600 hover:text-emerald-900 p-1 rounded" :title="`Ver detalles de ${referrer.from_id}`">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                  <button
                    @click="copyReferrerInfo(referrer)"
                    :class="[
                      'p-1 rounded transition-all duration-200',
                      copiedId === referrer.id
                        ? 'text-green-600 hover:text-green-900 bg-green-50'
                        : 'text-blue-600 hover:text-blue-900'
                    ]"
                    :title="copiedId === referrer.id ? 'Información copiada!' : `Copiar información completa de ${referrer.from_id || 'referrer'}`"
                  >
                    <svg v-if="copiedId === referrer.id" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                  </button>
                  <button
                    @click="deleteReferrer(referrer)"
                    :disabled="deletingId === referrer.id"
                    :class="[
                      'p-1 rounded transition-all duration-200',
                      deletingId === referrer.id
                        ? 'text-gray-400 cursor-not-allowed'
                        : 'text-red-600 hover:text-red-900 hover:bg-red-50'
                    ]"
                    :title="deletingId === referrer.id ? 'Eliminando...' : `Eliminar referrer ${referrer.from_id || 'ID ' + referrer.id}`"
                  >
                    <svg v-if="deletingId === referrer.id" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div v-if="processedReferrers.length > 0" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        <div class="flex items-center justify-between">
          <div class="flex-1 flex justify-between sm:hidden">
            <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
              Anterior
            </button>
            <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
              Siguiente
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Mostrando <span class="font-medium">1</span> a <span class="font-medium">{{ processedReferrers.length }}</span> de
                <span class="font-medium">{{ processedReferrers.length }}</span> resultados
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                  <span class="sr-only">Anterior</span>
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
                <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                  1
                </button>
                <button class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                  <span class="sr-only">Siguiente</span>
                  <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                  </svg>
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
