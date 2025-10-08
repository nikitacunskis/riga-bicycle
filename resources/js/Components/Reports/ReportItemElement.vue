<script setup>
import { computed } from 'vue';
const props = defineProps({ field: Object, report: Object });

const value = computed(() => props.field['foreign'] !== false
    ? props.report?.[props.field.foreign]
    : props.report?.[props.field.id]);

const isNumeric = (v) => typeof v === 'number' || (!isNaN(parseFloat(v)) && isFinite(v));
const cellClass = computed(() => {
    const v = value.value;
    const align = props.field.align ?? (isNumeric(v) ? 'text-right' : 'text-left');
    return `reports-td ${align}`;
});
</script>

<template>
    <td :class="cellClass">
        <div class="truncate max-w-[48rem]">{{ value }}</div>
    </td>
</template>
