<script setup>
import ReportTableHeadElement from './ReportTableHeadElement.vue';
const props = defineProps({ fields: Object });
</script>

<template>
    <thead>
    <tr class="reports-head-row">
        <ReportTableHeadElement v-for="f in props.fields" :key="f.id" :field="f" />
        <th scope="col" class="reports-th text-right">Actions</th>
    </tr>
    </thead>
</template>
