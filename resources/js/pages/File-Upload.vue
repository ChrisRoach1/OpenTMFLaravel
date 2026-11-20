<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, Ref, ref, watchEffect } from 'vue';
import Heading from '@/components/Heading.vue';
import PdfDropzone from '@/components/PdfDropzone.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import { cn } from '@/lib/utils';
import { fileUpload } from '@/routes';
import Select from '@/components/ui/select/Select.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectLabel from '@/components/ui/select/SelectLabel.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import PopoverTrigger from '@/components/ui/popover/PopoverTrigger.vue';
import Button from '@/components/ui/button/Button.vue';
import { CalendarIcon } from '@lucide/vue';
import PopoverContent from '@/components/ui/popover/PopoverContent.vue';
import Calendar from '@/components/ui/calendar/Calendar.vue';
import Popover from '@/components/ui/popover/Popover.vue';
import { DateFormatter, DateValue, getLocalTimeZone, today } from '@internationalized/date'
import Textarea from '@/components/ui/textarea/Textarea.vue';
import Checkbox from '@/components/ui/checkbox/Checkbox.vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'File Upload',
                href: fileUpload(),
            },
        ],
    },
});

const { documentMetadata = [] } = defineProps<{
    documentMetadata?: DocumentMetadata[];
}>();

const selectedFiles = ref<File[]>([]);

const hasSelectedFiles = computed(() => selectedFiles.value.length > 0);

const previewFile = computed(() =>
    selectedFiles.value.length === 1 ? selectedFiles.value[0] : null,
);

const previewUrl = ref<string | null>(null);

watchEffect((onCleanup) => {
    if (!previewFile.value) {
        previewUrl.value = null;

        return;
    }

    const objectUrl = URL.createObjectURL(
        previewFile.value.slice(0, previewFile.value.size, 'application/pdf'),
    );
    previewUrl.value = objectUrl;

    onCleanup(() => URL.revokeObjectURL(objectUrl));
});

const defaultPlaceholder = today(getLocalTimeZone())
const date = ref() as Ref<DateValue>
const df = new DateFormatter('en-US', {
  dateStyle: 'long',
})

</script>

