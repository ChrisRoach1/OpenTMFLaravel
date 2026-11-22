<script setup lang="ts">
import { DataTable } from '@/components/ui/data-table';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent } from '@/components/ui/dialog';
import { computed, h, ref, watch } from 'vue';
import { columns } from './columns';
import EditMetadata from './edit-metadata.vue';
import DeleteMetadata from './delete-metadata.vue';

const { documentMetadata = [], metadataTypes = [], documentsAdded = false } = defineProps<{
    documentMetadata?: DocumentMetadata[];
    metadataTypes?: MetadataType[];
    documentsAdded?: boolean;
}>();

const editOpen = ref(false);
const deleteOpen = ref(false);
const selectedMetadata = ref<DocumentMetadata>();
const metadataToDelete = ref<DocumentMetadata>();

watch(() => documentsAdded, (locked) => {
    if (locked) {
        editOpen.value = false;
        deleteOpen.value = false;
    }
});

const tableColumns = computed<typeof columns>(() => [
    ...columns,
    {
        id: 'actions',
        header: 'Actions',
        enableHiding: false,
        enableSorting: false,
        enableGlobalFilter: false,
        cell: ({ row }) =>
            h('div', { class: 'flex items-center gap-2' }, [
                h(
                    Button,
                    {
                        variant: 'outline',
                        size: 'sm',
                        disabled: documentsAdded,
                        'aria-label': `Edit ${row.original.title}`,
                        onClick: () => {
                            selectedMetadata.value = row.original;
                            editOpen.value = true;
                        },
                    },
                    () => 'Edit',
                ),
                h(
                    Button,
                    {
                        variant: 'ghost',
                        size: 'sm',
                        disabled: documentsAdded,
                        class: 'text-destructive hover:text-destructive',
                        'aria-label': `Delete ${row.original.title}`,
                        onClick: () => {
                            metadataToDelete.value = row.original;
                            deleteOpen.value = true;
                        },
                    },
                    () => 'Delete',
                ),
            ]),
    },
]);
</script>

<template>
    <div>
        <DataTable
            :columns="tableColumns"
            :data="documentMetadata"
            search-placeholder="Search metadata..."
            empty-message="No metadata has been configured yet."
        >
            <template #toolbar>
                <slot name="toolbar" />
            </template>
        </DataTable>
        <Dialog v-model:open="editOpen">
            <DialogContent>
                <EditMetadata
                    v-if="selectedMetadata"
                    :key="selectedMetadata.id"
                    :metadata="selectedMetadata"
                    :metadata-types="metadataTypes"
                    @saved="editOpen = false"
                />
            </DialogContent>
        </Dialog>
        <Dialog v-model:open="deleteOpen">
            <DialogContent>
                <DeleteMetadata
                    v-if="metadataToDelete"
                    :key="metadataToDelete.id"
                    :metadata="metadataToDelete"
                    @deleted="deleteOpen = false"
                />
            </DialogContent>
        </Dialog>
    </div>
</template>
