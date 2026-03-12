<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    entity: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Entities', href: '/entities' },
    { title: 'Edit Entity', href: `/entities/${props.entity.id}/edit` },
];

const form = useForm({
    is_customer: props.entity.is_customer,
    is_supplier: props.entity.is_supplier,
    vat_number: props.entity.vat_number,
    name: props.entity.name,
    address: props.entity.address || '',
    zip_code: props.entity.zip_code || '',
    city: props.entity.city || '',
    phone: props.entity.phone || '',
    mobile: props.entity.mobile || '',
    email: props.entity.email || '',
    website: props.entity.website || '',
    notes: props.entity.notes || '',
    gdpr_consent: props.entity.gdpr_consent,
});

const submit = () => {
    form.put(`/entities/${props.entity.id}`);
};
</script>

<template>
    <Head title="Edit Entity" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900"
                >
                    <div
                        class="mb-8 flex flex-col justify-between gap-4 border-b border-gray-100 pb-4 sm:flex-row sm:items-center dark:border-gray-800"
                    >
                        <div>
                            <h2
                                class="text-2xl leading-tight font-semibold text-gray-900 dark:text-white"
                            >
                                Edit Entity: {{ entity.name }}
                            </h2>
                            <p
                                class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                            >
                                Update the details or remove this entity from
                                the system.
                            </p>
                        </div>

                        <ConfirmDeleteDialog
                            :url="`/entities/${entity.id}`"
                            trigger-variant="destructive"
                            trigger-text="Delete Entity"
                            :description="`This action cannot be undone. This will permanently delete ${entity.name} from your database.`"
                        />
                    </div>

                    <form @submit.prevent="submit" class="space-y-8">
                        <div
                            class="flex flex-col gap-4 rounded-lg border border-gray-200 bg-gray-50 p-5 sm:flex-row sm:gap-8 dark:border-gray-700 dark:bg-gray-800/60"
                        >
                            <div class="flex items-center space-x-3">
                                <Checkbox
                                    id="is_customer"
                                    :checked="form.is_customer"
                                    @update:checked="
                                        (val: boolean) =>
                                            (form.is_customer = val)
                                    "
                                    class="border-gray-400 dark:border-gray-500 dark:data-[state=checked]:bg-white dark:data-[state=checked]:text-gray-900"
                                />
                                <Label
                                    for="is_customer"
                                    class="cursor-pointer text-base font-medium text-gray-800 dark:text-gray-200"
                                >
                                    Customer
                                </Label>
                            </div>
                            <div class="flex items-center space-x-3">
                                <Checkbox
                                    id="is_supplier"
                                    :checked="form.is_supplier"
                                    @update:checked="
                                        (val: boolean) =>
                                            (form.is_supplier = val)
                                    "
                                    class="border-gray-400 dark:border-gray-500 dark:data-[state=checked]:bg-white dark:data-[state=checked]:text-gray-900"
                                />
                                <Label
                                    for="is_supplier"
                                    class="cursor-pointer text-base font-medium text-gray-800 dark:text-gray-200"
                                >
                                    Supplier
                                </Label>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-2">
                                <Label
                                    for="vat_number"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >VAT Number (NIF) *</Label
                                >
                                <Input
                                    id="vat_number"
                                    v-model="form.vat_number"
                                    type="text"
                                    required
                                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-gray-400"
                                />
                                <p
                                    v-if="form.errors.vat_number"
                                    class="text-sm text-red-500 dark:text-red-400"
                                >
                                    {{ form.errors.vat_number }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label
                                    for="name"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Entity Name *</Label
                                >
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-gray-400"
                                />
                                <p
                                    v-if="form.errors.name"
                                    class="text-sm text-red-500 dark:text-red-400"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label
                                for="address"
                                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Address</Label
                            >
                            <Input
                                id="address"
                                v-model="form.address"
                                type="text"
                                class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-gray-400"
                            />
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-2">
                                <Label
                                    for="zip_code"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Zip Code</Label
                                >
                                <Input
                                    id="zip_code"
                                    v-model="form.zip_code"
                                    type="text"
                                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-gray-400"
                                />
                            </div>
                            <div class="space-y-2">
                                <Label
                                    for="city"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >City</Label
                                >
                                <Input
                                    id="city"
                                    v-model="form.city"
                                    type="text"
                                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-gray-400"
                                />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-2">
                                <Label
                                    for="email"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Email Address</Label
                                >
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-gray-400"
                                />
                            </div>
                            <div class="space-y-2">
                                <Label
                                    for="website"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Website</Label
                                >
                                <Input
                                    id="website"
                                    v-model="form.website"
                                    type="url"
                                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-gray-400"
                                />
                            </div>
                            <div class="space-y-2">
                                <Label
                                    for="phone"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Phone</Label
                                >
                                <Input
                                    id="phone"
                                    v-model="form.phone"
                                    type="text"
                                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-gray-400"
                                />
                            </div>
                            <div class="space-y-2">
                                <Label
                                    for="mobile"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Mobile</Label
                                >
                                <Input
                                    id="mobile"
                                    v-model="form.mobile"
                                    type="text"
                                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-gray-400"
                                />
                            </div>
                        </div>

                        <div class="flex items-center space-x-3 pt-2">
                            <Checkbox
                                id="gdpr_consent"
                                :checked="form.gdpr_consent"
                                @update:checked="
                                    (val: boolean) => (form.gdpr_consent = val)
                                "
                                class="border-gray-400 dark:border-gray-500 dark:data-[state=checked]:bg-white dark:data-[state=checked]:text-gray-900"
                            />
                            <Label
                                for="gdpr_consent"
                                class="cursor-pointer text-sm font-medium text-gray-700 dark:text-gray-300"
                            >
                                Entity has provided GDPR consent
                            </Label>
                        </div>

                        <div
                            class="mt-8 flex flex-col-reverse justify-end gap-4 border-t border-gray-100 pt-6 sm:flex-row dark:border-gray-800"
                        >
                            <Link href="/entities" class="w-full sm:w-auto">
                                <Button
                                    variant="outline"
                                    type="button"
                                    class="w-full sm:w-auto dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                                >
                                    Cancel
                                </Button>
                            </Link>
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full sm:w-auto dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200"
                            >
                                Update Entity
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
