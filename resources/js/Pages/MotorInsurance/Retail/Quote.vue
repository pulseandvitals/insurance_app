<script setup>
import { Head, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';

const props = defineProps({
    quote: Object,
    producer: Object,
});

function peso(value) {
    return '₱' + Number(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function proceed() {
    router.post(route('motor-risks.proceed', props.quote.id));
}
</script>

<template>
    <Head title="Motor Insurance Quote" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-4xl space-y-6 px-4 py-8 sm:px-6 lg:px-8 print:max-w-full">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 print:hidden">Motor Insurance Quote</h2>

            <Card>
                <div class="grid grid-cols-2 gap-4 border-b border-gray-100 pb-5 dark:border-gray-700 sm:grid-cols-3">
                    <div><p class="text-xs uppercase text-gray-400">Created</p><p class="font-medium text-gray-900 dark:text-gray-100">{{ new Date(quote.created_at).toLocaleString() }}</p></div>
                    <div><p class="text-xs uppercase text-gray-400">Vehicle</p><p class="font-medium text-gray-900 dark:text-gray-100">{{ quote.year_model }} {{ quote.brand }} {{ quote.model }} {{ quote.variant }}</p></div>
                    <div><p class="text-xs uppercase text-gray-400">Plate No.</p><p class="font-medium text-gray-900 dark:text-gray-100">{{ quote.plate_no }}</p></div>
                    <div><p class="text-xs uppercase text-gray-400">Registration Type</p><p class="font-medium text-gray-900 dark:text-gray-100">{{ quote.lto_registration_type }}</p></div>
                    <div><p class="text-xs uppercase text-gray-400">Policy Type</p><p class="font-medium text-gray-900 dark:text-gray-100">CTPL</p></div>
                    <div><p class="text-xs uppercase text-gray-400">Vehicle Class</p><p class="font-medium text-gray-900 dark:text-gray-100">{{ quote.vehicle_class }}</p></div>
                    <div><p class="text-xs uppercase text-gray-400">Producer</p><p class="font-medium text-gray-900 dark:text-gray-100">{{ producer.code }} — {{ producer.first_name }} {{ producer.last_name }}</p></div>
                    <div><p class="text-xs uppercase text-gray-400">Coverage Period</p><p class="font-medium text-gray-900 dark:text-gray-100">{{ quote.coverage_period }} Year(s)</p></div>
                </div>

                <table class="mt-5 w-full text-left text-sm">
                    <thead class="text-xs uppercase text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="py-2">Peril/s Covered</th>
                            <th class="py-2">Sum Insured</th>
                            <th class="py-2 text-right">Premium</th>
                        </tr>
                    </thead>
                    <tbody class="border-t border-gray-100 dark:border-gray-700">
                        <tr>
                            <td class="py-3 text-gray-700 dark:text-gray-200">Compulsory Liability: Bodily Injury/Death (CTPL)</td>
                            <td class="py-3 text-gray-700 dark:text-gray-200">As per Insurance Commission schedule</td>
                            <td class="py-3 text-right font-medium text-gray-900 dark:text-gray-100">{{ peso(quote.net_premium) }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4 ml-auto w-full max-w-xs space-y-1 border-t border-gray-100 pt-4 text-sm dark:border-gray-700">
                    <div class="flex justify-between"><span class="text-gray-500">Net Premium</span><span class="font-medium text-gray-900 dark:text-gray-100">{{ peso(quote.net_premium) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Documentary Stamps Tax</span><span>{{ peso(quote.doc_stamps_tax) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Value Added Tax</span><span>{{ peso(quote.vat) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Local Government Tax</span><span>{{ peso(quote.local_govt_tax) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">LTO DBP-DCI Fee</span><span>{{ peso(quote.lto_dbp_dci_fee) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">COC Verification</span><span>{{ peso(quote.coc_verification_fee) }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Others</span><span>{{ peso(quote.other_charges) }}</span></div>
                    <div class="flex justify-between border-t border-gray-200 pt-2 text-base font-bold text-gray-900 dark:border-gray-700 dark:text-gray-100">
                        <span>Total Premium</span><span>{{ peso(quote.total_premium) }}</span>
                    </div>
                </div>

                <p class="mt-6 text-xs text-gray-400">
                    This is a quotation only and does not bind Fortune General Insurance Corp. to provide coverage. Coverage
                    becomes binding only upon agreement and full payment of premium.
                </p>

                <div class="mt-6 flex justify-end gap-3 print:hidden">
                    <Button variant="secondary" @click="window.print()">Print Quote</Button>
                    <Button @click="proceed">Proceed</Button>
                </div>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
