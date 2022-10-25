<script setup>
import { computed } from "vue";
const props = defineProps({
    field: Object,
    report: Object,
})

let value = computed(() => {
    if(props.field['foreign'] !== false)
    {
        return props.report[props.field.foreign];
    }
    else
    {
        return props.report[props.field.id];
    }
});
console.log(props.field['foreign']);
</script>
<template>
    <td >
        <div class="border-dotted border-2 border-indigo-600 " >
            <span>{{ value }}</span>
        </div>
    </td>

</template>