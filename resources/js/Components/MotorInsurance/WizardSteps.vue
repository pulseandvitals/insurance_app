<script setup>
defineProps({
    current: {
        type: Number,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
});

const steps = ['Vehicle Info', 'Buy CTPL/Motor Insurance', 'Payment'];
</script>

<template>
    <div>
        <ol class="flex items-center gap-2 text-xs font-medium text-gray-400 dark:text-gray-500">
            <template v-for="(step, i) in steps" :key="step">
                <li class="flex items-center gap-2" :class="i + 1 <= current ? 'text-primary-600 dark:text-primary-400' : ''">
                    <span
                        class="flex h-6 w-6 items-center justify-center rounded-full border text-[11px]"
                        :class="i + 1 <= current ? 'border-primary-600 bg-primary-600 text-white dark:border-primary-400 dark:bg-primary-500' : 'border-gray-300 dark:border-gray-600'"
                    >
                        {{ i + 1 }}
                    </span>
                    {{ step }}
                </li>
                <li v-if="i < steps.length - 1" class="h-px w-8 bg-gray-200 dark:bg-gray-700"></li>
            </template>
        </ol>
        <h2 class="mt-3 text-xl font-semibold text-gray-900 dark:text-gray-100">Step {{ current }} of 3 — {{ title }}</h2>
    </div>
</template>
