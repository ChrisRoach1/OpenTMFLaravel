<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogTrigger,
} from '@/components/ui/dialog';
import AddMetadata from '@/components/metadataconfiguration/add-metadata.vue';
import MetadataTable from '@/components/metadataconfiguration/metadata-table.vue';
import { ref } from 'vue';

const { metadataTypes = [], documentMetadata = [] } = defineProps<{
    metadataTypes?: MetadataType[];
    documentMetadata?: DocumentMetadata[];
}>();

const open = ref(false);

</script>

<template>
    <div class="space-y-6 m-6">
        <MetadataTable :documentMetadata="documentMetadata">
            <template #toolbar>
                <Dialog v-model:open="open">
                    <DialogTrigger as-child>
                        <Button variant="default" size="sm" class="h-8" data-test="add-metadata-button">Add new</Button>
                    </DialogTrigger>
                    <DialogContent>
                        <AddMetadata :metadataTypes="metadataTypes" @created="open = false" />
                    </DialogContent>
                </Dialog>
            </template>
        </MetadataTable>
    </div>
</template>
