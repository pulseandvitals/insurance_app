<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { FileDown } from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Select from '@/Components/UI/Select.vue';
import Input from '@/Components/UI/Input.vue';
import Banner from '@/Components/UI/Banner.vue';
import { csrfToken } from '@/Composables/useCsrfToken';

const reportType = ref('Detailed Report');
const paidUnpaid = ref('All');
const dateFrom = ref('');
const dateTo = ref('');
const form = ref(null);
</script>

<template>
    <Head title="Policies Sold - Extractions" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Extraction of Policies Sold Screen</h2>
        </template>

        <div class="mx-auto max-w-2xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <Banner title="Instructions">
                Select a date range and click "Extract to CSV" to download a detailed CSV report of your policies sold
                within that period. The file will download directly to your browser.
            </Banner>

            <Card>
                <form ref="form" method="POST" :action="route('policies.extractions.export')" class="space-y-5">
                    <input type="hidden" name="_token" :value="csrfToken()" />

                    <Select id="report_type" v-model="reportType" name="report_type" label="Report Type" :options="['Detailed Report']" />
                    <Select id="paid_unpaid" v-model="paidUnpaid" name="paid_unpaid" label="Paid/Unpaid" :options="['All', 'PAID', 'UNPAID']" />
                    <Input id="date_from" v-model="dateFrom" name="date_from" type="datetime-local" label="Date From" />
                    <Input id="date_to" v-model="dateTo" name="date_to" type="datetime-local" label="Date To" />

                    <div class="flex justify-end">
                        <Button type="submit"><FileDown class="h-4 w-4" />Extract to CSV</Button>
                    </div>
                </form>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
