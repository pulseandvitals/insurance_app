<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Banner from '@/Components/UI/Banner.vue';
import { csrfToken } from '@/Composables/useCsrfToken';

function nowLocal() {
    const d = new Date();
    d.setMinutes(d.getMinutes() - d.getTimezoneOffset());
    return d.toISOString().slice(0, 16);
}

const dateFrom = ref(nowLocal());
const dateTo = ref(nowLocal());
</script>

<template>
    <Head title="Batch COC Extraction" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Generation of Batch COC</h2>
        </template>

        <div class="mx-auto max-w-2xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <Banner title="Instructions">
                Select a date/time range and click "Generate Batch COC to PDF" to download a single PDF bundling the
                Certificate/s of Cover for every policy issued within that period.
            </Banner>

            <Card>
                <form method="POST" :action="route('policies.batch-coc.export')" class="space-y-5">
                    <input type="hidden" name="_token" :value="csrfToken()" />

                    <Input id="date_from" v-model="dateFrom" name="date_from" type="datetime-local" label="Date From" required />
                    <Input id="date_to" v-model="dateTo" name="date_to" type="datetime-local" label="Date To" required />

                    <div class="flex justify-end">
                        <Button type="submit">Generate Batch COC to PDF</Button>
                    </div>
                </form>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
