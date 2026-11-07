<script setup lang="ts">
import { FileText, FileUp, X } from '@lucide/vue';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';

type Props = {
    multiple?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
    multiple: true,
});

const files = defineModel<File[]>({ default: () => [] });

const fileInput = ref<HTMLInputElement | null>(null);
const isDraggingOver = ref(false);
const dragDepth = ref(0);
const rejectionMessage = ref<string | undefined>();

function isPdf(file: File): boolean {
    return (
        file.type === 'application/pdf' ||
        file.name.toLowerCase().endsWith('.pdf')
    );
}

function formatFileSize(bytes: number): string {
    if (bytes < 1024 * 1024) {
        return `${(bytes / 1024).toFixed(1)} KB`;
    }

    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
}

function addFiles(incomingFiles: File[]): void {
    const pdfFiles = incomingFiles.filter(isPdf);
    const rejectedCount = incomingFiles.length - pdfFiles.length;

    rejectionMessage.value =
        rejectedCount > 0
            ? `${rejectedCount} file${rejectedCount === 1 ? ' was' : 's were'} skipped. Only PDF files are accepted.`
            : undefined;

    if (pdfFiles.length === 0) {
        return;
    }

    files.value = props.multiple
        ? [...files.value, ...pdfFiles]
        : [pdfFiles[0]];
}

function removeFile(index: number): void {
    files.value = files.value.filter((_, fileIndex) => fileIndex !== index);
}

function openFileBrowser(): void {
    fileInput.value?.click();
}

function onDragEnter(): void {
    dragDepth.value++;
    isDraggingOver.value = true;
}

function onDragLeave(): void {
    dragDepth.value--;

    if (dragDepth.value === 0) {
        isDraggingOver.value = false;
    }
}

function onDrop(event: DragEvent): void {
    dragDepth.value = 0;
    isDraggingOver.value = false;

    addFiles(Array.from(event.dataTransfer?.files ?? []));
}

function onFileInputChange(event: Event): void {
    const input = event.target as HTMLInputElement;

    addFiles(Array.from(input.files ?? []));
    input.value = '';
}
</script>

<template>
    <div class="flex w-full flex-col gap-4">
        <div
            role="button"
            tabindex="0"
            :aria-label="multiple ? 'Upload PDF files' : 'Upload a PDF file'"
            :class="
                cn(
                    'flex min-h-96 cursor-pointer flex-col items-center justify-center gap-4 rounded-xl border-2 border-dashed border-sidebar-border/70 p-10 text-center transition-all duration-500 outline-none hover:border-primary/50 hover:bg-accent/50 focus-visible:border-ring focus-visible:ring-[3px] focus-visible:ring-ring/50 dark:border-sidebar-border',
                    files.length > 0 && 'min-h-48',
                    isDraggingOver && 'border-primary bg-accent',
                )
            "
            @click="openFileBrowser"
            @keydown.enter.prevent="openFileBrowser"
            @keydown.space.prevent="openFileBrowser"
            @dragenter.prevent="onDragEnter"
            @dragover.prevent
            @dragleave.prevent="onDragLeave"
            @drop.prevent="onDrop"
        >
            <div
                class="flex size-16 items-center justify-center rounded-full bg-muted text-muted-foreground"
            >
                <FileUp class="size-8" />
            </div>

            <div class="flex flex-col gap-1">
                <p class="text-lg font-medium">
                    {{
                        isDraggingOver
                            ? 'Drop to add'
                            : 'Drag and drop files here'
                    }}
                </p>
                <p class="text-sm text-muted-foreground">
                    or click to browse. PDF files only.
                </p>
            </div>

            <input
                ref="fileInput"
                type="file"
                accept="application/pdf,.pdf"
                class="hidden"
                :multiple="multiple"
                @change="onFileInputChange"
            />
        </div>

        <InputError :message="rejectionMessage" />

        <ul v-if="files.length > 0" class="flex flex-col gap-2">
            <li
                v-for="(file, index) in files"
                :key="`${file.name}-${file.lastModified}-${index}`"
                class="flex items-center gap-3 rounded-lg border border-sidebar-border/70 px-4 py-3 dark:border-sidebar-border"
            >
                <FileText class="size-5 shrink-0 text-muted-foreground" />
                <div class="flex min-w-0 flex-1 flex-col">
                    <span class="truncate text-sm font-medium">
                        {{ file.name }}
                    </span>
                    <span class="text-xs text-muted-foreground">
                        {{ formatFileSize(file.size) }}
                    </span>
                </div>
                <Button
                    variant="ghost"
                    size="icon-sm"
                    :aria-label="`Remove ${file.name}`"
                    @click="removeFile(index)"
                >
                    <X />
                </Button>
            </li>
        </ul>
    </div>
</template>
