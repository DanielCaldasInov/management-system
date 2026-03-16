<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';
import ArticleForm from './Partials/ArticleForm.vue';

const props = defineProps<{
    article: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Articles', href: '/articles' },
    { title: 'Edit Article', href: `/articles/${props.article.id}/edit` },
];
</script>

<template>
    <Head title="Edit Article" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900"
                >
                    <div
                        class="mb-8 flex flex-col justify-between gap-4 border-b border-gray-100 pb-4 sm:flex-row sm:items-center dark:border-gray-800"
                    >
                        <div>
                            <h2
                                class="text-2xl leading-tight font-semibold text-gray-900 dark:text-white"
                            >
                                Edit Article: {{ article.reference }}
                            </h2>
                            <p
                                class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Update article details or remove it from the
                                catalog.
                            </p>
                        </div>

                        <ConfirmDeleteDialog
                            :url="`/articles/${article.id}`"
                            trigger-variant="destructive"
                            trigger-text="Delete Article"
                            :description="`This action will delete ${article.name} from your catalog.`"
                        />
                    </div>

                    <ArticleForm :is-edit="true" :article="article" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
