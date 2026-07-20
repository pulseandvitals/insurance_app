<script setup>
import { computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/UI/Modal.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Button from '@/Components/UI/Button.vue';
import { regions, provincesFor, citiesFor, barangaysFor } from '@/Data/addressData';

const props = defineProps({
    show: Boolean,
    motorQuoteId: [Number, String],
});
const emit = defineEmits(['close']);

const form = useForm({
    type: 'organization',
    name: '',
    assignee: '',
    tin: '',
    address_line_1: '',
    region: '',
    province: '',
    city: '',
    barangay: '',
    zip_code: '',
    email: '',
    contact_number: '',
});

watch(() => form.region, () => { form.province = ''; form.city = ''; form.barangay = ''; });
watch(() => form.province, () => { form.city = ''; form.barangay = ''; });
watch(() => form.city, () => { form.barangay = ''; });

const provinceOptions = computed(() => provincesFor(form.region));
const cityOptions = computed(() => citiesFor(form.region, form.province));
const barangayOptions = computed(() => barangaysFor(form.region, form.province, form.city));

function submit() {
    form.post(route('motor-risks.policyholders.store', props.motorQuoteId), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            emit('close');
        },
    });
}
</script>

<template>
    <Modal :show="show" title="Add Organization" max-width="lg" @close="$emit('close')">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <Input id="o_name" v-model="form.name" label="Name" required :error="form.errors.name" class="sm:col-span-2" />
            <Input id="o_assignee" v-model="form.assignee" label="Assignee" :error="form.errors.assignee" />
            <Input id="o_tin" v-model="form.tin" label="TIN #" :error="form.errors.tin" />
            <Input id="o_address_line_1" v-model="form.address_line_1" label="Address Line 1" required :error="form.errors.address_line_1" class="sm:col-span-2" />
            <Select id="o_region" v-model="form.region" label="Region" required :error="form.errors.region" :options="regions()" />
            <Select id="o_province" v-model="form.province" label="Province" required :error="form.errors.province" :disabled="!form.region" :options="provinceOptions" />
            <Select id="o_city" v-model="form.city" label="City/Municipality" required :error="form.errors.city" :disabled="!form.province" :options="cityOptions" />
            <Select id="o_barangay" v-model="form.barangay" label="Barangay" required :error="form.errors.barangay" :disabled="!form.city" :options="barangayOptions" />
            <Input id="o_country" model-value="Philippines" label="Country" disabled />
            <Input id="o_zip_code" v-model="form.zip_code" label="ZIP Code" :error="form.errors.zip_code" />
            <Input id="o_email" v-model="form.email" type="email" label="Email" :error="form.errors.email" />
            <Input id="o_contact_number" v-model="form.contact_number" label="Contact Number" :error="form.errors.contact_number" />
        </div>

        <template #footer>
            <Button variant="secondary" @click="$emit('close')">Close</Button>
            <Button :disabled="form.processing" @click="submit">Add Policyholder</Button>
        </template>
    </Modal>
</template>
