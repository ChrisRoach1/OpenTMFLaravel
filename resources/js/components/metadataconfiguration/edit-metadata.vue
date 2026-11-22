<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    DialogClose,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import {
    TagsInput,
    TagsInputInput,
    TagsInputItem,
    TagsInputItemDelete,
    TagsInputItemText,
} from '@/components/ui/tags-input';
import { update } from '@/routes/document-metadata';

const { metadata, metadataTypes } = defineProps<{
    metadata: DocumentMetadata;
    metadataTypes: MetadataType[];
}>();
const emit = defineEmits<{ saved: [] }>();
const form = useForm({
    title: metadata.title,
    metadata_type_id: metadata.metadata_type_id,
    is_required: metadata.is_required,
    options: [...(metadata.options ?? [])],
});
const showsOptions = computed(
    () =>
        metadataTypes.find((type) => type.id === form.metadata_type_id)
            ?.display === 'Select',
);

function submit() {
    form.transform((data) => ({
        ...data,
        options: showsOptions.value ? data.options : null,
    })).submit(update(metadata.id), {
        preserveScroll: true,
        onSuccess: () => emit('saved'),
    });
}
</script>

<template>
    <form class="space-y-6" @submit.prevent="submit">
        <DialogHeader>
            <DialogTitle>Edit metadata</DialogTitle>
            <DialogDescription
                >Update the document metadata field.</DialogDescription
            >
        </DialogHeader>
        <div class="grid gap-2">
            <Label for="edit-metadata-title">Title</Label>
            <Input
                id="edit-metadata-title"
                v-model="form.title"
                required
                maxlength="255"
                :disabled="form.processing"
                :aria-invalid="!!form.errors.title"
                aria-describedby="edit-metadata-title-error"
            />
            <InputError
                id="edit-metadata-title-error"
                :message="form.errors.title"
            />
        </div>
        <div class="grid gap-2">
            <Label for="edit-metadata-type">Data Type</Label>
            <Select
                v-model="form.metadata_type_id"
                required
                :disabled="form.processing"
            >
                <SelectTrigger
                    id="edit-metadata-type"
                    class="w-full"
                    :aria-invalid="!!form.errors.metadata_type_id"
                    aria-describedby="edit-metadata-type-error"
                >
                    <SelectValue placeholder="Select a data type" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="type in metadataTypes"
                        :key="type.id"
                        :value="type.id"
                    >
                        {{ type.display }}
                    </SelectItem>
                </SelectContent>
            </Select>
            <InputError
                id="edit-metadata-type-error"
                :message="form.errors.metadata_type_id"
            />
        </div>
        <div v-if="showsOptions" class="grid gap-2">
            <Label for="edit-metadata-options">Options</Label>
            <TagsInput
                v-model="form.options"
                class="w-full"
                add-on-paste
                add-on-blur
                :disabled="form.processing"
            >
                <TagsInputItem
                    v-for="option in form.options"
                    :key="option"
                    :value="option"
                >
                    <TagsInputItemText />
                    <TagsInputItemDelete />
                </TagsInputItem>
                <TagsInputInput
                    id="edit-metadata-options"
                    placeholder="Type an option and press Enter"
                />
            </TagsInput>
            <InputError :message="form.errors.options" />
        </div>
        <div class="grid gap-2">
            <Label for="edit-metadata-required" class="flex items-center gap-3">
                <Checkbox
                    id="edit-metadata-required"
                    v-model="form.is_required"
                    :disabled="form.processing"
                />
                <span>Required</span>
            </Label>
            <InputError :message="form.errors.is_required" />
        </div>
        <DialogFooter class="gap-2">
            <DialogClose as-child>
                <Button
                    type="button"
                    variant="secondary"
                    :disabled="form.processing"
                    >Cancel</Button
                >
            </DialogClose>
            <Button type="submit" :disabled="form.processing">{{
                form.processing ? 'Saving…' : 'Save changes'
            }}</Button>
        </DialogFooter>
    </form>
</template>
