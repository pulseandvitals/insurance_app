<script setup>
import { computed } from 'vue';
import { Info, CheckCircle2, AlertTriangle, AlertCircle } from 'lucide-vue-next';

const props = defineProps({
    variant: {
        type: String,
        default: 'info',
        validator: (v) => ['info', 'good', 'warning', 'critical'].includes(v),
    },
    title: {
        type: String,
        default: '',
    },
});

const styles = {
    info: {
        wrap: 'border-primary-200 bg-primary-50 text-primary-800 dark:border-primary-900 dark:bg-primary-950/40 dark:text-primary-200',
        icon: Info,
    },
    good: {
        wrap: 'border-status-good/30 bg-status-good/10 text-green-800 dark:text-green-300',
        icon: CheckCircle2,
    },
    warning: {
        wrap: 'border-status-warning/40 bg-status-warning/10 text-amber-800 dark:text-amber-300',
        icon: AlertTriangle,
    },
    critical: {
        wrap: 'border-status-critical/30 bg-status-critical/10 text-red-800 dark:text-red-300',
        icon: AlertCircle,
    },
};
const style = computed(() => styles[props.variant]);
</script>

<template>
    <div class="flex gap-3 rounded-lg border px-4 py-3 text-sm" :class="style.wrap">
        <component :is="style.icon" class="mt-0.5 h-5 w-5 shrink-0" stroke-width="1.75" />
        <div class="min-w-0">
            <p v-if="title" class="font-semibold">{{ title }}</p>
            <div class="text-[13px] leading-relaxed"><slot /></div>
        </div>
    </div>
</template>
