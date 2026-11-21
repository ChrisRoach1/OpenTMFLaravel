<script setup lang="ts">
import { computed } from 'vue';
import DatePicker from '@/components/DatePicker.vue';
import InputError from '@/components/InputError.vue';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Textarea from '@/components/ui/textarea/Textarea.vue';

const { property, error } = defineProps<{
    property: DocumentMetadata;
    error?: string;
}>();

const model = defineModel<MetadataValue>({ required: true });

const fieldId = computed(() => `document-metadata-${property.id}`);
const fieldType = computed(() => property.metadata_type.value);
const placeholder = computed(() => `Enter ${property.title.toLowerCase()}`);
const errorId = computed(() => `${fieldId.value}-error`);
const invalidAttributes = computed(() =>
    error ? { 'aria-invalid': true, 'aria-describedby': errorId.value } : {},
);

const textValue = computed({
    get: () => (typeof model.value === 'string' ? model.value : ''),
    set: (value: unknown) => {
        model.value = value == null ? '' : String(value);
    },
});

const isChecked = computed({
    get: () => model.value === true,
    set: (value: boolean | 'indeterminate' | null) => {
        model.value = value === true;
    },
});
</script>

<template>
    <div class="grid gap-2">
        <Label
            v-if="fieldType === 'Checkbox'"
            :for="fieldId"
            class="h-10 cursor-pointer gap-3 rounded-md border border-input bg-background px-3 font-normal shadow-xs transition-colors hover:bg-muted/40"
        >
            <Checkbox
                :id="fieldId"
                v-model="isChecked"
                v-bind="invalidAttributes"
                :required="property.is_required"
            />
            <span class="inline-flex items-center gap-0.5">
                {{ property.title }}
                <span
                    v-if="property.is_required"
                    class="text-destructive"
                    aria-hidden="true"
                    >*</span
                >
            </span>
        </Label>

        <template v-else>
            <Label :for="fieldId" class="gap-0.5">
                {{ property.title }}
                <span
                    v-if="property.is_required"
                    class="text-destructive"
                    aria-hidden="true"
                    >*</span
                >
            </Label>

            <Input
                v-if="fieldType === 'Text'"
                :id="fieldId"
                v-model="textValue"
                v-bind="invalidAttributes"
                type="text"
                :placeholder="placeholder"
                :required="property.is_required"
                autocomplete="off"
                class="h-10 bg-background"
            />

            <Textarea
                v-else-if="fieldType === 'TextArea'"
                :id="fieldId"
                v-model="textValue"
                v-bind="invalidAttributes"
                :placeholder="placeholder"
                :required="property.is_required"
                autocomplete="off"
                class="h-10 bg-background"
            />

            <Select
                v-else-if="fieldType === 'Select'"
                v-model="textValue"
                :required="property.is_required"
            >
                <SelectTrigger
                    :id="fieldId"
                    v-bind="invalidAttributes"
                    class="h-10 w-80 bg-background"
                >
                    <SelectValue
                        :placeholder="`Select ${property.title.toLowerCase()}`"
                    />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <SelectItem
                            v-for="option in property.options"
                            :key="option"
                            :value="option"
                        >
                            {{ option }}
                        </SelectItem>
                    </SelectGroup>
                </SelectContent>
            </Select>

            <DatePicker
                v-else-if="fieldType === 'Date'"
                :id="fieldId"
                v-model="textValue"
                v-bind="invalidAttributes"
            />
        </template>

        <InputError :id="errorId" :message="error" />
    </div>
</template>
