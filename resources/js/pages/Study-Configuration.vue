<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import MetadataSetup from '@/components/metadata-setup.vue';
import StudySetup from '@/components/study-setup.vue';
import studyConfiguration from '@/routes/study-configuration';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Study Configuration',
                href: studyConfiguration.index(),
            },
        ],
    },
});

const { study } = defineProps<{
    study: Study;
}>();

</script>

<template>
    <Head title="Study Configuration" />

    <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl m-6">
        <Heading
            title="Study details"
            description="Set the protocol and key dates for this study."
        />

        <StudySetup :study="study" />
    </div>
</template>
