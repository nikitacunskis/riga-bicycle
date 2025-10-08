<script setup>
const props = defineProps({ field: Object });
</script>

<template>
    <th v-if="field.crud" scope="col" class="reports-th text-left">
        {{ field.label }}
    </th>
</template>
