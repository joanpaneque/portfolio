<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from './Layouts/AdminLayout.vue';
</script>

<template>
    <Head title="Panel de Administración" />

    <AdminLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h1 class="mb-4 text-2xl font-bold">
                            Panel de Administración
                        </h1>
                        <p>Bienvenido al panel de administración.</p>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
