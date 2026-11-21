<script setup lang="ts">
import {
    DateFormatter,
    getLocalTimeZone,
    parseDate,
    today,
} from '@internationalized/date';
import type { DateValue } from '@internationalized/date';
import { CalendarIcon } from '@lucide/vue';
import { computed } from 'vue';
import Button from '@/components/ui/button/Button.vue';
import Calendar from '@/components/ui/calendar/Calendar.vue';
import Popover from '@/components/ui/popover/Popover.vue';
import PopoverContent from '@/components/ui/popover/PopoverContent.vue';
import PopoverTrigger from '@/components/ui/popover/PopoverTrigger.vue';
import { cn } from '@/lib/utils';

defineOptions({
    inheritAttrs: false,
});

const { minValue } = defineProps<{
    minValue?: string;
}>();

/**
 * The selected date as an ISO string (YYYY-MM-DD), or an empty string when unset.
 */
const model = defineModel<string>({ default: '' });

const toDateValue = (value?: string): DateValue | undefined =>
    value ? parseDate(value) : undefined;

const date = computed({
    get: () => toDateValue(model.value),
    set: (value: DateValue | undefined) => {
        model.value = value?.toString() ?? '';
    },
});

const minDate = computed(() => toDateValue(minValue));

const defaultPlaceholder = today(getLocalTimeZone());
const dateFormatter = new DateFormatter('en-US', {
    dateStyle: 'long',
});
</script>

<template>
    <Popover v-slot="{ close }">
        <PopoverTrigger as-child>
            <Button
                v-bind="$attrs"
                type="button"
                variant="outline"
                :class="
                    cn(
                        'h-10 w-60 justify-start bg-background text-left font-normal',
                        !date && 'text-muted-foreground',
                    )
                "
            >
                <CalendarIcon />
                {{
                    date
                        ? dateFormatter.format(date.toDate(getLocalTimeZone()))
                        : 'Pick a date'
                }}
            </Button>
        </PopoverTrigger>
        <PopoverContent class="w-auto p-0" align="start">
            <Calendar
                v-model="date"
                :default-placeholder="minDate ?? defaultPlaceholder"
                :min-value="minDate"
                layout="month-and-year"
                initial-focus
                @update:model-value="close"
            />
        </PopoverContent>
    </Popover>
</template>
