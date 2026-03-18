<script setup lang="ts">
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    order: any;
    suppliers: any[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Customer Orders', href: '/orders' },
    { title: props.order.reference, href: `/orders/${props.order.id}` },
];

const getStatusColor = (status: string) => {
    switch (status) {
        case 'draft':
            return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300';
        case 'closed':
            return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const form = useForm({
    lines: props.order.lines.map((line: any) => ({
        id: line.id,
        supplier_id: line.supplier_id || '',
        cost_price: line.cost_price || 0,
    })),
});

const saveLines = () => {
    form.put(`/orders/${props.order.id}/lines`, {
        preserveScroll: true,
    });
};

const updateStatus = (newStatus: string) => {
    router.patch(
        `/orders/${props.order.id}/status`,
        {
            status: newStatus,
        },
        {
            preserveScroll: true,
        },
    );
};
</script>

<template>
    <Head :title="`Order ${order.reference}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div
                    class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center"
                >
                    <div class="flex items-center gap-3">
                        <h2
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ order.reference }}
                        </h2>
                        <span
                            :class="getStatusColor(order.status)"
                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold tracking-wider uppercase"
                        >
                            {{ order.status }}
                        </span>
                        <span
                            v-if="order.quote_id"
                            class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1 text-xs font-medium text-purple-800 dark:bg-purple-900/30 dark:text-purple-300"
                        >
                            From: {{ order.quote?.reference || 'Quote' }}
                        </span>
                    </div>

                    <div class="flex w-full flex-wrap gap-2 sm:w-auto">
                        <Link href="/orders" class="flex-1 sm:flex-none">
                            <Button variant="outline" class="w-full"
                                >Back</Button
                            >
                        </Link>

                        <Button
                            v-if="order.status === 'draft'"
                            @click="updateStatus('closed')"
                            variant="default"
                            class="flex-1 bg-green-600 text-white hover:bg-green-700 sm:flex-none"
                        >
                            Mark as Closed
                        </Button>

                        <Button
                            v-if="order.status === 'closed'"
                            @click="updateStatus('draft')"
                            variant="outline"
                            class="flex-1 sm:flex-none"
                        >
                            Revert to Draft
                        </Button>
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900"
                >
                    <div
                        class="grid grid-cols-1 gap-8 border-b border-gray-100 p-8 md:grid-cols-2 dark:border-gray-800"
                    >
                        <div>
                            <h3
                                class="mb-4 text-sm font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Customer Details
                            </h3>
                            <div
                                class="text-base text-gray-900 dark:text-gray-100"
                            >
                                <p class="text-lg font-bold">
                                    {{ order.entity.name }}
                                </p>
                                <p v-if="order.entity.vat_number" class="mt-1">
                                    NIF: {{ order.entity.vat_number }}
                                </p>
                                <p v-if="order.entity.email" class="mt-1">
                                    {{ order.entity.email }}
                                </p>
                            </div>
                        </div>
                        <div class="md:text-right">
                            <h3
                                class="mb-4 text-sm font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Order Info
                            </h3>
                            <div
                                class="space-y-2 text-base text-gray-900 dark:text-gray-100"
                            >
                                <p>
                                    <span class="text-gray-500"
                                        >Issue Date:</span
                                    >
                                    {{
                                        new Date(
                                            order.issue_date,
                                        ).toLocaleDateString()
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <form @submit.prevent="saveLines" class="p-8">
                        <div class="mb-4 flex items-center justify-between">
                            <h3
                                class="text-lg font-bold text-gray-900 dark:text-white"
                            >
                                Order Lines & Sourcing
                            </h3>
                            <Button
                                type="submit"
                                :disabled="
                                    form.processing || order.status === 'closed'
                                "
                                size="sm"
                                class="dark:bg-white dark:text-gray-900"
                            >
                                {{
                                    form.processing
                                        ? 'Saving...'
                                        : 'Save Suppliers & Costs'
                                }}
                            </Button>
                        </div>

                        <div
                            class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-800"
                        >
                            <table class="w-full text-left text-sm">
                                <thead class="bg-gray-50 dark:bg-gray-800/50">
                                    <tr
                                        class="text-gray-500 dark:text-gray-400"
                                    >
                                        <th class="p-3 font-medium">
                                            Description
                                        </th>
                                        <th class="p-3 text-center font-medium">
                                            Qty
                                        </th>
                                        <th class="p-3 text-right font-medium">
                                            Sell Price
                                        </th>
                                        <th
                                            class="bg-blue-50/50 p-3 font-medium dark:bg-blue-900/10"
                                        >
                                            Supplier (For Sourcing)
                                        </th>
                                        <th
                                            class="w-32 bg-blue-50/50 p-3 font-medium dark:bg-blue-900/10"
                                        >
                                            Cost Price (€)
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-100 dark:divide-gray-800"
                                >
                                    <tr
                                        v-for="(line, index) in order.lines"
                                        :key="line.id"
                                        class="text-gray-900 hover:bg-gray-50/50 dark:text-gray-100 dark:hover:bg-gray-800/30"
                                    >
                                        <td class="p-3 font-medium">
                                            {{ line.description }}
                                        </td>
                                        <td class="p-3 text-center">
                                            {{
                                                Number(line.quantity).toFixed(2)
                                            }}
                                        </td>
                                        <td class="p-3 text-right">
                                            €
                                            {{
                                                Number(line.unit_price).toFixed(
                                                    2,
                                                )
                                            }}
                                        </td>

                                        <td
                                            class="bg-blue-50/20 p-3 dark:bg-blue-900/5"
                                        >
                                            <select
                                                v-model="
                                                    form.lines[index]
                                                        .supplier_id
                                                "
                                                :disabled="
                                                    order.status === 'closed'
                                                "
                                                class="flex h-9 w-full rounded-md border border-gray-300 bg-white px-3 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none disabled:opacity-50 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                            >
                                                <option value="">
                                                    -- Select Supplier --
                                                </option>
                                                <option
                                                    v-for="sup in suppliers"
                                                    :key="sup.id"
                                                    :value="sup.id"
                                                >
                                                    {{ sup.name }}
                                                </option>
                                            </select>
                                        </td>
                                        <td
                                            class="bg-blue-50/20 p-3 dark:bg-blue-900/5"
                                        >
                                            <Input
                                                v-model="
                                                    form.lines[index].cost_price
                                                "
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                :disabled="
                                                    order.status === 'closed'
                                                "
                                                class="h-9 dark:border-gray-700 dark:bg-gray-900"
                                            />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <div
                        class="grid grid-cols-1 gap-8 bg-gray-50 p-8 md:grid-cols-2 dark:bg-gray-800/50"
                    >
                        <div>
                            <h3
                                v-if="order.notes"
                                class="mb-2 text-sm font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Notes
                            </h3>
                            <p
                                class="text-sm whitespace-pre-line text-gray-700 dark:text-gray-300"
                            >
                                {{ order.notes }}
                            </p>
                        </div>

                        <div class="space-y-3">
                            <div
                                class="flex justify-between text-sm text-gray-600 dark:text-gray-400"
                            >
                                <span>Subtotal</span>
                                <span
                                    >€
                                    {{
                                        Number(order.subtotal).toFixed(2)
                                    }}</span
                                >
                            </div>
                            <div
                                class="flex justify-between border-b border-gray-200 pb-3 text-sm text-gray-600 dark:border-gray-700 dark:text-gray-400"
                            >
                                <span>VAT Total</span>
                                <span
                                    >€
                                    {{
                                        Number(order.vat_total).toFixed(2)
                                    }}</span
                                >
                            </div>
                            <div
                                class="flex justify-between pt-1 text-xl font-bold text-gray-900 dark:text-white"
                            >
                                <span>Total</span>
                                <span
                                    >€
                                    {{ Number(order.total).toFixed(2) }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
