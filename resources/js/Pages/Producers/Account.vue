<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Checkbox from '@/Components/Checkbox.vue';

const props = defineProps({
    producer: Object,
});

const form = useForm({
    password: '',
    password_confirmation: '',
    consent: true,
});

function submit() {
    form.patch(route('producers.update', props.producer.id), {
        onSuccess: () => form.reset('password', 'password_confirmation'),
    });
}
</script>

<template>
    <Head title="Account" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Account</h2>
        </template>

        <div class="mx-auto max-w-3xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <Card title="Login Credentials">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <Input id="username" :model-value="producer.code" label="Username" disabled valid />
                    <Input id="email" :model-value="producer.email" label="Email" disabled valid />
                    <Input
                        id="password"
                        v-model="form.password"
                        type="password"
                        label="Password"
                        placeholder="Leave blank to keep current password"
                        :error="form.errors.password"
                        class="sm:col-span-2"
                    />
                    <Input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        label="Confirm Password"
                        :error="form.errors.password_confirmation"
                        class="sm:col-span-2"
                    />
                </div>
            </Card>

            <Card title="User Profile">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <Input id="first_name" :model-value="producer.first_name" label="First Name" disabled valid />
                    <Input id="middle_name" :model-value="producer.middle_name" label="Middle Name" disabled valid />
                    <Input id="last_name" :model-value="producer.last_name" label="Last Name" disabled valid />
                </div>
            </Card>

            <Card title="Data Privacy">
                <label class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-300">
                    <Checkbox v-model:checked="form.consent" class="mt-0.5" />
                    I consent to the collection, use, and processing of my personal data in accordance with the Data
                    Privacy Act of 2012 (RA 10173) and Fortune General Insurance Corp.'s
                    <a href="#" class="font-medium text-primary-600 hover:underline">Privacy Policy</a>.
                </label>
            </Card>

            <div class="flex justify-end">
                <Button :disabled="form.processing" @click="submit">Save Changes</Button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
