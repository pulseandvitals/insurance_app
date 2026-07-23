<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Save } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';

const props = defineProps({
    user: Object,
    branches: Array,
});

const form = useForm({
    branch_id: props.user.producer.branch_id,
    first_name: props.user.producer.first_name,
    middle_name: props.user.producer.middle_name ?? '',
    last_name: props.user.producer.last_name,
    suffix: props.user.producer.suffix ?? '',
    address: props.user.producer.address,
    phone: props.user.producer.phone,
    status: props.user.producer.status,
});

function submit() {
    form.patch(route('admin.users.update', props.user.id));
}
</script>

<template>
    <Head title="Edit Producer" />

    <AdminLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Edit Producer</h2>
        </template>

        <div class="mx-auto max-w-3xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <Card title="Login Credentials">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <Input id="name" :model-value="user.name" label="Full Name" disabled valid />
                    <Input id="email" :model-value="user.email" label="Email" disabled valid />
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
                        id="status"
                        v-model="form.status"
                        label="Status"
                        required
                        :options="[{ value: 'Active', label: 'Active' }, { value: 'Inactive', label: 'Inactive' }]"
                        :error="form.errors.status"
                    />
                    <Input id="address" v-model="form.address" label="Address" required :error="form.errors.address" class="sm:col-span-3" />
                    <Select
                        id="branch_id"
                        v-model="form.branch_id"
                        label="Branch"
                        required
                        :options="branches.map((b) => ({ value: b.id, label: b.name }))"
                        :error="form.errors.branch_id"
                        class="sm:col-span-3"
                    />
                </div>
            </Card>

            <div class="flex justify-end">
                <Button :disabled="form.processing" @click="submit"><Save class="h-4 w-4" />Save Changes</Button>
            </div>
        </div>
    </AdminLayout>
</template>
