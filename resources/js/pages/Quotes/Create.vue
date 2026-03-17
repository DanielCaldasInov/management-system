<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    customers: any[];
    articles: any[];
    defaultReference: string;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Quotes', href: '/quotes' },
    { title: 'Create Quote', href: '/quotes/create' },
];

const getToday = () => new Date().toISOString().split('T')[0];
const getValidUntil = () => {
    const d = new Date();
    d.setDate(d.getDate() + 30);
    return d.toISOString().split('T')[0];
};

const form = useForm({
    entity_id: '',
    reference: props.defaultReference,
    issue_date: getToday(),
    valid_until: getValidUntil(),
    notes: '',
    subtotal: 0,
    vat_total: 0,
    total: 0,
    lines: [] as any[],
});

const addLine = () => {
    form.lines.push({
        article_id: '',
        description: '',
        quantity: 1,
        unit_price: 0,
        vat_percentage: 23,
        subtotal: 0,
        vat_amount: 0,
        total: 0,
    });
};

if (form.lines.length === 0) {
    addLine();
}

const removeLine = (index: number) => {
    form.lines.splice(index, 1);
};

const handleArticleSelect = (index: number) => {
    const line = form.lines[index];
    const article = props.articles.find((a) => a.id == line.article_id);

    if (article) {
        line.description = article.name;
        line.unit_price = Number(article.price);
        line.vat_percentage = Number(article.vat_percentage);
    }
};

watch(
    () => form.lines,
    (newLines) => {
        let globalSubtotal = 0;
        let globalVat = 0;

        newLines.forEach((line) => {
            const qty = Number(line.quantity) || 0;
            const price = Number(line.unit_price) || 0;
            const vatP = Number(line.vat_percentage) || 0;

            line.subtotal = qty * price;
            line.vat_amount = line.subtotal * (vatP / 100);
            line.total = line.subtotal + line.vat_amount;

            globalSubtotal += line.subtotal;
            globalVat += line.vat_amount;
        });

        form.subtotal = globalSubtotal;
        form.vat_total = globalVat;
        form.total = globalSubtotal + globalVat;
    },
    { deep: true },
);

const submit = () => {
    form.post('/quotes');
};
</script>

