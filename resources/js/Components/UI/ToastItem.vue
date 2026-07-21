<script setup>
import { computed } from 'vue';

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
        icon: 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z',
        defaultTitle: 'Success',
    },
    critical: {
        wrap: 'border-status-critical/30 bg-white dark:bg-gray-800',
        iconBg: 'bg-status-critical/15 text-status-critical dark:text-red-400',
        bar: 'bg-status-critical',
        title: 'text-gray-900 dark:text-gray-100',
        icon: 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
        defaultTitle: 'Something went wrong',
    },
    warning: {
        wrap: 'border-status-warning/40 bg-white dark:bg-gray-800',
        iconBg: 'bg-status-warning/15 text-status-warning dark:text-amber-400',
        bar: 'bg-status-warning',
        title: 'text-gray-900 dark:text-gray-100',
        icon: 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
        defaultTitle: 'Heads up',
    },
    info: {
        wrap: 'border-primary-200 bg-white dark:bg-gray-800',
        iconBg: 'bg-primary-100 text-primary-600 dark:bg-primary-900/40 dark:text-primary-300',
        bar: 'bg-primary-500',
        title: 'text-gray-900 dark:text-gray-100',
        icon: 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z',
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
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" :d="style.icon" />
                </svg>
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
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
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
