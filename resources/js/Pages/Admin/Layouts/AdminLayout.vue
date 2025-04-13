<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const showingNavigationDropdown = ref(false);

const logout = () => {
    router.post(route('admin.logout'));
};
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <nav
            class="border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800"
        >
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('admin.dashboard')">
                                <ApplicationLogo class="block h-9 w-auto" />
                            </Link>
                        </div>

                        <div
                            class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex"
                        >
                            <Link
                                :href="route('admin.dashboard')"
                                class="inline-flex items-center border-b-2 border-indigo-400 px-1 pt-1 text-sm font-medium leading-5 text-gray-900 transition duration-150 ease-in-out focus:border-indigo-700 focus:outline-none dark:text-gray-100"
                            >
                                Dashboard
                            </Link>
                            <Link
                                :href="route('admin.availability')"
                                class="inline-flex items-center border-b-2 border-transparent px-1 pt-1 text-sm font-medium leading-5 text-gray-500 transition duration-150 ease-in-out hover:border-gray-300 hover:text-gray-700 focus:border-gray-300 focus:text-gray-700 focus:outline-none dark:text-gray-400 dark:hover:text-gray-200"
                            >
                                Gestionar Disponibilidad
                            </Link>
                        </div>
                    </div>

                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <div class="relative ml-3">
                            <button
                                @click="logout"
                                class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                            >
                                Cerrar sesión
                            </button>
                        </div>
                    </div>

                    <div class="-mr-2 flex items-center sm:hidden">
                        <button
                            @click="
                                showingNavigationDropdown =
                                    !showingNavigationDropdown
                            "
                            class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-900 dark:hover:text-gray-400 dark:focus:bg-gray-900 dark:focus:text-gray-400"
                        >
                            <svg
                                class="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex':
                                            !showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex':
                                            showingNavigationDropdown,
                                    }"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <div
                :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }"
                class="sm:hidden"
            >
                <div class="space-y-1 pb-3 pt-2">
                    <Link
                        :href="route('admin.dashboard')"
                        class="block border-l-4 border-indigo-400 bg-indigo-50 py-2 pl-3 pr-4 text-base font-medium text-indigo-700 transition duration-150 ease-in-out focus:border-indigo-700 focus:bg-indigo-100 focus:text-indigo-800 focus:outline-none dark:bg-gray-800 dark:text-indigo-300 dark:focus:border-indigo-600 dark:focus:bg-gray-900 dark:focus:text-indigo-200"
                    >
                        Dashboard
                    </Link>
                    <Link
                        :href="route('admin.availability')"
                        class="block border-l-4 border-transparent py-2 pl-3 pr-4 text-base font-medium text-gray-600 transition duration-150 ease-in-out hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800 focus:border-gray-300 focus:bg-gray-50 focus:text-gray-800 focus:outline-none dark:text-gray-400 dark:hover:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-200"
                    >
                        Gestionar Disponibilidad
                    </Link>
                </div>

                <div
                    class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600"
                >
                    <div class="px-4">
                        <button
                            @click="logout"
                            class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100"
                        >
                            Cerrar sesión
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            <slot />
        </main>
    </div>
</template>
