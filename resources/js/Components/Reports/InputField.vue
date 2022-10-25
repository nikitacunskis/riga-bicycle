<script setup>

import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
const props = defineProps({
    field: Object,
    report: Object
});
</script>
<template>
    <div>
        <InputLabel :for="field.id" :value="field.label" v-if="field.type !== 'hidden' && field.visible"/>
        <TextInput
            :id="field.id"
            :type="field.type"
            :name = "field.id"
            class="mt-1 block w-full"
            :value = "props.report[props.field.id]"
            required
            autofocus
            v-if="field.visible"
        />
        <!-- <InputError class="mt-2" :message="form.errors.date" /> -->
    </div>
</template>