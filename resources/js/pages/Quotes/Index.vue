<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
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

defineProps<{
    quotes: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Quotes', href: '/quotes' },
];

const getStatusColor = (status: string) => {
    switch (status) {
        case 'draft':
            return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300';
        case 'sent':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300';
        case 'accepted':
            return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300';
        case 'rejected':
            return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const goToQuote = (id: number) => {
    router.get(`/quotes/${id}`);
};
</script>

<template>
    <Head title="Quotes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center"
                >
                    <h2
                        class="text-xl leading-tight font-semibold text-gray-800 dark:text-gray-200"
                    >
                        Quotes Management
                    </h2>

                    <Link href="/quotes/create" class="w-full sm:w-auto">
                        <Button class="w-full sm:w-auto"
                            >Create New Quote</Button
                        >
                    </Link>
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
                                        >Customer</TableHead
                                    >
                                    <TableHead class="whitespace-nowrap"
                                        >Issue Date</TableHead
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
                                    v-for="quote in quotes?.data"
                                    :key="quote.id"
                                    class="cursor-pointer transition-colors hover:bg-gray-50 dark:hover:bg-gray-800/50"
                                    @click="goToQuote(quote.id)"
                                >
                                    <TableCell
                                        class="font-medium whitespace-nowrap text-gray-900 dark:text-gray-100"
                                    >
                                        {{ quote.reference }}
                                    </TableCell>

                                    <TableCell
                                        class="whitespace-nowrap text-gray-700 dark:text-gray-300"
                                    >
                                        {{
                                            quote.entity?.name ||
                                            'Unknown Customer'
                                        }}
                                    </TableCell>

                                    <TableCell
                                        class="whitespace-nowrap text-gray-700 dark:text-gray-300"
                                    >
                                        {{
                                            new Date(
                                                quote.issue_date,
                                            ).toLocaleDateString()
                                        }}
                                    </TableCell>

                                    <TableCell
                                        class="text-right font-medium whitespace-nowrap text-gray-900 dark:text-gray-100"
                                    >
                                        € {{ Number(quote.total).toFixed(2) }}
                                    </TableCell>

                                    <TableCell
                                        class="text-center whitespace-nowrap"
                                    >
                                        <span
                                            :class="
                                                getStatusColor(quote.status)
                                            "
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium tracking-wider uppercase"
                                        >
                                            {{ quote.status }}
                                        </span>
                                    </TableCell>
                                </TableRow>

                                <TableRow
                                    v-if="
                                        !quotes?.data ||
                                        quotes.data.length === 0
                                    "
                                >
                                    <TableCell
                                        colspan="5"
                                        class="py-8 text-center text-gray-500 dark:text-gray-400"
                                    >
                                        No quotes found. Create your first
                                        quote!
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <div
                        v-if="quotes?.links && quotes.links.length > 3"
                        class="flex flex-col items-center justify-between gap-4 border-t border-gray-200 bg-gray-50 p-4 sm:flex-row dark:border-gray-800 dark:bg-gray-800/50"
                    >
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Showing
                            <span
                                class="font-medium text-gray-900 dark:text-white"
                                >{{ quotes.from }}</span
                            >
                            to
                            <span
                                class="font-medium text-gray-900 dark:text-white"
                                >{{ quotes.to }}</span
                            >
                            of
                            <span
                                class="font-medium text-gray-900 dark:text-white"
                                >{{ quotes.total }}</span
                            >
                            entries
                        </div>
                        <div
                            class="flex flex-wrap items-center justify-center gap-1"
                        >
                            <template
                                v-for="(link, index) in quotes.links"
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
