<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

const props = defineProps<{
    url: string;
    title?: string;
    description?: string;
    triggerText?: string;
    triggerVariant?:
        | 'default'
        | 'destructive'
        | 'outline'
        | 'secondary'
        | 'ghost'
        | 'link';
    triggerSize?: 'default' | 'sm' | 'lg' | 'icon';
}>();

const isOpen = ref(false);
const isProcessing = ref(false);

const confirmDelete = () => {
    router.delete(props.url, {
        onBefore: () => (isProcessing.value = true),
        onSuccess: () => {
            isOpen.value = false;
        },
        onFinish: () => (isProcessing.value = false),
    });
};
</script>

<template>
    <Dialog v-model:open="isOpen">
        <DialogTrigger as-child>
            <Button
                :variant="triggerVariant || 'destructive'"
                :size="triggerSize || 'default'"
            >
                <slot name="trigger">{{ triggerText || 'Delete' }}</slot>
            </Button>
        </DialogTrigger>
        <DialogContent class="sm:max-w-106.25">
            <DialogHeader>
                <DialogTitle>{{
                    title || 'Are you absolutely sure?'
                }}</DialogTitle>
                <DialogDescription>
                    <slot name="description">
                        {{
                            description ||
                            'This action cannot be undone. This will permanently delete this record from your database.'
                        }}
                    </slot>
                </DialogDescription>
            </DialogHeader>
            <DialogFooter class="mt-4 gap-2 sm:gap-0">
                <Button
                    variant="outline"
                    @click="isOpen = false"
                    :disabled="isProcessing"
                >
                    Cancel
                </Button>
                <Button
                    variant="destructive"
                    @click="confirmDelete"
                    :disabled="isProcessing"
                >
                    Confirm Delete
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
