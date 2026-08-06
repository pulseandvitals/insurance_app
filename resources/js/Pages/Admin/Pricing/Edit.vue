<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Save } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';

const props = defineProps({
    pricing: Object,
});

const form = useForm({
    motorcycle_base_rate: props.pricing.motorcycle_base_rate,
    pc_suv_base_rate: props.pricing.pc_suv_base_rate,
    cv_truck_base_rate: props.pricing.cv_truck_base_rate,
    motorcycle_remittance_rate: props.pricing.motorcycle_remittance_rate,
    pc_suv_remittance_rate: props.pricing.pc_suv_remittance_rate,
    cv_truck_remittance_rate: props.pricing.cv_truck_remittance_rate,
    doc_stamp_tax_percent: props.pricing.doc_stamp_tax_percent,
    vat_percent: props.pricing.vat_percent,
    local_govt_tax_percent: props.pricing.local_govt_tax_percent,
    lto_dbp_dci_fee: props.pricing.lto_dbp_dci_fee,
});

function submit() {
    form.put(route('admin.pricing.update'));
}
</script>

<template>
    <Head title="Global Pricing" />

    <AdminLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Global Pricing</h2>
        </template>

        <div class="mx-auto max-w-2xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <Card title="Base Premium Rates" subtitle="Total policy price per vehicle category, inclusive of all taxes and fees below. A 3-year term multiplies this by 3. Net premium is back-calculated to make the breakdown add up to this total.">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <Input id="motorcycle_base_rate" v-model="form.motorcycle_base_rate" type="number" label="Motorcycle" required :error="form.errors.motorcycle_base_rate" />
                    <Input id="pc_suv_base_rate" v-model="form.pc_suv_base_rate" type="number" label="PC (SUV)" required :error="form.errors.pc_suv_base_rate" />
                    <Input id="cv_truck_base_rate" v-model="form.cv_truck_base_rate" type="number" label="CV (Trucks)" required :error="form.errors.cv_truck_base_rate" />
                </div>
            </Card>

            <Card title="Head Office Remittance" subtitle="The fixed amount remitted to head office per issuance, per vehicle category. The admin dashboard reports profit as the producer's issuance price minus this rate (scaled by coverage period).">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <Input id="motorcycle_remittance_rate" v-model="form.motorcycle_remittance_rate" type="number" label="Motorcycle" required :error="form.errors.motorcycle_remittance_rate" />
                    <Input id="pc_suv_remittance_rate" v-model="form.pc_suv_remittance_rate" type="number" label="PC (SUV)" required :error="form.errors.pc_suv_remittance_rate" />
                    <Input id="cv_truck_remittance_rate" v-model="form.cv_truck_remittance_rate" type="number" label="CV (Trucks)" required :error="form.errors.cv_truck_remittance_rate" />
                </div>
            </Card>

            <Card title="Taxes & Fees" subtitle="Deducted from the total above to derive net premium — applied to every policy computation, regardless of producer.">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <Input id="doc_stamp_tax_percent" v-model="form.doc_stamp_tax_percent" type="number" label="Doc Stamp Tax (%)" required :error="form.errors.doc_stamp_tax_percent" />
                    <Input id="vat_percent" v-model="form.vat_percent" type="number" label="Value Added Tax (%)" required :error="form.errors.vat_percent" />
                    <Input id="local_govt_tax_percent" v-model="form.local_govt_tax_percent" type="number" label="Local Government Tax (%)" required :error="form.errors.local_govt_tax_percent" />
                    <Input id="lto_dbp_dci_fee" v-model="form.lto_dbp_dci_fee" type="number" label="LTO DBP-DCI Fee" required :error="form.errors.lto_dbp_dci_fee" help="Fixed amount, not scaled by coverage period." />
                </div>
            </Card>

            <div class="flex justify-end">
                <Button :disabled="form.processing" @click="submit"><Save class="h-4 w-4" />Save Changes</Button>
            </div>
        </div>
    </AdminLayout>
</template>
