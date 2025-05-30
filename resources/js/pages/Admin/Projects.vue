<script setup>
import AdminLayout from '../../layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const searchQuery = ref('');

// Datos de ejemplo para proyectos
const projects = [
  {
    id: 1,
    title: 'E-commerce Platform',
    description: 'Plataforma completa de comercio electrónico con Vue.js y Laravel',
    status: 'active',
    progress: 85,
    technologies: ['Vue.js', 'Laravel', 'Tailwind'],
    startDate: '2024-01-01',
    endDate: '2024-03-15',
    image: '/images/project1.jpg'
  },
  {
    id: 2,
    title: 'Dashboard Analytics',
    description: 'Panel de control con métricas y análisis en tiempo real',
    status: 'completed',
    progress: 100,
    technologies: ['React', 'Node.js', 'D3.js'],
    startDate: '2023-11-01',
    endDate: '2024-01-10',
    image: '/images/project2.jpg'
  },
  {
    id: 3,
    title: 'Mobile App',
    description: 'Aplicación móvil para gestión de tareas y productividad',
    status: 'planning',
    progress: 15,
    technologies: ['React Native', 'Firebase'],
    startDate: '2024-02-01',
    endDate: '2024-06-30',
    image: '/images/project3.jpg'
  },
  {
    id: 4,
    title: 'API Gateway',
    description: 'Microservicio para gestión de APIs y autenticación',
    status: 'active',
    progress: 60,
    technologies: ['Node.js', 'Docker', 'Redis'],
    startDate: '2024-01-15',
    endDate: '2024-04-01',
    image: '/images/project4.jpg'
  }
];

const getStatusColor = (status) => {
  const colors = {
    'active': 'bg-blue-100 text-blue-800',
    'completed': 'bg-green-100 text-green-800',
    'planning': 'bg-yellow-100 text-yellow-800',
    'paused': 'bg-red-100 text-red-800'
  };
  return colors[status] || 'bg-gray-100 text-gray-800';
};

const getStatusText = (status) => {
  const texts = {
    'active': 'En Progreso',
    'completed': 'Completado',
    'planning': 'Planificación',
    'paused': 'Pausado'
  };
  return texts[status] || status;
};

const getProgressColor = (progress) => {
  if (progress >= 80) return 'bg-green-500';
  if (progress >= 50) return 'bg-blue-500';
  if (progress >= 25) return 'bg-yellow-500';
  return 'bg-red-500';
};
</script>

<template>
  <Head title="Proyectos - Admin" />

  <AdminLayout>
    <template #title>Gestión de Proyectos</template>

    <!-- Header -->
    <div class="mb-8">
      <div class="sm:flex sm:items-center sm:justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Proyectos</h1>
          <p class="mt-2 text-sm text-gray-700">Gestiona todos los proyectos del portafolio</p>
        </div>
        <div class="mt-4 sm:mt-0">
          <Link href="/admin/projects/create"
                class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Nuevo Proyecto
          </Link>
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
              placeholder="Buscar proyectos..."
              class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
            >
            <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
          <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            <option value="">Todos los estados</option>
            <option value="active">En Progreso</option>
            <option value="completed">Completado</option>
            <option value="planning">Planificación</option>
            <option value="paused">Pausado</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tecnología</label>
          <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            <option value="">Todas las tecnologías</option>
            <option value="vue">Vue.js</option>
            <option value="react">React</option>
            <option value="laravel">Laravel</option>
            <option value="node">Node.js</option>
          </select>
        </div>
        <div class="flex items-end">
          <button class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-lg font-medium hover:bg-gray-200 transition-colors">
            Limpiar Filtros
          </button>
        </div>
      </div>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div v-for="project in projects" :key="project.id"
           class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
        <!-- Project Image -->
        <div class="h-48 bg-gradient-to-br from-emerald-500 to-blue-600 relative">
          <div class="absolute inset-0 bg-black/20"></div>
          <div class="absolute top-4 right-4">
            <span :class="['inline-flex px-2 py-1 text-xs font-semibold rounded-full', getStatusColor(project.status)]">
              {{ getStatusText(project.status) }}
            </span>
          </div>
          <div class="absolute bottom-4 left-4 right-4">
            <h3 class="text-xl font-bold text-white mb-2">{{ project.title }}</h3>
            <div class="flex flex-wrap gap-1">
              <span v-for="tech in project.technologies" :key="tech"
                    class="inline-flex px-2 py-1 text-xs font-medium bg-white/20 text-white rounded">
                {{ tech }}
              </span>
            </div>
          </div>
        </div>

        <!-- Project Content -->
        <div class="p-6">
          <p class="text-gray-600 text-sm mb-4">{{ project.description }}</p>

          <!-- Progress -->
          <div class="mb-4">
            <div class="flex items-center justify-between mb-2">
              <span class="text-sm font-medium text-gray-700">Progreso</span>
              <span class="text-sm font-medium text-gray-900">{{ project.progress }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div :class="['h-2 rounded-full transition-all duration-300', getProgressColor(project.progress)]"
                   :style="{ width: `${project.progress}%` }"></div>
            </div>
          </div>

          <!-- Dates -->
          <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
            <div>
              <span class="font-medium">Inicio:</span> {{ project.startDate }}
            </div>
            <div>
              <span class="font-medium">Fin:</span> {{ project.endDate }}
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <div class="flex items-center gap-2">
              <Link :href="`/admin/projects/${project.id}`"
                    class="text-emerald-600 hover:text-emerald-700 p-2 rounded-lg hover:bg-emerald-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </Link>
              <Link :href="`/admin/projects/${project.id}/edit`"
                    class="text-blue-600 hover:text-blue-700 p-2 rounded-lg hover:bg-blue-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </Link>
              <button class="text-red-600 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
            <Link :href="`/admin/projects/${project.id}/edit`"
                  class="text-sm font-medium text-emerald-600 hover:text-emerald-700">
              Editar →
            </Link>
          </div>
        </div>
      </div>

      <!-- Add New Project Card -->
      <Link href="/admin/projects/create"
            class="bg-white rounded-xl shadow-sm border-2 border-dashed border-gray-300 hover:border-emerald-400 transition-colors group">
        <div class="h-full flex flex-col items-center justify-center p-8 text-center">
          <div class="w-16 h-16 bg-gray-100 group-hover:bg-emerald-100 rounded-full flex items-center justify-center mb-4 transition-colors">
            <svg class="w-8 h-8 text-gray-400 group-hover:text-emerald-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 group-hover:text-emerald-600 transition-colors">Nuevo Proyecto</h3>
          <p class="text-sm text-gray-500 mt-2">Crear un nuevo proyecto en el portafolio</p>
        </div>
      </Link>
    </div>

    <!-- Stats Summary -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center">
          <div class="p-3 bg-blue-100 rounded-lg">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Proyectos</p>
            <p class="text-2xl font-bold text-gray-900">{{ projects.length }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center">
          <div class="p-3 bg-green-100 rounded-lg">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Completados</p>
            <p class="text-2xl font-bold text-gray-900">{{ projects.filter(p => p.status === 'completed').length }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center">
          <div class="p-3 bg-yellow-100 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">En Progreso</p>
            <p class="text-2xl font-bold text-gray-900">{{ projects.filter(p => p.status === 'active').length }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center">
          <div class="p-3 bg-purple-100 rounded-lg">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Planificación</p>
            <p class="text-2xl font-bold text-gray-900">{{ projects.filter(p => p.status === 'planning').length }}</p>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
