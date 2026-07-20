<script setup>
import { computed } from 'vue';

const props = defineProps({
    modelValue: {
        type: [String, Number],
        default: '',
    },
    label: {
        type: String,
        default: '',
    },
    id: {
        type: String,
        default: '',
    },
    name: {
        type: String,
        default: '',
    },
    type: {
        type: String,
        default: 'text',
    },
    required: {
        type: Boolean,
        default: false,
    },
    error: {
        type: String,
        default: '',
    },
    valid: {
        type: Boolean,
        default: false,
    },
    help: {
        type: String,
        default: '',
    },
    placeholder: {
        type: String,
        default: '',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['update:modelValue']);

const inputClasses = computed(() => [
    'block w-full rounded-lg border py-2 text-sm shadow-sm transition placeholder:text-gray-400 focus:outline-none focus:ring-2 disabled:bg-gray-100 disabled:text-gray-400',
    'bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 dark:placeholder:text-gray-500 dark:disabled:bg-gray-800',
    props.error
        ? 'border-status-critical focus:border-status-critical focus:ring-status-critical/30 pr-9'
        : props.valid
          ? 'border-status-good focus:border-status-good focus:ring-status-good/30 pr-9'
          : 'border-gray-300 focus:border-primary-500 focus:ring-primary-500/30 dark:border-gray-600',
]);
</script>

<template>
    <div>
        <label v-if="label" :for="id" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ label }}
            <span v-if="required" class="text-status-critical">*</span>
        </label>

        <div class="relative">
            <input
                :id="id"
                :name="name || id"
                :type="type"
                :value="modelValue"
                :placeholder="placeholder"
                :disabled="disabled"
                :class="inputClasses"
                @input="$emit('update:modelValue', $event.target.value)"
            />

            <svg
                v-if="valid && !error"
                class="absolute right-2.5 top-1/2 h-4 w-4 -translate-y-1/2 text-status-good"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="2.5"
                stroke="currentColor"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
        </div>

        <p v-if="error" class="mt-1 text-xs font-medium text-status-critical">{{ error }}</p>
        <p v-else-if="help" class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ help }}</p>
    </div>
</template>
