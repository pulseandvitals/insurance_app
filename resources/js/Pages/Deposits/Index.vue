<script setup>
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';
import Modal from '@/Components/UI/Modal.vue';
import Input from '@/Components/UI/Input.vue';

const props = defineProps({
    deposits: Array,
    walletHandle: String,
});

const viewing = ref(null);
const uploading = ref(null);

const uploadForm = useForm({ proof: null });

function isPending(deposit) {
    return deposit.needs_payment || deposit.needs_upload;
}

function statusVariant(deposit) {
    return isPending(deposit) ? 'gray' : 'good';
}

function rowClass(deposit) {
    return isPending(deposit) ? 'bg-gray-50/60 dark:bg-gray-900/30' : 'bg-status-good/5';
}

function pay(deposit) {
    router.post(route('topups.pay', deposit.id), {}, { preserveScroll: true });
}

function submitUpload() {
    uploadForm.post(route('topups.upload', uploading.value.id), {
        forceFormData: true,
        onSuccess: () => {
            uploading.value = null;
            uploadForm.reset();
        },
    });
}

function peso(value) {
    return '₱' + Number(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>

<template>
    <Head title="Deposits" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">List of Deposits</h2>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <Card :padded="false">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-900/40 dark:text-gray-400">
                            <tr>
                                <th class="px-5 py-3">ID</th>
                                <th class="px-5 py-3">Ref. #</th>
                                <th class="px-5 py-3">Producer</th>
                                <th class="px-5 py-3 text-right">Amount</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3">Deposit Type</th>
                                <th class="px-5 py-3">Approved By</th>
                                <th class="px-5 py-3">Approved Date</th>
                                <th class="px-5 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr v-for="deposit in deposits" :key="deposit.id" :class="rowClass(deposit)">
                                <td class="px-5 py-3 font-mono text-xs text-gray-500 dark:text-gray-400">{{ deposit.id }}</td>
                                <td class="px-5 py-3 text-gray-700 dark:text-gray-200">{{ deposit.ref_no }}</td>
                                <td class="px-5 py-3 text-gray-700 dark:text-gray-200">{{ walletHandle }}</td>
                                <td class="px-5 py-3 text-right font-medium text-gray-900 dark:text-gray-100">{{ peso(deposit.amount) }}</td>
                                <td class="px-5 py-3"><Badge :variant="statusVariant(deposit)">{{ deposit.status }}</Badge></td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ deposit.deposit_type }}</td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ deposit.approved_by ?? '—' }}</td>
                                <td class="px-5 py-3 text-xs text-gray-500 dark:text-gray-400">
                                    {{ deposit.approved_date ? new Date(deposit.approved_date).toLocaleString('en-PH', { dateStyle: 'medium', timeStyle: 'short' }) : '—' }}
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <Button size="sm" variant="ghost" @click="viewing = deposit">View</Button>
                                        <Button v-if="deposit.needs_payment" size="sm" variant="primary" @click="pay(deposit)">Pay</Button>
                                        <Button v-if="deposit.needs_upload" size="sm" variant="outline" @click="uploading = deposit">Upload</Button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="deposits.length === 0">
                                <td colspan="9" class="px-5 py-10 text-center text-sm text-gray-400">No deposits yet.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </Card>
        </div>

        <!-- View modal -->
        <Modal :show="!!viewing" title="Deposit Details" @close="viewing = null">
            <dl v-if="viewing" class="grid grid-cols-2 gap-4 text-sm">
                <div><dt class="text-xs uppercase text-gray-400">Reference #</dt><dd class="font-medium text-gray-900 dark:text-gray-100">{{ viewing.ref_no }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-400">Amount</dt><dd class="font-medium text-gray-900 dark:text-gray-100">{{ peso(viewing.amount) }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-400">Status</dt><dd class="font-medium text-gray-900 dark:text-gray-100">{{ viewing.status }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-400">Deposit Type</dt><dd class="font-medium text-gray-900 dark:text-gray-100">{{ viewing.deposit_type }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-400">Approved By</dt><dd class="font-medium text-gray-900 dark:text-gray-100">{{ viewing.approved_by ?? '—' }}</dd></div>
                <div><dt class="text-xs uppercase text-gray-400">Created</dt><dd class="font-medium text-gray-900 dark:text-gray-100">{{ new Date(viewing.created_at).toLocaleString() }}</dd></div>
            </dl>
            <template #footer>
                <Button variant="secondary" @click="viewing = null">Close</Button>
            </template>
        </Modal>

        <!-- Upload proof modal -->
        <Modal :show="!!uploading" title="Upload Proof of Payment" @close="uploading = null">
            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Upload your bank deposit slip or bills payment receipt for reference
                    <span class="font-semibold">{{ uploading?.ref_no }}</span>.
                </p>
                <Input
                    id="proof"
                    type="file"
                    label="Proof of Payment"
                    required
                    :error="uploadForm.errors.proof"
                    @change="(e) => (uploadForm.proof = e.target.files[0])"
                />
            </div>
            <template #footer>
                <Button variant="secondary" @click="uploading = null">Close</Button>
                <Button :disabled="uploadForm.processing" @click="submitUpload">Upload</Button>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>
