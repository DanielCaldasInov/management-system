<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    TrendingUp,
    TrendingDown,
    AlertCircle,
    Users,
    ShoppingCart,
    FileText,
    ArrowRight,
} from 'lucide-vue-next';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

defineProps<{
    metrics: {
        revenue: number;
        expenses: number;
        pendingCount: number;
        pendingTotal: number;
        customers: number;
    };
    recentOrders: any[];
    recentInvoices: any[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('pt-PT', {
        style: 'currency',
        currency: 'EUR',
    }).format(value);
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
                <div>
                    <h2
                        class="text-2xl leading-tight font-bold text-gray-900 dark:text-white"
                    >
                        Overview
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Here's what's happening in your business today.
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4"
                >
                    <Link
                        href="/orders"
                        class="group relative block overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:border-indigo-500 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-500"
                    >
                        <div class="flex items-center justify-between">
                            <h3
                                class="text-sm font-medium text-gray-500 transition-colors group-hover:text-indigo-600 dark:text-gray-400 dark:group-hover:text-indigo-400"
                            >
                                Total Revenue
                            </h3>
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 text-green-600 dark:bg-green-900/30 dark:text-green-400"
                            >
                                <TrendingUp class="h-5 w-5" />
                            </div>
                        </div>
                        <p
                            class="mt-4 text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ formatCurrency(metrics.revenue) }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">
                            From customer orders
                        </p>
                    </Link>

                    <Link
                        href="/supplier-invoices"
                        class="group relative block overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:border-indigo-500 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-indigo-500"
                    >
                        <div class="flex items-center justify-between">
                            <h3
                                class="text-sm font-medium text-gray-500 transition-colors group-hover:text-indigo-600 dark:text-gray-400 dark:group-hover:text-indigo-400"
                            >
                                Total Expenses
                            </h3>
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400"
                            >
                                <TrendingDown class="h-5 w-5" />
                            </div>
                        </div>
                        <p
                            class="mt-4 text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ formatCurrency(metrics.expenses) }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">
                            From supplier invoices
                        </p>
                    </Link>

                    <Link
                        href="/supplier-invoices?status=pending"
                        class="group relative block overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:border-amber-500 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-amber-500"
                    >
                        <div class="flex items-center justify-between">
                            <h3
                                class="text-sm font-medium text-gray-500 transition-colors group-hover:text-amber-600 dark:text-gray-400 dark:group-hover:text-amber-400"
                            >
                                Pending Payments
                            </h3>
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400"
                            >
                                <AlertCircle class="h-5 w-5" />
                            </div>
                        </div>
                        <p
                            class="mt-4 text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ formatCurrency(metrics.pendingTotal) }}
                        </p>
                        <p
                            class="mt-1 text-xs font-medium text-amber-600 dark:text-amber-400"
                        >
                            {{ metrics.pendingCount }} invoices waiting
                        </p>
                    </Link>

                    <Link
                        href="/entities?type=customer"
                        class="group relative block overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm transition-all hover:border-blue-500 hover:shadow-md dark:border-gray-800 dark:bg-gray-900 dark:hover:border-blue-500"
                    >
                        <div class="flex items-center justify-between">
                            <h3
                                class="text-sm font-medium text-gray-500 transition-colors group-hover:text-blue-600 dark:text-gray-400 dark:group-hover:text-blue-400"
                            >
                                Active Customers
                            </h3>
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400"
                            >
                                <Users class="h-5 w-5" />
                            </div>
                        </div>
                        <p
                            class="mt-4 text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ metrics.customers }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500">
                            Registered in the database
                        </p>
                    </Link>
                </div>

                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div
                        class="flex flex-col rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div
                            class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800"
                        >
                            <div class="flex items-center gap-2">
                                <ShoppingCart class="h-5 w-5 text-gray-400" />
                                <h3
                                    class="text-base font-semibold text-gray-900 dark:text-white"
                                >
                                    Recent Customer Orders
                                </h3>
                            </div>
                            <Link
                                href="/orders"
                                class="flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
                            >
                                View all <ArrowRight class="h-4 w-4" />
                            </Link>
                        </div>
                        <div class="p-0">
                            <div class="overflow-x-auto">
                                <table
                                    class="w-full text-left text-sm whitespace-nowrap"
                                >
                                    <thead
                                        class="bg-gray-50 text-gray-500 dark:bg-gray-800/50 dark:text-gray-400"
                                    >
                                        <tr>
                                            <th class="px-6 py-3 font-medium">
                                                Reference
                                            </th>
                                            <th class="px-6 py-3 font-medium">
                                                Customer
                                            </th>
                                            <th
                                                class="px-6 py-3 text-right font-medium"
                                            >
                                                Total
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="divide-y divide-gray-200 dark:divide-gray-800"
                                    >
                                        <tr
                                            v-for="order in recentOrders"
                                            :key="order.id"
                                            class="hover:bg-gray-50 dark:hover:bg-gray-800/50"
                                        >
                                            <td
                                                class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100"
                                            >
                                                <Link
                                                    :href="`/orders/${order.id}`"
                                                    class="hover:text-indigo-600 dark:hover:text-indigo-400"
                                                >
                                                    {{ order.reference }}
                                                </Link>
                                            </td>
                                            <td
                                                class="px-6 py-4 text-gray-600 dark:text-gray-300"
                                            >
                                                {{
                                                    order.entity?.name ||
                                                    'Unknown'
                                                }}
                                            </td>
                                            <td
                                                class="px-6 py-4 text-right font-medium text-gray-900 dark:text-gray-100"
                                            >
                                                {{
                                                    formatCurrency(order.total)
                                                }}
                                            </td>
                                        </tr>
                                        <tr v-if="recentOrders.length === 0">
                                            <td
                                                colspan="3"
                                                class="px-6 py-8 text-center text-gray-500"
                                            >
                                                No orders found.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex flex-col rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
                    >
                        <div
                            class="flex items-center justify-between border-b border-gray-200 px-6 py-4 dark:border-gray-800"
                        >
                            <div class="flex items-center gap-2">
                                <FileText class="h-5 w-5 text-gray-400" />
                                <h3
                                    class="text-base font-semibold text-gray-900 dark:text-white"
                                >
                                    Recent Supplier Invoices
                                </h3>
                            </div>
                            <Link
                                href="/supplier-invoices"
                                class="flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400"
                            >
                                View all <ArrowRight class="h-4 w-4" />
                            </Link>
                        </div>
                        <div class="p-0">
                            <div class="overflow-x-auto">
                                <table
                                    class="w-full text-left text-sm whitespace-nowrap"
                                >
                                    <thead
                                        class="bg-gray-50 text-gray-500 dark:bg-gray-800/50 dark:text-gray-400"
                                    >
                                        <tr>
                                            <th class="px-6 py-3 font-medium">
                                                Reference
                                            </th>
                                            <th class="px-6 py-3 font-medium">
                                                Supplier
                                            </th>
                                            <th
                                                class="px-6 py-3 text-center font-medium"
                                            >
                                                Status
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="divide-y divide-gray-200 dark:divide-gray-800"
                                    >
                                        <tr
                                            v-for="invoice in recentInvoices"
                                            :key="invoice.id"
                                            class="hover:bg-gray-50 dark:hover:bg-gray-800/50"
                                        >
                                            <td
                                                class="px-6 py-4 font-medium text-gray-900 dark:text-gray-100"
                                            >
                                                <Link
                                                    :href="`/supplier-invoices/${invoice.id}`"
                                                    class="hover:text-indigo-600 dark:hover:text-indigo-400"
                                                >
                                                    {{ invoice.reference }}
                                                </Link>
                                            </td>
                                            <td
                                                class="px-6 py-4 text-gray-600 dark:text-gray-300"
                                            >
                                                {{
                                                    invoice.entity?.name ||
                                                    'Unknown'
                                                }}
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span
                                                    :class="[
                                                        invoice.status ===
                                                        'paid'
                                                            ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'
                                                            : invoice.status ===
                                                                'pending'
                                                              ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300'
                                                              : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
                                                        'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold tracking-wider uppercase',
                                                    ]"
                                                >
                                                    {{ invoice.status }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-if="recentInvoices.length === 0">
                                            <td
                                                colspan="3"
                                                class="px-6 py-8 text-center text-gray-500"
                                            >
                                                No invoices found.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
