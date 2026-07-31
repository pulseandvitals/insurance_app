<script setup>
import { ref, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { Inbox } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Pagination from '@/Components/UI/Pagination.vue';

const props = defineProps({
    errors: Object,
    filters: Object,
});

const search = ref(props.filters.search ?? '');
const method = ref(props.filters.method ?? '');

function applyFilters() {
    router.get(
        route('admin.dev.errors.index'),
        { search: search.value || undefined, method: method.value || undefined },
        { preserveState: true, replace: true },
    );
}

let debounceTimer;
watch(search, () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(applyFilters, 350);
});
watch(method, applyFilters);

function methodVariant(m) {
    return { GET: 'gray', POST: 'primary', PUT: 'warning', PATCH: 'warning', DELETE: 'critical' }[m] ?? 'gray';
}
</script>

<template>
    <Head title="Error Logs" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Error Logs</h2>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <Card>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <Input id="search" v-model="search" label="Search" placeholder="Message, endpoint, or exception" class="sm:col-span-2" />
                    <Select
                        id="method"
                        v-model="method"
                        label="Method"
                        placeholder="All Methods"
                        :options="['GET', 'POST', 'PUT', 'PATCH', 'DELETE'].map((m) => ({ value: m, label: m }))"
                    />
                </div>
            </Card>

            <Card :padded="false">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-900/40 dark:text-gray-400">
                            <tr>
                                <th class="px-5 py-3 font-medium">Endpoint</th>
                                <th class="px-5 py-3 font-medium">Exception</th>
                                <th class="px-5 py-3 font-medium">Message</th>
                                <th class="px-5 py-3 font-medium">When</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr
                                v-for="error in errors.data"
                                :key="error.id"
                                class="cursor-pointer transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/30"
                                @click="router.get(route('admin.dev.errors.show', error.id))"
                            >
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <Badge :variant="methodVariant(error.method)">{{ error.method ?? '—' }}</Badge>
                                        <span class="truncate font-mono text-xs text-gray-600 dark:text-gray-300">{{ error.url ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ error.exception_class }}</td>
                                <td class="max-w-md truncate px-5 py-3 text-gray-600 dark:text-gray-300">{{ error.message }}</td>
                                <td class="px-5 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                    {{ new Date(error.created_at).toLocaleString('en-PH', { dateStyle: 'medium', timeStyle: 'short' }) }}
                                </td>
                            </tr>
                            <tr v-if="errors.data.length === 0">
                                <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <Inbox class="h-8 w-8 text-gray-300 dark:text-gray-600" />
                                        No errors recorded.
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700">
                    <Pagination :paginator="errors" />
                </div>
            </Card>
        </div>
    </AdminLayout>
</template>
