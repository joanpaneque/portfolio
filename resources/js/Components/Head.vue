<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

interface Props {
  title?: string;
  description?: string;
  keywords?: string;
  image?: string;
  canonicalUrl?: string;
}

const props = withDefaults(defineProps<Props>(), {
  title: '',
  description: '',
  keywords: '',
  image: '/images/preview.jpg',
  canonicalUrl: ''
});

const { t, locale } = useI18n();

// Calcular título completo con el nombre del sitio
const fullTitle = computed(() => {
  const baseTitle = props.title || t('app.name');
  return `${baseTitle} - Joan Paneque`;
});

// Calcular descripción localizada
const metaDescription = computed(() => {
  return props.description || t('meta.description');
});

// Calcular palabras clave localizadas
const metaKeywords = computed(() => {
  return props.keywords || t('meta.keywords');
});

// Calcular URL canónica
const canonical = computed(() => {
  if (props.canonicalUrl) return props.canonicalUrl;
  
  const baseUrl = 'https://joanpaneque.dev';
  // Ahora todas las URLs incluyen el código de idioma, incluido el español
  const langPrefix = `/${locale.value}`;
  
  // Usamos solo el prefijo de idioma sin intentar combinar con window.location.pathname
  return `${baseUrl}${langPrefix}`;
});

// Construir objeto de metadatos para pasarlo a la página
const meta = {
  title: fullTitle.value,
  description: metaDescription.value,
  keywords: metaKeywords.value,
  image: props.image,
  canonical: canonical.value
};

defineExpose({
  meta
});
</script>

<template>
  <Head>
    <title>{{ fullTitle }}</title>
    <meta name="description" :content="metaDescription">
    <meta name="keywords" :content="metaKeywords">
    <link rel="canonical" :href="canonical">
  </Head>
</template> 