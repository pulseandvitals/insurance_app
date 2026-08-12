<script setup>
import { computed, ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { Landmark, Building2, UserPlus, Save, ArrowLeft, ArrowRight, Loader2 } from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import SearchableSelect from '@/Components/UI/SearchableSelect.vue';
import WalletBanner from '@/Components/MotorInsurance/WalletBanner.vue';
import WizardSteps from '@/Components/MotorInsurance/WizardSteps.vue';
import PolicyholderTable from '@/Components/MotorInsurance/PolicyholderTable.vue';
import AddPersonModal from '@/Components/MotorInsurance/AddPersonModal.vue';
import AddOrganizationModal from '@/Components/MotorInsurance/AddOrganizationModal.vue';
import AddLenderModal from '@/Components/MotorInsurance/AddLenderModal.vue';

const props = defineProps({
    quote: Object,
    wallet: Object,
    colors: {
        type: Array,
        default: () => [],
    },
});

function peso(value) {
    return '₱' + Number(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

const detailsForm = useForm({
    authentication_no: props.quote.authentication_no ?? '',
    chassis_no: props.quote.chassis_no ?? '',
    motor_no: props.quote.motor_no ?? '',
    mv_file_no: props.quote.mv_file_no ?? '',
    color: props.quote.color ?? '',
    other_info: props.quote.other_info ?? '',
    inception_date: props.quote.inception_date ?? new Date().toISOString().slice(0, 10),
});

const expiryPreview = computed(() => {
    if (!detailsForm.inception_date) return '';
    const d = new Date(detailsForm.inception_date);
    d.setFullYear(d.getFullYear() + Number(props.quote.coverage_period));
    return d.toISOString().slice(0, 10);
});

function saveDetails() {
    detailsForm.post(route('motor-risks.pre-flight.store', props.quote.id), { preserveScroll: true });
}

const showPerson = ref(false);
const showOrganization = ref(false);
const showLender = ref(false);

const proceeding = ref(false);

function proceedToCheckout() {
    proceeding.value = true;
    router.post(route('motor-risks.authenticate-lto', props.quote.id), {}, {
        onFinish: () => { proceeding.value = false; },
    });
}
</script>

<template>
    <Head title="Buy CTPL/Motor Insurance" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-4xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <WizardSteps :current="2" title="Buy CTPL/Motor Insurance" />

            <WalletBanner :wallet="wallet" />

            <Card>
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ quote.vehicle_title }}</p>
                        <p class="text-sm text-gray-500">Plate No. {{ quote.plate_no }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs uppercase text-gray-400">Total Premium</p>
                        <p class="text-xl font-bold text-primary-600 dark:text-primary-400">{{ peso(quote.total_premium) }}</p>
                    </div>
                </div>
            </Card>

            <Card
                title="Authentication #"
                subtitle="LTO's real-time authentication service is not yet connected. Obtain the authentication number from the existing LTO authentication system and enter it here — it will carry over to the issued policy and print on the Confirmation of Cover."
            >
                <Input
                    id="authentication_no"
                    v-model="detailsForm.authentication_no"
                    label="Authentication #"
                    placeholder="e.g., C151-B5125-C12512"
                    required
                    :error="detailsForm.errors.authentication_no"
                    help="Copy this exactly as issued by the LTO authentication system — letters and numbers, no prefixes added."
                />
            </Card>

            <Card title="Add Vehicle Details">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <Input id="plate_no_locked" :model-value="quote.plate_no" label="Plate No." disabled />
                    <Input
                        id="chassis_no"
                        v-model="detailsForm.chassis_no"
                        label="Chassis/Serial No."
                        required
                        :error="detailsForm.errors.chassis_no"
                        help="This is 15 characters for most vehicles."
                    />
                    <Input id="motor_no" v-model="detailsForm.motor_no" label="Motor/Engine No." required :error="detailsForm.errors.motor_no" />
                    <Input
                        id="mv_file_no"
                        v-model="detailsForm.mv_file_no"
                        label="LTO MV File No."
                        required
                        :error="detailsForm.errors.mv_file_no"
                        help="You can find this in your LTO Certificate of Registration. This is 15 characters."
                    />
                    <SearchableSelect
                        id="color"
                        v-model="detailsForm.color"
                        label="Color"
                        required
                        creatable
                        entry-label="color"
                        :error="detailsForm.errors.color"
                        :options="colors"
                        placeholder="Type or select a color..."
                    />
                    <Input id="other_info" v-model="detailsForm.other_info" label="Other Info" :error="detailsForm.errors.other_info" />
                </div>
            </Card>

            <Card title="Check the Inception Date">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <Input
                        id="inception_date"
                        v-model="detailsForm.inception_date"
                        type="date"
                        label="Policy Inception Date"
                        required
                        :error="detailsForm.errors.inception_date"
                    />
                    <Input id="expiry_date" :model-value="expiryPreview" type="date" label="Policy Expiry Date" disabled />
                </div>

                <div class="mt-4 flex justify-end">
                    <Button variant="secondary" :disabled="detailsForm.processing" @click="saveDetails"><Save class="h-4 w-4" />Save Vehicle Details</Button>
                </div>
            </Card>

            <Card title="Add Policyholder/s">
                <div class="mb-4 flex flex-wrap gap-2">
                    <Button variant="outline" size="sm" @click="showLender = true"><Landmark class="h-3.5 w-3.5" />Add Lender</Button>
                    <Button variant="outline" size="sm" @click="showOrganization = true"><Building2 class="h-3.5 w-3.5" />Add Organization</Button>
                    <Button variant="outline" size="sm" @click="showPerson = true"><UserPlus class="h-3.5 w-3.5" />Add Person</Button>
                </div>

                <PolicyholderTable :motor-quote-id="quote.id" :policyholders="quote.policyholders" />
            </Card>

            <div class="flex items-center justify-between">
                <Button variant="secondary" @click="router.get(route('motor-risks.show', quote.id))"><ArrowLeft class="h-4 w-4" />Back</Button>
                <Button :disabled="proceeding" @click="proceedToCheckout">
                    <Loader2 v-if="proceeding" class="h-4 w-4 animate-spin" />
                    <ArrowRight v-else class="h-4 w-4" />
                    {{ proceeding ? 'Proceeding…' : 'Proceed to Checkout' }}
                </Button>
            </div>
        </div>

        <AddPersonModal :show="showPerson" :motor-quote-id="quote.id" @close="showPerson = false" />
        <AddOrganizationModal :show="showOrganization" :motor-quote-id="quote.id" @close="showOrganization = false" />
        <AddLenderModal :show="showLender" :motor-quote-id="quote.id" @close="showLender = false" />
    </AuthenticatedLayout>
</template>
