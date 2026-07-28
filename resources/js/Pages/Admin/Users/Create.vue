<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Save } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';

const props = defineProps({
    branches: Array,
});

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    branch_id: '',
    first_name: '',
    middle_name: '',
    last_name: '',
    suffix: '',
    address: '',
    phone: '',
    others_fee: '10.00',
    coc_verification_fee: '40.40',
    motorcycle_price: '160.00',
    pc_suv_price: '260.00',
    cv_truck_price: '450.00',
});

function submit() {
    form.post(route('admin.users.store'));
}
</script>

<template>
    <Head title="New Producer" />

    <AdminLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">New Producer</h2>
        </template>

        <div class="mx-auto max-w-3xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <Card title="Login Credentials">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <Input id="name" v-model="form.name" label="Full Name" required :error="form.errors.name" class="sm:col-span-2" />
                    <Input id="email" v-model="form.email" type="email" label="Email" required :error="form.errors.email" class="sm:col-span-2" />
                    <Input id="password" v-model="form.password" type="password" label="Password" required :error="form.errors.password" />
                    <Input id="password_confirmation" v-model="form.password_confirmation" type="password" label="Confirm Password" required :error="form.errors.password_confirmation" />
                </div>
            </Card>

            <Card title="Producer Profile">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <Input id="first_name" v-model="form.first_name" label="First Name" required :error="form.errors.first_name" />
                    <Input id="middle_name" v-model="form.middle_name" label="Middle Name" :error="form.errors.middle_name" />
                    <Input id="last_name" v-model="form.last_name" label="Last Name" required :error="form.errors.last_name" />
                    <Input id="suffix" v-model="form.suffix" label="Suffix" :error="form.errors.suffix" />
                    <Input id="phone" v-model="form.phone" label="Phone" required :error="form.errors.phone" />
                    <Select
                        id="branch_id"
                        v-model="form.branch_id"
                        label="Branch"
                        required
                        :options="branches.map((b) => ({ value: b.id, label: b.name }))"
                        :error="form.errors.branch_id"
                    />
                    <Input id="address" v-model="form.address" label="Address" required :error="form.errors.address" class="sm:col-span-3" />
                </div>
            </Card>

            <Card title="Pricing Configuration" subtitle="Defaults to the standard rates below — override per producer if needed.">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <Input id="others_fee" v-model="form.others_fee" type="number" label="Others Fee" required :error="form.errors.others_fee" />
                    <Input id="coc_verification_fee" v-model="form.coc_verification_fee" type="number" label="COC Verification" required :error="form.errors.coc_verification_fee" />
                </div>
                <p class="mb-1 mt-5 text-sm font-medium text-gray-700 dark:text-gray-300">Issuance Price per Product</p>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <Input id="motorcycle_price" v-model="form.motorcycle_price" type="number" label="Motorcycle" required :error="form.errors.motorcycle_price" />
                    <Input id="pc_suv_price" v-model="form.pc_suv_price" type="number" label="PC (SUV)" required :error="form.errors.pc_suv_price" />
                    <Input id="cv_truck_price" v-model="form.cv_truck_price" type="number" label="CV (Trucks)" required :error="form.errors.cv_truck_price" />
                </div>
            </Card>

            <div class="flex justify-end">
                <Button :disabled="form.processing" @click="submit"><Save class="h-4 w-4" />Create Producer</Button>
            </div>
        </div>
    </AdminLayout>
</template>
