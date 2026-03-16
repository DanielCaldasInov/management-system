<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

const props = defineProps<{
    article?: any;
    isEdit?: boolean;
}>();

// Use _method: 'put' for edit because HTML forms don't support file uploads via PUT
const form = useForm({
    reference: props.article?.reference ?? '',
    name: props.article?.name ?? '',
    description: props.article?.description ?? '',
    price: props.article?.price ?? 0,
    vat_percentage: props.article?.vat_percentage ?? 23.0,
    photo: null as File | null,
    notes: props.article?.notes ?? '',
    is_active:
        props.article !== undefined ? Boolean(props.article.is_active) : true,
    _method: props.isEdit ? 'put' : 'post',
});

const photoPreview = ref<string | null>(
    props.article?.photo_path ? `/storage/${props.article.photo_path}` : null,
);

const handlePhotoChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        const file = target.files[0];
        form.photo = file;
        photoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    const payload = {
        ...form.data(),
        is_active: form.is_active ? 1 : 0,
    };

    if (props.isEdit && props.article) {
        form.transform(() => payload).post(`/articles/${props.article.id}`, {
            preserveScroll: true,
        });
    } else {
        form.transform(() => payload).post('/articles');
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-8">
        <div
            class="flex items-center space-x-3 rounded-lg border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-800/60"
        >
            <input
                type="checkbox"
                id="is_active"
                v-model="form.is_active"
                class="h-5 w-5 cursor-pointer rounded border-gray-400 text-gray-900 focus:ring-gray-900 dark:border-gray-500 dark:bg-gray-800 dark:checked:bg-white dark:checked:text-gray-900"
            />
            <Label
                for="is_active"
                class="cursor-pointer text-base font-medium text-gray-800 dark:text-gray-200"
            >
                Active Article
            </Label>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
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
                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-gray-400"
                />
                <p v-if="form.errors.reference" class="text-sm text-red-500">
                    {{ form.errors.reference }}
                </p>
            </div>

            <div class="space-y-2">
                <Label
                    for="name"
                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                    >Article Name *</Label
                >
                <Input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-gray-400"
                />
                <p v-if="form.errors.name" class="text-sm text-red-500">
                    {{ form.errors.name }}
                </p>
            </div>
        </div>

        <div class="space-y-2">
            <Label
                for="description"
                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                >Description</Label
            >
            <textarea
                id="description"
                v-model="form.description"
                rows="3"
                class="flex w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-gray-400 dark:focus:ring-offset-gray-900"
            ></textarea>
            <p v-if="form.errors.description" class="text-sm text-red-500">
                {{ form.errors.description }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="space-y-2">
                <Label
                    for="price"
                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                    >Price (€) *</Label
                >
                <Input
                    id="price"
                    v-model="form.price"
                    type="number"
                    step="0.01"
                    min="0"
                    required
                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-gray-400"
                />
                <p v-if="form.errors.price" class="text-sm text-red-500">
                    {{ form.errors.price }}
                </p>
            </div>

            <div class="space-y-2">
                <Label
                    for="vat_percentage"
                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                    >VAT (%) *</Label
                >
                <Input
                    id="vat_percentage"
                    v-model="form.vat_percentage"
                    type="number"
                    step="0.01"
                    min="0"
                    max="100"
                    required
                    class="dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:focus:border-gray-400"
                />
                <p
                    v-if="form.errors.vat_percentage"
                    class="text-sm text-red-500"
                >
                    {{ form.errors.vat_percentage }}
                </p>
            </div>
        </div>

        <div class="space-y-2">
            <Label
                for="photo"
                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                >Article Photo</Label
            >
            <div class="flex items-center gap-4">
                <div
                    v-if="photoPreview"
                    class="h-20 w-20 shrink-0 overflow-hidden rounded-md border border-gray-200 dark:border-gray-700"
                >
                    <img
                        :src="photoPreview"
                        alt="Preview"
                        class="h-full w-full object-cover"
                    />
                </div>
                <Input
                    id="photo"
                    type="file"
                    accept="image/*"
                    @change="handlePhotoChange"
                    class="w-full cursor-pointer dark:border-gray-600 dark:bg-gray-800 dark:text-white"
                />
            </div>
            <p v-if="form.errors.photo" class="text-sm text-red-500">
                {{ form.errors.photo }}
            </p>
        </div>

        <div class="space-y-2">
            <Label
                for="notes"
                class="text-sm font-medium text-gray-700 dark:text-gray-300"
                >Internal Notes</Label
            >
            <textarea
                id="notes"
                v-model="form.notes"
                rows="2"
                class="flex w-full rounded-md border border-gray-300 bg-transparent px-3 py-2 text-sm placeholder:text-gray-400 focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:focus:ring-gray-400 dark:focus:ring-offset-gray-900"
            ></textarea>
        </div>

        <div
            class="mt-8 flex flex-col-reverse justify-end gap-4 border-t border-gray-100 pt-6 sm:flex-row dark:border-gray-800"
        >
            <Link href="/articles" class="w-full sm:w-auto">
                <Button
                    variant="outline"
                    type="button"
                    class="w-full sm:w-auto dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700"
                    >Cancel</Button
                >
            </Link>
            <Button
                type="submit"
                :disabled="form.processing"
                class="w-full sm:w-auto dark:bg-white dark:text-gray-900 dark:hover:bg-gray-200"
            >
                {{ isEdit ? 'Update Article' : 'Save Article' }}
            </Button>
        </div>
    </form>
</template>
