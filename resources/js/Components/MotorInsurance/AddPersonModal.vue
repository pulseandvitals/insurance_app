<script setup>
import { computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { X, UserPlus } from 'lucide-vue-next';
import Modal from '@/Components/UI/Modal.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import SearchableSelect from '@/Components/UI/SearchableSelect.vue';
import Button from '@/Components/UI/Button.vue';
import Checkbox from '@/Components/Checkbox.vue';
import { regions, provincesFor, citiesFor, barangaysFor } from '@/Data/addressData';

const props = defineProps({
    show: Boolean,
    motorQuoteId: [Number, String],
});
const emit = defineEmits(['close']);

const days = Array.from({ length: 31 }, (_, i) => i + 1);
const years = Array.from({ length: 90 }, (_, i) => new Date().getFullYear() - 18 - i);

const form = useForm({
    type: 'person',
    first_name: '',
    middle_name: '',
    last_name: '',
    suffix: '',
    birth_day: '',
    birth_year: '',
    street: '',
    email: '',
    region: '',
    province: '',
    city: '',
    barangay: '',
    contact_number: '',
    consent: true,
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
    <Modal :show="show" title="Add Person" max-width="lg" @close="$emit('close')">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <Input id="p_first_name" v-model="form.first_name" label="First name" required :error="form.errors.first_name" />
            <Input id="p_middle_name" v-model="form.middle_name" label="Middle name" :error="form.errors.middle_name" />
            <Input id="p_last_name" v-model="form.last_name" label="Last name" required :error="form.errors.last_name" />
            <Input id="p_suffix" v-model="form.suffix" label="Suffix" :error="form.errors.suffix" />
        </div>

        <div class="mt-4">
            <p class="mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Birth Date</p>
            <div class="grid grid-cols-2 gap-4">
                <Select id="p_birth_day" v-model="form.birth_day" placeholder="Day" :options="days" />
                <Select id="p_birth_year" v-model="form.birth_year" placeholder="Year" :options="years" />
            </div>
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <Input id="p_street" v-model="form.street" label="Building or Street No. + Street Name" required :error="form.errors.street" class="sm:col-span-2" />
            <Input id="p_email" v-model="form.email" type="email" label="Email" required :error="form.errors.email" />
            <Input id="p_contact_number" v-model="form.contact_number" label="Contact Number" required :error="form.errors.contact_number" help="Mobile (12-digit) or landline (8-digit)" />
        </div>

        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <SearchableSelect id="p_region" v-model="form.region" label="Region" required placeholder="Search regions..." :error="form.errors.region" :options="regions()" />
            <SearchableSelect id="p_province" v-model="form.province" label="Province" required placeholder="Search provinces..." :error="form.errors.province" :disabled="!form.region" :options="provinceOptions" />
            <SearchableSelect id="p_city" v-model="form.city" label="City/Municipality" required creatable entry-label="city" placeholder="Search or type a city..." :error="form.errors.city" :disabled="!form.province" :options="cityOptions" />
            <SearchableSelect id="p_barangay" v-model="form.barangay" label="Barangay" creatable entry-label="barangay" placeholder="Search or type a barangay..." :disabled="!form.city" :options="barangayOptions" />
        </div>

        <label class="mt-5 flex items-start gap-2 text-sm text-gray-600 dark:text-gray-300">
            <Checkbox v-model:checked="form.consent" class="mt-0.5" />
            I consent to the collection and processing of my personal data in accordance with the Data Privacy Act of 2012 (RA 10173).
        </label>

        <template #footer>
            <Button variant="secondary" @click="$emit('close')"><X class="h-4 w-4" />Close</Button>
            <Button :disabled="form.processing" @click="submit"><UserPlus class="h-4 w-4" />Add Policyholder</Button>
        </template>
    </Modal>
</template>
