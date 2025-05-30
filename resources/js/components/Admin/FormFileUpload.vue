<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  modelValue: {
    type: File,
    default: null
  },
  label: {
    type: String,
    required: true
  },
  accept: {
    type: String,
    default: 'image/*'
  },
  maxSize: {
    type: String,
    default: '2MB'
  },
  helpText: {
    type: String,
    default: 'PNG, JPG, GIF hasta 2MB'
  },
  error: {
    type: String,
    default: ''
  }
});

const emit = defineEmits(['update:modelValue']);

const fileInput = ref(null);

const hasFile = computed(() => props.modelValue !== null);

const handleFileSelect = (event) => {
  const file = event.target.files[0];
  if (file) {
    emit('update:modelValue', file);
  }
};

const removeFile = () => {
  emit('update:modelValue', null);
  if (fileInput.value) {
    fileInput.value.value = '';
  }
};

const triggerFileSelect = () => {
  fileInput.value?.click();
};
</script>

<template>
  <div>
    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ label }}</h3>

    <div class="space-y-4">
      <!-- File Preview -->
      <div v-if="hasFile" class="relative">
        <div class="w-full h-32 bg-gradient-to-br from-emerald-500 to-blue-600 rounded-lg flex items-center justify-center">
          <span class="text-white font-medium">{{ modelValue.name }}</span>
        </div>
        <button
          @click="removeFile"
          type="button"
          class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600 transition-colors"
        >
          ×
        </button>
      </div>

      <!-- Upload Area -->
      <div v-else
           class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-emerald-400 transition-colors"
           :class="{ 'border-red-300': error }"
           @click="triggerFileSelect">
        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
          <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        <div class="mt-4">
          <span class="mt-2 block text-sm font-medium text-gray-900">Subir imagen</span>
          <p class="mt-1 text-xs text-gray-500">{{ helpText }}</p>
        </div>
      </div>

      <!-- Hidden File Input -->
      <input
        ref="fileInput"
        type="file"
        class="sr-only"
        :accept="accept"
        @change="handleFileSelect"
      >
    </div>

    <p v-if="error" class="text-sm text-red-600 mt-2">{{ error }}</p>
  </div>
</template>
