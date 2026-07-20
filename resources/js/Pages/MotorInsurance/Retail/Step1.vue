<script setup>
import { computed, watch } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Select from '@/Components/UI/Select.vue';
import Input from '@/Components/UI/Input.vue';
import SearchableSelect from '@/Components/UI/SearchableSelect.vue';
import Banner from '@/Components/UI/Banner.vue';
import WalletBanner from '@/Components/MotorInsurance/WalletBanner.vue';
import WizardSteps from '@/Components/MotorInsurance/WizardSteps.vue';
import { vehicleClasses, yearModels } from '@/Data/vehicleCatalog';

const props = defineProps({
    wallet: Object,
    prefill: Object,
    catalog: {
        type: Object,
        default: () => ({}),
    },
});

const currentYear = new Date().getFullYear();

const form = useForm({
    lto_registration_type: props.prefill?.lto_registration_type ?? '',
    vehicle_class: props.prefill?.vehicle_class ?? '',
    coverage_period: '1',
    surplus_vehicle: false,
    year_model: props.prefill?.year_model ?? '',
    brand: props.prefill?.brand ?? '',
    model: '',
    variant: '',
    plate_no: props.prefill?.plate_no ?? '',
});

// Reset dependent cascading fields when a parent selection changes.
watch(() => form.vehicle_class, () => { form.brand = ''; form.model = ''; form.variant = ''; });
watch(() => form.brand, () => { form.model = ''; form.variant = ''; });
watch(() => form.model, () => { form.variant = ''; });

function findByName(list, name) {
    const needle = (name ?? '').trim().toLowerCase();
    return needle ? list.find((item) => item.name.toLowerCase() === needle) : undefined;
}

const brandsForClass = computed(() => props.catalog[form.vehicle_class] ?? []);
const brandOptions = computed(() => brandsForClass.value.map((b) => b.name));

const currentBrand = computed(() => findByName(brandsForClass.value, form.brand));
const modelOptions = computed(() => currentBrand.value?.models.map((m) => m.name) ?? []);

const currentModel = computed(() => findByName(currentBrand.value?.models ?? [], form.model));
const variantOptions = computed(() => currentModel.value?.variants.map((v) => v.name) ?? []);

const oneYearBlockedForNew = computed(
    () => form.lto_registration_type === 'New' && !form.surplus_vehicle && form.coverage_period === '1',
);

const yearRequiresNewRegistration = computed(
    () => form.year_model && Number(form.year_model) >= currentYear && form.lto_registration_type && form.lto_registration_type !== 'New',
);

const blocked = computed(() => oneYearBlockedForNew.value || yearRequiresNewRegistration.value);

function submit() {
    form.post(route('motor-risks.store'));
}
</script>

<template>
    <Head title="Motor CTPL - Retail" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-4xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <WizardSteps :current="1" title="Vehicle Info" />

            <WalletBanner :wallet="wallet" />

            <Banner v-if="prefill" variant="good" title="Extracted from your uploaded document">
                Please review the pre-filled details below before creating your quote.
            </Banner>

            <Banner v-if="oneYearBlockedForNew" variant="critical" title="1-Year term unavailable">
                A 1-year CTPL term for "New" registration type is only allowed for Surplus vehicles. Please select the
                3-Year coverage period, or mark this as a Surplus Vehicle.
            </Banner>
            <Banner v-else-if="yearRequiresNewRegistration" variant="critical" title="Registration type mismatch">
                Vehicles with year model {{ form.year_model }} or later must use Registration Type: New.
            </Banner>

            <Card title="Vehicle Info">
                <form class="space-y-5" @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <Select
                            id="lto_registration_type"
                            v-model="form.lto_registration_type"
                            label="LTO Registration Type"
                            required
                            :error="form.errors.lto_registration_type"
                            :options="['New', 'Renewal']"
                        />
                        <Select
                            id="vehicle_class"
                            v-model="form.vehicle_class"
                            label="Vehicle Class"
                            required
                            :error="form.errors.vehicle_class"
                            :options="vehicleClasses"
                        />
                        <Select
                            id="coverage_period"
                            v-model="form.coverage_period"
                            label="CTPL Coverage Period"
                            required
                            :error="form.errors.coverage_period"
                            :options="[{ value: '1', label: '1 Year' }, { value: '3', label: '3 Years' }]"
                        />
                        <Select
                            id="surplus_vehicle"
                            :model-value="form.surplus_vehicle ? '1' : '0'"
                            label="Surplus Vehicle"
                            :options="[{ value: '0', label: 'No' }, { value: '1', label: 'Yes' }]"
                            @update:model-value="(v) => (form.surplus_vehicle = v === '1')"
                        />
                        <Select
                            id="year_model"
                            v-model="form.year_model"
                            label="Year Model"
                            required
                            :error="form.errors.year_model"
                            :options="yearModels"
                        />
                        <SearchableSelect
                            id="brand"
                            v-model="form.brand"
                            label="Brand (Make)"
                            required
                            creatable
                            entry-label="brand"
                            placeholder="Type or select a brand..."
                            :error="form.errors.brand"
                            :disabled="!form.vehicle_class"
                            :options="brandOptions"
                        />
                        <SearchableSelect
                            id="model"
                            v-model="form.model"
                            label="Model (Type)"
                            required
                            creatable
                            entry-label="model"
                            placeholder="Type or select a model..."
                            :error="form.errors.model"
                            :disabled="!form.brand"
                            :options="modelOptions"
                        />
                        <SearchableSelect
                            id="variant"
                            v-model="form.variant"
                            label="Variant (Trim)"
                            required
                            creatable
                            entry-label="variant"
                            placeholder="Type or select a variant..."
                            :error="form.errors.variant"
                            :disabled="!form.model"
                            :options="variantOptions"
                        />
                    </div>

                    <p class="text-xs text-gray-400">
                        Don't see your vehicle's Brand, Model, or Variant? Just type it — it'll be saved and available
                        to select the next time you or another producer processes the same vehicle.
                    </p>

                    <Input
                        id="plate_no"
                        v-model="form.plate_no"
                        label="Plate No."
                        required
                        :error="form.errors.plate_no"
                        :placeholder="form.lto_registration_type === 'New' ? 'NEW' : 'e.g. NBA1234'"
                        help="If you don't have a plate number, please input your Conduction Sticker Number."
                    />

                    <div class="flex items-center justify-between pt-2">
                        <Link :href="route('dashboard')"><Button type="button" variant="secondary">Back</Button></Link>
                        <Button type="submit" :disabled="form.processing || blocked">Create Quote</Button>
                    </div>
                </form>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
