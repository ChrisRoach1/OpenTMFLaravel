<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';
import { studyConfiguration } from '@/routes';
import MetadataSetup from '@/components/metadata-setup.vue';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Study Configuration',
                href: studyConfiguration(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Study Configuration" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
        <MetadataSetup></MetadataSetup>
    </div>
</template>
