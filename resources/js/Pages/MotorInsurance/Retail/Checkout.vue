<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import { Wallet, Loader2 } from "lucide-vue-next";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/UI/Card.vue";
import Button from "@/Components/UI/Button.vue";
import Banner from "@/Components/UI/Banner.vue";
import Checkbox from "@/Components/Checkbox.vue";
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

const form = useForm({
    terms_accepted: false,
});

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
            </Card>

            <Card title="Terms and Conditions">
                <div
                    class="max-h-48 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50 p-4 text-xs leading-relaxed text-gray-600 dark:border-gray-700 dark:bg-gray-900/40 dark:text-gray-300"
                >
                    <ol class="list-decimal space-y-2 pl-4">
                        <li>
                            This transaction and the resulting Confirmation of Cover / Policy are subject to the full terms,
                            conditions, and exclusions of the Compulsory Third Party Liability (CTPL) master policy issued by
                            Stronghold Insurance Company, Incorporated.
                        </li>
                        <li>
                            The premium reflected above is inclusive of all applicable taxes, fees, and charges. Once the policy
                            is issued, the e-Wallet Deduction shown is final and non-refundable.
                        </li>
                        <li>
                            The Producer warrants that all vehicle, policyholder, and authentication details entered for this
                            transaction are true, accurate, and obtained from valid, authorized sources.
                        </li>
                        <li>
                            Coverage is effective only upon successful issuance of the policy and is strictly limited to the
                            vehicle, coverage period, and terms stated in the Policy Schedule and Confirmation of Cover.
                        </li>
                        <li>
                            Stronghold Insurance Company, Incorporated reserves the right to cancel or void any coverage found to
                            have been issued on the basis of false, inaccurate, or fraudulent information.
                        </li>
                        <li>
                            This transaction and document are governed by the Insurance Code of the Philippines and other
                            applicable laws, rules, and regulations.
                        </li>
                    </ol>
                </div>

                <label class="mt-4 flex items-start gap-2.5 text-sm text-gray-700 dark:text-gray-300">
                    <Checkbox v-model:checked="form.terms_accepted" class="mt-0.5 shrink-0" />
                    <span>I have read and agree to the Terms and Conditions governing this CTPL policy issuance.</span>
                </label>
                <p v-if="form.errors.terms_accepted" class="mt-1.5 text-xs font-medium text-status-critical">
                    {{ form.errors.terms_accepted }}
                </p>

                <div class="mt-6 flex justify-end">
                    <Button
                        :disabled="form.processing || insufficientFunds || !form.terms_accepted"
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
