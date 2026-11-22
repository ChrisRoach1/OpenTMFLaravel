<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import DatePicker from '@/components/DatePicker.vue';
import InputError from '@/components/InputError.vue';
import Button from '@/components/ui/button/Button.vue';
import Input from '@/components/ui/input/Input.vue';
import Label from '@/components/ui/label/Label.vue';
import studyConfiguration from '@/routes/study-configuration';

const { study } = defineProps<{
    study: Study;
}>();

const form = useForm({
    protocol_title: study.protocol_title,
    start_date: study.start_date,
    end_date: study.end_date,
    database_lock_date: study.database_lock_date,
});

const submit = () => {
    form.patch(studyConfiguration.update({study: study}).url);
};
</script>

<template>
    <form class="grid max-w-xl gap-6" @submit.prevent="submit">
        <div class="grid gap-2">
            <Label for="protocol">Protocol</Label>
            <Input
                id="protocol"
                v-model="form.protocol_title"
                name="protocol"
                type="text"
                placeholder="Enter protocol"
                autocomplete="off"
                class="h-10 bg-background"
                :aria-invalid="!!form.errors.protocol_title || undefined"
                @update:model-value="form.clearErrors('protocol_title')"
            />
            <InputError :message="form.errors.protocol_title" />
        </div>

        <div class="grid gap-2">
            <Label for="study_start_date">Study Start Date</Label>
            <DatePicker
                id="study_start_date"
                v-model="form.start_date"
                :aria-invalid="!!form.errors.start_date || undefined"
                @update:model-value="form.clearErrors('start_date')"
            />
            <InputError :message="form.errors.start_date" />
        </div>

        <div class="grid gap-2">
            <Label for="study_end_date">Study End Date</Label>
            <DatePicker
                id="study_end_date"
                v-model="form.end_date"
                :min-value="form.start_date"
                :aria-invalid="!!form.errors.end_date || undefined"
                @update:model-value="form.clearErrors('end_date')"
            />
            <InputError :message="form.errors.end_date" />
        </div>

        <div class="grid gap-2">
            <Label for="database_lock_date">Database Lock Date</Label>
            <DatePicker
                id="database_lock_date"
                v-model="form.database_lock_date"
                :aria-invalid="!!form.errors.database_lock_date || undefined"
                @update:model-value="form.clearErrors('database_lock_date')"
            />
            <InputError :message="form.errors.database_lock_date" />
        </div>

        <div>
            <Button type="submit" :disabled="form.processing">
                Save study details
            </Button>
        </div>
    </form>
</template>
