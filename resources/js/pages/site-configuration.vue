<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import type { Country } from '@/components/countryconfiguration/types';
import SiteTable from '@/components/siteconfiguration/site-table.vue';
import type { Site } from '@/components/siteconfiguration/types';
import { index } from '@/routes/site-configuration';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Site Configuration', href: index() }],
    },
});

const { sites = [], countries = [] } = defineProps<{
    sites?: Site[];
    countries?: Country[];
}>();
</script>

<template>
    <div class="m-6 space-y-6">
        <Head title="Site Configuration" />
        <Heading
            title="Site Configuration"
            description="Set up the sites for the study."
        />
        <SiteTable :sites="sites" :countries="countries" />
    </div>
</template>
