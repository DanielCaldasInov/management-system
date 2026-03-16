<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    articles: any;
    filters: {
        search?: string;
        status?: string;
        sortField?: string;
        sortDirection?: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Articles', href: '/articles' },
];

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || 'all');
const sortField = ref(props.filters?.sortField || 'created_at');
const sortDirection = ref(props.filters?.sortDirection || 'desc');

let searchTimeout: ReturnType<typeof setTimeout>;

watch([search, status, sortField, sortDirection], () => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        router.get(
            '/articles',
            {
                search: search.value,
                status: status.value,
                sortField: sortField.value,
                sortDirection: sortDirection.value,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    }, 300);
});

const toggleSort = (field: string) => {
    if (sortField.value === field) {
        sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortField.value = field;
        sortDirection.value = 'asc';
    }
};

const goToArticle = (id: number) => {
    router.get(`/articles/${id}`);
};
</script>

<template>
    <Head title="Articles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center"
                >
                    <h2
                        class="text-xl leading-tight font-semibold text-gray-800 dark:text-gray-200"
                    >
                        Articles Catalog
                    </h2>

                    <Link href="/articles/create" class="w-full sm:w-auto">
                        <Button class="w-full sm:w-auto"
                            >Add New Article</Button
                        >
                    </Link>
                </div>

                <div
                    class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div
                        class="flex w-full flex-col gap-2 sm:max-w-md sm:flex-row"
                    >
                        <Input
                            v-model="search"
                            type="text"
                            placeholder="Search by name or reference..."
                            class="w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                        />
                    </div>

                    <div class="flex w-full gap-2 sm:w-auto">
                        <Select v-model="status">
                            <SelectTrigger
                                class="w-full sm:w-40 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                            >
                                <SelectValue placeholder="Status" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem value="all"
                                        >All Status</SelectItem
                                    >
                                    <SelectItem value="active"
                                        >Active</SelectItem
                                    >
                                    <SelectItem value="inactive"
                                        >Inactive</SelectItem
                                    >
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="overflow-x-auto">
                        <Table class="w-full">
                            <TableHeader>
                                <TableRow>
                                    <TableHead
                                        class="w-16 whitespace-nowrap text-gray-500"
                                        >Photo</TableHead
                                    >

                                    <TableHead
                                        class="cursor-pointer whitespace-nowrap select-none hover:bg-gray-50 dark:hover:bg-gray-800"
                                        @click="toggleSort('reference')"
                                    >
                                        <div class="flex items-center gap-1">
                                            Reference
                                            <span
                                                v-if="sortField === 'reference'"
                                                class="text-xs"
                                                >{{
                                                    sortDirection === 'asc'
                                                        ? '▲'
                                                        : '▼'
                                                }}</span
                                            >
                                        </div>
                                    </TableHead>

                                    <TableHead
                                        class="cursor-pointer whitespace-nowrap select-none hover:bg-gray-50 dark:hover:bg-gray-800"
                                        @click="toggleSort('name')"
                                    >
                                        <div class="flex items-center gap-1">
                                            Name
                                            <span
                                                v-if="sortField === 'name'"
                                                class="text-xs"
                                                >{{
                                                    sortDirection === 'asc'
                                                        ? '▲'
                                                        : '▼'
                                                }}</span
                                            >
                                        </div>
                                    </TableHead>

                                    <TableHead
                                        class="whitespace-nowrap text-gray-500"
                                        >Description</TableHead
                                    >

                                    <TableHead
                                        class="cursor-pointer text-right whitespace-nowrap select-none hover:bg-gray-50 dark:hover:bg-gray-800"
                                        @click="toggleSort('price')"
                                    >
                                        <div
                                            class="flex items-center justify-end gap-1"
                                        >
                                            Price
                                            <span
                                                v-if="sortField === 'price'"
                                                class="text-xs"
                                                >{{
                                                    sortDirection === 'asc'
                                                        ? '▲'
                                                        : '▼'
                                                }}</span
                                            >
                                        </div>
                                    </TableHead>

                                    <TableHead
                                        class="text-right whitespace-nowrap text-gray-500"
                                        >Actions</TableHead
                                    >
                                </TableRow>
                            </TableHeader>

                            <TableBody>
                                <TableRow
                                    v-for="article in articles?.data"
                                    :key="article.id"
                                    class="cursor-pointer transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50"
                                    @click="goToArticle(article.id)"
                                >
                                    <TableCell>
                                        <div
                                            class="h-10 w-10 overflow-hidden rounded-md border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800"
                                        >
                                            <img
                                                v-if="article.photo_path"
                                                :src="`/storage/${article.photo_path}`"
                                                class="h-full w-full object-cover"
                                                alt="Photo"
                                            />
                                            <div
                                                v-else
                                                class="flex h-full w-full items-center justify-center text-xs text-gray-400"
                                            >
                                                N/A
                                            </div>
                                        </div>
                                    </TableCell>

                                    <TableCell
                                        class="font-medium whitespace-nowrap text-gray-900 dark:text-gray-100"
                                    >
                                        {{ article.reference }}
                                    </TableCell>

                                    <TableCell
                                        class="whitespace-nowrap text-gray-700 dark:text-gray-300"
                                    >
                                        <div class="flex items-center gap-2">
                                            {{ article.name }}
                                            <span
                                                v-if="!article.is_active"
                                                class="inline-flex h-2 w-2 rounded-full bg-red-500"
                                                title="Inactive"
                                            ></span>
                                        </div>
                                    </TableCell>

                                    <TableCell
                                        class="max-w-50 truncate text-gray-500 dark:text-gray-400"
                                    >
                                        {{ article.description || '-' }}
                                    </TableCell>

                                    <TableCell
                                        class="text-right font-medium whitespace-nowrap text-gray-900 dark:text-gray-100"
                                    >
                                        € {{ Number(article.price).toFixed(2) }}
                                    </TableCell>

                                    <TableCell
                                        class="text-right whitespace-nowrap"
                                        @click.stop
                                    >
                                        <div class="flex justify-end gap-2">
                                            <Link
                                                :href="`/articles/${article.id}/edit`"
                                            >
                                                <Button
                                                    variant="outline"
                                                    size="sm"
                                                    >Edit</Button
                                                >
                                            </Link>
                                            <ConfirmDeleteDialog
                                                :url="`/articles/${article.id}`"
                                                trigger-variant="destructive"
                                                trigger-size="sm"
                                                trigger-text="Delete"
                                                :description="`This will permanently delete ${article.name} from the catalog.`"
                                            />
                                        </div>
                                    </TableCell>
                                </TableRow>

                                <TableRow
                                    v-if="
                                        !articles?.data ||
                                        articles.data.length === 0
                                    "
                                >
                                    <TableCell
                                        colspan="6"
                                        class="py-8 text-center text-gray-500 dark:text-gray-400"
                                    >
                                        <p>No articles found.</p>
                                        <Button
                                            variant="link"
                                            @click="
                                                search = '';
                                                status = 'all';
                                            "
                                            class="mt-2 text-blue-600 dark:text-blue-400"
                                        >
                                            Clear Filters
                                        </Button>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <div
                        v-if="articles?.links && articles.links.length > 3"
                        class="flex flex-col items-center justify-between gap-4 border-t border-gray-200 bg-gray-50 p-4 sm:flex-row dark:border-gray-800 dark:bg-gray-800/50"
                    >
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Showing
                            <span
                                class="font-medium text-gray-900 dark:text-white"
                                >{{ articles.from }}</span
                            >
                            to
                            <span
                                class="font-medium text-gray-900 dark:text-white"
                                >{{ articles.to }}</span
                            >
                            of
                            <span
                                class="font-medium text-gray-900 dark:text-white"
                                >{{ articles.total }}</span
                            >
                            entries
                        </div>
                        <div
                            class="flex flex-wrap items-center justify-center gap-1"
                        >
                            <template
                                v-for="(link, index) in articles.links"
                                :key="index"
                            >
                                <div
                                    v-if="link.url === null"
                                    class="rounded-md border border-transparent px-3 py-1.5 text-sm text-gray-400 dark:text-gray-600"
                                    v-html="link.label"
                                ></div>
                                <Link
                                    v-else
                                    :href="link.url"
                                    class="rounded-md border px-3 py-1.5 text-sm font-medium transition-colors"
                                    :class="
                                        link.active
                                            ? 'border-gray-900 bg-gray-900 text-white dark:border-gray-100 dark:bg-gray-100 dark:text-gray-900'
                                            : 'border-gray-200 bg-white text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-gray-800'
                                    "
                                    preserve-scroll
                                    ><span v-html="link.label"></span
                                ></Link>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
