<script setup>
defineProps({
    title: {
        type: String,
        default: '',
    },
    subtitle: {
        type: String,
        default: '',
    },
    padded: {
        type: Boolean,
        default: true,
    },
});
</script>

<template>
    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div v-if="title || $slots.actions" class="flex items-center justify-between border-b border-gray-100 px-5 py-4 dark:border-gray-700">
            <div>
                <h3 v-if="title" class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ title }}</h3>
                <p v-if="subtitle" class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">{{ subtitle }}</p>
            </div>
            <div v-if="$slots.actions">
                <slot name="actions" />
            </div>
        </div>
        <div :class="padded ? 'p-5' : ''">
            <slot />
        </div>
    </div>
</template>
