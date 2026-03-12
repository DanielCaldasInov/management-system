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
    entities: any;
    filters: {
        search?: string;
        searchField?: string;
        type?: string;
        sortField?: string;
        sortDirection?: string;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Entities', href: '/entities' },
];

const search = ref(props.filters?.search || '');
const searchField = ref(props.filters?.searchField || 'all');
const type = ref(props.filters?.type || 'all');
const sortField = ref(props.filters?.sortField || 'created_at');
const sortDirection = ref(props.filters?.sortDirection || 'desc');

let searchTimeout: ReturnType<typeof setTimeout>;

watch([search, searchField, type, sortField, sortDirection], () => {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        router.get(
            '/entities',
            {
                search: search.value,
                searchField: searchField.value,
                type: type.value,
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

// Navigate to the show page when a row is clicked
const goToEntity = (id: number) => {
    router.get(`/entities/${id}`);
};
</script>

<template>
    <Head title="Entities" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center"
                >
                    <h2
                        class="text-xl leading-tight font-semibold text-gray-800 dark:text-gray-200"
                    >
                        Entities (Customers & Suppliers)
                    </h2>

                    <Link href="/entities/create" class="w-full sm:w-auto">
                        <Button class="w-full sm:w-auto">Add New Entity</Button>
                    </Link>
                </div>

                <div
                    class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                >
                    <div
                        class="flex w-full flex-col gap-2 sm:max-w-md sm:flex-row"
                    >
                        <Select v-model="searchField">
                            <SelectTrigger
                                class="w-full sm:w-35 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                            >
                                <SelectValue placeholder="Search by..." />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem value="all"
                                        >All Fields</SelectItem
                                    >
                                    <SelectItem value="name">Name</SelectItem>
                                    <SelectItem value="vat_number"
                                        >NIF</SelectItem
                                    >
                                </SelectGroup>
                            </SelectContent>
                        </Select>

                        <Input
                            v-model="search"
                            type="text"
                            placeholder="Type to search..."
                            class="w-full dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200"
                        />
                    </div>

                    <div class="flex w-full gap-2 sm:w-auto">
                        <Button
                            :variant="type === 'all' ? 'default' : 'outline'"
                            @click="type = 'all'"
                            class="flex-1 sm:flex-none"
                            >All</Button
                        >
                        <Button
                            :variant="
                                type === 'customer' ? 'default' : 'outline'
                            "
                            @click="type = 'customer'"
                            class="flex-1 sm:flex-none"
                            >Customers</Button
                        >
                        <Button
                            :variant="
                                type === 'supplier' ? 'default' : 'outline'
                            "
                            @click="type = 'supplier'"
                            class="flex-1 sm:flex-none"
                            >Suppliers</Button
                        >
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
                                        class="cursor-pointer whitespace-nowrap select-none hover:bg-gray-50 dark:hover:bg-gray-800"
                                        @click="toggleSort('vat_number')"
                                    >
                                        <div class="flex items-center gap-1">
                                            NIF
                                            <span
                                                v-if="
                                                    sortField === 'vat_number'
                                                "
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
                                        >Type</TableHead
                                    >
                                    <TableHead
                                        class="whitespace-nowrap text-gray-500"
                                        >Email</TableHead
                                    >
                                    <TableHead
                                        class="text-right whitespace-nowrap"
                                        >Actions</TableHead
                                    >
                                </TableRow>
                            </TableHeader>

                            <TableBody>
                                <TableRow
                                    v-for="entity in entities?.data"
                                    :key="entity.id"
                                    class="cursor-pointer transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50"
                                    @click="goToEntity(entity.id)"
                                >
                                    <TableCell
                                        class="font-medium whitespace-nowrap text-gray-900 dark:text-gray-100"
                                    >
                                        {{ entity.vat_number }}
                                    </TableCell>
                                    <TableCell
                                        class="whitespace-nowrap text-gray-700 dark:text-gray-300"
                                    >
                                        {{ entity.name }}
                                    </TableCell>

                                    <TableCell class="whitespace-nowrap">
                                        <span
                                            v-if="
                                                entity.is_customer &&
                                                entity.is_supplier
                                            "
                                            class="inline-flex items-center rounded-md bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 ring-1 ring-purple-700/10 ring-inset dark:bg-purple-900/30 dark:text-purple-400 dark:ring-purple-900/50"
                                        >
                                            Customer & Supplier
                                        </span>
                                        <span
                                            v-else-if="entity.is_customer"
                                            class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-blue-700/10 ring-inset dark:bg-blue-900/30 dark:text-blue-400 dark:ring-blue-900/50"
                                        >
                                            Customer
                                        </span>
                                        <span
                                            v-else-if="entity.is_supplier"
                                            class="inline-flex items-center rounded-md bg-orange-50 px-2 py-1 text-xs font-medium text-orange-700 ring-1 ring-orange-700/10 ring-inset dark:bg-orange-900/30 dark:text-orange-400 dark:ring-orange-900/50"
                                        >
                                            Supplier
                                        </span>
                                    </TableCell>

                                    <TableCell
                                        class="whitespace-nowrap text-gray-700 dark:text-gray-300"
                                    >
                                        {{ entity.email || '-' }}
                                    </TableCell>

                                    <TableCell
                                        class="text-right whitespace-nowrap"
                                        @click.stop
                                    >
                                        <div class="flex justify-end gap-2">
                                            <Link
                                                :href="`/entities/${entity.id}/edit`"
                                            >
                                                <Button
                                                    variant="outline"
                                                    size="sm"
                                                    >Edit</Button
                                                >
                                            </Link>

                                            <ConfirmDeleteDialog
                                                :url="`/entities/${entity.id}`"
                                                trigger-variant="destructive"
                                                trigger-size="sm"
                                                trigger-text="Delete"
                                                :description="`This will permanently delete ${entity.name} from the database.`"
                                            />
                                        </div>
                                    </TableCell>
                                </TableRow>

                                <TableRow
                                    v-if="
                                        !entities?.data ||
                                        entities.data.length === 0
                                    "
                                >
                                    <TableCell
                                        colspan="5"
                                        class="py-8 text-center text-gray-500 dark:text-gray-400"
                                    >
                                        <p>
                                            No entities found matching your
                                            filters.
                                        </p>
                                        <Button
                                            variant="link"
                                            @click="
                                                search = '';
                                                type = 'all';
                                                searchField = 'all';
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
                </div>
            </div>
        </div>
    </AppLayout>
</template>
