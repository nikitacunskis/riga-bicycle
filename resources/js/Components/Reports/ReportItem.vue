<script setup>
import UpdateButton from './UpdateButton.vue';
import DeleteButton from './DeleteButton.vue';
import ReportItemElement from './ReportItemElement.vue';
import ReportSetup from './ReportSetup.js';

const reportSetup = new ReportSetup();
const fieldsToRender = reportSetup.getItemsShow;
const props = defineProps({ report: Object });
</script>

<template>
    <!-- row height + zebra + subtle hover -->
    <tr class="reports-row">
        <ReportItemElement
            v-for="f in fieldsToRender"
            :key="f.id"
            :field="f"
            :report="props.report"
        />
        <td class="reports-td">
            <div class="flex items-center justify-end gap-3">
                <UpdateButton :report="props.report" class="reports-btn" />
                <DeleteButton :report="props.report" class="reports-btn reports-btn--danger" />
            </div>
        </td>
    </tr>
</template>
