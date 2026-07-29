<script setup>
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Filter, Plus, X, Inbox } from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Modal from '@/Components/UI/Modal.vue';
import Banner from '@/Components/UI/Banner.vue';
import Pagination from '@/Components/UI/Pagination.vue';

const props = defineProps({
    wallet: Object,
    transactions: Object,
    walletHandle: String,
    filters: Object,
});

const dateFrom = ref(props.filters.date_from ?? '');
const dateTo = ref(props.filters.date_to ?? '');
const showDepositModal = ref(false);

function filterData() {
    router.get(route('producers.wallet'), { date_from: dateFrom.value, date_to: dateTo.value }, { preserveState: true, preserveScroll: true });
}

const depositForm = useForm({
    amount: '0.00',
    payment_method: '',
});

function submitDeposit() {
    depositForm.post(route('producers.wallet.deposit'), {
        onSuccess: () => {
            showDepositModal.value = false;
            depositForm.reset();
        },
    });
}

function peso(value) {
    return '₱' + Number(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
</script>

<template>
    <Head title="Ewallet" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">e-Wallet</h2>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <Banner v-if="Number(wallet.balance) < 1000" variant="critical" title="Low e-wallet balance">
                Your ewallet is already used up. Please reload your wallet to avoid inconvenience.
            </Banner>

            <!-- Remaining balance -->
            <div class="rounded-xl border border-gray-200 bg-gradient-to-br from-primary-600 to-primary-800 p-6 text-white shadow-sm dark:border-gray-700">
                <p class="text-sm font-medium text-primary-100">Remaining Balance</p>
                <p class="mt-2 text-4xl font-bold">{{ peso(wallet.balance) }}</p>
                <p class="mt-1 text-xs text-primary-200">{{ walletHandle }}</p>
            </div>

            <!-- Summary row -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Card title="Initial Deposit"><p class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ peso(wallet.initial_deposit) }}</p></Card>
                <Card title="Accumulated Deposit"><p class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ peso(wallet.accumulated_deposit) }}</p></Card>
                <Card title="Total Net Remittance"><p class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ peso(wallet.total_net_remittance) }}</p></Card>
                <Card title="Total Received"><p class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ peso(wallet.total_received) }}</p></Card>
            </div>

            <!-- Filter bar -->
            <Card title="Filter">
                <div class="grid grid-cols-1 items-end gap-4 sm:grid-cols-4">
                    <Input id="date_from" v-model="dateFrom" type="date" label="Date From" />
                    <Input id="date_to" v-model="dateTo" type="date" label="Date To" />
                    <Button variant="secondary" @click="filterData"><Filter class="h-4 w-4" />Filter Data</Button>
                    <Button @click="showDepositModal = true"><Plus class="h-4 w-4" />Deposit</Button>
                </div>
            </Card>

            <!-- History table -->
            <Card title="e-Ewallet History" :padded="false">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-900/40 dark:text-gray-400">
                            <tr>
                                <th class="px-5 py-3 font-medium">ID</th>
                                <th class="px-5 py-3 font-medium">From</th>
                                <th class="px-5 py-3 font-medium">To</th>
                                <th class="px-5 py-3 font-medium">Transaction</th>
                                <th class="px-5 py-3 text-right font-medium">Debit</th>
                                <th class="px-5 py-3 text-right font-medium">Credit</th>
                                <th class="px-5 py-3 font-medium">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr v-for="tx in transactions.data" :key="tx.id" class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-5 py-3 font-mono text-xs text-gray-500 dark:text-gray-400">{{ tx.id }}</td>
                                <td class="px-5 py-3 text-gray-700 dark:text-gray-200">{{ tx.from_handle }}</td>
                                <td class="px-5 py-3 text-gray-700 dark:text-gray-200">{{ tx.to_handle ?? '—' }}</td>
                                <td class="px-5 py-3">
                                    <p class="font-medium text-gray-800 dark:text-gray-100">{{ tx.transaction_type }}</p>
                                    <p class="text-xs text-gray-400">{{ tx.reference_label }}</p>
                                </td>
                                <td class="px-5 py-3 text-right tabular-nums text-status-critical">{{ Number(tx.debit) > 0 ? peso(tx.debit) : '—' }}</td>
                                <td class="px-5 py-3 text-right tabular-nums text-status-good">{{ Number(tx.credit) > 0 ? peso(tx.credit) : '—' }}</td>
                                <td class="px-5 py-3 whitespace-nowrap text-xs text-gray-500 dark:text-gray-400">
                                    {{ new Date(tx.created_at).toLocaleString('en-PH', { dateStyle: 'medium', timeStyle: 'short' }) }}
                                </td>
                            </tr>
                            <tr v-if="transactions.data.length === 0">
                                <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <Inbox class="h-8 w-8 text-gray-300 dark:text-gray-600" />
                                        No wallet transactions found.
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </Card>

            <Pagination :paginator="transactions" />
        </div>

        <Modal :show="showDepositModal" title="Deposit Ewallet" @close="showDepositModal = false">
            <div class="space-y-4">
                <Input
                    id="amount"
                    v-model="depositForm.amount"
                    type="number"
                    label="Amount"
                    required
                    :error="depositForm.errors.amount"
                />
                <Select
                    id="payment_method"
                    v-model="depositForm.payment_method"
                    label="Payment Method"
                    required
                    :error="depositForm.errors.payment_method"
                    :options="['Pay with Credit/Debit Card', 'GCash', 'GrabPay', 'Pay Through Bills Payment']"
                />
            </div>

            <template #footer>
                <Button variant="secondary" @click="showDepositModal = false"><X class="h-4 w-4" />Close</Button>
                <Button :disabled="depositForm.processing" @click="submitDeposit"><Plus class="h-4 w-4" />Deposit</Button>
            </template>
        </Modal>
    </AuthenticatedLayout>
</template>
