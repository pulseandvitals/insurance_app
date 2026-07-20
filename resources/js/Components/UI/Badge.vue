<script setup>
import { computed } from 'vue';

const props = defineProps({
    variant: {
        type: String,
        default: 'gray',
        validator: (v) => ['gray', 'primary', 'good', 'warning', 'critical'].includes(v),
    },
});

const variants = {
    gray: 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
    primary: 'bg-primary-50 text-primary-700 dark:bg-primary-950/50 dark:text-primary-300',
    good: 'bg-status-good/10 text-status-good dark:bg-status-good/20',
    warning: 'bg-status-warning/15 text-amber-700 dark:bg-status-warning/20 dark:text-amber-400',
    critical: 'bg-status-critical/10 text-status-critical dark:bg-status-critical/20',
};

const classes = computed(() => [
    'inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-semibold',
    variants[props.variant],
]);
</script>

<template>
    <span :class="classes">
        <slot />
    </span>
</template>
