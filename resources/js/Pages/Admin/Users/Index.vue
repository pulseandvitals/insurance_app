<script setup>
import { ref, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Pencil, Inbox } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';

const props = defineProps({
    users: Array,
    branches: Array,
    filters: Object,
});

const search = ref(props.filters.search ?? '');
const branchId = ref(props.filters.branch_id ?? '');

function applyFilters() {
    router.get(
        route('admin.users.index'),
        { search: search.value || undefined, branch_id: branchId.value || undefined },
        { preserveState: true, replace: true },
    );
}

let debounceTimer;
watch(search, () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(applyFilters, 350);
});
watch(branchId, applyFilters);
</script>

<template>
    <Head title="Producers" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Producers</h2>
                <Link :href="route('admin.users.create')">
                    <Button><Plus class="h-4 w-4" />New Producer</Button>
                </Link>
            </div>
        </template>

        <div class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <Card>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <Input id="search" v-model="search" label="Search" placeholder="Name or email" class="sm:col-span-2" />
                    <Select
                        id="branch_id"
                        v-model="branchId"
                        label="Branch"
                        placeholder="All Branches"
                        :options="branches.map((b) => ({ value: b.id, label: b.name }))"
                    />
                </div>
            </Card>

            <Card :padded="false">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-900/40 dark:text-gray-400">
                            <tr>
                                <th class="px-5 py-3 font-medium">Name</th>
                                <th class="px-5 py-3 font-medium">Email</th>
                                <th class="px-5 py-3 font-medium">Phone</th>
                                <th class="px-5 py-3 font-medium">Branch</th>
                                <th class="px-5 py-3 font-medium">Status</th>
                                <th class="px-5 py-3 text-right font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr v-for="user in users" :key="user.id" class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-5 py-3 font-medium text-gray-900 dark:text-gray-100">{{ user.producer?.full_name ?? user.name }}</td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ user.email }}</td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ user.producer?.phone ?? '—' }}</td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ user.producer?.branch?.name ?? '—' }}</td>
                                <td class="px-5 py-3">
                                    <Badge :variant="user.producer?.status === 'Active' ? 'good' : 'gray'">{{ user.producer?.status ?? '—' }}</Badge>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex justify-end">
                                        <Link :href="route('admin.users.edit', user.id)">
                                            <Button size="sm" variant="ghost"><Pencil class="h-3.5 w-3.5" />Edit</Button>
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="users.length === 0">
                                <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <Inbox class="h-8 w-8 text-gray-300 dark:text-gray-600" />
                                        No producers found.
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </Card>
        </div>
    </AdminLayout>
</template>
