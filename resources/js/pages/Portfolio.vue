<script setup>
import JoanLayout from '../layouts/joanpaneque.dev/JoanLayout.vue';
import JoanHero from '../components/joanpaneque.dev/JoanHero.vue';
import JoanSkills from '../components/joanpaneque.dev/JoanSkills.vue';
import JoanCompanies from '../components/joanpaneque.dev/JoanCompanies.vue';
import JoanProjects from '../components/joanpaneque.dev/JoanProjects.vue';
import JoanAwards from '../components/joanpaneque.dev/JoanAwards.vue';
import JoanAbout from '../components/joanpaneque.dev/JoanAbout.vue';
import JoanContact from '../components/joanpaneque.dev/JoanContact.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>

    <Head title="Test" />
    <JoanLayout>
        <JoanHero />
        <JoanSkills />
        <JoanCompanies />
        <JoanProjects />
        <JoanAwards />
        <JoanAbout />
        <JoanContact />
    </JoanLayout>
</template>
