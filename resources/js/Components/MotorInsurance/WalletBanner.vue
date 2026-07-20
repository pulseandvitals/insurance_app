<script setup>
import StatCard from '@/Components/UI/StatCard.vue';
import Banner from '@/Components/UI/Banner.vue';

defineProps({
    wallet: {
        type: Object,
        required: true,
    },
});

function peso(value) {
    return '₱' + Number(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>

<template>
    <div class="space-y-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <StatCard label="Current e-Wallet" :value="peso(wallet.balance)" />
            <StatCard
                label="Total Spent Net Remittance"
                :value="peso(wallet.total_net_remittance)"
                accent="bg-status-good/10 text-status-good"
            />
        </div>

        <Banner v-if="Number(wallet.balance) < 1000" variant="critical" title="Low e-wallet balance">
            Your ewallet is already used up. Please reload your wallet to avoid inconvenience.
        </Banner>

        <Banner variant="warning" title="IMPORTANT NOTICE">
            A COC Verification Fee and LTO DBP-DCI Fee are collected on every Certificate of Cover (COC) issued, as
            required by the LTO and DBP-Data Center, Inc. for real-time verification of your policy. These charges are
            already itemized in your quotation and total premium below.
        </Banner>
    </div>
</template>
