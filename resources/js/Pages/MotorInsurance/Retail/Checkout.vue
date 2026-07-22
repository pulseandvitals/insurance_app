<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Wallet, Loader2 } from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Banner from '@/Components/UI/Banner.vue';
import WizardSteps from '@/Components/MotorInsurance/WizardSteps.vue';

const props = defineProps({
    quote: Object,
    wallet: Object,
});

function peso(value) {
    return '₱' + Number(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

const form = useForm({});

function pay() {
    form.post(route('motor-risks.checkout.store', props.quote.id));
}

const insufficientFunds = Number(props.wallet.balance) < Number(props.quote.total_premium);
</script>

<template>
    <Head title="Payment" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-2xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <WizardSteps :current="3" title="Payment" />

            <Card title="Payment Summary">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Vehicle</span><span class="font-medium text-gray-900 dark:text-gray-100">{{ quote.vehicle_title }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Plate No.</span><span class="font-medium text-gray-900 dark:text-gray-100">{{ quote.plate_no }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Coverage Period</span><span class="font-medium text-gray-900 dark:text-gray-100">{{ quote.coverage_period }} Year(s)</span></div>
                    <div class="flex justify-between border-t border-gray-100 pt-2 dark:border-gray-700"><span class="text-gray-500">Current e-Wallet Balance</span><span class="font-medium text-gray-900 dark:text-gray-100">{{ peso(wallet.balance) }}</span></div>
                    <div class="flex justify-between text-base font-bold"><span>Total Premium Due</span><span class="text-primary-600 dark:text-primary-400">{{ peso(quote.total_premium) }}</span></div>
                </div>

                <Banner v-if="insufficientFunds" variant="critical" title="Insufficient e-wallet balance" class="mt-4">
                    Your e-wallet balance is not enough to cover this premium. Please reload your wallet before proceeding.
                </Banner>

                <div class="mt-6 flex justify-end">
                    <Button :disabled="form.processing || insufficientFunds" @click="pay">
                        <Loader2 v-if="form.processing" class="h-4 w-4 animate-spin" />
                        <Wallet v-else class="h-4 w-4" />
                        Pay &amp; Issue Policy
                    </Button>
                </div>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
