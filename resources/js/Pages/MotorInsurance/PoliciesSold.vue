<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Select from '@/Components/UI/Select.vue';
import Input from '@/Components/UI/Input.vue';

const props = defineProps({
    policies: Array,
    filters: Object,
});

const form = ref({
    assured_name: props.filters.assured_name ?? '',
    online_policy_no: props.filters.online_policy_no ?? '',
    chassis_no: props.filters.chassis_no ?? '',
    motor_no: props.filters.motor_no ?? '',
    cov_status: props.filters.cov_status ?? '',
    direct_status: props.filters.direct_status ?? '',
});

function search() {
    router.get(route('policies.index'), form.value, { preserveState: true });
}

function daysBeforeExpiration(contractTo) {
    const diff = Math.ceil((new Date(contractTo) - new Date()) / (1000 * 60 * 60 * 24));
    return diff;
}
</script>

<template>
    <Head title="Policies Sold" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Online Policies Sold</h2>
            <p class="mt-1 text-xs font-medium uppercase tracking-wide text-gray-400">
                (Policies shown here are limited to latest 30 days encoded.)
            </p>
        </template>

        <div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <Card title="Filter">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <Input id="assured_name" v-model="form.assured_name" label="Assured Name" required />
                    <Input id="online_policy_no" v-model="form.online_policy_no" label="Online Policy No." required />
                    <Input id="chassis_no" v-model="form.chassis_no" label="Chassis No." required />
                    <Input id="motor_no" v-model="form.motor_no" label="Motor No." required />
                    <Select id="cov_status" v-model="form.cov_status" label="Cov Status" placeholder="All" :options="['No COV', 'With COV']" />
                    <Select id="direct_status" v-model="form.direct_status" label="Direct Status" placeholder="All" :options="['Yes', 'No']" />
                </div>
                <div class="mt-4 flex justify-end">
                    <Button @click="search">Search</Button>
                </div>
            </Card>

            <div class="space-y-3">
                <Card v-for="policy in policies" :key="policy.id">
                    <div class="flex flex-col justify-between gap-4 sm:flex-row">
                        <div class="min-w-0 space-y-1">
                            <Link :href="route('policies.show', policy.id)" class="font-semibold text-primary-600 hover:underline dark:text-primary-400">
                                {{ policy.motor_quote.policyholders[0]?.name ?? 'Assured Name Unavailable' }}
                            </Link>
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                ePolicy No. {{ policy.online_policy_no }} · CTPL · GENWEB {{ policy.genweb_code }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Issued {{ new Date(policy.issued_at).toLocaleString() }} · {{ daysBeforeExpiration(policy.contract_to) }} days before expiration
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ policy.motor_quote.vehicle_title }} · Chassis {{ policy.motor_quote.chassis_no }} · Engine {{ policy.motor_quote.motor_no }} ·
                                {{ policy.motor_quote.color }} · Plate {{ policy.motor_quote.plate_no }}
                            </p>
                        </div>

                        <div class="flex shrink-0 flex-col gap-2 sm:items-end">
                            <Link :href="route('policies.show', policy.id)"><Button variant="secondary" size="sm">View Policy</Button></Link>
                            <a :href="route('policies.download', policy.id)"><Button variant="outline" size="sm">Download COC</Button></a>
                        </div>
                    </div>
                </Card>

                <div v-if="policies.length === 0" class="rounded-xl border border-dashed border-gray-300 py-10 text-center text-sm text-gray-400 dark:border-gray-700">
                    No policies found for the selected filters.
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
