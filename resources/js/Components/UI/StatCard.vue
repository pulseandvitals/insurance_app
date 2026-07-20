<script setup>
defineProps({
    label: {
        type: String,
        required: true,
    },
    value: {
        type: [String, Number],
        required: true,
    },
    sub: {
        type: String,
        default: '',
    },
    accent: {
        type: String,
        default: 'bg-primary-50 text-primary-600 dark:bg-primary-950/50 dark:text-primary-400',
    },
});
</script>

<template>
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="flex items-start justify-between">
            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ label }}</span>
            <span v-if="$slots.icon" class="flex h-9 w-9 items-center justify-center rounded-lg" :class="accent">
                <slot name="icon" />
            </span>
        </div>
        <p class="mt-3 text-3xl font-semibold text-gray-900 dark:text-gray-50">{{ value }}</p>
        <p v-if="sub" class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ sub }}</p>
    </div>
</template>
