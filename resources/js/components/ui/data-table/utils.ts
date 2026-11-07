import type { RowData, Updater } from '@tanstack/vue-table';
import type { Ref } from 'vue';

declare module '@tanstack/vue-table' {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    interface ColumnMeta<TData extends RowData, TValue> {
        title?: string;
    }
}

export function valueUpdater<T>(updaterOrValue: Updater<T>, ref: Ref<T>) {
    ref.value =
        typeof updaterOrValue === 'function'
            ? (updaterOrValue as (old: T) => T)(ref.value)
            : updaterOrValue;
}
