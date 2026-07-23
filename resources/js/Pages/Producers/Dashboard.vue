<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Filter } from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import StatCard from '@/Components/UI/StatCard.vue';

const props = defineProps({
    producer: Object,
    filters: Object,
    stats: Object,
});

const dateFrom = ref(props.filters.date_from ?? '');
const dateTo = ref(props.filters.date_to ?? '');

function filterData() {
    router.get(
        route('producers.dashboard', props.producer.id),
        { date_from: dateFrom.value, date_to: dateTo.value },
        { preserveState: true, preserveScroll: true },
    );
}

function peso(value) {
    return '₱' + Number(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <!-- Profile header -->
            <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                        {{ producer.code }} - {{ producer.full_name }}
                    </h2>
                    <div class="mt-2 flex flex-wrap items-center gap-2">
                        <Badge variant="primary">{{ producer.code }}</Badge>
                        <Badge v-if="producer.is_oa" variant="gray">OA</Badge>
                        <Badge variant="good">{{ producer.status }}</Badge>
                        <Badge variant="gray">[{{ producer.tier }}]</Badge>
                    </div>
                </div>
                <Link :href="route('producers.edit', producer.id)">
                    <Button variant="secondary"><Pencil class="h-4 w-4" />Edit Profile</Button>
                </Link>
            </div>

            <!-- Info cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Card title="Address">
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ producer.address }}</p>
                </Card>

                <Card title="Contact">
                    <div class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            {{ producer.email }}
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            {{ producer.phone }}
                        </div>
                    </div>
                </Card>

                <Card title="Attached to">
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ producer.branch?.code }} — {{ producer.branch?.name }}</p>
                </Card>

                <Card title="Producer Tier">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{ producer.tier }} Producer<br />
                        <span class="text-xs text-gray-400">Onboarded {{ new Date(producer.created_at).toLocaleDateString() }}</span>
                    </p>
                </Card>
            </div>

            <!-- Filter -->
            <Card title="Filter">
                <div class="grid grid-cols-1 items-end gap-4 sm:grid-cols-3">
                    <Input id="date_from" v-model="dateFrom" type="date" label="Date From" />
                    <Input id="date_to" v-model="dateTo" type="date" label="Date To" />
                    <Button @click="filterData"><Filter class="h-4 w-4" />Filter Data</Button>
                </div>
            </Card>

            <!-- Stat cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <StatCard label="CTPL Quotations" :value="stats.quotations_count" :sub="peso(stats.quotations_total)">
                    <template #icon>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </template>
                </StatCard>

                <StatCard label="CTPL Policies" :value="stats.policies_count" :sub="peso(stats.policies_total)" accent="bg-status-good/10 text-status-good">
                    <template #icon>
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                        </svg>
                    </template>
                </StatCard>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
