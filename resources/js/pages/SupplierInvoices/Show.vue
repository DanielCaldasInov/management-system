<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
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
    invoice: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Supplier Invoices', href: '/supplier-invoices' },
    {
        title: props.invoice.reference,
        href: `/supplier-invoices/${props.invoice.id}`,
    },
];

const getStatusColor = (status: string) => {
    switch (status) {
        case 'draft':
            return 'bg-gray-100 text-gray-800';
        case 'pending':
            return 'bg-amber-100 text-amber-800';
        case 'paid':
            return 'bg-green-100 text-green-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const form = useForm({
    attachment: null as File | null,
});

const fileInput = ref<HTMLInputElement | null>(null);

const handleFileUpload = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.attachment = target.files[0];

        form.post(`/supplier-invoices/${props.invoice.id}/attachment`, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                if (fileInput.value) fileInput.value.value = ''; // Limpar o input
            },
        });
    }
};

const triggerFileInput = () => {
    fileInput.value?.click();
};

const sendingEmail = ref(false);

const sendEmail = () => {
    if (!props.invoice.entity.email) {
        alert('Este fornecedor não tem email configurado!');
        return;
    }

    if (confirm(`Deseja enviar a fatura para ${props.invoice.entity.email}?`)) {
        sendingEmail.value = true;
        router.post(
            `/supplier-invoices/${props.invoice.id}/send-email`,
            {},
            {
                preserveScroll: true,
                onFinish: () => (sendingEmail.value = false),
            },
        );
    }
};
</script>

