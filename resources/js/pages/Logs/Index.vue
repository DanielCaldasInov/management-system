<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
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
    logs: any;
    filters: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'System Logs', href: '/logs' },
];

const search = ref(props.filters?.search || '');
const action = ref(props.filters?.action || '');
const model = ref(props.filters?.model || '');

let timeout: ReturnType<typeof setTimeout>;
watch([search, action, model], () => {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        router.get(
            '/logs',
            { search: search.value, action: action.value, model: model.value },
            { preserveState: true, replace: true },
        );
    }, 300);
});

const getEventBadge = (event: string) => {
    switch (event) {
        case 'created':
            return 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400';
        case 'updated':
            return 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
        case 'deleted':
            return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
        default:
            return 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400';
    }
};

const formatModelName = (type: string) => type.split('\\').pop();
</script>

<template>
    <Head title="Logs" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
                >
                    <h2
                        class="text-xl font-semibold text-gray-800 dark:text-gray-200"
                    >
                        System Audit Logs
                    </h2>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <Input
                            v-model="search"
                            placeholder="Search user..."
                            class="w-full sm:w-48 dark:bg-gray-900"
                        />

                        <select
                            v-model="action"
                            class="h-10 rounded-md border border-gray-300 bg-transparent px-3 text-sm focus:outline-none sm:w-40 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        >
                            <option value="">All Actions</option>
                            <option value="created">Created</option>
                            <option value="updated">Updated</option>
                            <option value="deleted">Deleted</option>
                        </select>

                        <select
                            v-model="model"
                            class="h-10 rounded-md border border-gray-300 bg-transparent px-3 text-sm focus:outline-none sm:w-40 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                        >
                            <option value="">All Models</option>
                            <option value="Entity">Entities</option>
                            <option value="Article">Articles</option>
                            <option value="Order">Orders</option>
                            <option value="Invoice">Invoices</option>
                        </select>
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900"
                >
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Date & Time</TableHead>
                                <TableHead>User</TableHead>
                                <TableHead>Action</TableHead>
                                <TableHead>Model</TableHead>
                                <TableHead>Record ID</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="log in logs.data" :key="log.id">
                                <TableCell class="font-mono text-xs">
                                    {{
                                        new Date(log.created_at).toLocaleString(
                                            'pt-PT',
                                        )
                                    }}
                                </TableCell>
                                <TableCell
                                    class="font-medium text-gray-900 dark:text-gray-100"
                                >
                                    {{ log.causer?.name || 'System' }}
                                </TableCell>
                                <TableCell>
                                    <span
                                        :class="getEventBadge(log.event)"
                                        class="rounded-full px-2.5 py-0.5 text-xs font-medium tracking-wider uppercase"
                                    >
                                        {{ log.event }}
                                    </span>
                                </TableCell>
                                <TableCell
                                    class="text-gray-600 dark:text-gray-400"
                                >
                                    {{ formatModelName(log.subject_type) }}
                                </TableCell>
                                <TableCell class="text-gray-500">
                                    #{{ log.subject_id }}
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="logs.data.length === 0">
                                <TableCell
                                    colspan="5"
                                    class="py-8 text-center text-gray-500"
                                    >No activity logs found.</TableCell
                                >
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
