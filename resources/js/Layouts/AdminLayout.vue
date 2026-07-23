<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';
import AdminSidebar from '@/Layouts/Partials/AdminSidebar.vue';
import AdminNavbar from '@/Layouts/Partials/AdminNavbar.vue';
import Footer from '@/Layouts/Partials/Footer.vue';
import ToastItem from '@/Components/UI/ToastItem.vue';

const page = usePage();
const sidebarOpen = ref(false);

const toasts = ref([]);
let toastSeq = 0;

function pushToast(variant, message) {
    if (!message) return;
    const id = ++toastSeq;
    const duration = variant === 'critical' ? 7000 : 5000;
    toasts.value.push({ id, variant, message, duration });
    setTimeout(() => dismissToast(id), duration);
}

function dismissToast(id) {
    toasts.value = toasts.value.filter((toast) => toast.id !== id);
}

watch(
    () => [page.props.flash?.success, page.props.flash?.error],
    ([success, error]) => {
        if (success) pushToast('good', success);
        if (error) pushToast('critical', error);
    },
    { immediate: true },
);
</script>

<template>
    <div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-900">
        <AdminSidebar :open="sidebarOpen" @close="sidebarOpen = false" />

        <div class="flex min-w-0 flex-1 flex-col">
            <AdminNavbar @toggle-sidebar="sidebarOpen = !sidebarOpen" />

            <!-- Page Heading -->
            <div v-if="$slots.header" class="border-b border-gray-200 bg-white px-4 py-6 dark:border-gray-700 dark:bg-gray-800 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>

            <main class="flex-1 overflow-y-auto">
                <slot />
            </main>

            <Footer />
        </div>

        <Teleport to="body">
            <div class="pointer-events-none fixed inset-x-4 top-4 z-[100] flex flex-col items-stretch gap-3 sm:inset-x-auto sm:right-4 sm:w-96">
                <TransitionGroup name="toast">
                    <ToastItem
                        v-for="toast in toasts"
                        :key="toast.id"
                        class="pointer-events-auto"
                        :variant="toast.variant"
                        :message="toast.message"
                        :duration="toast.duration"
                        @dismiss="dismissToast(toast.id)"
                    />
                </TransitionGroup>
            </div>
        </Teleport>
    </div>
</template>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.25s ease;
}
.toast-enter-from {
    opacity: 0;
    transform: translateX(24px);
}
.toast-leave-to {
    opacity: 0;
    transform: translateX(24px) scale(0.96);
}
.toast-leave-active {
    position: absolute;
    width: 100%;
}
</style>
