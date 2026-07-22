<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { Upload } from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Checkbox from '@/Components/Checkbox.vue';
import WalletBanner from '@/Components/MotorInsurance/WalletBanner.vue';

defineProps({
    wallet: Object,
});

const form = useForm({
    document_type: 'Certificate of Registration (CR)',
    image: null,
    consent: true,
});

function submit() {
    form.post(route('motor-risks.ocr.store'), { forceFormData: true });
}
</script>

<template>
    <Head title="Motor CTPL - Using OCR" />

    <AuthenticatedLayout>
        <div class="mx-auto max-w-2xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Motor CTPL - Using OCR</h2>

            <WalletBanner :wallet="wallet" />

            <Card title="Scan Certificate of Registration">
                <form class="space-y-5" @submit.prevent="submit">
                    <div>
                        <p class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Document type</p>
                        <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                            <input type="radio" checked class="h-4 w-4 border-gray-300 text-primary-600 focus:ring-primary-500" />
                            Certificate of Registration (CR)
                        </label>
                    </div>

                    <div>
                        <label for="image" class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Image for scanning <span class="text-status-critical">*</span>
                        </label>
                        <input
                            id="image"
                            type="file"
                            accept="image/*,.pdf"
                            class="block w-full rounded-lg border border-gray-300 bg-white text-sm text-gray-600 shadow-sm file:mr-4 file:rounded-md file:border-0 file:bg-primary-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-700 hover:file:bg-primary-100 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300"
                            @change="(e) => (form.image = e.target.files[0])"
                        />
                        <p v-if="form.errors.image" class="mt-1 text-xs font-medium text-status-critical">{{ form.errors.image }}</p>
                    </div>

                    <label class="flex items-start gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <Checkbox v-model:checked="form.consent" class="mt-0.5" />
                        Privacy Consent Statement — I consent to the collection and processing of the data in this document in
                        accordance with the Data Privacy Act of 2012 (RA 10173).
                    </label>

                    <div class="flex justify-end">
                        <Button type="submit" :disabled="form.processing"><Upload class="h-4 w-4" />Upload</Button>
                    </div>
                </form>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
