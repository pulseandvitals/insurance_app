<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ThemeToggle from '@/Components/UI/ThemeToggle.vue';
import Button from '@/Components/UI/Button.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
});

const features = [
    {
        title: 'Policy Management',
        description: 'Issue, view, and download policy contracts, schedules, and certificates of coverage from one place.',
        icon: 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
    },
    {
        title: 'Motor CTPL & OCR',
        description: 'Run new motor CTPL quotes, auto-fill risk details with OCR, and process renewals in a guided wizard.',
        icon: 'M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.25h5.788c.66 0 1.276.303 1.679.822l3.485 4.517a2.25 2.25 0 0 1 .463 1.353v4.183a1.5 1.5 0 0 1-1.5 1.5h-.75m-4.5-9v9m0-9h-3.75a1.5 1.5 0 0 0-1.5 1.5v6a1.5 1.5 0 0 0 1.5 1.5h3.75',
    },
    {
        title: 'Wallet & Deposits',
        description: 'Top up your producer wallet, track deposit approvals, and keep tabs on your available balance.',
        icon: 'M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z',
    },
    {
        title: 'Reports & Extractions',
        description: 'Pull batch CoCs, premium statements, and policy extractions whenever you need them, exported and ready.',
        icon: 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z',
    },
];
</script>

<template>
    <Head title="Welcome" />
    <div class="min-h-screen bg-gray-50 font-sans text-gray-900 dark:bg-gray-900 dark:text-gray-100">
        <!-- Header -->
        <header class="border-b border-gray-200 bg-white/80 backdrop-blur dark:border-gray-800 dark:bg-gray-900/80">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 lg:px-8">
                <Link href="/" class="flex items-center gap-2">
                    <ApplicationLogo class="h-8 w-8" />
                    <span class="text-lg font-bold tracking-tight">InsurApp</span>
                </Link>

                <div class="flex items-center gap-2">
                    <ThemeToggle />

                    <template v-if="canLogin">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-lg px-4 py-2 text-sm font-semibold text-primary-600 hover:bg-primary-50 dark:text-primary-400 dark:hover:bg-primary-950/40"
                        >
                            Dashboard
                        </Link>
                        <Link
                            v-else
                            :href="route('login')"
                            class="rounded-lg px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-800"
                        >
                            Log in
                        </Link>
                    </template>
                </div>
            </div>
        </header>

        <!-- Hero -->
        <section class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-50 via-white to-white dark:from-primary-950/30 dark:via-gray-900 dark:to-gray-900"></div>

            <div class="relative mx-auto max-w-7xl px-6 py-20 text-center lg:px-8 lg:py-28">
                <span class="inline-flex items-center rounded-full bg-primary-100 px-3 py-1 text-xs font-semibold text-primary-700 dark:bg-primary-900 dark:text-primary-200">
                    SICI Producers&rsquo; Portal
                </span>

                <h1 class="mx-auto mt-6 max-w-3xl text-4xl font-bold tracking-tight sm:text-5xl">
                    Protecting what matters,
                    <span class="text-primary-600 dark:text-primary-400">one policy at a time.</span>
                </h1>

                <p class="mx-auto mt-6 max-w-2xl text-lg text-gray-600 dark:text-gray-400">
                    InsurApp gives SICI producers a single place to quote motor CTPL, manage policies, track deposits,
                    and pull reports &mdash; all without leaving the dashboard.
                </p>

                <div class="mt-10 flex items-center justify-center gap-4">
                    <Link v-if="$page.props.auth?.user" :href="route('dashboard')">
                        <Button size="md">Go to Dashboard</Button>
                    </Link>
                    <Link v-else :href="route('login')">
                        <Button size="md">Log in</Button>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="mx-auto max-w-7xl px-6 pb-24 lg:px-8">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="feature in features"
                    :key="feature.title"
                    class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-800"
                >
                    <div class="flex h-11 w-11 items-center justify-center rounded-lg bg-primary-100 dark:bg-primary-900">
                        <svg class="h-6 w-6 text-primary-600 dark:text-primary-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="feature.icon" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-base font-semibold">{{ feature.title }}</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ feature.description }}</p>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t border-gray-200 py-8 text-center text-sm text-gray-500 dark:border-gray-800 dark:text-gray-400">
            &copy; {{ new Date().getFullYear() }} InsurApp &mdash; SICI Producers&rsquo; Portal. All rights reserved.
        </footer>
    </div>
</template>
