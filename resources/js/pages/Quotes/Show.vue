<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    quote: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Quotes', href: '/quotes' },
    { title: props.quote.reference, href: `/quotes/${props.quote.id}` },
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

const updateStatus = (newStatus: string) => {
    router.patch(
        `/quotes/${props.quote.id}/status`,
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
    <Head :title="`Quote ${quote.reference}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                <div
                    class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center"
                >
                    <div class="flex items-center gap-3">
                        <h2
                            class="text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ quote.reference }}
                        </h2>
                        <span
                            :class="getStatusColor(quote.status)"
                            class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold tracking-wider uppercase"
                        >
                            {{ quote.status }}
                        </span>
                    </div>

                    <div class="flex w-full flex-wrap gap-2 sm:w-auto">
                        <Link href="/quotes" class="flex-1 sm:flex-none">
                            <Button variant="outline" class="w-full"
                                >Back</Button
                            >
                        </Link>

                        <Button
                            v-if="quote.status === 'draft'"
                            @click="updateStatus('sent')"
                            variant="default"
                            class="flex-1 bg-blue-600 text-white hover:bg-blue-700 sm:flex-none"
                        >
                            Mark as Sent
                        </Button>

                        <template v-if="quote.status === 'sent'">
                            <Button
                                @click="updateStatus('accepted')"
                                variant="default"
                                class="flex-1 bg-green-600 text-white hover:bg-green-700 sm:flex-none"
                            >
                                Accept Quote
                            </Button>
                            <Button
                                @click="updateStatus('rejected')"
                                variant="destructive"
                                class="flex-1 sm:flex-none"
                            >
                                Reject
                            </Button>
                        </template>

                        <Button
                            v-if="
                                quote.status === 'accepted' ||
                                quote.status === 'rejected'
                            "
                            @click="updateStatus('draft')"
                            variant="outline"
                            class="flex-1 sm:flex-none"
                        >
                            Revert to Draft
                        </Button>

                        <a
                            :href="`/quotes/${quote.id}/pdf`"
                            class="flex-1 sm:flex-none"
                        >
                            <Button variant="secondary" class="w-full">
                                Download PDF
                            </Button>
                        </a>
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
                                    {{ quote.entity.name }}
                                </p>
                                <p v-if="quote.entity.vat_number" class="mt-1">
                                    NIF: {{ quote.entity.vat_number }}
                                </p>
                                <p v-if="quote.entity.email" class="mt-1">
                                    {{ quote.entity.email }}
                                </p>
                            </div>
                        </div>
                        <div class="md:text-right">
                            <h3
                                class="mb-4 text-sm font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Quote Info
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
                                            quote.issue_date,
                                        ).toLocaleDateString()
                                    }}
                                </p>
                                <p>
                                    <span class="text-gray-500"
                                        >Valid Until:</span
                                    >
                                    {{
                                        new Date(
                                            quote.valid_until,
                                        ).toLocaleDateString()
                                    }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr
                                        class="border-b border-gray-200 text-gray-500 dark:border-gray-700"
                                    >
                                        <th class="pb-3 font-medium">
                                            Description
                                        </th>
                                        <th
                                            class="pb-3 text-center font-medium"
                                        >
                                            Qty
                                        </th>
                                        <th class="pb-3 text-right font-medium">
                                            Unit Price
                                        </th>
                                        <th class="pb-3 text-right font-medium">
                                            VAT
                                        </th>
                                        <th class="pb-3 text-right font-medium">
                                            Total
                                        </th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-100 dark:divide-gray-800"
                                >
                                    <tr
                                        v-for="line in quote.lines"
                                        :key="line.id"
                                        class="text-gray-900 dark:text-gray-100"
                                    >
                                        <td class="py-4">
                                            {{ line.description }}
                                        </td>
                                        <td class="py-4 text-center">
                                            {{
                                                Number(line.quantity).toFixed(2)
                                            }}
                                        </td>
                                        <td class="py-4 text-right">
                                            €
                                            {{
                                                Number(line.unit_price).toFixed(
                                                    2,
                                                )
                                            }}
                                        </td>
                                        <td class="py-4 text-right">
                                            {{
                                                Number(
                                                    line.vat_percentage,
                                                ).toFixed(2)
                                            }}%
                                        </td>
                                        <td class="py-4 text-right font-medium">
                                            €
                                            {{ Number(line.total).toFixed(2) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div
                        class="grid grid-cols-1 gap-8 bg-gray-50 p-8 md:grid-cols-2 dark:bg-gray-800/50"
                    >
                        <div>
                            <h3
                                v-if="quote.notes"
                                class="mb-2 text-sm font-medium tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >
                                Notes / Terms
                            </h3>
                            <p
                                class="text-sm whitespace-pre-line text-gray-700 dark:text-gray-300"
                            >
                                {{ quote.notes }}
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
                                        Number(quote.subtotal).toFixed(2)
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
                                        Number(quote.vat_total).toFixed(2)
                                    }}</span
                                >
                            </div>
                            <div
                                class="flex justify-between pt-1 text-xl font-bold text-gray-900 dark:text-white"
                            >
                                <span>Total</span>
                                <span
                                    >€
                                    {{ Number(quote.total).toFixed(2) }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
