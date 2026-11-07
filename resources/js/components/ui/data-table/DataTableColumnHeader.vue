<script setup lang="ts">
import { ArrowDown, ArrowUp, ChevronsUpDown } from '@lucide/vue';
import type { Column } from '@tanstack/vue-table';
import type { HTMLAttributes } from 'vue';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';

const props = defineProps<{
    column: Pick<Column<unknown>, 'getCanSort' | 'getIsSorted' | 'toggleSorting'>;
    title: string;
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <Button
        v-if="column.getCanSort()"
        variant="ghost"
        size="sm"
        :class="cn('-ml-3 h-8', props.class)"
        @click="column.toggleSorting(column.getIsSorted() === 'asc')"
    >
        <span>{{ title }}</span>
        <ArrowDown v-if="column.getIsSorted() === 'desc'" />
        <ArrowUp v-else-if="column.getIsSorted() === 'asc'" />
        <ChevronsUpDown v-else class="text-muted-foreground" />
    </Button>
    <div v-else :class="props.class">{{ title }}</div>
</template>
