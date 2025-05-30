<script setup>
defineProps({
  modelValue: {
    type: Boolean,
    default: false
  },
  label: {
    type: String,
    required: true
  },
  helpText: {
    type: String,
    default: ''
  },
  error: {
    type: String,
    default: ''
  }
});

defineEmits(['update:modelValue']);
</script>

<template>
  <div>
    <div class="flex items-center">
      <input
        :checked="modelValue"
        @change="$emit('update:modelValue', $event.target.checked)"
        type="checkbox"
        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded"
        :class="{ 'border-red-300 focus:ring-red-500': error }"
      >
      <label class="ml-2 block text-sm text-gray-900">{{ label }}</label>
    </div>
    <p v-if="helpText" class="text-sm text-gray-500 mt-1 ml-6">{{ helpText }}</p>
    <p v-if="error" class="text-sm text-red-600 mt-1 ml-6">{{ error }}</p>
  </div>
</template>
