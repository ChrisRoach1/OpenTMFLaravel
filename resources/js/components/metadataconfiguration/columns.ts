import type { ColumnDef } from '@tanstack/vue-table';
import { h } from 'vue';
import { Badge } from '@/components/ui/badge';
import { DataTableColumnHeader } from '@/components/ui/data-table';

export const columns: ColumnDef<DocumentMetadata>[] = [
    {
        accessorKey: 'title',
        meta: { title: 'Title' },
        header: ({ column }) =>
            h(DataTableColumnHeader, { column, title: 'Title' }),
        cell: ({ row }) =>
            h('div', { class: 'font-medium' }, row.original.title),
    },
    {
        id: 'metadata_type',
        accessorFn: (row) => row.metadata_type?.display ?? '',
        meta: { title: 'Data Type' },
        header: ({ column }) =>
            h(DataTableColumnHeader, { column, title: 'Data Type' }),
    },
    {
        id: 'options',
        accessorFn: (row) => (row.options ?? []).join(', '),
        meta: { title: 'Options' },
        enableSorting: false,
        header: ({ column }) =>
            h(DataTableColumnHeader, { column, title: 'Options' }),
        cell: ({ row }) => {
            const options = row.original.options ?? [];

            if (!options.length) {
                return h('span', { class: 'text-muted-foreground' }, '—');
            }

            return h(
                'div',
                { class: 'flex flex-wrap gap-1' },
                options.map((option) =>
                    h(Badge, { variant: 'secondary' }, () => option),
                ),
            );
        },
    },
    {
        accessorKey: 'is_required',
        meta: { title: 'Required' },
        header: ({ column }) =>
            h(DataTableColumnHeader, { column, title: 'Required' }),
        cell: ({ row }) =>
            row.original.is_required
                ? h(Badge, () => 'Required')
                : h(Badge, { variant: 'outline' }, () => 'Optional'),
    },
];
