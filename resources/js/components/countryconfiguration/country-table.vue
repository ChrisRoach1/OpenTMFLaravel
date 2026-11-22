<script setup lang="ts">
import type { ColumnDef } from '@tanstack/vue-table';
import { h, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { DataTable, DataTableColumnHeader } from '@/components/ui/data-table';
import { Dialog, DialogContent, DialogTrigger } from '@/components/ui/dialog';
import CountryForm from './country-form.vue';
import DeleteCountry from './delete-country.vue';
import type { Country } from './types';

const { countries = [] } = defineProps<{ countries?: Country[] }>();
const formOpen = ref(false);
const deleteOpen = ref(false);
const selectedCountry = ref<Country>();
const countryToDelete = ref<Country>();

function addCountry() {
    selectedCountry.value = undefined;
}

function editCountry(country: Country) {
    selectedCountry.value = country;
    formOpen.value = true;
}

function confirmDelete(country: Country) {
    countryToDelete.value = country;
    deleteOpen.value = true;
}

function formatDate(value: string | null) {
    if (!value) {
        return '—';
    }

    const date = new Date(value);
    return Number.isNaN(date.getTime()) ? '—' : date.toLocaleString();
}

const columns: ColumnDef<Country>[] = [
    {
        accessorKey: 'name',
        meta: { title: 'Name' },
        header: ({ column }) =>
            h(DataTableColumnHeader, { column, title: 'Name' }),
        cell: ({ row }) =>
            h('div', { class: 'font-medium' }, row.original.name),
    },
    {
        accessorKey: 'code',
        meta: { title: 'Code' },
        header: ({ column }) =>
            h(DataTableColumnHeader, { column, title: 'Code' }),
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
                        onClick: () => editCountry(row.original),
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
            :data="countries"
            search-placeholder="Search countries..."
            empty-message="No countries found. Add a country or adjust your search."
        >
            <template #toolbar>
                <Dialog v-model:open="formOpen">
                    <DialogTrigger as-child>
                        <Button size="sm" class="h-8" @click="addCountry"
                            >Add new</Button
                        >
                    </DialogTrigger>
                    <DialogContent>
                        <CountryForm
                            :key="selectedCountry?.id ?? 'new'"
                            :country="selectedCountry"
                            @saved="formOpen = false"
                        />
                    </DialogContent>
                </Dialog>
            </template>
        </DataTable>
        <Dialog v-model:open="deleteOpen">
            <DialogContent>
                <DeleteCountry
                    v-if="countryToDelete"
                    :key="countryToDelete.id"
                    :country="countryToDelete"
                    @deleted="deleteOpen = false"
                />
            </DialogContent>
        </Dialog>
    </div>
</template>
