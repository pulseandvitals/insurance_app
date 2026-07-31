<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { ArrowLeft, Trash2 } from 'lucide-vue-next';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';

const props = defineProps({
    error: Object,
});

function destroy() {
    if (!confirm('Delete this error log?')) return;
    router.delete(route('admin.dev.errors.destroy', props.error.id));
}
</script>

<template>
    <Head title="Error Detail" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <Link :href="route('admin.dev.errors.index')" class="mb-1 flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <ArrowLeft class="h-3.5 w-3.5" />Back to Error Logs
                    </Link>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ error.exception_class }}</h2>
                </div>
                <Button variant="danger" size="sm" @click="destroy">
                    <Trash2 class="h-4 w-4" />Delete
                </Button>
            </div>
        </template>

        <div class="mx-auto max-w-5xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
            <Card title="Overview">
                <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="text-xs uppercase text-gray-400">Endpoint</dt>
                        <dd class="mt-1 flex items-center gap-2">
                            <Badge>{{ error.method ?? '—' }}</Badge>
                            <span class="break-all font-mono text-xs text-gray-700 dark:text-gray-300">{{ error.url ?? '—' }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-gray-400">Route</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ error.route_name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-gray-400">Status Code</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ error.status_code ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-gray-400">User</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ error.user?.name ?? 'Guest' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-gray-400">IP Address</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ error.ip ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase text-gray-400">Occurred</dt>
                        <dd class="mt-1 font-medium text-gray-900 dark:text-gray-100">{{ new Date(error.created_at).toLocaleString() }}</dd>
                    </div>
                </dl>
            </Card>

            <Card title="Message">
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ error.message }}</p>
            </Card>

            <Card title="Location">
                <p class="break-all font-mono text-xs text-gray-600 dark:text-gray-300">{{ error.file }}:{{ error.line }}</p>
            </Card>

            <Card title="Stack Trace">
                <pre class="max-h-[32rem] overflow-auto rounded-lg bg-gray-900 p-4 text-xs leading-relaxed text-gray-200">{{ error.trace }}</pre>
            </Card>
        </div>
    </AdminLayout>
</template>
