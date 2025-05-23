<script setup>
console.log($trans('hello')['world']);
</script>

<template>
    <div>
        <h1>Portfolio</h1>
        <p>{{ $trans('hello')['world'] }}</p>
    </div>
</template>