<template>
    <Head title="Create Quote" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <form @submit.prevent="submit" class="space-y-6">
                    <div
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900"
                    >
                        <h2
                            class="mb-6 border-b border-gray-100 pb-2 text-lg font-semibold text-gray-900 dark:border-gray-800 dark:text-white"
                        >
                            Quote Details
                        </h2>

                        <div
                            class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4"
                        >
                            <div class="space-y-2 sm:col-span-2">
                                <Label
                                    for="entity_id"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Customer *</Label
                                >
                                <select
                                    id="entity_id"
                                    v-model="form.entity_id"
                                    required
                                    class="flex h-10 w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                >
                                    <option value="" disabled>
                                        Select a customer...
                                    </option>
                                    <option
                                        v-for="customer in customers"
                                        :key="customer.id"
                                        :value="customer.id"
                                    >
                                        {{ customer.name }} (NIF:
                                        {{ customer.vat_number || 'N/A' }})
                                    </option>
                                </select>
                                <p
                                    v-if="form.errors.entity_id"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.entity_id }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label
                                    for="reference"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Reference *</Label
                                >
                                <Input
                                    id="reference"
                                    v-model="form.reference"
                                    type="text"
                                    required
                                    class="dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                />
                                <p
                                    v-if="form.errors.reference"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.reference }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label
                                    for="issue_date"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Issue Date *</Label
                                >
                                <Input
                                    id="issue_date"
                                    v-model="form.issue_date"
                                    type="date"
                                    required
                                    class="dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                />
                            </div>

                            <div class="space-y-2">
                                <Label
                                    for="valid_until"
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >Valid Until *</Label
                                >
                                <Input
                                    id="valid_until"
                                    v-model="form.valid_until"
                                    type="date"
                                    required
                                    class="dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                />
                            </div>
                        </div>
                    </div>

                    <div
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900"
                    >
                        <div
                            class="mb-4 flex items-center justify-between border-b border-gray-100 pb-2 dark:border-gray-800"
                        >
                            <h2
                                class="text-lg font-semibold text-gray-900 dark:text-white"
                            >
                                Articles & Services
                            </h2>
                            <Button
                                type="button"
                                variant="outline"
                                size="sm"
                                @click="addLine"
                            >
                                + Add Line
                            </Button>
                        </div>

                        <p
                            v-if="form.errors.lines"
                            class="mb-4 text-sm text-red-500"
                        >
                            {{ form.errors.lines }}
                        </p>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr
                                        class="border-b border-gray-200 text-gray-500 dark:border-gray-800"
                                    >
                                        <th class="pb-2 font-medium">
                                            Article (Optional)
                                        </th>
                                        <th class="pb-2 font-medium">
                                            Description *
                                        </th>
                                        <th class="w-24 pb-2 font-medium">
                                            Qty *
                                        </th>
                                        <th class="w-32 pb-2 font-medium">
                                            Price (€) *
                                        </th>
                                        <th class="w-24 pb-2 font-medium">
                                            VAT (%) *
                                        </th>
                                        <th
                                            class="w-32 pb-2 text-right font-medium"
                                        >
                                            Line Total
                                        </th>
                                        <th class="pb-2"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(line, index) in form.lines"
                                        :key="index"
                                        class="border-b border-gray-100 dark:border-gray-800"
                                    >
                                        <td class="py-3 pr-2">
                                            <select
                                                v-model="line.article_id"
                                                @change="
                                                    handleArticleSelect(index)
                                                "
                                                class="flex h-9 w-full rounded-md border border-gray-300 bg-transparent px-3 py-1 text-sm focus:ring-2 focus:ring-gray-400 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                            >
                                                <option value="">
                                                    Custom Item
                                                </option>
                                                <option
                                                    v-for="art in articles"
                                                    :key="art.id"
                                                    :value="art.id"
                                                >
                                                    {{ art.name }}
                                                </option>
                                            </select>
                                        </td>

                                        <td class="py-3 pr-2">
                                            <Input
                                                v-model="line.description"
                                                type="text"
                                                required
                                                placeholder="Description..."
                                                class="h-9 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                            />
                                        </td>

                                        <td class="py-3 pr-2">
                                            <Input
                                                v-model="line.quantity"
                                                type="number"
                                                step="0.01"
                                                min="0.01"
                                                required
                                                class="h-9 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                            />
                                        </td>

                                        <td class="py-3 pr-2">
                                            <Input
                                                v-model="line.unit_price"
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                required
                                                class="h-9 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                            />
                                        </td>

                                        <td class="py-3 pr-2">
                                            <Input
                                                v-model="line.vat_percentage"
                                                type="number"
                                                step="0.01"
                                                min="0"
                                                required
                                                class="h-9 dark:border-gray-700 dark:bg-gray-800 dark:text-white"
                                            />
                                        </td>

                                        <td
                                            class="py-3 pr-2 text-right font-medium text-gray-900 dark:text-gray-100"
                                        >
                                            €
                                            {{ Number(line.total).toFixed(2) }}
                                        </td>

                                        <td class="py-3 text-right">
                                            <Button
                                                type="button"
                                                variant="ghost"
                                                size="icon"
                                                @click="removeLine(index)"
                                                class="h-8 w-8 text-red-500 hover:bg-red-50 hover:text-red-700 dark:hover:bg-red-900/30"
                                            >
                                                &times;
                                            </Button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div
                                v-if="form.lines.length === 0"
                                class="py-6 text-center text-sm text-gray-500"
                            >
                                No lines added. Please add at least one article
                                or service.
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col-reverse gap-6 lg:flex-row">
                        <div
                            class="flex-1 overflow-hidden rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900"
                        >
                            <Label
                                for="notes"
                                class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                                >Internal Notes / Terms</Label
                            >
                            <textarea
                                id="notes"
                                v-model="form.notes"
                                rows="4"
                                class="flex w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-gray-400"
                                placeholder="Payment terms, special conditions..."
                            ></textarea>
                        </div>

                        <div
                            class="w-full overflow-hidden rounded-xl border border-gray-200 bg-gray-50 p-6 shadow-sm lg:w-80 dark:border-gray-800 dark:bg-gray-800/50"
                        >
                            <div class="space-y-3">
                                <div
                                    class="flex justify-between text-sm text-gray-600 dark:text-gray-400"
                                >
                                    <span>Subtotal</span>
                                    <span
                                        >€
                                        {{
                                            Number(form.subtotal).toFixed(2)
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
                                            Number(form.vat_total).toFixed(2)
                                        }}</span
                                    >
                                </div>
                                <div
                                    class="flex justify-between pt-1 text-xl font-bold text-gray-900 dark:text-white"
                                >
                                    <span>Total</span>
                                    <span
                                        >€
                                        {{
                                            Number(form.total).toFixed(2)
                                        }}</span
                                    >
                                </div>
                            </div>

                            <Button
                                type="submit"
                                :disabled="
                                    form.processing || form.lines.length === 0
                                "
                                class="mt-6 w-full dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200"
                            >
                                {{
                                    form.processing ? 'Saving...' : 'Save Quote'
                                }}
                            </Button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
