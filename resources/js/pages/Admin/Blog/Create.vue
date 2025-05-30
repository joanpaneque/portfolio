<script setup>
import AdminLayout from '../../../layouts/AdminLayout.vue';
import FormInput from '../../../components/Admin/FormInput.vue';
import FormTextarea from '../../../components/Admin/FormTextarea.vue';
import FormSelect from '../../../components/Admin/FormSelect.vue';
import FormCheckbox from '../../../components/Admin/FormCheckbox.vue';
import FormSlugInput from '../../../components/Admin/FormSlugInput.vue';
import FormFileUpload from '../../../components/Admin/FormFileUpload.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

// Form data
const form = ref({
  language: 'es',
  title: '',
  slug: '',
  excerpt: '',
  content: '',
  category: '',
  tags: '',
  featuredImage: null,
  status: 'draft',
  featured: false,
  publishedAt: '',
  metaTitle: '',
  metaDescription: ''
});

// Available categories
const categories = [
  'Frontend',
  'Backend',
  'CSS',
  'JavaScript',
  'Vue.js',
  'Laravel',
  'Performance',
  'Tutorial',
  'Tips'
];

// Status options
const statusOptions = [
  { value: 'draft', label: 'Borrador' },
  { value: 'published', label: 'Publicado' },
  { value: 'scheduled', label: 'Programado' },
  { value: 'archived', label: 'Archivado' }
];

// Language options
const languageOptions = [
  { value: 'es', label: 'Español' },
  { value: 'en', label: 'English' },
  { value: 'ca', label: 'Català' },
  { value: 'fr', label: 'Français' },
  { value: 'de', label: 'Deutsch' },
  { value: 'it', label: 'Italiano' },
  { value: 'pt', label: 'Português' }
];

// Generate slug from title
const generateSlug = () => {
  form.value.slug = form.value.title
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-')
    .trim('-');
};

// Handle file upload
const handleImageUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    form.value.featuredImage = file;
  }
};

// Save post
const savePost = (status = null) => {
  if (status) {
    form.value.status = status;
  }

  // Aquí iría la lógica para guardar el post
  console.log('Guardando post:', form.value);

  // Simular guardado exitoso
  alert(`Post ${status === 'published' ? 'publicado' : 'guardado'} correctamente`);
};

// Preview post
const previewPost = () => {
  // Aquí iría la lógica para mostrar vista previa
  console.log('Vista previa del post');
};
</script>

<template>
  <Head title="Crear Post - Admin" />

  <AdminLayout>
    <template #title>Crear Nuevo Post</template>

    <!-- Header -->
    <div class="mb-8">
      <div class="sm:flex sm:items-center sm:justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Crear Nuevo Post</h1>
          <p class="mt-2 text-sm text-gray-700">Crea una nueva entrada para el blog</p>
        </div>
        <div class="mt-4 sm:mt-0">
          <Link href="/admin/blog"
                class="inline-flex items-center gap-2 bg-gray-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-700 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver al Blog
          </Link>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Main Content -->
      <div class="lg:col-span-2 space-y-6">
        <!-- Basic Information -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Información Básica</h2>

          <div class="space-y-6">
            <!-- Language -->
            <FormSelect
              v-model="form.language"
              label="Idioma del Post"
              :options="languageOptions"
              required
            />

            <!-- Title -->
            <FormInput
              v-model="form.title"
              @update:model-value="generateSlug"
              label="Título"
              placeholder="Título del post"
              required
            />

            <!-- Slug -->
            <FormSlugInput
              v-model="form.slug"
              label="URL (Slug)"
              prefix="/blog/"
              placeholder="url-del-post"
            />

            <!-- Excerpt -->
            <FormTextarea
              v-model="form.excerpt"
              label="Extracto"
              placeholder="Breve descripción del post..."
              help-text="Máximo 160 caracteres recomendado"
              :rows="3"
            />
          </div>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Contenido</h2>

          <FormTextarea
            v-model="form.content"
            label="Contenido del Post"
            placeholder="Escribe el contenido del post aquí..."
            help-text="Puedes usar Markdown para formatear el contenido"
            :rows="20"
            monospace
            required
          />
        </div>

        <!-- SEO Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-6">Configuración SEO</h2>

          <div class="space-y-6">
            <!-- Meta Title -->
            <FormInput
              v-model="form.metaTitle"
              label="Meta Título"
              placeholder="Título para SEO (opcional)"
              help-text="Si está vacío, se usará el título del post"
            />

            <!-- Meta Description -->
            <FormTextarea
              v-model="form.metaDescription"
              label="Meta Descripción"
              placeholder="Descripción para motores de búsqueda..."
              help-text="Máximo 160 caracteres recomendado"
              :rows="3"
            />

            <!-- Tags -->
            <FormInput
              v-model="form.tags"
              label="Etiquetas"
              placeholder="vue, javascript, tutorial"
              help-text="Separar con comas"
            />
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="space-y-6">
        <!-- Publish Settings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Publicación</h3>

          <div class="space-y-4">
            <!-- Status -->
            <FormSelect
              v-model="form.status"
              label="Estado"
              :options="statusOptions"
            />

            <!-- Publish Date -->
            <FormInput
              v-if="form.status === 'scheduled'"
              v-model="form.publishedAt"
              label="Fecha de Publicación"
              type="datetime-local"
            />

            <!-- Featured -->
            <FormCheckbox
              v-model="form.featured"
              label="Post Destacado"
            />
          </div>

          <!-- Action Buttons -->
          <div class="mt-6 pt-6 border-t border-gray-200 space-y-3">
            <button
              @click="savePost('published')"
              class="w-full bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-emerald-700 transition-colors"
            >
              Publicar
            </button>
            <button
              @click="savePost('draft')"
              class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-700 transition-colors"
            >
              Guardar Borrador
            </button>
            <button
              @click="previewPost"
              class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition-colors"
            >
              Vista Previa
            </button>
          </div>
        </div>

        <!-- Category -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Categoría</h3>

          <div class="space-y-4">
            <!-- Category -->
            <FormSelect
              v-model="form.category"
              label="Categoría"
              :options="categories"
              placeholder="Seleccionar categoría"
            />
          </div>
        </div>

        <!-- Featured Image -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
          <FormFileUpload
            v-model="form.featuredImage"
            label="Imagen del post"
          />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
