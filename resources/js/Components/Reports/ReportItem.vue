<script setup>
import UpdateButton from './UpdateButton.vue';
import DeleteButton from './DeleteButton.vue';
import ReportItemElement from './ReportItemElement.vue';
import ReportSetup from './ReportSetup.js';

let reportSetup = new ReportSetup();
const fieldsToRender = reportSetup.getItemsShow; 

const props = defineProps({
    report: Object,
});
console.log(props.report);
</script>

<template>

    <tr>
        <ReportItemElement
            v-for="field in fieldsToRender"
            :field="field"
            :report="props.report"
        />
        <td>
            <div class="border-dotted border-2 border-indigo-600 ">
                <UpdateButton :report="props.report" />
                <DeleteButton :report="props.report" />
            </div>
        </td>
    </tr>
</template>