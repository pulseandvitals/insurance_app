<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    open: {
        type: Boolean,
        default: false,
    },
});

defineEmits(['close']);

const icons = {
    home: 'M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69zM12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z',
    branch: 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21',
    users: 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z',
    pricing: 'M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
};

const navItems = [
    { name: 'Dashboard', route: 'admin.dashboard', icon: 'home' },
    { name: 'Branches', route: 'admin.branches.index', icon: 'branch' },
    { name: 'Producers', route: 'admin.users.index', icon: 'users' },
    { name: 'Global Pricing', route: 'admin.pricing.edit', icon: 'pricing' },
];
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
            <img src="/logo/stronghold_logo.png" alt="Stronghold" class="h-9 w-9 rounded-lg object-contain" />
            <div class="leading-tight">
                <p class="text-sm font-bold tracking-tight text-gray-900 dark:text-gray-100">InsurApp</p>
                <p class="text-[11px] text-gray-400 dark:text-gray-500">Admin</p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
            <Link
                v-for="item in navItems"
                :key="item.name"
                :href="route(item.route)"
                :class="[
                    'group flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                    route().current(item.route + '*') ? 'bg-primary-50 text-primary-700 dark:bg-primary-950/50 dark:text-primary-300' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-300 dark:hover:bg-gray-700/60 dark:hover:text-gray-100',
                ]"
            >
                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" :d="icons[item.icon]" />
                </svg>
                {{ item.name }}
            </Link>
        </nav>

        <div class="shrink-0 border-t border-gray-200 p-4 dark:border-gray-700">
            <p class="text-[11px] leading-relaxed text-gray-400 dark:text-gray-500">
                Copyright &copy; 2018-{{ new Date().getFullYear() }} Stronghold. All rights reserved.
            </p>
        </div>
    </aside>
</template>
