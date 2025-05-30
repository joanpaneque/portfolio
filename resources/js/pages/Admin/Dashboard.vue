<script setup>
import AdminLayout from '../../layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';

// Datos de ejemplo para las estadísticas
const stats = [
  {
    name: 'Total Usuarios',
    value: '1,234',
    change: '+12%',
    changeType: 'positive',
    icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z'
  },
  {
    name: 'Proyectos Activos',
    value: '23',
    change: '+5%',
    changeType: 'positive',
    icon: 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'
  },
  {
    name: 'Posts del Blog',
    value: '89',
    change: '+18%',
    changeType: 'positive',
    icon: 'M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z'
  },
  {
    name: 'Visitas del Mes',
    value: '12,543',
    change: '-2%',
    changeType: 'negative',
    icon: 'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z'
  }
];

const quickActions = [
  {
    name: 'Crear Usuario',
    description: 'Añadir un nuevo usuario al sistema',
    href: '/admin/users/create',
    icon: 'M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z',
    color: 'bg-blue-500'
  },
  {
    name: 'Nuevo Proyecto',
    description: 'Crear un nuevo proyecto',
    href: '/admin/projects/create',
    icon: 'M12 10.5v6m3-3H9m4.06-7.19l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z',
    color: 'bg-green-500'
  },
  {
    name: 'Escribir Post',
    description: 'Crear una nueva entrada del blog',
    href: '/admin/blog/create',
    icon: 'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125',
    color: 'bg-purple-500'
  },
  {
    name: 'Ver Configuración',
    description: 'Gestionar configuración del sistema',
    href: '/admin/settings',
    icon: 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z',
    color: 'bg-gray-500'
  }
];

const recentActivity = [
  {
    id: 1,
    type: 'user',
    message: 'Nuevo usuario registrado: María García',
    time: 'Hace 2 horas',
    icon: 'M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM3 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 019.374 21c-2.331 0-4.512-.645-6.374-1.766z'
  },
  {
    id: 2,
    type: 'project',
    message: 'Proyecto "E-commerce App" actualizado',
    time: 'Hace 4 horas',
    icon: 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'
  },
  {
    id: 3,
    type: 'blog',
    message: 'Nuevo post publicado: "Vue.js 3 Tips"',
    time: 'Hace 1 día',
    icon: 'M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z'
  }
];
</script>

<template>
  <Head title="Dashboard - Admin" />

  <AdminLayout>
    <template #title>Dashboard</template>

    <!-- Welcome Section -->
    <div class="mb-8">
      <div class="bg-gradient-to-r from-[#002626] to-emerald-800 rounded-2xl p-8 text-white">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold mb-2">¡Bienvenido de vuelta!</h1>
            <p class="text-emerald-100 text-lg">Aquí tienes un resumen de tu panel de administración</p>
          </div>
          <div class="hidden md:block">
            <div class="w-24 h-24 bg-white/10 rounded-full flex items-center justify-center">
              <svg class="w-12 h-12 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div v-for="stat in stats" :key="stat.name" class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-sm font-medium text-gray-600">{{ stat.name }}</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">{{ stat.value }}</p>
            <div class="flex items-center mt-2">
              <span :class="[
                'text-sm font-medium',
                stat.changeType === 'positive' ? 'text-green-600' : 'text-red-600'
              ]">
                {{ stat.change }}
              </span>
              <span class="text-sm text-gray-500 ml-1">vs mes anterior</span>
            </div>
          </div>
          <div class="p-3 bg-gray-50 rounded-lg">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="stat.icon" />
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
      <div class="lg:col-span-2">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Acciones Rápidas</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <a v-for="action in quickActions" :key="action.name" :href="action.href"
             class="group bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200 hover:border-emerald-200">
            <div class="flex items-start gap-4">
              <div :class="[action.color, 'p-3 rounded-lg text-white group-hover:scale-110 transition-transform duration-200']">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="action.icon" />
                </svg>
              </div>
              <div class="flex-1">
                <h3 class="font-semibold text-gray-900 group-hover:text-emerald-600 transition-colors">{{ action.name }}</h3>
                <p class="text-sm text-gray-600 mt-1">{{ action.description }}</p>
              </div>
              <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </div>
          </a>
        </div>
      </div>

      <!-- Recent Activity -->
      <div>
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Actividad Reciente</h2>
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
          <div class="p-6">
            <div class="space-y-4">
              <div v-for="activity in recentActivity" :key="activity.id" class="flex items-start gap-3">
                <div class="p-2 bg-gray-50 rounded-lg">
                  <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="activity.icon" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm text-gray-900">{{ activity.message }}</p>
                  <p class="text-xs text-gray-500 mt-1">{{ activity.time }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="px-6 py-3 bg-gray-50 rounded-b-xl">
            <a href="/admin/activity" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
              Ver toda la actividad →
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- System Status -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
      <h2 class="text-xl font-semibold text-gray-900 mb-6">Estado del Sistema</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="flex items-center gap-3">
          <div class="w-3 h-3 bg-green-500 rounded-full"></div>
          <div>
            <p class="font-medium text-gray-900">Servidor</p>
            <p class="text-sm text-gray-600">Funcionando correctamente</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="w-3 h-3 bg-green-500 rounded-full"></div>
          <div>
            <p class="font-medium text-gray-900">Base de Datos</p>
            <p class="text-sm text-gray-600">Conexión estable</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
          <div>
            <p class="font-medium text-gray-900">Almacenamiento</p>
            <p class="text-sm text-gray-600">75% utilizado</p>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
