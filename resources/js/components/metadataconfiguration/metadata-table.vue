<script setup lang="ts">
import { DataTable } from '@/components/ui/data-table';
import { columns } from './columns';

const { documentMetadata = [] } = defineProps<{
    documentMetadata?: DocumentMetadata[];
}>();
</script>

<template>
    <DataTable
        :columns="columns"
        :data="documentMetadata"
        search-placeholder="Search metadata..."
        empty-message="No metadata has been configured yet."
    >
        <template #toolbar>
            <slot name="toolbar" />
        </template>
    </DataTable>
</template>
