<script setup>
import { Head } from '@inertiajs/vue3';
import { Building2, Coins, Landmark, TrendingUp, Users } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/UI/StatCard.vue';
import Card from '@/Components/UI/Card.vue';

const props = defineProps({
    stats: Object,
    issuance: Object,
});

function peso(value) {
    return '₱' + Number(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Dashboard</h2>
        </template>

        <div class="mx-auto max-w-7xl space-y-8 px-4 py-8 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <StatCard label="Total Producers" :value="stats.producers_count">
                    <template #icon><Users class="h-5 w-5" /></template>
                </StatCard>
                <StatCard label="Total Branches" :value="stats.branches_count">
                    <template #icon><Building2 class="h-5 w-5" /></template>
                </StatCard>
            </div>

            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Issuance Income & Profit</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Every completed policy issuance across all producers, deducted from their wallets at checkout. Profit is
                    the issuance price collected minus the head office remittance rate configured under Global Pricing.
                </p>

                <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <StatCard label="Total Issuance Income" :value="peso(issuance.totals.income)" :sub="`${issuance.totals.policies} policies issued`">
                        <template #icon><Coins class="h-5 w-5" /></template>
                    </StatCard>
                    <StatCard
                        label="Remitted to Head Office"
                        :value="peso(issuance.totals.remittance)"
                        accent="bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300"
                    >
                        <template #icon><Landmark class="h-5 w-5" /></template>
                    </StatCard>
                    <StatCard
                        label="Total Profit"
                        :value="peso(issuance.totals.profit)"
                        accent="bg-status-good/10 text-status-good dark:bg-status-good/20"
                    >
                        <template #icon><TrendingUp class="h-5 w-5" /></template>
                    </StatCard>
                </div>

                <Card :padded="false" class="mt-5">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-900/40 dark:text-gray-400">
                                <tr>
                                    <th class="px-5 py-3 font-medium">Category</th>
                                    <th class="px-5 py-3 text-right font-medium">Policies Issued</th>
                                    <th class="px-5 py-3 text-right font-medium">Issuance Income</th>
                                    <th class="px-5 py-3 text-right font-medium">Remitted to Head Office</th>
                                    <th class="px-5 py-3 text-right font-medium">Profit</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                <tr v-for="(row, key) in issuance.categories" :key="key">
                                    <td class="px-5 py-3 font-medium text-gray-900 dark:text-gray-100">{{ row.label }}</td>
                                    <td class="px-5 py-3 text-right text-gray-600 dark:text-gray-300">{{ row.policies }}</td>
                                    <td class="px-5 py-3 text-right text-gray-600 dark:text-gray-300">{{ peso(row.income) }}</td>
                                    <td class="px-5 py-3 text-right text-gray-600 dark:text-gray-300">{{ peso(row.remittance) }}</td>
                                    <td class="px-5 py-3 text-right font-medium text-status-good">{{ peso(row.profit) }}</td>
                                </tr>
                            </tbody>
                            <tfoot class="border-t-2 border-gray-200 dark:border-gray-700">
                                <tr>
                                    <td class="px-5 py-3 font-semibold text-gray-900 dark:text-gray-100">Total</td>
                                    <td class="px-5 py-3 text-right font-semibold text-gray-900 dark:text-gray-100">{{ issuance.totals.policies }}</td>
                                    <td class="px-5 py-3 text-right font-semibold text-gray-900 dark:text-gray-100">{{ peso(issuance.totals.income) }}</td>
                                    <td class="px-5 py-3 text-right font-semibold text-gray-900 dark:text-gray-100">{{ peso(issuance.totals.remittance) }}</td>
                                    <td class="px-5 py-3 text-right font-semibold text-status-good">{{ peso(issuance.totals.profit) }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </Card>
            </div>
        </div>
    </AdminLayout>
</template>
