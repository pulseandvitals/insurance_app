<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { Search } from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import WalletBanner from '@/Components/MotorInsurance/WalletBanner.vue';

defineProps({
    wallet: Object,
});

const form = useForm({
    online_policy_no: '',
    chassis_no: '',
    motor_no: '',
});

function submit() {
    form.post(route('policies.renewal.search'));
}
</script>

<template>
    <Head title="Motor CTPL - Renewal" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-2xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Motor CTPL - Renewal</h2>

            <WalletBanner :wallet="wallet" />

            <Card title="Enter any of the following fields">
                <form class="space-y-5" @submit.prevent="submit">
                    <Input id="online_policy_no" v-model="form.online_policy_no" label="Online Policy Number" :error="form.errors.online_policy_no" />
                    <Input id="chassis_no" v-model="form.chassis_no" label="Chassis Number" :error="form.errors.chassis_no" />
                    <Input id="motor_no" v-model="form.motor_no" label="Engine Number" :error="form.errors.motor_no" />

                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Not sure of your Online Policy Number? Contact
                        <a href="#" class="font-medium text-primary-600 hover:underline">Customer Care</a>.
                    </p>

                    <div class="flex justify-end">
                        <Button type="submit" :disabled="form.processing"><Search class="h-4 w-4" />Search</Button>
                    </div>
                </form>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
