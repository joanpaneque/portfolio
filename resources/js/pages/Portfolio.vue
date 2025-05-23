<script setup>
import { router } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <h1>Portfolio</h1>
        <button @click="router.get(route('test'))">Test</button>
    </div>
</template>
