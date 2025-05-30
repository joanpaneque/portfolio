<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const isMenuOpen = ref(false);
const page = usePage();

const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value;
};

const closeMenu = () => {
  isMenuOpen.value = false;
};

const adminRoutes = [
  {
    name: 'Dashboard',
    href: '/admin/dashboard',
    icon: 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z'
  },
  {
    name: 'Usuarios',
    href: '/admin/users',
    icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z'
  },
  {
    name: 'Proyectos',
    href: '/admin/projects',
    icon: 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'
  },
  {
    name: 'Blog',
    href: '/admin/blog',
    icon: 'M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z'
  },
  {
    name: 'Configuración',
    href: '/admin/settings',
    icon: 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z'
  }
];

const isCurrentRoute = (href) => {
  return page.url.startsWith(href);
};
</script>

<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <div class="fixed inset-y-0 left-0 z-50 w-64 bg-[#002626] transform transition-transform duration-300 ease-in-out lg:translate-x-0" :class="{ '-translate-x-full': !isMenuOpen, 'translate-x-0': isMenuOpen }">
      <div class="flex items-center justify-center h-16 px-4 border-b border-white/10">
        <Link href="/" class="flex items-center gap-2 text-white">
          <img alt="Logo" width="32" height="32" src="/images/logo.svg" />
          <span class="text-xl font-bold">Admin Panel</span>
        </Link>
      </div>

      <nav class="mt-8 px-4">
        <div class="space-y-2">
          <Link
            v-for="route in adminRoutes"
            :key="route.name"
            :href="route.href"
            class="flex items-center gap-3 px-4 py-3 text-white/80 rounded-lg hover:bg-white/10 hover:text-white transition-colors group"
            :class="{ 'bg-emerald-600 text-white': isCurrentRoute(route.href) }"
            @click="closeMenu"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="route.icon" />
            </svg>
            <span class="font-medium">{{ route.name }}</span>
          </Link>
        </div>
      </nav>

      <div class="absolute bottom-4 left-4 right-4">
        <Link href="/" class="flex items-center gap-3 px-4 py-3 text-white/60 rounded-lg hover:bg-white/10 hover:text-white transition-colors">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          <span class="font-medium">Volver al sitio</span>
        </Link>
      </div>
    </div>

    <!-- Mobile overlay -->
    <div v-if="isMenuOpen" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" @click="closeMenu"></div>

    <!-- Main content -->
    <div class="lg:pl-64">
      <!-- Top bar -->
      <div class="sticky top-0 z-30 flex h-16 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
        <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="toggleMenu">
          <span class="sr-only">Abrir sidebar</span>
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
          </svg>
        </button>

        <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
          <div class="flex items-center gap-x-4 lg:gap-x-6">
            <h1 class="text-xl font-semibold text-gray-900">
              <slot name="title">Panel de Administración</slot>
            </h1>
          </div>

          <div class="flex items-center gap-x-4 lg:gap-x-6 ml-auto">
            <div class="flex items-center gap-2">
              <div class="h-8 w-8 rounded-full bg-emerald-600 flex items-center justify-center">
                <span class="text-sm font-medium text-white">A</span>
              </div>
              <span class="text-sm font-medium text-gray-700">Admin</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Page content -->
      <main class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <slot />
        </div>
      </main>
    </div>
  </div>
</template>
