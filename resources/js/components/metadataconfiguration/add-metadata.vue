<script setup lang="ts">
import { Form } from '@inertiajs/vue3';
import DocumentMetadataController from '@/actions/App/Http/Controllers/DocumentMetadataController';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
  DialogClose,
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
import { computed, ref, watch } from 'vue';

const { metadataTypes = [] } = defineProps<{
  metadataTypes?: MetadataType[];
}>();

const emit = defineEmits<{
  created: [];
}>();

const transformMetadata = (data: Record<string, unknown>) => ({
  ...data,
  is_required: Boolean(data.is_required),
});

const selectedTypeId = ref<number | undefined>();

const selectedType = computed(() => metadataTypes.find(x => x.id === selectedTypeId.value));

const showsOptions = computed(() => selectedType.value?.display === 'Select');

const options = ref<string[]>([]);

</script>

<template>
  <div class="space-y-6">
    <div>
      <Form v-bind="DocumentMetadataController.create.form()" :transform="transformMetadata" reset-on-success :options="{
        preserveScroll: true,
      }" class="space-y-6" @success="options = []; emit('created');"
        v-slot="{ processing, reset, clearErrors, errors }">
        <DialogHeader class="space-y-3">
          <DialogTitle>Add Metadata</DialogTitle>
        </DialogHeader>

        <div class="grid gap-2">
          <Label for="title">Title</Label>
          <Input id="title" class="mt-1 block w-full" name="title" required maxlength="255" placeholder="Title" />
          <InputError class="mt-2" :message="errors.title" />
        </div>

        <div class="grid gap-2">
          <Label for="metadata_type_id">Data Type</Label>
          <Select v-model="selectedTypeId" name="metadata_type_id" required>
            <SelectTrigger id="metadata_type_id" class="mt-1 w-full">
              <SelectValue placeholder="Select a data type" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem v-for="metadataType in metadataTypes" :key="metadataType.id" :value="metadataType.id">
                {{ metadataType.display }}
              </SelectItem>
            </SelectContent>
          </Select>
          <InputError class="mt-2" :message="errors.metadata_type_id" />
        </div>

        <div class="grid gap-2" v-if="showsOptions">
          <Label for="options">Options</Label>
          <TagsInput v-model="options" name="options" class="mt-1 w-full" add-on-paste add-on-blur>
            <TagsInputItem v-for="option in options" :key="option" :value="option">
              <TagsInputItemText />
              <TagsInputItemDelete />
            </TagsInputItem>
            <TagsInputInput id="options" placeholder="Type an option and press Enter" />
          </TagsInput>
          <InputError class="mt-2" :message="errors.options" />
        </div>

        <div class="grid gap-2">
          <Label for="is_required" class="flex items-center gap-3">
            <Checkbox id="is_required" name="is_required" value="1" />
            <span>Required</span>
          </Label>
          <InputError class="mt-2" :message="errors.is_required" />
        </div>

        <DialogFooter class="gap-2">
          <DialogClose as-child>
            <Button type="button" variant="secondary" @click="
              () => {
                clearErrors();
                reset();
                options = [];
              }
            ">
              Cancel
            </Button>
          </DialogClose>

          <Button type="submit" variant="default" :disabled="processing" data-test="submit-metadata-create-button">
            Create
          </Button>
        </DialogFooter>
      </Form>
    </div>
  </div>
</template>
