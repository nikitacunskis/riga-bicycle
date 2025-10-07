<script setup>

import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
const props = defineProps({
    field: Object,
    report: Object
});
</script>
<template>
    <div>
        <InputLabel :for="field.id" :value="field.label" v-if="field.type !== 'hidden' && field.visible"
            class="text-white"
        />
        <TextInput
            :id="field.id"
            :type="field.type"
            :name = "field.id"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
            :value = "props.report[props.field.id]"
            required
            autofocus
            v-if="field.visible"
        />
        <!-- <InputError class="mt-2" :message="form.errors.date" /> -->
    </div>
</template>
