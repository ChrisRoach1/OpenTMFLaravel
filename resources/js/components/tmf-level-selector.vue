<script setup lang="ts">
import Label from '@/components/ui/label/Label.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';

const { isForm } = defineProps<{
    isForm: boolean;
}>();

const model = defineModel();

</script>

<template>
    <div class="grid gap-2">
        <Label for="tmf-level" class="gap-0.5">
            TMF Level
            <span
                v-if="isForm"
                class="text-destructive"
                aria-hidden="true"
                >*</span
            >
        </Label>

        <Select
            name="tmf-level"
            :required="isForm"
            v-model="model"
        >
            <SelectTrigger id="tmf-level" class="h-10 w-80 bg-background">
                <SelectValue
                            
                    placeholder="Select TMF Level"
                />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectItem key="General" value="General">
                        General
                    </SelectItem>
                    <SelectItem key="Site" value="Site">
                        Site
                    </SelectItem>                    
                    <SelectItem key="Country" value="Country">
                        Country
                    </SelectItem>                    
                </SelectGroup>
            </SelectContent>
        </Select>
    </div>

</template>
