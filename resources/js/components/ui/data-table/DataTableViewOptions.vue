<script setup lang="ts" generic="TData">
import { Settings2 } from '@lucide/vue';
import type { Table } from '@tanstack/vue-table';
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuCheckboxItem,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

const { table } = defineProps<{
    table: Table<TData>;
}>();

const columns = computed(() =>
    table
        .getAllColumns()
        .filter((column) => column.accessorFn !== undefined && column.getCanHide()),
);
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="outline" size="sm" class="h-8">
                <Settings2 />
                View
            </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end" class="w-[160px]">
            <DropdownMenuLabel>Toggle columns</DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuCheckboxItem
                v-for="column in columns"
                :key="column.id"
                :model-value="column.getIsVisible()"
                @update:model-value="(value) => column.toggleVisibility(!!value)"
                @select.prevent
            >
                {{ column.columnDef.meta?.title ?? column.id }}
            </DropdownMenuCheckboxItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>
