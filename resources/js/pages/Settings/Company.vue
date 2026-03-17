<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    company: any;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Settings', href: '#' },
    { title: 'Company Profile', href: '/settings/company' },
];

const form = useForm({
    name: props.company.name ?? '',
    vat_number: props.company.vat_number ?? '',
    address: props.company.address ?? '',
    zip_code: props.company.zip_code ?? '',
    city: props.company.city ?? '',
    logo: null as File | null,
});

const logoPreview = ref<string | null>(
    props.company.logo_path ? `/storage/${props.company.logo_path}` : null,
);

const handleLogoChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post('/settings/company', {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Company Profile" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900"
                >
                    <div
                        class="mb-8 border-b border-gray-100 pb-4 dark:border-gray-800"
                    >
                        <h2
                            class="text-2xl leading-tight font-semibold text-gray-900 dark:text-white"
                        >
                            Company Profile
                        </h2>
                        <p
                            class="mt-1 text-sm text-gray-500 dark:text-gray-400"
                        >
                            Manage your company's details and logo. This
                            information will appear on generated PDFs, quotes,
                            and invoices.
                        </p>
                    </div>

                    <form @submit.prevent="submit" class="space-y-8">
                        <div
                            class="flex flex-col gap-6 sm:flex-row sm:items-center"
                        >
                            <div
                                class="flex h-32 w-32 shrink-0 items-center justify-center overflow-hidden rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 dark:border-gray-700 dark:bg-gray-800"
                            >
                                <img
                                    v-if="logoPreview"
                                    :src="logoPreview"
                                    alt="Company Logo"
                                    class="h-full w-full object-contain p-2"
                                />
                                <div
                                    v-else
                                    class="text-center text-sm text-gray-400"
                                >
                                    No Logo
                                </div>
                            </div>
                            <div class="flex-1 space-y-2">
                                <Label
                                    for="logo"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Company Logo</Label
                                >
                                <Input
                                    id="logo"
                                    type="file"
                                    accept="image/*"
                                    @change="handleLogoChange"
                                    class="w-full cursor-pointer dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                                />
                                <p
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    Recommended size: 500x500px. Max size: 2MB.
                                </p>
                                <p
                                    v-if="form.errors.logo"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.logo }}
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div class="space-y-2">
                                <Label
                                    for="name"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Company Name *</Label
                                >
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-gray-400"
                                />
                                <p
                                    v-if="form.errors.name"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label
                                    for="vat_number"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >VAT Number (NIF)</Label
                                >
                                <Input
                                    id="vat_number"
                                    v-model="form.vat_number"
                                    type="text"
                                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-gray-400"
                                />
                                <p
                                    v-if="form.errors.vat_number"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.vat_number }}
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
                                class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-gray-400"
                            />
                            <p
                                v-if="form.errors.address"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.address }}
                            </p>
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
                                    placeholder="XXXX-XXX"
                                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-gray-400"
                                />
                                <p
                                    v-if="form.errors.zip_code"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.zip_code }}
                                </p>
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
                                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-gray-400"
                                />
                                <p
                                    v-if="form.errors.city"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.city }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="mt-8 flex justify-end border-t border-gray-100 pt-6 dark:border-gray-800"
                        >
                            <Button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full sm:w-auto dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200"
                            >
                                {{
                                    form.processing
                                        ? 'Saving...'
                                        : 'Save Settings'
                                }}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
