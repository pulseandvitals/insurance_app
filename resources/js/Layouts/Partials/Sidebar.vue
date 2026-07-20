<script setup>
import { computed, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

defineProps({
    open: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['close']);

const page = usePage();
const producer = computed(() => page.props.auth.producer);

const currentPath = computed(() => new URL(page.url, window.location.origin).pathname);

const icons = {
    home: 'M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69zM12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z',
    wallet: 'M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6.75A2.25 2.25 0 0018.75 4.5H5.25A2.25 2.25 0 003 6.75V9',
    deposit: 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z',
    sell: 'M9 14.25l6-6m4.5-3.493V21.75l-3.75-1.875-3.75 1.875-3.75-1.875-3.75 1.875V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0c1.1.128 1.907 1.077 1.907 2.185zM9.75 9h.008v.008H9.75V9zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm4.125 4.5h.008v.008h-.008V13.5zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z',
    online: 'M2.25 18L9 11.25l4.306 4.306a11.95 11.95 0 015.814-5.518l2.74-1.22m0 0l-5.94-2.281m5.94 2.28l-2.28 5.941',
    chevron: 'M8.25 4.5l7.5 7.5-7.5 7.5',
};

const navGroups = ref([
    {
        label: 'Main',
        items: [
            { name: 'Dashboard', route: 'producers.dashboard', icon: 'home', params: () => (producer.value ? { producer: producer.value.id } : null) },
            { name: 'Ewallet', route: 'producers.wallet', icon: 'wallet' },
            { name: 'Deposits', route: 'topups.index', icon: 'deposit' },
        ],
    },
]);

const dropdowns = ref({
    sell: {
        label: 'Sell Insurance',
        icon: 'sell',
        open: false,
        items: [
            { name: 'Motor CTPL - Retail', route: 'motor-risks.create' },
            { name: 'Motor CTPL - Using OCR', route: 'motor-risks.ocr' },
            { name: 'Motor CTPL - Renewal', route: 'policies.renewal' },
        ],
    },
    online: {
        label: 'Online Sales',
        icon: 'online',
        open: false,
        items: [
            { name: 'Quotations Made', route: 'motor-risks.index' },
            { name: 'Policies Sold', route: 'policies.index' },
            { name: 'Policies Sold - Extractions', route: 'policies.extractions' },
            { name: 'Batch COC Extraction', route: 'policies.batch-coc' },
        ],
    },
});

function isDropdownActive(dropdown) {
    return dropdown.items.some((item) => route().current(item.route));
}

function toggleDropdown(key) {
    dropdowns.value[key].open = !dropdowns.value[key].open;
}

// Auto-expand the dropdown containing the active route.
Object.entries(dropdowns.value).forEach(([key, dropdown]) => {
    if (isDropdownActive(dropdown)) dropdown.open = true;
});
</script>

<template>
    <!-- Mobile backdrop -->
    <div v-show="open" class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden" @click="$emit('close')"></div>

    <aside
        class="fixed inset-y-0 left-0 z-40 flex w-72 shrink-0 -translate-x-full transform flex-col border-r border-gray-200 bg-white transition-transform duration-200 ease-in-out dark:border-gray-700 dark:bg-gray-800 lg:static lg:translate-x-0"
        :class="{ 'translate-x-0': open }"
    >
        <!-- Brand -->
        <div class="flex h-16 shrink-0 items-center gap-2 border-b border-gray-200 px-6 dark:border-gray-700">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-primary-600 text-sm font-bold text-white">FG</span>
            <div class="leading-tight">
                <p class="text-sm font-bold tracking-tight text-gray-900 dark:text-gray-100">InsurApp</p>
                <p class="text-[11px] text-gray-400 dark:text-gray-500">Producers' Portal</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
            <template v-for="group in navGroups" :key="group.label">
                <Link
                    v-for="item in group.items"
                    :key="item.name"
                    :href="item.params ? (item.params() ? route(item.route, item.params()) : '#') : route(item.route)"
                    :class="[
                        'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                        route().current(item.route) ? 'bg-primary-50 text-primary-700 dark:bg-primary-950/50 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700/60 dark:hover:text-gray-100',
                    ]"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="icons[item.icon]" />
                    </svg>
                    {{ item.name }}
                </Link>
            </template>

            <div class="my-3 border-t border-gray-100 dark:border-gray-700"></div>

            <div v-for="(dropdown, key) in dropdowns" :key="key">
                <button
                    type="button"
                    class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors"
                    :class="isDropdownActive(dropdown) ? 'text-primary-700 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700/60 dark:hover:text-gray-100'"
                    @click="toggleDropdown(key)"
                >
                    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="icons[dropdown.icon]" />
                    </svg>
                    <span class="flex-1 text-left">{{ dropdown.label }}</span>
                    <svg
                        class="h-4 w-4 shrink-0 transition-transform"
                        :class="{ 'rotate-90': dropdown.open }"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="2"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" :d="icons.chevron" />
                    </svg>
                </button>

                <div v-show="dropdown.open" class="ml-4 mt-1 space-y-1 border-l border-gray-200 pl-4 dark:border-gray-700">
                    <Link
                        v-for="item in dropdown.items"
                        :key="item.name"
                        :href="route(item.route)"
                        :class="[
                            'block rounded-lg px-3 py-2 text-sm transition-colors',
                            route().current(item.route) ? 'bg-primary-50 font-medium text-primary-700 dark:bg-primary-950/50 dark:text-primary-300' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-800 dark:text-gray-400 dark:hover:bg-gray-700/60 dark:hover:text-gray-100',
                        ]"
                    >
                        {{ item.name }}
                    </Link>
                </div>
            </div>
        </nav>

        <div class="shrink-0 border-t border-gray-200 p-4 dark:border-gray-700">
            <p class="text-[11px] leading-relaxed text-gray-400 dark:text-gray-500">
                Copyright &copy; 2018-{{ new Date().getFullYear() }} Fortune General Insurance Corp. All rights reserved.
            </p>
        </div>
    </aside>
</template>
