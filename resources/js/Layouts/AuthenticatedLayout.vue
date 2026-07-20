<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Sidebar from '@/Layouts/Partials/Sidebar.vue';
import Navbar from '@/Layouts/Partials/Navbar.vue';
import Footer from '@/Layouts/Partials/Footer.vue';
import Banner from '@/Components/UI/Banner.vue';

const page = usePage();
const sidebarOpen = ref(false);
</script>

<template>
    <div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-900">
        <Sidebar :open="sidebarOpen" @close="sidebarOpen = false" />

        <div class="flex min-w-0 flex-1 flex-col">
            <Navbar @toggle-sidebar="sidebarOpen = !sidebarOpen" />

            <!-- Page Heading -->
            <div v-if="$slots.header" class="border-b border-gray-200 bg-white px-4 py-6 dark:border-gray-700 dark:bg-gray-800 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>

            <main class="flex-1 overflow-y-auto">
                <div v-if="page.props.flash?.success" class="mx-4 mt-4 sm:mx-6 lg:mx-8">
                    <Banner variant="good">{{ page.props.flash.success }}</Banner>
                </div>
                <div v-if="page.props.flash?.error" class="mx-4 mt-4 sm:mx-6 lg:mx-8">
                    <Banner variant="critical">{{ page.props.flash.error }}</Banner>
                </div>

                <slot />
            </main>

            <Footer />
        </div>
    </div>
</template>
