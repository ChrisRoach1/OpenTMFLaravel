<script setup lang="ts">
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogTrigger,
} from '@/components/ui/dialog';
import AddMetadata from '@/components/metadataconfiguration/add-metadata.vue';
import MetadataTable from '@/components/metadataconfiguration/metadata-table.vue';
import { ref, watch } from 'vue';
import Heading from '@/components/Heading.vue';
import metadataConfiguration from '@/routes/metadata-configuration';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Metadata Configuration',
                href: metadataConfiguration.index(),
            },
        ],
    },
});

const { metadataTypes = [], documentMetadata = [], documentsAdded = false } = defineProps<{
    metadataTypes?: MetadataType[];
    documentMetadata?: DocumentMetadata[];
    documentsAdded: boolean;

}>();

const open = ref(false);

watch(() => documentsAdded, (locked) => {
    if (locked) {
        open.value = false;
    }
});
</script>

<template>
    <div class="space-y-6 m-6">
        <Heading
            title="Document Metadata"
            description="Set the custom metadata to be collected."
        />        
        <p v-if="documentsAdded" class="text-sm text-muted-foreground">
            Documents have already been indexed, no changes can be made.
        </p>
        <MetadataTable :documentMetadata="documentMetadata" :metadata-types="metadataTypes" :documents-added="documentsAdded">
            <template #toolbar>
                <Dialog v-model:open="open">
                    <DialogTrigger as-child>
                        <Button variant="default" size="sm" class="h-8" :disabled="documentsAdded" data-test="add-metadata-button">Add new</Button>
                    </DialogTrigger>
                    <DialogContent>
                        <AddMetadata :metadataTypes="metadataTypes" @created="open = false" />
                    </DialogContent>
                </Dialog>
            </template>
        </MetadataTable>
    </div>
</template>
