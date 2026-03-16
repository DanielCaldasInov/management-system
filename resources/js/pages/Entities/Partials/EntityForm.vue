<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import axios from 'axios';
import { Loader2, Search } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const props = defineProps<{
    entity?: any;
    isEdit?: boolean;
}>();

const form = useForm({
    is_customer:
        props.entity !== undefined ? Boolean(props.entity.is_customer) : true,
    is_supplier:
        props.entity !== undefined ? Boolean(props.entity.is_supplier) : false,
    vat_number: props.entity?.vat_number ?? '',
    name: props.entity?.name ?? '',
    address: props.entity?.address ?? '',
    zip_code: props.entity?.zip_code ?? '',
    city: props.entity?.city ?? '',
    phone: props.entity?.phone ?? '',
    mobile: props.entity?.mobile ?? '',
    email: props.entity?.email ?? '',
    website: props.entity?.website ?? '',
    notes: props.entity?.notes ?? '',
    gdpr_consent:
        props.entity !== undefined ? Boolean(props.entity.gdpr_consent) : false,
});

const countryCode = ref('PT');
const isCheckingVies = ref(false);
const viesMessage = ref('');
const viesError = ref(false);

const checkVies = async () => {
    if (!form.vat_number) {
        viesError.value = true;
        viesMessage.value = 'Please enter a VAT number first.';
        return;
    }

    let parsedCountry = countryCode.value.toUpperCase();
    let parsedVat = form.vat_number.replace(/\s+/g, '');

    const match = parsedVat.match(/^([A-Za-z]{2})(.+)$/);
    if (match) {
        parsedCountry = match[1].toUpperCase();
        parsedVat = match[2];
        countryCode.value = parsedCountry;
        form.vat_number = parsedVat;
    }

    if (!parsedCountry) {
        parsedCountry = 'PT';
        countryCode.value = 'PT';
    }

    isCheckingVies.value = true;
    viesMessage.value = '';
    viesError.value = false;

    try {
        const response = await axios.post('/api/vies/check', {
            country_code: parsedCountry,
            vat_number: parsedVat,
        });

        if (response.data.success) {
            const viesData = response.data.data;

            form.name = viesData.name;
            form.address = viesData.address;
            form.zip_code = viesData.zip_code;
            form.city = viesData.city;

            viesError.value = false;
            viesMessage.value = 'Entity found! Form auto-filled successfully.';
        }
    } catch (error: any) {
        viesError.value = true;
        if (error.response?.data?.message) {
            viesMessage.value = error.response.data.message;
        } else {
            viesMessage.value = 'Failed to check VIES. Please try again.';
        }
    } finally {
        isCheckingVies.value = false;
    }
};

const submit = () => {
    const payload = {
        ...form.data(),
        is_customer: form.is_customer ? 1 : 0,
        is_supplier: form.is_supplier ? 1 : 0,
        gdpr_consent: form.gdpr_consent ? 1 : 0,
    };

    if (props.isEdit && props.entity) {
        form.transform((data) => ({
            ...data,
            is_customer: payload.is_customer,
            is_supplier: payload.is_supplier,
            gdpr_consent: payload.gdpr_consent,
        })).put(`/entities/${props.entity.id}`);
    } else {
        form.transform((data) => ({
            ...data,
            is_customer: payload.is_customer,
            is_supplier: payload.is_supplier,
            gdpr_consent: payload.gdpr_consent,
        })).post('/entities');
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-8">
        <div
            class="flex flex-col gap-4 rounded-lg border border-gray-200 bg-gray-50 p-5 sm:flex-row sm:gap-8 dark:border-gray-700 dark:bg-gray-800/60"
        >
            <div class="flex items-center space-x-3">
                <input
                    type="checkbox"
                    id="is_customer"
                    v-model="form.is_customer"
                    class="h-5 w-5 cursor-pointer rounded border-gray-400 text-gray-900 focus:ring-gray-900 dark:border-gray-500 dark:bg-gray-800 dark:checked:bg-white dark:checked:text-gray-900"
                />
                <Label
                    for="is_customer"
                    class="cursor-pointer text-base font-medium text-gray-800 dark:text-gray-200"
                    >Customer</Label
                >
            </div>
            <div class="flex items-center space-x-3">
                <input
                    type="checkbox"
                    id="is_supplier"
                    v-model="form.is_supplier"
                    class="h-5 w-5 cursor-pointer rounded border-gray-400 text-gray-900 focus:ring-gray-900 dark:border-gray-500 dark:bg-gray-800 dark:checked:bg-white dark:checked:text-gray-900"
                />
                <Label
                    for="is_supplier"
                    class="cursor-pointer text-base font-medium text-gray-800 dark:text-gray-200"
                    >Supplier</Label
                >
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="space-y-2">
                <Label
                    for="vat_number"
                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                    >VAT Number (NIF) *</Label
                >
                <div class="flex items-center gap-2">
                    <Input
                        v-model="countryCode"
                        type="text"
                        maxlength="2"
                        class="w-15 text-center uppercase dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                        placeholder="PT"
                        title="Country Code"
                    />
                    <Input
                        id="vat_number"
                        v-model="form.vat_number"
                        type="text"
                        required
                        class="flex-1 dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500 dark:focus:border-gray-400"
                        placeholder="501234567"
                    />
                    <Button
                        type="button"
                        variant="secondary"
                        @click="checkVies"
                        :disabled="isCheckingVies"
                        class="dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600"
                    >
                        <Loader2
                            v-if="isCheckingVies"
                            class="mr-2 h-4 w-4 animate-spin"
                        />
                        <Search v-else class="mr-2 h-4 w-4" />
                        <span class="hidden sm:inline">Check VIES</span>
                    </Button>
                </div>
                <p
                    v-if="viesMessage"
                    :class="
                        viesError
                            ? 'text-red-500 dark:text-red-400'
                            : 'text-green-600 dark:text-green-400'
                    "
                    class="text-sm font-medium"
                >
                    {{ viesMessage }}
                </p>
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
                    placeholder="XXXX-XXX"
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
            <input
                type="checkbox"
                id="gdpr_consent"
                v-model="form.gdpr_consent"
                class="h-5 w-5 cursor-pointer rounded border-gray-400 text-gray-900 focus:ring-gray-900 dark:border-gray-500 dark:bg-gray-800 dark:checked:bg-white dark:checked:text-gray-900"
            />
            <Label
                for="gdpr_consent"
                class="cursor-pointer text-sm font-medium text-gray-700 dark:text-gray-300"
                >Entity has provided GDPR consent</Label
            >
        </div>

        <div
            class="mt-8 flex flex-col-reverse justify-end gap-4 border-t border-gray-100 pt-6 sm:flex-row dark:border-gray-800"
        >
            <Link href="/entities" class="w-full sm:w-auto">
                <Button
                    variant="outline"
                    type="button"
                    class="w-full sm:w-auto dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                    >Cancel</Button
                >
            </Link>
            <Button
                type="submit"
                :disabled="form.processing || isCheckingVies"
                class="w-full sm:w-auto dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200"
            >
                {{ isEdit ? 'Update Entity' : 'Save Entity' }}
            </Button>
        </div>
    </form>
</template>
