<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import RawTable from '@/Components/RawTable.vue';

const props = defineProps({
    reports: Object,
});

</script>
<template>
    <FrontLayout title="Neapstrādāti (RAW) dati">
        <RawTable :reports="props.reports"/>
    </FrontLayout>
</template>