<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    entity: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Entities', href: '/entities' },
    { title: props.entity.name, href: `/entities/${props.entity.id}` },
];
</script>

<template>
    <Head :title="entity.name" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div
                    class="mb-6 flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center"
                >
                    <h2
                        class="text-xl leading-tight font-semibold text-gray-800 dark:text-gray-200"
                    >
                        Entity Details
                    </h2>

                    <div class="flex w-full gap-2 sm:w-auto">
                        <Link href="/entities" class="flex-1 sm:flex-none">
                            <Button variant="outline" class="w-full sm:w-auto"
                                >Back to List</Button
                            >
                        </Link>
                        <Link
                            :href="`/entities/${entity.id}/edit`"
                            class="flex-1 sm:flex-none"
                        >
                            <Button class="w-full sm:w-auto"
                                >Edit Entity</Button
                            >
                        </Link>
                    </div>
                </div>

                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-900"
                >
                    <div
                        class="border-b border-gray-100 px-6 py-5 dark:border-gray-800"
                    >
                        <div class="flex items-center justify-between">
                            <h3
                                class="text-lg leading-6 font-medium text-gray-900 dark:text-white"
                            >
                                {{ entity.name }}
                            </h3>

                            <div class="flex gap-2">
                                <span
                                    v-if="entity.is_customer"
                                    class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-blue-700/10 ring-inset dark:bg-blue-900/30 dark:text-blue-400 dark:ring-blue-900/50"
                                >
                                    Customer
                                </span>
                                <span
                                    v-if="entity.is_supplier"
                                    class="inline-flex items-center rounded-md bg-orange-50 px-2 py-1 text-xs font-medium text-orange-700 ring-1 ring-orange-700/10 ring-inset dark:bg-orange-900/30 dark:text-orange-400 dark:ring-orange-900/50"
                                >
                                    Supplier
                                </span>
                            </div>
                        </div>
                        <p
                            class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400"
                        >
                            Internal System Number: #{{ entity.number }}
                        </p>
                    </div>

                    <div
                        class="border-t border-gray-100 px-6 py-5 sm:p-0 dark:border-gray-800"
                    >
                        <dl
                            class="sm:divide-y sm:divide-gray-100 dark:sm:divide-gray-800"
                        >
                            <div
                                class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                            >
                                <dt
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    VAT Number (NIF)
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100"
                                >
                                    {{ entity.vat_number }}
                                </dd>
                            </div>

                            <div
                                class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                            >
                                <dt
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    Address
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100"
                                >
                                    {{ entity.address || 'Not provided'
                                    }}<br v-if="entity.address" />
                                    {{ entity.zip_code }} {{ entity.city }}
                                </dd>
                            </div>

                            <div
                                class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                            >
                                <dt
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    Contact Information
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100"
                                >
                                    <ul class="space-y-1">
                                        <li>
                                            <strong>Email:</strong>
                                            {{ entity.email || 'N/A' }}
                                        </li>
                                        <li>
                                            <strong>Phone:</strong>
                                            {{ entity.phone || 'N/A' }}
                                        </li>
                                        <li>
                                            <strong>Mobile:</strong>
                                            {{ entity.mobile || 'N/A' }}
                                        </li>
                                        <li>
                                            <strong>Website:</strong>
                                            <a
                                                v-if="entity.website"
                                                :href="entity.website"
                                                target="_blank"
                                                class="text-blue-600 hover:underline dark:text-blue-400"
                                                >{{ entity.website }}</a
                                            >
                                            <span v-else>N/A</span>
                                        </li>
                                    </ul>
                                </dd>
                            </div>

                            <div
                                class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                            >
                                <dt
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    GDPR Consent
                                </dt>
                                <dd
                                    class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100"
                                >
                                    <span
                                        v-if="entity.gdpr_consent"
                                        class="font-medium text-green-600 dark:text-green-400"
                                        >Yes, consent provided</span
                                    >
                                    <span
                                        v-else
                                        class="font-medium text-red-600 dark:text-red-400"
                                        >No consent on file</span
                                    >
                                </dd>
                            </div>

                            <div
                                class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                            >
                                <dt
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    Notes
                                </dt>
                                <dd
                                    class="mt-1 text-sm whitespace-pre-line text-gray-900 sm:col-span-2 sm:mt-0 dark:text-gray-100"
                                >
                                    {{ entity.notes || 'No notes available.' }}
                                </dd>
                            </div>
                        </dl>
                    </div>

                    <div
                        class="flex justify-end border-t border-gray-100 bg-gray-50 px-6 py-4 dark:border-gray-800 dark:bg-gray-800/50"
                    >
                        <ConfirmDeleteDialog
                            :url="`/entities/${entity.id}`"
                            trigger-variant="destructive"
                            trigger-text="Delete Entity"
                            :description="`This will permanently delete ${entity.name} from the database. This action cannot be undone.`"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
