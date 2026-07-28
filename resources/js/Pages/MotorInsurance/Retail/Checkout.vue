<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { Wallet, Loader2 } from "lucide-vue-next";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/UI/Card.vue";
import Button from "@/Components/UI/Button.vue";
import Banner from "@/Components/UI/Banner.vue";
import WizardSteps from "@/Components/MotorInsurance/WizardSteps.vue";

const props = defineProps({
    quote: Object,
    wallet: Object,
});

function peso(value) {
    return (
        "₱" +
        Number(value).toLocaleString("en-PH", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        })
    );
}

const form = useForm({});

function pay() {
    form.post(route("motor-risks.checkout.store", props.quote.id));
}

const insufficientFunds =
    Number(props.wallet.balance) < Number(props.quote.issuance_price);
</script>

<template>
    <Head title="Payment" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-2xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <WizardSteps :current="3" title="Payment" />

            <Card title="Payment Summary">
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="shrink-0 text-gray-500">Vehicle</span
                        ><span
                            class="text-right font-medium text-gray-900 dark:text-gray-100"
                            >{{ quote.vehicle_title }}</span
                        >
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="shrink-0 text-gray-500">Plate No.</span
                        ><span
                            class="text-right font-medium text-gray-900 dark:text-gray-100"
                            >{{ quote.plate_no }}</span
                        >
                    </div>
                    <div class="flex justify-between gap-4">
                        <span class="shrink-0 text-gray-500"
                            >Coverage Period</span
                        ><span
                            class="text-right font-medium text-gray-900 dark:text-gray-100"
                            >{{ quote.coverage_period }} Year(s)</span
                        >
                    </div>
                    <div
                        class="flex justify-between gap-4 border-t border-gray-100 pt-2 dark:border-gray-700"
                    >
                        <span class="shrink-0 text-gray-500"
                            >Current e-Wallet Balance</span
                        ><span
                            class="text-right font-medium text-gray-900 dark:text-gray-100"
                            >{{ peso(wallet.balance) }}</span
                        >
                    </div>
                    <div class="flex justify-between gap-4 text-base font-bold">
                        <span class="shrink-0 text-white"
                            >Total Premium Due</span
                        ><span
                            class="text-right text-primary-600 dark:text-primary-400"
                            >{{ peso(quote.total_premium) }}</span
                        >
                    </div>
                </div>

                <div class="mt-4 flex justify-between gap-4 rounded-lg bg-primary-50 px-4 py-3 text-base font-bold dark:bg-primary-950/40">
                    <span class="shrink-0 text-primary-700 dark:text-primary-300">e-Wallet Deduction</span>
                    <span class="text-right text-primary-700 dark:text-primary-300">{{ peso(quote.issuance_price) }}</span>
                </div>
                <p class="mt-1.5 text-xs text-gray-400">
                    Only the issuance price configured for this product is deducted from your e-wallet — not the total premium above.
                </p>

                <Banner
                    v-if="insufficientFunds"
                    variant="critical"
                    title="Insufficient e-wallet balance"
                    class="mt-4"
                >
                    Your e-wallet balance is not enough to cover this premium.
                    Please reload your wallet before proceeding.
                </Banner>

                <div class="mt-6 flex justify-end">
                    <Button
                        :disabled="form.processing || insufficientFunds"
                        @click="pay"
                    >
                        <Loader2
                            v-if="form.processing"
                            class="h-4 w-4 animate-spin"
                        />
                        <Wallet v-else class="h-4 w-4" />
                        Pay &amp; Issue Policy
                    </Button>
                </div>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
