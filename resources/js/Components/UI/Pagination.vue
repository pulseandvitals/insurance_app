<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

const props = defineProps({
    /** A Laravel paginator object (or any subset with from/to/total/links). */
    paginator: {
        type: Object,
        required: true,
    },
});

// Laravel's `links` array always starts with "Previous" and ends with "Next",
// with numbered/"…" links in between.
const prevLink = computed(() => props.paginator.links[0]);
const nextLink = computed(() => props.paginator.links[props.paginator.links.length - 1]);
const pageLinks = computed(() => props.paginator.links.slice(1, -1));
</script>

<template>
    <div
        v-if="paginator.last_page > 1"
        class="flex flex-col items-center justify-between gap-3 border-t border-gray-100 pt-4 dark:border-gray-700 sm:flex-row"
    >
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Showing <span class="font-medium text-gray-700 dark:text-gray-200">{{ paginator.from }}</span>
            to <span class="font-medium text-gray-700 dark:text-gray-200">{{ paginator.to }}</span>
            of <span class="font-medium text-gray-700 dark:text-gray-200">{{ paginator.total }}</span> results
        </p>

        <nav class="flex items-center gap-1">
            <span
                v-if="!prevLink.url"
                class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-300 dark:text-gray-700"
            >
                <ChevronLeft class="h-4 w-4" />
            </span>
            <Link
                v-else
                :href="prevLink.url"
                preserve-scroll
                preserve-state
                title="Previous page"
                class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                <ChevronLeft class="h-4 w-4" />
            </Link>

            <template v-for="(link, i) in pageLinks" :key="i">
                <span
                    v-if="!link.url"
                    class="flex h-8 min-w-8 items-center justify-center px-1 text-sm text-gray-400 dark:text-gray-600"
                    v-html="link.label"
                ></span>
                <Link
                    v-else
                    :href="link.url"
                    preserve-scroll
                    preserve-state
                    class="flex h-8 min-w-8 items-center justify-center rounded-lg px-2.5 text-sm font-medium transition-colors"
                    :class="link.active
                        ? 'bg-primary-600 text-white'
                        : 'text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'"
                    v-html="link.label"
                />
            </template>

            <span
                v-if="!nextLink.url"
                class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-300 dark:text-gray-700"
            >
                <ChevronRight class="h-4 w-4" />
            </span>
            <Link
                v-else
                :href="nextLink.url"
                preserve-scroll
                preserve-state
                title="Next page"
                class="flex h-8 w-8 items-center justify-center rounded-lg text-gray-600 transition-colors hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                <ChevronRight class="h-4 w-4" />
            </Link>
        </nav>
    </div>
</template>
