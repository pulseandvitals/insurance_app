<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="flex min-h-screen">
        <!-- Branding panel -->
        <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-primary-700 p-12 text-white lg:flex">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900"></div>

            <Link href="/" class="relative z-10 flex items-center gap-2">
                <ApplicationLogo class="h-9 w-9" />
                <span class="text-xl font-bold tracking-tight">InsureApp</span>
            </Link>

            <div class="relative z-10 flex max-w-md flex-col items-center text-center">
                <ApplicationLogo class="mb-8 h-48 w-48 drop-shadow-xl" />
                <h2 class="text-3xl font-bold leading-tight">
                    Protecting what matters, one policy at a time.
                </h2>
                <p class="mt-4 text-primary-100">
                    Manage policies, track claims, and stay on top of every client relationship from a single dashboard.
                </p>
            </div>

            <p class="relative z-10 text-sm text-primary-200">&copy; {{ new Date().getFullYear() }} InsureApp. All rights reserved.</p>
        </div>

        <!-- Form panel -->
        <div class="flex w-full flex-col justify-center bg-white px-6 py-12 sm:px-12 lg:w-1/2 lg:px-16">
            <div class="mx-auto w-full max-w-sm">
                <Link href="/" class="mb-8 flex items-center gap-2 lg:hidden">
                    <ApplicationLogo class="h-9 w-9" />
                    <span class="text-xl font-bold tracking-tight text-gray-900">InsureApp</span>
                </Link>

                <slot />
            </div>
        </div>
    </div>
</template>
