<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2, Inbox } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';

defineProps({
    branches: Array,
});

function destroy(branch) {
    if (!confirm(`Delete branch "${branch.name}"? This cannot be undone.`)) return;
    router.delete(route('admin.branches.destroy', branch.id), { preserveScroll: true });
}
</script>

<template>
    <Head title="Branches" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Branches</h2>
                <Link :href="route('admin.branches.create')">
                    <Button><Plus class="h-4 w-4" />New Branch</Button>
                </Link>
            </div>
        </template>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <Card :padded="false">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-500 dark:bg-gray-900/40 dark:text-gray-400">
                            <tr>
                                <th class="px-5 py-3 font-medium">Code</th>
                                <th class="px-5 py-3 font-medium">Name</th>
                                <th class="px-5 py-3 font-medium">Address</th>
                                <th class="px-5 py-3 font-medium">Status</th>
                                <th class="px-5 py-3 text-right font-medium">Producers</th>
                                <th class="px-5 py-3 text-right font-medium">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            <tr v-for="branch in branches" :key="branch.id" class="transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/30">
                                <td class="px-5 py-3 font-mono text-xs text-gray-500 dark:text-gray-400">{{ branch.code }}</td>
                                <td class="px-5 py-3 font-medium text-gray-900 dark:text-gray-100">{{ branch.name }}</td>
                                <td class="px-5 py-3 text-gray-600 dark:text-gray-300">{{ branch.address ?? '—' }}</td>
                                <td class="px-5 py-3"><Badge :variant="branch.status === 'Active' ? 'good' : 'gray'">{{ branch.status }}</Badge></td>
                                <td class="px-5 py-3 text-right tabular-nums text-gray-700 dark:text-gray-200">{{ branch.producers_count }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex flex-wrap justify-end gap-2">
                                        <Link :href="route('admin.branches.edit', branch.id)">
                                            <Button size="sm" variant="ghost"><Pencil class="h-3.5 w-3.5" />Edit</Button>
                                        </Link>
                                        <Button size="sm" variant="danger" @click="destroy(branch)"><Trash2 class="h-3.5 w-3.5" />Delete</Button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="branches.length === 0">
                                <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-400">
                                    <div class="flex flex-col items-center gap-2">
                                        <Inbox class="h-8 w-8 text-gray-300 dark:text-gray-600" />
                                        No branches yet.
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
