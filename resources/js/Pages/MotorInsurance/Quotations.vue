<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Search, Eye, Inbox } from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';
import Select from '@/Components/UI/Select.vue';
import Input from '@/Components/UI/Input.vue';
import Pagination from '@/Components/UI/Pagination.vue';

const props = defineProps({
    quotes: Object,
    filters: Object,
});

const transactionType = ref(props.filters.transaction_type ?? '');
const plateNo = ref(props.filters.plate_no ?? '');
const quoteRef = ref(props.filters.quote_ref ?? '');

function search() {
    router.get(route('motor-risks.index'), {
        transaction_type: transactionType.value,
        plate_no: plateNo.value,
        quote_ref: quoteRef.value,
    }, { preserveState: true });
}
</script>

<template>
    <Head title="Quotations" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Quotations</h2>
        </template>

        <div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <Card title="Filter">
                <div class="grid grid-cols-1 items-end gap-4 sm:grid-cols-4">
                    <Select
                        id="transaction_type"
                        v-model="transactionType"
                        label="Transaction Type"
                        placeholder="Select a Transaction Type"
                        :options="['Compulsory Third Party Liability (CTPL)', 'Motor Comprehensive']"
                    />
                    <Input id="plate_no" v-model="plateNo" label="Plate / Conduction Number" />
                    <Input id="quote_ref" v-model="quoteRef" label="Quotation Ref#:" />
                    <Button @click="search"><Search class="h-4 w-4" />Search</Button>
                </div>
            </Card>

            <div class="space-y-3">
                <Card v-for="quote in quotes.data" :key="quote.id" :padded="true">
                    <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <p class="font-semibold text-gray-900 dark:text-gray-100">{{ quote.year_model }} {{ quote.brand }} {{ quote.model }} {{ quote.variant }}</p>
                                <Badge v-if="quote.status === 'policy'" variant="good">Policy Issued</Badge>
                                <Badge v-else variant="gray">Quote</Badge>
                            </div>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Quote #{{ quote.id }} · {{ quote.plate_no }} · Ref {{ quote.quote_ref }} · Created {{ new Date(quote.created_at).toLocaleDateString() }}
                            </p>
                            <p class="mt-1 text-xs text-gray-400">Compulsory Third Party Liability (CTPL)</p>
                        </div>

                        <Link :href="quote.status === 'policy' ? route('policies.show', quote.policy.id) : route('motor-risks.show', quote.id)">
                            <Button variant="secondary" size="sm"><Eye class="h-3.5 w-3.5" />{{ quote.status === 'policy' ? 'View Policy' : 'View Quote' }}</Button>
                        </Link>
                    </div>
                </Card>

                <div v-if="quotes.data.length === 0" class="flex flex-col items-center gap-2 rounded-xl border border-dashed border-gray-300 py-10 text-center text-sm text-gray-400 dark:border-gray-700">
                    <Inbox class="h-8 w-8 text-gray-300 dark:text-gray-600" />
                    No quotations found.
                </div>

                <Pagination :paginator="quotes" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
