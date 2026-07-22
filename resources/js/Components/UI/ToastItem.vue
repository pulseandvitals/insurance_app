<script setup>
import { computed } from 'vue';
import { CheckCircle2, AlertCircle, AlertTriangle, Info, X } from 'lucide-vue-next';

const props = defineProps({
    variant: {
        type: String,
        default: 'good',
        validator: (v) => ['good', 'critical', 'warning', 'info'].includes(v),
    },
    title: {
        type: String,
        default: '',
    },
    message: {
        type: String,
        required: true,
    },
    duration: {
        type: Number,
        default: 5000,
    },
});

defineEmits(['dismiss']);

const styles = {
    good: {
        wrap: 'border-status-good/30 bg-white dark:bg-gray-800',
        iconBg: 'bg-status-good/15 text-status-good dark:text-green-400',
        bar: 'bg-status-good',
        title: 'text-gray-900 dark:text-gray-100',
        icon: CheckCircle2,
        defaultTitle: 'Success',
    },
    critical: {
        wrap: 'border-status-critical/30 bg-white dark:bg-gray-800',
        iconBg: 'bg-status-critical/15 text-status-critical dark:text-red-400',
        bar: 'bg-status-critical',
        title: 'text-gray-900 dark:text-gray-100',
        icon: AlertCircle,
        defaultTitle: 'Something went wrong',
    },
    warning: {
        wrap: 'border-status-warning/40 bg-white dark:bg-gray-800',
        iconBg: 'bg-status-warning/15 text-status-warning dark:text-amber-400',
        bar: 'bg-status-warning',
        title: 'text-gray-900 dark:text-gray-100',
        icon: AlertTriangle,
        defaultTitle: 'Heads up',
    },
    info: {
        wrap: 'border-primary-200 bg-white dark:bg-gray-800',
        iconBg: 'bg-primary-100 text-primary-600 dark:bg-primary-900/40 dark:text-primary-300',
        bar: 'bg-primary-500',
        title: 'text-gray-900 dark:text-gray-100',
        icon: Info,
        defaultTitle: 'Note',
    },
};

const style = computed(() => styles[props.variant]);
const heading = computed(() => props.title || style.value.defaultTitle);
</script>

<template>
    <div
        role="alert"
        class="relative w-full overflow-hidden rounded-xl border shadow-lg ring-1 ring-black/5 dark:ring-white/10"
        :class="style.wrap"
    >
        <div class="flex items-start gap-3 p-4">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full" :class="style.iconBg">
                <component :is="style.icon" class="h-5 w-5" stroke-width="1.75" />
            </div>
            <div class="min-w-0 flex-1 pt-0.5">
                <p class="text-sm font-semibold" :class="style.title">{{ heading }}</p>
                <p class="mt-0.5 text-[13px] leading-relaxed text-gray-600 dark:text-gray-300">{{ message }}</p>
            </div>
            <button
                type="button"
                class="-mr-1 -mt-1 shrink-0 rounded-md p-1.5 text-gray-400 transition hover:bg-gray-900/5 hover:text-gray-600 dark:hover:bg-white/10 dark:hover:text-gray-300"
                @click="$emit('dismiss')"
            >
                <span class="sr-only">Dismiss</span>
                <X class="h-4 w-4" />
            </button>
        </div>
        <div class="h-1 w-full bg-black/5 dark:bg-white/10">
            <div class="toast-progress h-full origin-left" :class="style.bar" :style="{ animationDuration: `${duration}ms` }" />
        </div>
    </div>
</template>

<style scoped>
.toast-progress {
    animation-name: toast-shrink;
    animation-timing-function: linear;
    animation-fill-mode: forwards;
}

@keyframes toast-shrink {
    from {
        transform: scaleX(1);
    }
    to {
        transform: scaleX(0);
    }
}
</style>
