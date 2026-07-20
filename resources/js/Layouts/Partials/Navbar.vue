<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import ThemeToggle from '@/Components/UI/ThemeToggle.vue';

defineEmits(['toggle-sidebar']);

const page = usePage();
const producer = computed(() => page.props.auth.producer);

const greeting = computed(() => {
    if (!producer.value) return '';
    const middleInitial = producer.value.middle_name ? `${producer.value.middle_name.charAt(0)}.` : '';
    const nameParts = [producer.value.first_name, middleInitial, producer.value.last_name].filter(Boolean);
    return `Hi, ${nameParts.join(' ')} !`;
});
</script>

<template>
    <header class="sticky top-0 z-20 flex h-16 shrink-0 items-center gap-4 border-b border-gray-200 bg-white px-4 dark:border-gray-700 dark:bg-gray-800 sm:px-6 lg:px-8">
        <button
            type="button"
            class="-ml-1 rounded-md p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 lg:hidden"
            @click="$emit('toggle-sidebar')"
        >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
            </svg>
        </button>

        <Link href="/" class="hidden items-center gap-2 sm:flex">
            <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary-600 text-xs font-bold text-white">FG</span>
        </Link>

        <h1 class="truncate text-base font-semibold text-gray-900 dark:text-gray-100 sm:text-lg">SICI Producers' Portal</h1>

        <div class="ml-auto flex items-center gap-1">
            <ThemeToggle />

            <Dropdown v-if="producer" align="right" width="48">
                <template #trigger>
                    <button
                        type="button"
                        class="flex items-center gap-2 rounded-full py-1 pl-2 pr-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                    >
                        <span class="hidden text-sm font-medium text-gray-700 dark:text-gray-200 sm:block">{{ greeting }}</span>
                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-primary-100 text-sm font-semibold text-primary-700 dark:bg-primary-900 dark:text-primary-200">
                            {{ producer.first_name.charAt(0).toUpperCase() }}
                        </span>
                        <svg class="hidden h-4 w-4 text-gray-400 sm:block" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                </template>

                <template #content>
                    <div class="border-b border-gray-100 px-4 py-3 dark:border-gray-700 sm:hidden">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ greeting }}</p>
                    </div>
                    <DropdownLink :href="route('producers.edit', producer.id)">Account</DropdownLink>
                    <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                </template>
            </Dropdown>
        </div>
    </header>
</template>
