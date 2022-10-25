<script setup>

const props = defineProps({
    field: Object,
});
</script>

<template>
    <th v-if="field.crud">
        <div class="border-solid border-2 border-indigo-600 ">
            <span>
                {{ field.label }}
            </span>
        </div>
    </th>
</template>