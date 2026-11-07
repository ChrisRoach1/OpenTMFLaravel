<script setup lang="ts" generic="TData">
import {
    ChevronLeft,
    ChevronRight,
    ChevronsLeft,
    ChevronsRight,
} from '@lucide/vue';
import type { Table } from '@tanstack/vue-table';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

const { table, pageSizes = [10, 20, 30, 50] } = defineProps<{
    table: Table<TData>;
    pageSizes?: number[];
}>();
</script>

<template>
    <div class="flex items-center justify-between px-2">
        <div class="flex-1 text-sm text-muted-foreground">
            {{ table.getFilteredRowModel().rows.length }} row(s)
        </div>
        <div class="flex items-center gap-6 lg:gap-8">
            <div class="flex items-center gap-2">
                <p class="text-sm font-medium">Rows per page</p>
                <Select
                    :model-value="table.getState().pagination.pageSize"
                    @update:model-value="(value) => table.setPageSize(Number(value))"
                >
                    <SelectTrigger size="sm" class="w-[70px]">
                        <SelectValue />
                    </SelectTrigger>
                    <SelectContent side="top">
                        <SelectItem
                            v-for="pageSize in pageSizes"
                            :key="pageSize"
                            :value="pageSize"
                        >
                            {{ pageSize }}
                        </SelectItem>
                    </SelectContent>
                </Select>
            </div>
            <div class="flex w-[100px] items-center justify-center text-sm font-medium">
                Page {{ table.getState().pagination.pageIndex + 1 }} of
                {{ Math.max(table.getPageCount(), 1) }}
            </div>
            <div class="flex items-center gap-2">
                <Button
                    variant="outline"
                    size="icon-sm"
                    class="hidden lg:flex"
                    :disabled="!table.getCanPreviousPage()"
                    @click="table.setPageIndex(0)"
                >
                    <span class="sr-only">Go to first page</span>
                    <ChevronsLeft />
                </Button>
                <Button
                    variant="outline"
                    size="icon-sm"
                    :disabled="!table.getCanPreviousPage()"
                    @click="table.previousPage()"
                >
                    <span class="sr-only">Go to previous page</span>
                    <ChevronLeft />
                </Button>
                <Button
                    variant="outline"
                    size="icon-sm"
                    :disabled="!table.getCanNextPage()"
                    @click="table.nextPage()"
                >
                    <span class="sr-only">Go to next page</span>
                    <ChevronRight />
                </Button>
                <Button
                    variant="outline"
                    size="icon-sm"
                    class="hidden lg:flex"
                    :disabled="!table.getCanNextPage()"
                    @click="table.setPageIndex(table.getPageCount() - 1)"
                >
                    <span class="sr-only">Go to last page</span>
                    <ChevronsRight />
                </Button>
            </div>
        </div>
    </div>
</template>
