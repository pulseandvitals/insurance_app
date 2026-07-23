<script setup>
import { Head } from '@inertiajs/vue3';
import { Building2, Users } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';

defineProps({
    stats: Object,
});
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Dashboard</h2>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <StatCard label="Total Producers" :value="stats.producers_count">
                    <template #icon><Users class="h-5 w-5" /></template>
                </StatCard>
                <StatCard label="Total Branches" :value="stats.branches_count">
                    <template #icon><Building2 class="h-5 w-5" /></template>
                </StatCard>
            </div>
        </div>
    </AdminLayout>
</template>
