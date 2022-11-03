<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import RawTable from '@/Components/RawTable.vue';

const props = defineProps({
    reports: Object,
});

const breadcrumbs = [
    {
        text: 'Neapstrādāti (RAW) dati',
        href: '/raw'
    }
];
</script>
<template>
    <FrontLayout title="Neapstrādāti (RAW) dati" :breadcrumbs="breadcrumbs">
        <RawTable :reports="props.reports"/>
    </FrontLayout>
</template>