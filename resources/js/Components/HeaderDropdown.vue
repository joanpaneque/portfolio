<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref } from 'vue';

interface DropdownOption {
    value: string;
    label: string;
}

const props = defineProps<{
    options: DropdownOption[];
    selected: string;
    label?: string;
}>();

const emit = defineEmits<{
    (e: 'select', value: string): void;
}>();

const isOpen = ref(false);
const dropdownRef = ref<HTMLDivElement | null>(null);

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const selectOption = (value: string) => {
    emit('select', value);
    isOpen.value = false;
};

const closeDropdown = (event: MouseEvent) => {
    if (
        dropdownRef.value &&
        !dropdownRef.value.contains(event.target as Node)
    ) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', closeDropdown);
});

onUnmounted(() => {
    document.removeEventListener('click', closeDropdown);
});

const selectedOption = computed(() => {
    return props.options.find((option) => option.value === props.selected);
});
</script>

<template>
    <div ref="dropdownRef" class="relative">
        <button
            @click="toggleDropdown"
            class="flex w-32 items-center justify-between rounded-lg bg-white/80 px-4 py-2 text-gray-700 shadow-md backdrop-blur transition-all duration-300 hover:shadow-lg dark:bg-gray-800/80 dark:text-gray-300"
            :aria-label="label"
        >
            <span>{{ selectedOption?.label }}</span>
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="ml-2 h-4 w-4 transition-transform duration-200"
                :class="{ 'rotate-180': isOpen }"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M19 9l-7 7-7-7"
                />
            </svg>
        </button>

        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-1"
        >
            <div
                v-if="isOpen"
                class="absolute right-0 z-20 mt-2 w-32 overflow-hidden rounded-lg border border-gray-100 bg-white py-1 shadow-lg dark:border-gray-700 dark:bg-gray-800"
            >
                <button
                    v-for="option in options"
                    :key="option.value"
                    @click="selectOption(option.value)"
                    class="w-full px-4 py-2 text-left text-sm transition-colors duration-150 hover:bg-gray-100 dark:hover:bg-gray-700"
                    :class="[
                        option.value === selected
                            ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300'
                            : 'text-gray-700 dark:text-gray-300',
                    ]"
                >
                    {{ option.label }}
                </button>
            </div>
        </transition>
    </div>
</template>
