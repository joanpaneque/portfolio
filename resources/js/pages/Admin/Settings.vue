<script setup>
import AdminLayout from '../../layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const activeTab = ref('general');

const tabs = [
  { id: 'general', name: 'General', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' },
  { id: 'profile', name: 'Perfil', icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' },
  { id: 'security', name: 'Seguridad', icon: 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z' },
  { id: 'notifications', name: 'Notificaciones', icon: 'M15 17h5l-5 5v-5zM10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z' },
  { id: 'appearance', name: 'Apariencia', icon: 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4 4 4 0 004-4V5z' }
];

// Datos de ejemplo para configuraciones
const settings = ref({
  general: {
    siteName: 'Joan Paneque Portfolio',
    siteDescription: 'Desarrollador Full Stack especializado en Vue.js y Laravel',
    siteUrl: 'https://joanpaneque.dev',
    timezone: 'Europe/Madrid',
    language: 'es',
    maintenanceMode: false
  },
  profile: {
    name: 'Joan Paneque',
    email: 'joan@joanpaneque.dev',
    bio: 'Desarrollador Full Stack con más de 5 años de experiencia',
    location: 'Madrid, España',
    website: 'https://joanpaneque.dev',
    twitter: '@joanpaneque',
    github: 'joanpaneque',
    linkedin: 'joanpaneque'
  },
  security: {
    twoFactorEnabled: true,
    sessionTimeout: 120,
    passwordExpiry: 90,
    loginAttempts: 5,
    ipWhitelist: ['192.168.1.1', '10.0.0.1']
  },
  notifications: {
    emailNotifications: true,
    newComments: true,
    newUsers: false,
    systemUpdates: true,
    securityAlerts: true,
    weeklyReports: true
  },
  appearance: {
    theme: 'light',
    primaryColor: '#059669',
    accentColor: '#0891b2',
    showBranding: true,
    customCSS: ''
  }
});

const saveSettings = (section) => {
  // Aquí iría la lógica para guardar las configuraciones
  console.log(`Guardando configuraciones de ${section}:`, settings.value[section]);
  // Mostrar notificación de éxito
};

// Computed property for IP whitelist
const ipWhitelistText = computed({
  get: () => settings.value.security.ipWhitelist.join('\n'),
  set: (value) => {
    settings.value.security.ipWhitelist = value.split('\n').filter(ip => ip.trim() !== '');
  }
});
</script>

<template>
  <Head title="Configuración - Admin" />

  <AdminLayout>
    <template #title>Configuración</template>

    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-2xl font-bold text-gray-900">Configuración</h1>
      <p class="mt-2 text-sm text-gray-700">Gestiona la configuración del sistema y tu perfil</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
      <!-- Sidebar Navigation -->
      <div class="lg:col-span-1">
        <nav class="space-y-1">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            :class="[
              'w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-lg transition-colors text-left',
              activeTab === tab.id
                ? 'bg-emerald-100 text-emerald-700 border border-emerald-200'
                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'
            ]"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="tab.icon" />
            </svg>
            {{ tab.name }}
          </button>
        </nav>
      </div>

      <!-- Content Area -->
      <div class="lg:col-span-3">
        <!-- General Settings -->
        <div v-if="activeTab === 'general'" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Configuración General</h2>

          <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Sitio</label>
                <input
                  v-model="settings.general.siteName"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">URL del Sitio</label>
                <input
                  v-model="settings.general.siteUrl"
                  type="url"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Descripción del Sitio</label>
              <textarea
                v-model="settings.general.siteDescription"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
              ></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Zona Horaria</label>
                <select
                  v-model="settings.general.timezone"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
                  <option value="Europe/Madrid">Europa/Madrid</option>
                  <option value="America/New_York">América/Nueva York</option>
                  <option value="Asia/Tokyo">Asia/Tokio</option>
                </select>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Idioma</label>
                <select
                  v-model="settings.general.language"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
                  <option value="es">Español</option>
                  <option value="en">English</option>
                  <option value="fr">Français</option>
                </select>
              </div>
            </div>

            <div class="flex items-center">
              <input
                v-model="settings.general.maintenanceMode"
                type="checkbox"
                class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
              >
              <label class="ml-2 block text-sm text-gray-900">Modo de Mantenimiento</label>
            </div>
          </div>

          <div class="mt-6 pt-6 border-t border-gray-200">
            <button
              @click="saveSettings('general')"
              class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors"
            >
              Guardar Cambios
            </button>
          </div>
        </div>

        <!-- Profile Settings -->
        <div v-if="activeTab === 'profile'" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Configuración del Perfil</h2>

          <div class="space-y-6">
            <div class="flex items-center gap-6">
              <div class="w-20 h-20 bg-emerald-600 rounded-full flex items-center justify-center">
                <span class="text-2xl font-bold text-white">JP</span>
              </div>
              <div>
                <button class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                  Cambiar Avatar
                </button>
                <p class="text-sm text-gray-500 mt-1">JPG, PNG o GIF. Máximo 2MB.</p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                <input
                  v-model="settings.profile.name"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input
                  v-model="settings.profile.email"
                  type="email"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Biografía</label>
              <textarea
                v-model="settings.profile.bio"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
              ></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ubicación</label>
                <input
                  v-model="settings.profile.location"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sitio Web</label>
                <input
                  v-model="settings.profile.website"
                  type="url"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Twitter</label>
                <input
                  v-model="settings.profile.twitter"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">GitHub</label>
                <input
                  v-model="settings.profile.github"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">LinkedIn</label>
                <input
                  v-model="settings.profile.linkedin"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
              </div>
            </div>
          </div>

          <div class="mt-6 pt-6 border-t border-gray-200">
            <button
              @click="saveSettings('profile')"
              class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors"
            >
              Guardar Cambios
            </button>
          </div>
        </div>

        <!-- Security Settings -->
        <div v-if="activeTab === 'security'" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Configuración de Seguridad</h2>

          <div class="space-y-6">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
              <div>
                <h3 class="font-medium text-gray-900">Autenticación de Dos Factores</h3>
                <p class="text-sm text-gray-600">Añade una capa extra de seguridad a tu cuenta</p>
              </div>
              <input
                v-model="settings.security.twoFactorEnabled"
                type="checkbox"
                class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
              >
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tiempo de Sesión (minutos)</label>
                <input
                  v-model="settings.security.sessionTimeout"
                  type="number"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Expiración de Contraseña (días)</label>
                <input
                  v-model="settings.security.passwordExpiry"
                  type="number"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Intentos de Login Máximos</label>
              <input
                v-model="settings.security.loginAttempts"
                type="number"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
              >
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Lista Blanca de IPs</label>
              <textarea
                v-model="ipWhitelistText"
                rows="3"
                placeholder="Una IP por línea"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
              ></textarea>
            </div>

            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
              <div class="flex">
                <svg class="w-5 h-5 text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
                <div>
                  <h3 class="text-sm font-medium text-yellow-800">Cambiar Contraseña</h3>
                  <p class="text-sm text-yellow-700 mt-1">Se recomienda cambiar la contraseña regularmente</p>
                  <button class="mt-2 text-sm font-medium text-yellow-800 hover:text-yellow-900">
                    Cambiar Contraseña →
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="mt-6 pt-6 border-t border-gray-200">
            <button
              @click="saveSettings('security')"
              class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors"
            >
              Guardar Cambios
            </button>
          </div>
        </div>

        <!-- Notifications Settings -->
        <div v-if="activeTab === 'notifications'" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Configuración de Notificaciones</h2>

          <div class="space-y-6">
            <div class="space-y-4">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="font-medium text-gray-900">Notificaciones por Email</h3>
                  <p class="text-sm text-gray-600">Recibir notificaciones por correo electrónico</p>
                </div>
                <input
                  v-model="settings.notifications.emailNotifications"
                  type="checkbox"
                  class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                >
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <h3 class="font-medium text-gray-900">Nuevos Comentarios</h3>
                  <p class="text-sm text-gray-600">Notificar cuando hay nuevos comentarios</p>
                </div>
                <input
                  v-model="settings.notifications.newComments"
                  type="checkbox"
                  class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                >
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <h3 class="font-medium text-gray-900">Nuevos Usuarios</h3>
                  <p class="text-sm text-gray-600">Notificar cuando se registran nuevos usuarios</p>
                </div>
                <input
                  v-model="settings.notifications.newUsers"
                  type="checkbox"
                  class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                >
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <h3 class="font-medium text-gray-900">Actualizaciones del Sistema</h3>
                  <p class="text-sm text-gray-600">Notificar sobre actualizaciones importantes</p>
                </div>
                <input
                  v-model="settings.notifications.systemUpdates"
                  type="checkbox"
                  class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                >
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <h3 class="font-medium text-gray-900">Alertas de Seguridad</h3>
                  <p class="text-sm text-gray-600">Notificar sobre problemas de seguridad</p>
                </div>
                <input
                  v-model="settings.notifications.securityAlerts"
                  type="checkbox"
                  class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                >
              </div>

              <div class="flex items-center justify-between">
                <div>
                  <h3 class="font-medium text-gray-900">Reportes Semanales</h3>
                  <p class="text-sm text-gray-600">Recibir resumen semanal de actividad</p>
                </div>
                <input
                  v-model="settings.notifications.weeklyReports"
                  type="checkbox"
                  class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
                >
              </div>
            </div>
          </div>

          <div class="mt-6 pt-6 border-t border-gray-200">
            <button
              @click="saveSettings('notifications')"
              class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors"
            >
              Guardar Cambios
            </button>
          </div>
        </div>

        <!-- Appearance Settings -->
        <div v-if="activeTab === 'appearance'" class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Configuración de Apariencia</h2>

          <div class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Tema</label>
              <div class="grid grid-cols-3 gap-3">
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                  <input
                    v-model="settings.appearance.theme"
                    type="radio"
                    value="light"
                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300"
                  >
                  <span class="ml-2 text-sm font-medium text-gray-900">Claro</span>
                </label>
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                  <input
                    v-model="settings.appearance.theme"
                    type="radio"
                    value="dark"
                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300"
                  >
                  <span class="ml-2 text-sm font-medium text-gray-900">Oscuro</span>
                </label>
                <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                  <input
                    v-model="settings.appearance.theme"
                    type="radio"
                    value="auto"
                    class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300"
                  >
                  <span class="ml-2 text-sm font-medium text-gray-900">Auto</span>
                </label>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Color Primario</label>
                <input
                  v-model="settings.appearance.primaryColor"
                  type="color"
                  class="w-full h-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Color de Acento</label>
                <input
                  v-model="settings.appearance.accentColor"
                  type="color"
                  class="w-full h-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                >
              </div>
            </div>

            <div class="flex items-center">
              <input
                v-model="settings.appearance.showBranding"
                type="checkbox"
                class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
              >
              <label class="ml-2 block text-sm text-gray-900">Mostrar Branding</label>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">CSS Personalizado</label>
              <textarea
                v-model="settings.appearance.customCSS"
                rows="6"
                placeholder="/* Añade tu CSS personalizado aquí */"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-mono text-sm"
              ></textarea>
            </div>
          </div>

          <div class="mt-6 pt-6 border-t border-gray-200">
            <button
              @click="saveSettings('appearance')"
              class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors"
            >
              Guardar Cambios
            </button>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
