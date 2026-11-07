<script setup lang="ts" generic="TData, TValue">
import type {
    ColumnDef,
    SortingState,
    VisibilityState,
} from '@tanstack/vue-table';
import {
    FlexRender,
    getCoreRowModel,
    getFilteredRowModel,
    getPaginationRowModel,
    getSortedRowModel,
    useVueTable,
} from '@tanstack/vue-table';
import { ref } from 'vue';
import { Input } from '@/components/ui/input';
import {
    Table,
    TableBody,
    TableCell,
    TableEmpty,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import DataTablePagination from './DataTablePagination.vue';
import DataTableViewOptions from './DataTableViewOptions.vue';
import { valueUpdater } from './utils';

const props = withDefaults(
    defineProps<{
        columns: ColumnDef<TData, TValue>[];
        data: TData[];
        searchable?: boolean;
        searchPlaceholder?: string;
        emptyMessage?: string;
        pageSize?: number;
    }>(),
    {
        searchable: true,
        searchPlaceholder: 'Search...',
        emptyMessage: 'No results.',
        pageSize: 10,
    },
);

const sorting = ref<SortingState>([]);
const globalFilter = ref('');
const columnVisibility = ref<VisibilityState>({});

const table = useVueTable({
    get data() {
        return props.data;
    },
    get columns() {
        return props.columns;
    },
    initialState: {
        pagination: { pageSize: props.pageSize },
    },
    state: {
        get sorting() {
            return sorting.value;
        },
        get globalFilter() {
            return globalFilter.value;
        },
        get columnVisibility() {
            return columnVisibility.value;
        },
    },
    onSortingChange: (updaterOrValue) => valueUpdater(updaterOrValue, sorting),
    onGlobalFilterChange: (updaterOrValue) => valueUpdater(updaterOrValue, globalFilter),
    onColumnVisibilityChange: (updaterOrValue) => valueUpdater(updaterOrValue, columnVisibility),
    getCoreRowModel: getCoreRowModel(),
    getSortedRowModel: getSortedRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
});
</script>

<template>
    <div class="space-y-4">
        <div class="flex items-center gap-2">
            <Input
                v-if="searchable"
                :model-value="globalFilter"
                :placeholder="searchPlaceholder"
                class="h-8 max-w-sm"
                @update:model-value="(value) => table.setGlobalFilter(String(value))"
            />
            <div class="ml-auto flex items-center gap-2">
                <slot name="toolbar" :table="table" />
                <DataTableViewOptions :table="table" />
            </div>
        </div>

        <div class="overflow-hidden rounded-md border">
            <Table>
                <TableHeader>
                    <TableRow v-for="headerGroup in table.getHeaderGroups()" :key="headerGroup.id">
                        <TableHead v-for="header in headerGroup.headers" :key="header.id">
                            <FlexRender
                                v-if="!header.isPlaceholder"
                                :render="header.column.columnDef.header"
                                :props="header.getContext()"
                            />
                        </TableHead>
                    </TableRow>
                </TableHeader>
                <TableBody>
                    <template v-if="table.getRowModel().rows.length">
                        <TableRow
                            v-for="row in table.getRowModel().rows"
                            :key="row.id"
                            :data-state="row.getIsSelected() ? 'selected' : undefined"
                        >
                            <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                <FlexRender :render="cell.column.columnDef.cell" :props="cell.getContext()" />
                            </TableCell>
                        </TableRow>
                    </template>
                    <TableEmpty v-else :colspan="table.getVisibleLeafColumns().length">
                        {{ emptyMessage }}
                    </TableEmpty>
                </TableBody>
            </Table>
        </div>

        <DataTablePagination :table="table" />
    </div>
</template>