<template>
    <Head :title="`Invoice ${invoice.reference}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center"
                >
                    <div>
                        <h2
                            class="flex items-center gap-3 text-2xl font-bold text-gray-900 dark:text-white"
                        >
                            {{ invoice.reference }}
                            <span
                                :class="getStatusColor(invoice.status)"
                                class="rounded-full px-2.5 py-0.5 text-xs font-semibold tracking-wide uppercase"
                            >
                                {{ invoice.status }}
                            </span>
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Supplier:
                            <span
                                class="font-medium text-gray-900 dark:text-gray-300"
                            >
                                {{ invoice.entity.name }}
                            </span>
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <button
                            @click="sendEmail"
                            :disabled="sendingEmail || !invoice.entity.email"
                            class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-indigo-700 disabled:opacity-50"
                        >
                            <svg
                                v-if="!sendingEmail"
                                xmlns="http://www.w3.org/2000/svg"
                                class="h-4 w-4"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                />
                            </svg>
                            <span
                                v-else
                                class="h-4 w-4 animate-spin rounded-full border-2 border-white border-t-transparent"
                            ></span>
                            {{
                                sendingEmail
                                    ? 'Sending...'
                                    : 'Notify Supplier'
                            }}
                        </button>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <div class="space-y-6 lg:col-span-2">
                        <div
                            class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
                        >
                            <div
                                class="border-b border-gray-200 px-6 py-4 dark:border-gray-800"
                            >
                                <h3
                                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                                >
                                    Invoice Lines
                                </h3>
                            </div>
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>Description</TableHead>
                                        <TableHead class="text-right"
                                            >Qty</TableHead
                                        >
                                        <TableHead class="text-right"
                                            >Unit Price</TableHead
                                        >
                                        <TableHead class="text-right"
                                            >Total</TableHead
                                        >
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow
                                        v-for="line in invoice.lines"
                                        :key="line.id"
                                    >
                                        <TableCell
                                            class="font-medium text-gray-900 dark:text-gray-100"
                                        >
                                            {{
                                                line.article
                                                    ? line.article.name
                                                    : line.description
                                            }}
                                        </TableCell>
                                        <TableCell class="text-right">
                                            {{
                                                Number(line.quantity).toFixed(2)
                                            }}
                                        </TableCell>
                                        <TableCell class="text-right">
                                            €
                                            {{
                                                Number(line.unit_price).toFixed(
                                                    2,
                                                )
                                            }}
                                        </TableCell>
                                        <TableCell
                                            class="text-right font-medium"
                                        >
                                            €
                                            {{ Number(line.total).toFixed(2) }}
                                        </TableCell>
                                    </TableRow>
                                    <TableRow v-if="!invoice.lines.length">
                                        <TableCell
                                            colspan="4"
                                            class="py-4 text-center text-gray-500"
                                        >
                                            No lines found.
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div
                            class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                        >
                            <h3
                                class="mb-4 text-lg font-medium text-gray-900 dark:text-gray-100"
                            >
                                Summary
                            </h3>
                            <div class="space-y-3 text-sm">
                                <div
                                    class="flex justify-between text-gray-600 dark:text-gray-400"
                                >
                                    <span>Issue Date</span>
                                    <span
                                        class="font-medium text-gray-900 dark:text-gray-200"
                                    >
                                        {{
                                            new Date(
                                                invoice.issue_date,
                                            ).toLocaleDateString()
                                        }}
                                    </span>
                                </div>
                                <div
                                    class="flex justify-between text-gray-600 dark:text-gray-400"
                                >
                                    <span>Due Date</span>
                                    <span
                                        class="font-medium text-gray-900 dark:text-gray-200"
                                    >
                                        {{
                                            new Date(
                                                invoice.due_date,
                                            ).toLocaleDateString()
                                        }}
                                    </span>
                                </div>
                                <div
                                    class="my-3 border-t border-gray-200 dark:border-gray-800"
                                ></div>
                                <div
                                    class="flex justify-between text-gray-600 dark:text-gray-400"
                                >
                                    <span>Subtotal</span>
                                    <span
                                        >€
                                        {{
                                            Number(invoice.subtotal).toFixed(2)
                                        }}</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between text-gray-600 dark:text-gray-400"
                                >
                                    <span>VAT Total</span>
                                    <span
                                        >€
                                        {{
                                            Number(invoice.vat_total).toFixed(2)
                                        }}</span
                                    >
                                </div>
                                <div
                                    class="my-3 border-t border-gray-200 dark:border-gray-800"
                                ></div>
                                <div
                                    class="flex justify-between text-lg font-bold text-gray-900 dark:text-white"
                                >
                                    <span>Total</span>
                                    <span
                                        >€
                                        {{
                                            Number(invoice.total).toFixed(2)
                                        }}</span
                                    >
                                </div>
                            </div>
                        </div>

                        <div
                            class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900"
                        >
                            <h3
                                class="mb-4 text-lg font-medium text-gray-900 dark:text-gray-100"
                            >
                                Original Invoice
                            </h3>

                            <input
                                type="file"
                                ref="fileInput"
                                class="hidden"
                                accept=".pdf,.jpg,.jpeg,.png"
                                @change="handleFileUpload"
                            />

                            <div v-if="invoice.attachment_path" class="mb-4">
                                <a
                                    :href="`/storage/${invoice.attachment_path}`"
                                    target="_blank"
                                    class="flex items-center gap-2 rounded-md bg-blue-50 p-3 text-blue-700 transition-colors hover:bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"
                                        />
                                    </svg>
                                    <span class="truncate text-sm font-medium"
                                        >View Attachment</span
                                    >
                                </a>
                            </div>

                            <button
                                @click="triggerFileInput"
                                :disabled="form.processing"
                                class="flex w-full items-center justify-center gap-2 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm transition-colors hover:bg-gray-50 focus:ring-2 focus:ring-black focus:ring-offset-2 focus:outline-none disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                            >
                                <svg
                                    v-if="!form.processing"
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 text-gray-400"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"
                                    />
                                </svg>
                                <span
                                    v-if="form.processing"
                                    class="h-5 w-5 animate-spin rounded-full border-2 border-gray-500 border-t-transparent"
                                ></span>
                                {{
                                    form.processing
                                        ? 'Uploading...'
                                        : invoice.attachment_path
                                          ? 'Replace File'
                                          : 'Upload File'
                                }}
                            </button>
                            <p class="mt-2 text-center text-xs text-gray-500">
                                PDF, JPG or PNG up to 5MB
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
