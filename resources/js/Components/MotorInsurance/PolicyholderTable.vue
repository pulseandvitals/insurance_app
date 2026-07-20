<script setup>
import { router } from '@inertiajs/vue3';
import Badge from '@/Components/UI/Badge.vue';

const props = defineProps({
    motorQuoteId: [Number, String],
    policyholders: {
        type: Array,
        default: () => [],
    },
});

function useAddress(policyholder) {
    router.post(route('motor-risks.policyholders.use-address', [props.motorQuoteId, policyholder.id]), {}, { preserveScroll: true });
}

function remove(policyholder) {
    router.delete(route('motor-risks.policyholders.destroy', [props.motorQuoteId, policyholder.id]), { preserveScroll: true });
}

const typeLabel = { person: 'Person', organization: 'Organization', lender: 'Lender' };
</script>

<template>
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-900/40 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Address</th>
                    <th class="px-4 py-2">Use this Address?</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <tr v-for="ph in policyholders" :key="ph.id">
                    <td class="px-4 py-2.5">
                        <p class="font-medium text-gray-900 dark:text-gray-100">{{ ph.name }}</p>
                        <Badge variant="gray">{{ typeLabel[ph.type] }}</Badge>
                    </td>
                    <td class="px-4 py-2.5 text-gray-600 dark:text-gray-300">{{ ph.address ?? '—' }}</td>
                    <td class="px-4 py-2.5">
                        <input
                            type="radio"
                            name="use_address"
                            :disabled="!ph.address"
                            :checked="ph.use_as_address"
                            class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800"
                            @change="useAddress(ph)"
                        />
                    </td>
                    <td class="px-4 py-2.5">
                        <button type="button" class="text-sm font-medium text-status-critical hover:underline" @click="remove(ph)">Remove</button>
                    </td>
                </tr>
                <tr v-if="policyholders.length === 0">
                    <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-400">No policyholders added yet.</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
