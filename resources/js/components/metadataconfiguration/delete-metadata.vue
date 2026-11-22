<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import {
    DialogClose,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { deleteMethod } from '@/routes/document-metadata';

const { metadata } = defineProps<{ metadata: DocumentMetadata }>();
const emit = defineEmits<{ deleted: [] }>();
const form = useForm({});

function submit() {
    form.submit(deleteMethod(metadata.id), {
        preserveScroll: true,
        onSuccess: () => emit('deleted'),
    });
}
</script>

<template>
    <form class="space-y-6" @submit.prevent="submit">
        <DialogHeader>
            <DialogTitle>Delete metadata</DialogTitle>
            <DialogDescription
                >Delete {{ metadata.title }}? This action cannot be
                undone.</DialogDescription
            >
        </DialogHeader>
        <InputError
            v-for="(error, key) in form.errors"
            :key="key"
            :message="error"
        />
        <DialogFooter class="gap-2">
            <DialogClose as-child>
                <Button
                    type="button"
                    variant="secondary"
                    :disabled="form.processing"
                    >Cancel</Button
                >
            </DialogClose>
            <Button
                type="submit"
                variant="destructive"
                :disabled="form.processing"
            >
                {{ form.processing ? 'Deleting…' : 'Delete metadata' }}
            </Button>
        </DialogFooter>
    </form>
</template>
