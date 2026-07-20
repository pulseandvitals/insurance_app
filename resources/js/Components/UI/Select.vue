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
    disabled: {
        type: Boolean,
        default: false,
    },
    options: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: 'Select One',
    },
});

defineEmits(['update:modelValue']);

const selectClasses = computed(() => [
    'block w-full rounded-lg border py-2 pr-8 text-sm shadow-sm transition focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-gray-100 disabled:text-gray-400',
    'bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 dark:disabled:bg-gray-800',
    props.error
        ? 'border-status-critical focus:border-status-critical focus:ring-status-critical/30'
        : props.valid
          ? 'border-status-good focus:border-status-good focus:ring-status-good/30'
          : 'border-gray-300 focus:border-primary-500 focus:ring-primary-500/30 dark:border-gray-600',
]);
</script>

<template>
    <div>
        <label v-if="label" :for="id" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ label }}
            <span v-if="required" class="text-status-critical">*</span>
        </label>

        <select
            :id="id"
            :name="name || id"
            :value="modelValue"
            :disabled="disabled"
            :class="selectClasses"
            @change="$emit('update:modelValue', $event.target.value)"
        >
            <option value="">{{ placeholder }}</option>
            <option v-for="opt in options" :key="opt.value ?? opt" :value="opt.value ?? opt">
                {{ opt.label ?? opt }}
            </option>
        </select>

        <p v-if="error" class="mt-1 text-xs font-medium text-status-critical">{{ error }}</p>
        <p v-else-if="help" class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ help }}</p>
    </div>
</template>
