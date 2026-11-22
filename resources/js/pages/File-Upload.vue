<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed, ref, watch, watchEffect } from 'vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import MetadataField from '@/components/MetadataField.vue';
import PdfDropzone from '@/components/PdfDropzone.vue';
import { cn } from '@/lib/utils';
import { fileUpload } from '@/routes';
import Button from '@/components/ui/button/Button.vue';
import TmfLevelSelector from '@/components/tmf-level-selector.vue';
import type { Country } from '@/components/countryconfiguration/types';
import type { Site } from '@/components/siteconfiguration/types';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

const { documentMetadata = [], countries = [], sites = [] } = defineProps<{
    documentMetadata?: DocumentMetadata[];
    countries: Country[];
    sites: Site[];
}>();

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

const form = useForm({
    tmfLevel: '',
    site: '',
    country: '',
    metadata: documentMetadata.map(
        (property): MetadataFormItem => ({
            metadataId: property.id,
            metadataName: property.title,
            inputType: property.metadata_type.value,
            value: property.metadata_type.value === 'Checkbox' ? false : '',
        }),
    ),
});

watch(() => form.tmfLevel, () => {
    form.site = '';
    form.country = '';
    form.clearErrors('tmfLevel', 'site', 'country');
});

/**
 * Flag every required field that has no value. A required checkbox must be checked.
 */
const validateRequiredFields = (): boolean => {
    form.clearErrors();

    if (form.tmfLevel === '') {
        form.setError('tmfLevel', 'TMF level is required.');
    }

    if (form.tmfLevel === 'Site' && form.site === '') {
        form.setError('site', 'Site is required.');
    }

    if (form.tmfLevel === 'Country' && form.country === '') {
        form.setError('country', 'Country is required.');
    }

    form.metadata.forEach((item, index) => {
        const property = documentMetadata[index];

        if (property.is_required && (item.value === '' || item.value === false)) {
            form.setError(
                `metadata.${index}.value`,
                `${property.title} is required.`,
            );
        }
    });

    return !form.hasErrors;
};

const submit = () => {
    if (!validateRequiredFields()) {
        return;
    }

    console.log('Form submitted:', form.data());
};

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

</script>

<template>
    <div
        :class="
            cn(
                'grid h-full flex-1 gap-0 rounded-xl p-4 transition-[grid-template-columns,gap] duration-500 ease-in-out lg:grid-cols-[1fr_0fr]',
                hasSelectedFiles && 'gap-6 lg:grid-cols-[1fr_1fr]',
            )
        "
    >
        <Head title="File Upload" />
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

            <form
                novalidate
                class="flex min-h-0 flex-1 flex-col gap-5 overflow-auto rounded-xl border border-sidebar-border/70 p-5 dark:border-sidebar-border"
                @submit.prevent="submit"
            >
                <div class="grid gap-2">
                    <TmfLevelSelector
                        v-model="form.tmfLevel"
                        :isForm="true"
                        @update:model-value="form.clearErrors('tmfLevel')"
                    />
                    <InputError :message="form.errors.tmfLevel" />
                </div>

                <div v-if="form.tmfLevel === 'Country'" class="grid gap-2">
                    <Label for="upload-country" class="gap-0.5">
                        Country <span class="text-destructive" aria-hidden="true">*</span>
                    </Label>
                    <Select v-model="form.country" name="country" required @update:model-value="form.clearErrors('country')">
                        <SelectTrigger id="upload-country" class="h-10 w-80 max-w-full bg-background" :aria-invalid="!!form.errors.country" aria-describedby="upload-country-error">
                            <SelectValue placeholder="Select a country" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="country in countries" :key="country.id" :value="String(country.id)">
                                {{ country.name }} ({{ country.code }})
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="!countries.length" class="text-sm text-muted-foreground">No countries have been configured.</p>
                    <InputError id="upload-country-error" :message="form.errors.country" />
                </div>

                <div v-if="form.tmfLevel === 'Site'" class="grid gap-2">
                    <Label for="upload-site" class="gap-0.5">
                        Site <span class="text-destructive" aria-hidden="true">*</span>
                    </Label>
                    <Select v-model="form.site" name="site" required @update:model-value="form.clearErrors('site')">
                        <SelectTrigger id="upload-site" class="h-10 w-80 max-w-full bg-background" :aria-invalid="!!form.errors.site" aria-describedby="upload-site-error">
                            <SelectValue placeholder="Select a site" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="site in sites" :key="site.id" :value="String(site.id)">
                                {{ site.name }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="!sites.length" class="text-sm text-muted-foreground">No sites have been configured.</p>
                    <InputError id="upload-site-error" :message="form.errors.site" />
                </div>

                <MetadataField
                    v-for="(item, index) in form.metadata"
                    :key="item.metadataId"
                    v-model="item.value"
                    :property="documentMetadata[index]"
                    :error="form.errors[`metadata.${index}.value`]"
                    @update:model-value="
                        form.clearErrors(`metadata.${index}.value`)
                    "
                />

                <Button type="submit" :disabled="form.processing">
                    {{ selectedFiles.length > 1 ? 'Index files' : 'Index file' }}
                </Button>
            </form>
        </section>
    </div>
</template>
