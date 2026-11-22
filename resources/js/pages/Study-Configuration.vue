<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import studyConfiguration from '@/routes/study-configuration';
import StudySetup from '@/components/studyconfiguration/study-setup.vue';

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
