<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
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
import { store, update } from '@/routes/country-configuration';
import type { Country } from './types';

const { country } = defineProps<{ country?: Country }>();
const emit = defineEmits<{ saved: [] }>();
const form = useForm({
    name: country?.name ?? '',
    code: country?.code ?? '',
});

function submit() {
    form.submit(country ? update(country.id) : store(), {
        preserveScroll: true,
        onSuccess: () => emit('saved'),
    });
}
</script>

<template>
    <form class="space-y-6" @submit.prevent="submit">
        <DialogHeader>
            <DialogTitle>{{
                country ? 'Edit country' : 'Add country'
            }}</DialogTitle>
            <DialogDescription
                >Set the country name and code for this
                study.</DialogDescription
            >
        </DialogHeader>
        <div class="grid gap-2">
            <Label for="country-name">Name</Label>
            <Input
                id="country-name"
                v-model="form.name"
                name="name"
                required
                maxlength="255"
                placeholder="United Kingdom"
                :disabled="form.processing"
                :aria-invalid="!!form.errors.name"
                aria-describedby="country-name-error"
            />
            <InputError id="country-name-error" :message="form.errors.name" />
        </div>
        <div class="grid gap-2">
            <Label for="country-code">Code</Label>
            <Input
                id="country-code"
                v-model="form.code"
                name="code"
                required
                placeholder="GB"
                :disabled="form.processing"
                :aria-invalid="!!form.errors.code"
                aria-describedby="country-code-error"
            />
            <InputError id="country-code-error" :message="form.errors.code" />
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
            <Button type="submit" :disabled="form.processing">
                {{
                    form.processing
                        ? 'Saving…'
                        : country
                          ? 'Save changes'
                          : 'Add country'
                }}
            </Button>
        </DialogFooter>
    </form>
</template>
