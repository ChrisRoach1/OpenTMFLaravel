<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, ref, watchEffect } from 'vue';
import Heading from '@/components/Heading.vue';
import PdfDropzone from '@/components/PdfDropzone.vue';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { cn } from '@/lib/utils';
import { fileUpload } from '@/routes';

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
                class="relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border"
            >
                <PlaceholderPattern />
            </div>
        </section>
    </div>
</template>
