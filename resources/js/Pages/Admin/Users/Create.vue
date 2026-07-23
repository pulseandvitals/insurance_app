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

            <div class="flex justify-end">
                <Button :disabled="form.processing" @click="submit"><Save class="h-4 w-4" />Create Producer</Button>
            </div>
        </div>
    </AdminLayout>
</template>
