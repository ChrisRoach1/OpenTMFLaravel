<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import type { Country } from '@/components/countryconfiguration/types';
import { Button } from '@/components/ui/button';
import {
    DialogClose,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { store, update } from '@/routes/site-configuration';
import type { Site } from './types';

const { site, countries } = defineProps<{
    site?: Site;
    countries: Country[];
}>();
const emit = defineEmits<{ saved: [] }>();
const form = useForm({
    name: site?.name ?? '',
    primary_investigator: site?.primary_investigator ?? '',
    country_id: site?.country_id ?? (undefined as number | undefined),
});

function submit() {
    form.submit(site ? update(site.id) : store(), {
        preserveScroll: true,
        onSuccess: () => emit('saved'),
    });
}
</script>

<template>
    <form class="space-y-6" @submit.prevent="submit">
        <DialogHeader>
            <DialogTitle>{{ site ? 'Edit site' : 'Add site' }}</DialogTitle>
            <DialogDescription
                >Set the site details and country for this
                study.</DialogDescription
            >
        </DialogHeader>
        <div class="grid gap-2">
            <Label for="site-name">Name</Label>
            <Input
                id="site-name"
                v-model="form.name"
                name="name"
                required
                maxlength="255"
                :disabled="form.processing"
                :aria-invalid="!!form.errors.name"
                aria-describedby="site-name-error"
            />
            <InputError id="site-name-error" :message="form.errors.name" />
        </div>
        <div class="grid gap-2">
            <Label for="site-investigator">Primary investigator</Label>
            <Input
                id="site-investigator"
                v-model="form.primary_investigator"
                name="primary_investigator"
                required
                maxlength="255"
                :disabled="form.processing"
                :aria-invalid="!!form.errors.primary_investigator"
                aria-describedby="site-investigator-error"
            />
            <InputError
                id="site-investigator-error"
                :message="form.errors.primary_investigator"
            />
        </div>
        <div class="grid gap-2">
            <Label for="site-country">Country</Label>
            <Select
                v-model="form.country_id"
                name="country_id"
                required
                :disabled="form.processing"
            >
                <SelectTrigger
                    id="site-country"
                    class="w-full"
                    :aria-invalid="!!form.errors.country_id"
                    aria-describedby="site-country-error"
                >
                    <SelectValue placeholder="Select a country" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem
                        v-for="country in countries"
                        :key="country.id"
                        :value="country.id"
                    >
                        {{ country.name }} ({{ country.code }})
                    </SelectItem>
                </SelectContent>
            </Select>
            <p v-if="!countries.length" class="text-sm text-muted-foreground">
                Add a country before creating a site.
            </p>
            <InputError
                id="site-country-error"
                :message="form.errors.country_id"
            />
        </div>
        <DialogFooter class="gap-2">
            <DialogClose as-child>
                <Button
                    type="button"
                    variant="secondary"
                    :disabled="form.processing"
                    >Cancel</Button
                >
            </DialogClose>
            <Button
                type="submit"
                :disabled="form.processing || !countries.length"
            >
                {{
                    form.processing
                        ? 'Saving…'
                        : site
                          ? 'Save changes'
                          : 'Add site'
                }}
            </Button>
        </DialogFooter>
    </form>
</template>
