<script setup>
import { Head, router } from '@inertiajs/vue3';
import { Printer, ArrowRight } from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';

const props = defineProps({
    quote: Object,
    producer: Object,
});

const COVER_INSURANCE_AMOUNT = 200000;

function peso(value) {
    return '₱' + Number(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function proceed() {
    router.post(route('motor-risks.proceed', props.quote.id));
}

function printQuote() {
    window.open(route('motor-risks.print', props.quote.id), '_blank');
}
</script>

<template>
    <Head title="Motor Insurance Quote" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-4xl space-y-6 px-4 py-8 sm:px-6 lg:px-8 print:max-w-full">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 print:hidden">Motor Insurance Quote</h2>

            <Card title="Quote Summary">
                <div class="grid grid-cols-2 gap-x-4 gap-y-5 border-b border-gray-100 pb-5 dark:border-gray-700 sm:grid-cols-4">
                    <div><p class="text-xs uppercase tracking-wide text-gray-400">Created</p><p class="mt-0.5 font-medium text-gray-900 dark:text-gray-100">{{ new Date(quote.created_at).toLocaleString() }}</p></div>
                    <div><p class="text-xs uppercase tracking-wide text-gray-400">Vehicle</p><p class="mt-0.5 font-medium text-gray-900 dark:text-gray-100">{{ quote.year_model }} {{ quote.brand }} {{ quote.model }} {{ quote.variant }}</p></div>
                    <div><p class="text-xs uppercase tracking-wide text-gray-400">Plate No.</p><p class="mt-0.5 font-medium text-gray-900 dark:text-gray-100">{{ quote.plate_no }}</p></div>
                    <div><p class="text-xs uppercase tracking-wide text-gray-400">Registration Type</p><p class="mt-0.5 font-medium text-gray-900 dark:text-gray-100">{{ quote.lto_registration_type }}</p></div>
                    <div><p class="text-xs uppercase tracking-wide text-gray-400">Policy Type</p><p class="mt-0.5 font-medium text-gray-900 dark:text-gray-100">CTPL</p></div>
                    <div><p class="text-xs uppercase tracking-wide text-gray-400">Vehicle Class</p><p class="mt-0.5 font-medium text-gray-900 dark:text-gray-100">{{ quote.vehicle_class }}</p></div>
                    <div><p class="text-xs uppercase tracking-wide text-gray-400">Producer</p><p class="mt-0.5 font-medium text-gray-900 dark:text-gray-100">{{ producer.code }} — {{ producer.first_name }} {{ producer.last_name }}</p></div>
                    <div><p class="text-xs uppercase tracking-wide text-gray-400">Coverage Period</p><p class="mt-0.5 font-medium text-gray-900 dark:text-gray-100">{{ quote.coverage_period }} Year(s)</p></div>
                </div>

                <table class="mt-5 w-full text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        <tr>
                            <th class="py-2 font-medium">Peril/s Covered</th>
                            <th class="py-2 text-right font-medium">Sum Insured</th>
                            <th class="py-2 text-right font-medium">Premium</th>
                        </tr>
                    </thead>
                    <tbody class="border-t border-gray-100 dark:border-gray-700">
                        <tr class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/30">
                            <td class="py-3 text-gray-700 dark:text-gray-200">Compulsory Liability: Bodily Injury/Death (CTPL)</td>
                            <td class="py-3 text-right tabular-nums text-gray-700 dark:text-gray-200">{{ peso(COVER_INSURANCE_AMOUNT) }}</td>
                            <td class="py-3 text-right font-medium tabular-nums text-gray-900 dark:text-gray-100">{{ peso(quote.net_premium) }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4 ml-auto w-full max-w-xs space-y-1.5 border-t border-gray-100 pt-4 text-sm dark:border-gray-700">
                    <div class="flex items-center justify-between"><span class="text-gray-500">Net Premium</span><span class="font-medium tabular-nums text-gray-900 dark:text-gray-100">{{ peso(quote.net_premium) }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-gray-500">Documentary Stamps Tax</span><span class="tabular-nums text-gray-700 dark:text-gray-200">{{ peso(quote.doc_stamps_tax) }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-gray-500">Value Added Tax</span><span class="tabular-nums text-gray-700 dark:text-gray-200">{{ peso(quote.vat) }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-gray-500">Local Government Tax</span><span class="tabular-nums text-gray-700 dark:text-gray-200">{{ peso(quote.local_govt_tax) }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-gray-500">LTO DBP-DCI Fee</span><span class="tabular-nums text-gray-700 dark:text-gray-200">{{ peso(quote.lto_dbp_dci_fee) }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-gray-500">COC Verification</span><span class="tabular-nums text-gray-700 dark:text-gray-200">{{ peso(quote.coc_verification_fee) }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-gray-500">Others</span><span class="tabular-nums text-gray-700 dark:text-gray-200">{{ peso(quote.other_charges) }}</span></div>
                    <div class="flex items-center justify-between border-t border-gray-200 pt-2 text-base font-bold text-gray-900 dark:border-gray-700 dark:text-gray-100">
                        <span>Total Premium</span><span class="tabular-nums">{{ peso(quote.total_premium) }}</span>
                    </div>
                </div>

                <p class="mt-6 text-xs text-gray-400">
                    This is a quotation only and does not bind Stronghold to provide coverage. Coverage
                    becomes binding only upon agreement and full payment of premium.
                </p>

                <div class="mt-6 flex justify-end gap-3 print:hidden">
                    <Button variant="secondary" @click="printQuote"><Printer class="h-4 w-4" />Print Quote</Button>
                    <Button @click="proceed">Proceed<ArrowRight class="h-4 w-4" /></Button>
                </div>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
