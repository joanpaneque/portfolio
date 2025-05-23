<script setup>
import { router } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <h1>Outside</h1>
        {{ route('test') }}
        <button @click="router.get(route('test'))">Portfolio</button>
    </div>
</template>

<style scoped>
</style>