<script setup lang="ts">
import type { ColumnDef } from '@tanstack/vue-table';
import { h, ref } from 'vue';
import type { Country } from '@/components/countryconfiguration/types';
import { Button } from '@/components/ui/button';
import { DataTable, DataTableColumnHeader } from '@/components/ui/data-table';
import { Dialog, DialogContent, DialogTrigger } from '@/components/ui/dialog';
import DeleteSite from './delete-site.vue';
import SiteForm from './site-form.vue';
import type { Site } from './types';

const { sites = [], countries = [] } = defineProps<{
    sites?: Site[];
    countries?: Country[];
}>();
const formOpen = ref(false);
const deleteOpen = ref(false);
const selectedSite = ref<Site>();
const siteToDelete = ref<Site>();

function editSite(site: Site) {
    selectedSite.value = site;
    formOpen.value = true;
}

function confirmDelete(site: Site) {
    siteToDelete.value = site;
    deleteOpen.value = true;
}

function formatDate(value: string | null) {
    if (!value) return '—';
    const date = new Date(value);
    return Number.isNaN(date.getTime()) ? '—' : date.toLocaleString();
}

const columns: ColumnDef<Site>[] = [
    {
        accessorKey: 'name',
        meta: { title: 'Name' },
        header: ({ column }) =>
            h(DataTableColumnHeader, { column, title: 'Name' }),
        cell: ({ row }) =>
            h('div', { class: 'font-medium' }, row.original.name),
    },
    {
        accessorKey: 'primary_investigator',
        meta: { title: 'Primary investigator' },
        header: ({ column }) =>
            h(DataTableColumnHeader, { column, title: 'Primary investigator' }),
        cell: ({ row }) => row.original.primary_investigator || '—',
    },
    {
        id: 'country',
        accessorFn: (site) =>
            site.country?.name ??
            countries.find((country) => country.id === site.country_id)?.name ??
            '',
        meta: { title: 'Country' },
        header: ({ column }) =>
            h(DataTableColumnHeader, { column, title: 'Country' }),
        cell: ({ getValue }) => getValue<string>() || '—',
    },
    {
        accessorKey: 'created_at',
        meta: { title: 'Created at' },
        header: ({ column }) =>
            h(DataTableColumnHeader, { column, title: 'Created at' }),
        cell: ({ row }) => formatDate(row.original.created_at),
    },
    {
        accessorKey: 'updated_at',
        meta: { title: 'Updated at' },
        header: ({ column }) =>
            h(DataTableColumnHeader, { column, title: 'Updated at' }),
        cell: ({ row }) => formatDate(row.original.updated_at),
    },
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
                        'aria-label': `Edit ${row.original.name}`,
                        onClick: () => editSite(row.original),
                    },
                    () => 'Edit',
                ),
                h(
                    Button,
                    {
                        variant: 'ghost',
                        size: 'sm',
                        class: 'text-destructive hover:text-destructive',
                        'aria-label': `Delete ${row.original.name}`,
                        onClick: () => confirmDelete(row.original),
                    },
                    () => 'Delete',
                ),
            ]),
    },
];
</script>

<template>
    <div>
        <DataTable
            :columns="columns"
            :data="sites"
            search-placeholder="Search sites..."
            empty-message="No sites found. Add a site or adjust your search."
        >
            <template #toolbar>
                <Dialog v-model:open="formOpen">
                    <DialogTrigger as-child>
                        <Button
                            size="sm"
                            class="h-8"
                            @click="selectedSite = undefined"
                            >Add new</Button
                        >
                    </DialogTrigger>
                    <DialogContent>
                        <SiteForm
                            :key="selectedSite?.id ?? 'new'"
                            :site="selectedSite"
                            :countries="countries"
                            @saved="formOpen = false"
                        />
                    </DialogContent>
                </Dialog>
            </template>
        </DataTable>
        <Dialog v-model:open="deleteOpen">
            <DialogContent>
                <DeleteSite
                    v-if="siteToDelete"
                    :key="siteToDelete.id"
                    :site="siteToDelete"
                    @deleted="deleteOpen = false"
                />
            </DialogContent>
        </Dialog>
    </div>
</template>
