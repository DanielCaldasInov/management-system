<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Input } from '@/components/ui/input';
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
    invoices: any;
    filters: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Supplier Invoices', href: '/supplier-invoices' },
];

const getStatusColor = (status: string) => {
    switch (status) {
        case 'draft':
            return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300';
        case 'pending':
            return 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300';
        case 'paid':
            return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const goToInvoice = (id: number) => {
    router.get(`/supplier-invoices/${id}`);
};

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');

let searchTimeout: ReturnType<typeof setTimeout>;
watch([search, status], ([newSearch, newStatus]) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            '/supplier-invoices',
            { search: newSearch, status: newStatus },
            { preserveState: true, replace: true },
        );
    }, 300);
});
</script>

<template>
    <Head title="Supplier Invoices" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center"
                >
                    <h2
                        class="text-xl leading-tight font-semibold text-gray-800 dark:text-gray-200"
                    >
                        Supplier Invoices
                    </h2>

                    <div
                        class="flex w-full flex-col items-center gap-3 sm:w-auto sm:flex-row"
                    >
                        <Input
                            v-model="search"
                            placeholder="Search reference or supplier..."
                            class="w-full sm:w-64 dark:bg-gray-900"
                        />

                        <select
                            v-model="status"
                            class="flex h-10 w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm focus:ring-2 focus:ring-gray-400 focus:outline-none sm:w-40 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        >
                            <option value="">All Statuses</option>
                            <option value="draft">Draft</option>
                            <option value="pending">Pending Payment</option>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <div class="overflow-x-auto">
                        <Table class="w-full">
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="whitespace-nowrap"
                                        >Reference</TableHead
                                    >
                                    <TableHead class="whitespace-nowrap"
                                        >Supplier</TableHead
                                    >
                                    <TableHead class="whitespace-nowrap"
                                        >Issue Date</TableHead
                                    >
                                    <TableHead class="whitespace-nowrap"
                                        >Due Date</TableHead
                                    >
                                    <TableHead
                                        class="text-right whitespace-nowrap"
                                        >Total</TableHead
                                    >
                                    <TableHead
                                        class="text-center whitespace-nowrap"
                                        >Status</TableHead
                                    >
                                </TableRow>
                            </TableHeader>

                            <TableBody>
                                <TableRow
                                    v-for="invoice in invoices?.data"
                                    :key="invoice.id"
                                    class="cursor-pointer transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50"
                                    @click="goToInvoice(invoice.id)"
                                >
                                    <TableCell
                                        class="font-medium whitespace-nowrap text-gray-900 dark:text-gray-100"
                                    >
                                        {{ invoice.reference }}
                                    </TableCell>
                                    <TableCell
                                        class="whitespace-nowrap text-gray-700 dark:text-gray-300"
                                    >
                                        {{ invoice.entity?.name || 'Unknown' }}
                                    </TableCell>
                                    <TableCell
                                        class="whitespace-nowrap text-gray-700 dark:text-gray-300"
                                    >
                                        {{
                                            invoice.issue_date
                                                ? new Date(
                                                      invoice.issue_date,
                                                  ).toLocaleDateString()
                                                : '-'
                                        }}
                                    </TableCell>
                                    <TableCell
                                        class="whitespace-nowrap text-gray-700 dark:text-gray-300"
                                    >
                                        <span
                                            :class="{
                                                'font-medium text-red-600 dark:text-red-400':
                                                    invoice.status !== 'paid' &&
                                                    new Date(invoice.due_date) <
                                                        new Date(),
                                            }"
                                        >
                                            {{
                                                invoice.due_date
                                                    ? new Date(
                                                          invoice.due_date,
                                                      ).toLocaleDateString()
                                                    : '-'
                                            }}
                                        </span>
                                    </TableCell>
                                    <TableCell
                                        class="text-right font-medium whitespace-nowrap text-gray-900 dark:text-gray-100"
                                    >
                                        € {{ Number(invoice.total).toFixed(2) }}
                                    </TableCell>
                                    <TableCell
                                        class="text-center whitespace-nowrap"
                                    >
                                        <span
                                            :class="
                                                getStatusColor(invoice.status)
                                            "
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium tracking-wider uppercase"
                                        >
                                            {{ invoice.status }}
                                        </span>
                                    </TableCell>
                                </TableRow>

                                <TableRow
                                    v-if="
                                        !invoices?.data ||
                                        invoices.data.length === 0
                                    "
                                >
                                    <TableCell
                                        colspan="6"
                                        class="py-8 text-center text-gray-500 dark:text-gray-400"
                                    >
                                        No invoices found.
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <div
                        v-if="invoices?.links && invoices.links.length > 3"
                        class="flex flex-col items-center justify-between gap-4 border-t border-gray-200 bg-gray-50 p-4 sm:flex-row dark:border-gray-800 dark:bg-gray-800/50"
                    >
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Showing
                            <span
                                class="font-medium text-gray-900 dark:text-white"
                                >{{ invoices.from || 0 }}</span
                            >
                            to
                            <span
                                class="font-medium text-gray-900 dark:text-white"
                                >{{ invoices.to || 0 }}</span
                            >
                            of
                            <span
                                class="font-medium text-gray-900 dark:text-white"
                                >{{ invoices.total || 0 }}</span
                            >
                            entries
                        </div>
                        <div
                            class="flex flex-wrap items-center justify-center gap-1"
                        >
                            <template
                                v-for="(link, index) in invoices.links"
                                :key="index"
                            >
                                <div
                                    v-if="link.url === null"
                                    class="rounded-md border border-transparent px-3 py-1.5 text-sm text-gray-400 dark:text-gray-600"
                                >
                                    <span v-html="link.label"></span>
                                </div>
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
                                >
                                    <span v-html="link.label"></span>
                                </Link>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
