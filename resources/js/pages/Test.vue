<script setup>
import { router } from '@inertiajs/vue3';
</script>

<template>
    <div>
        <h1>Test</h1>
        <button @click="router.get(route('portfolio.index'))">Test</button>
    </div>
</template>