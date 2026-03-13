<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ExternalLink } from 'lucide-vue-next';
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
                        Customers & Suppliers
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
                                            >
                                                {{
                                                    sortDirection === 'asc'
                                                        ? '▲'
                                                        : '▼'
                                                }}
                                            </span>
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
                                            >
                                                {{
                                                    sortDirection === 'asc'
                                                        ? '▲'
                                                        : '▼'
                                                }}
                                            </span>
                                        </div>
                                    </TableHead>

                                    <TableHead
                                        class="whitespace-nowrap text-gray-500"
                                        >Phone</TableHead
                                    >
                                    <TableHead
                                        class="whitespace-nowrap text-gray-500"
                                        >Mobile</TableHead
                                    >
                                    <TableHead
                                        class="whitespace-nowrap text-gray-500"
                                        >Website</TableHead
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

                                    <TableCell
                                        class="whitespace-nowrap text-gray-700 dark:text-gray-300"
                                    >
                                        {{ entity.phone || '-' }}
                                    </TableCell>

                                    <TableCell
                                        class="whitespace-nowrap text-gray-700 dark:text-gray-300"
                                    >
                                        {{ entity.mobile || '-' }}
                                    </TableCell>

                                    <TableCell
                                        class="whitespace-nowrap text-gray-700 dark:text-gray-300"
                                    >
                                        <div
                                            v-if="entity.website"
                                            class="flex items-center gap-1"
                                            @click.stop
                                        >
                                            <a
                                                :href="entity.website"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                            >
                                                <Button
                                                    variant="ghost"
                                                    size="icon"
                                                    class="h-8 w-8 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400"
                                                    title="Visit Website"
                                                >
                                                    <ExternalLink
                                                        class="h-4 w-4"
                                                    />
                                                </Button>
                                            </a>

                                        </div>
                                        <span v-else>-</span>
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
                                        colspan="7"
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