<template>
    <Head title="File Upload" />

    <div
        :class="
            cn(
                'grid h-full flex-1 gap-0 rounded-xl p-4 transition-[grid-template-columns,gap] duration-500 ease-in-out lg:grid-cols-[1fr_0fr]',
                hasSelectedFiles && 'gap-6 lg:grid-cols-[1fr_1fr]',
            )
        "
    >
        <div
            :class="
                cn(
                    'mx-auto flex w-full max-w-3xl min-w-0 flex-col gap-4 self-center',
                    hasSelectedFiles && 'self-start lg:sticky lg:top-5',
                )
            "
        >
            <PdfDropzone v-model="selectedFiles" />

            <iframe
                v-if="previewUrl && previewFile"
                :key="previewUrl"
                :src="previewUrl"
                :title="`Preview of ${previewFile.name}`"
                class="h-[calc(100vh-20rem)] min-h-96 w-full rounded-xl border border-sidebar-border/70 bg-muted dark:border-sidebar-border"
            />
        </div>

        <section
            :inert="!hasSelectedFiles"
            :class="
                cn(
                    'flex min-w-0 flex-col overflow-hidden opacity-0 transition-opacity duration-500 max-lg:hidden',
                    hasSelectedFiles && 'opacity-100 delay-200 max-lg:flex',
                )
            "
        >
            <Heading
                title="Document details"
                description="Fill in the details for the selected documents."
            />

            <div
                class="flex min-h-0 flex-1 flex-col gap-5 overflow-auto rounded-xl border border-sidebar-border/70 p-5 dark:border-sidebar-border"
            >
                <template
                    v-for="property in documentMetadata"
                    :key="property.id"
                >
                    <div
                        v-if="property.metadata_type.value === 'Text'"
                        class="grid gap-2"
                    >
                        <Label
                            :for="`document-metadata-${property.id}`"
                            class="gap-0.5"
                        >
                            {{ property.title }}
                            <span
                                v-if="property.is_required"
                                class="text-destructive"
                                aria-hidden="true"
                                >*</span
                            >
                        </Label>
                        <Input
                            :id="`document-metadata-${property.id}`"
                            :name="`metadata[${property.id}]`"
                            type="text"
                            :placeholder="`Enter ${property.title.toLowerCase()}`"
                            :required="property.is_required"
                            autocomplete="off"
                            class="h-10 bg-background"
                        />
                    </div>

                    <div
                        v-if="property.metadata_type.value === 'TextArea'"
                        class="grid gap-2"
                    >
                        <Label
                            :for="`document-metadata-${property.id}`"
                            class="gap-0.5"
                        >
                            {{ property.title }}
                            <span
                                v-if="property.is_required"
                                class="text-destructive"
                                aria-hidden="true"
                                >*</span
                            >
                        </Label>
                        <Textarea
                            :id="`document-metadata-${property.id}`"
                            :name="`metadata[${property.id}]`"
                            type="text"
                            :placeholder="`Enter ${property.title.toLowerCase()}`"
                            :required="property.is_required"
                            autocomplete="off"
                            class="h-10 bg-background"
                        />
                    </div>

                    <Label
                        v-if="property.metadata_type.value === 'Checkbox'"
                        :for="`document-metadata-${property.id}`"
                        class="h-10 cursor-pointer gap-3 rounded-md border border-input bg-background px-3 font-normal shadow-xs transition-colors hover:bg-muted/40"
                    >
                        <Checkbox
                            :id="`document-metadata-${property.id}`"
                            :name="`metadata[${property.id}]`"
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

                    <div
                        v-if="property.metadata_type.value === 'Select'"
                        class="grid gap-2"
                    >
                        <Label
                            :for="`document-metadata-${property.id}`"
                            class="gap-0.5"
                        >
                            {{ property.title }}
                            <span
                                v-if="property.is_required"
                                class="text-destructive"
                                aria-hidden="true"
                                >*</span
                            >
                        </Label>
                        <Select>
                        <SelectTrigger class="w-80 h-10 bg-background":id="`document-metadata-${property.id}`" :name="`metadata[${property.id}]`">
                        <SelectValue placeholder="Select a fruit" :required="property.is_required" />
                        </SelectTrigger>
                        <SelectContent>
                        <SelectGroup>
                            <template v-for="option in property.options">
                                <SelectItem value={{ option }}>
                                    {{ option }}
                                </SelectItem>
                            </template>
                        </SelectGroup>
                        </SelectContent>
                    </Select>

                    </div>

                    <div
                        v-if="property.metadata_type.value === 'Date'"
                        class="grid gap-2"
                    >
                        <Label
                            :for="`document-metadata-${property.id}`"
                            class="gap-0.5"
                        >
                            {{ property.title }}
                            <span
                                v-if="property.is_required"
                                class="text-destructive"
                                aria-hidden="true"
                                >*</span
                            >
                        </Label>
                        <Popover v-slot="{ close }">
                        <PopoverTrigger as-child>
                        <Button
                            variant="outline"
                            :class="cn('w-60 justify-start text-left font-normal', !date && 'text-muted-foreground')"
                        >
                            <CalendarIcon />
                            {{ date ? df.format(date.toDate(getLocalTimeZone())) : "Pick a date" }}
                        </Button>
                        </PopoverTrigger>
                        <PopoverContent class="w-auto p-0" align="start">
                        <Calendar
                            v-model="date"
                            :default-placeholder="defaultPlaceholder"
                            layout="month-and-year"
                            initial-focus
                            @update:model-value="close"
                        />
                        </PopoverContent>
                    </Popover>

                    </div>

                </template>
            </div>
        </section>
    </div>
</template>
