<script setup>
import { router } from '@inertiajs/vue3';
import { User, Building2, Landmark, MapPin, Trash2, Users } from 'lucide-vue-next';
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
const typeIcon = { person: User, organization: Building2, lender: Landmark };
</script>

<template>
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="w-full text-left text-sm">
            <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-900/40 dark:text-gray-400">
                <tr>
                    <th class="px-4 py-2.5 font-medium">Name</th>
                    <th class="px-4 py-2.5 font-medium">Address</th>
                    <th class="px-4 py-2.5 font-medium">Use this Address?</th>
                    <th class="px-4 py-2.5 text-right font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                <tr v-for="ph in policyholders" :key="ph.id" class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/30">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2.5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-primary-50 text-primary-600 dark:bg-primary-950/50 dark:text-primary-300">
                                <component :is="typeIcon[ph.type]" class="h-4 w-4" />
                            </span>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ ph.name }}</p>
                                <Badge variant="gray">{{ typeLabel[ph.type] }}</Badge>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">
                        <span v-if="ph.address" class="inline-flex items-center gap-1.5">
                            <MapPin class="h-3.5 w-3.5 shrink-0 text-gray-400" />{{ ph.address }}
                        </span>
                        <span v-else class="text-gray-400">—</span>
                    </td>
                    <td class="px-4 py-3">
                        <input
                            type="radio"
                            name="use_address"
                            :disabled="!ph.address"
                            :checked="ph.use_as_address"
                            class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800"
                            @change="useAddress(ph)"
                        />
                    </td>
                    <td class="px-4 py-3 text-right">
                        <button
                            type="button"
                            title="Remove policyholder"
                            class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-xs font-medium text-status-critical transition hover:bg-status-critical/10"
                            @click="remove(ph)"
                        >
                            <Trash2 class="h-3.5 w-3.5" />Remove
                        </button>
                    </td>
                </tr>
                <tr v-if="policyholders.length === 0">
                    <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <Users class="h-8 w-8 text-gray-300 dark:text-gray-600" />
                            No policyholders added yet.
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
