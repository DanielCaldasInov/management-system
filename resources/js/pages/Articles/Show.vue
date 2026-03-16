<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    article: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Articles', href: '/articles' },
    { title: props.article.reference, href: `/articles/${props.article.id}` },
];
</script>

<template>
    <Head :title="article.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div
                    class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center"
                >
                    <h2
                        class="text-xl leading-tight font-semibold text-gray-800 dark:text-gray-200"
                    >
                        Article Details
                    </h2>

                    <div class="flex w-full gap-2 sm:w-auto">
                        <Link href="/articles" class="flex-1 sm:flex-none">
                            <Button variant="outline" class="w-full sm:w-auto"
                                >Back to List</Button
                            >
                        </Link>
                        <Link
                            :href="`/articles/${article.id}/edit`"
                            class="flex-1 sm:flex-none"
                        >
                            <Button class="w-full sm:w-auto"
                                >Edit Article</Button
                            >
                        </Link>
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900"
                >
                    <div class="flex flex-col sm:flex-row">
                        <div
                            class="flex w-full items-center justify-center bg-gray-50 p-6 sm:w-1/3 sm:border-r sm:border-gray-100 dark:border-gray-800 dark:bg-gray-800/50"
                        >
                            <div
                                class="aspect-square w-full max-w-50 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900"
                            >
                                <img
                                    v-if="article.photo_path"
                                    :src="`/storage/${article.photo_path}`"
                                    class="h-full w-full object-cover"
                                 alt="Photo"/>
                                <div
                                    v-else
                                    class="flex h-full w-full flex-col items-center justify-center text-gray-400"
                                >
                                    <svg
                                        class="mb-2 h-10 w-10 opacity-50"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                        ></path>
                                    </svg>
                                    <span class="text-sm">No Photo</span>
                                </div>
                            </div>
                        </div>

                        <div class="w-full sm:w-2/3">
                            <div
                                class="border-b border-gray-100 px-6 py-5 dark:border-gray-800"
                            >
                                <div class="flex items-center justify-between">
                                    <h3
                                        class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                                    >
                                        {{ article.name }}
                                    </h3>
                                    <span
                                        :class="
                                            article.is_active
                                                ? 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-900/30 dark:text-green-400'
                                                : 'bg-red-50 text-red-700 ring-red-600/10 dark:bg-red-900/30 dark:text-red-400'
                                        "
                                        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset"
                                    >
                                        {{
                                            article.is_active
                                                ? 'Active'
                                                : 'Inactive'
                                        }}
                                    </span>
                                </div>
                                <p
                                    class="mt-1 max-w-2xl font-mono text-sm text-gray-500 dark:text-gray-400"
                                >
                                    {{ article.reference }}
                                </p>
                            </div>

                            <div class="px-6 py-5 sm:p-0">
                                <dl
                                    class="sm:divide-y sm:divide-gray-100 dark:sm:divide-gray-800"
                                >
                                    <div
                                        class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                                    >
                                        <dt
                                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                        >
                                            Price
                                        </dt>
                                        <dd
                                            class="mt-1 text-sm font-semibold text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100"
                                        >
                                            €
                                            {{
                                                Number(article.price).toFixed(2)
                                            }}
                                        </dd>
                                    </div>

                                    <div
                                        class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                                    >
                                        <dt
                                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                        >
                                            VAT Applied
                                        </dt>
                                        <dd
                                            class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100"
                                        >
                                            {{
                                                Number(
                                                    article.vat_percentage,
                                                ).toFixed(2)
                                            }}
                                            %
                                        </dd>
                                    </div>

                                    <div
                                        class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                                    >
                                        <dt
                                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                        >
                                            Description
                                        </dt>
                                        <dd
                                            class="mt-1 text-sm whitespace-pre-line text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100"
                                        >
                                            {{
                                                article.description ||
                                                'No description provided.'
                                            }}
                                        </dd>
                                    </div>

                                    <div
                                        class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                                    >
                                        <dt
                                            class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                        >
                                            Internal Notes
                                        </dt>
                                        <dd
                                            class="mt-1 text-sm whitespace-pre-line text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100"
                                        >
                                            {{
                                                article.notes ||
                                                'No notes available.'
                                            }}
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex justify-end border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-800 dark:bg-gray-800/50"
                    >
                        <ConfirmDeleteDialog
                            :url="`/articles/${article.id}`"
                            trigger-variant="destructive"
                            trigger-text="Delete Article"
                            :description="`This will permanently delete ${article.name} from the catalog.`"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
