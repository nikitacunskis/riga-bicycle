<script setup>
import ReportTableHeadElement from './ReportTableHeadElement.vue';

const props = defineProps({
    fields: Object,
});
</script>

<template>

    <ReportTableHeadElement
     v-for="field in fields"
     :field="field"
     />
    <th>
        <div class="border-solid border-2 border-indigo-600 ">
            <span>
                Actions
            </span>
        </div>
    </th>
</template>