<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';
import ArticleForm from './Partials/ArticleForm.vue';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Articles', href: '/articles' },
    { title: 'Create Article', href: '/articles/create' },
];
</script>

<template>
    <Head title="Create Article" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900"
                >
                    <div
                        class="mb-8 border-b border-gray-100 pb-4 dark:border-gray-800"
                    >
                        <h2
                            class="text-2xl leading-tight font-semibold text-gray-900 dark:text-white"
                        >
                            Create New Article
                        </h2>
                        <p
                            class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                        >
                            Add a new product or service to your catalog.
                        </p>
                    </div>

                    <ArticleForm :is-edit="false" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
