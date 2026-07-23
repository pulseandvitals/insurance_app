<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Save } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';

const props = defineProps({
    branch: Object,
});

const form = useForm({
    code: props.branch.code,
    name: props.branch.name,
    address: props.branch.address ?? '',
    status: props.branch.status,
});

function submit() {
    form.patch(route('admin.branches.update', props.branch.id));
}
</script>

<template>
    <Head title="Edit Branch" />

    <AdminLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Edit Branch</h2>
        </template>

        <div class="mx-auto max-w-2xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <Card>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <Input id="code" v-model="form.code" label="Branch Code" required :error="form.errors.code" />
                    <Input id="name" v-model="form.name" label="Branch Name" required :error="form.errors.name" />
                    <Input id="address" v-model="form.address" label="Address" :error="form.errors.address" class="sm:col-span-2" />
                    <Select
                        id="status"
                        v-model="form.status"
                        label="Status"
                        required
                        :options="[{ value: 'Active', label: 'Active' }, { value: 'Inactive', label: 'Inactive' }]"
                        :error="form.errors.status"
                    />
                </div>
            </Card>

            <div class="flex justify-end">
                <Button :disabled="form.processing" @click="submit"><Save class="h-4 w-4" />Save Changes</Button>
            </div>
        </div>
    </AdminLayout>
</template>
