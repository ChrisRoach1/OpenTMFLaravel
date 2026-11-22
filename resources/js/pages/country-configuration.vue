<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import CountryTable from '@/components/countryconfiguration/country-table.vue';
import type { Country } from '@/components/countryconfiguration/types';
import { index } from '@/routes/country-configuration';

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Country Configuration',
                href: index(),
            },
        ],
    },
});

const { countries = [] } = defineProps<{
    countries?: Country[];
}>();
</script>

<template>
    <div class="m-6 space-y-6">
        <Head title="Country Configuration" />
        <Heading
            title="Country Configuration"
            description="Set up the countries for the study."
        />
        <CountryTable :countries="countries" />
    </div>
</template>
