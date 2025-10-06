<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ReportItem from '@/Components/Reports/ReportItem.vue';
import BodySection from '@/Components/BodySection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ReportTableHeader from '@/Components/Reports/ReportTableHeader.vue';
import ReportSetup from '@/Components/Reports/ReportSetup.js';
import { ZiggyVue } from 'ziggy-js';
import { Ziggy }    from '@/ziggy';
import EventSelector from "../../Components/Reports/EventSelector.vue";

let reportSetup = new ReportSetup();
const fieldsToRender = reportSetup.getItemsShow;

const props = defineProps({
    reports: Array,
    events: Array,
});


const createReport = () => {
    window.open(route("dashboard.reports.create"),"_self");
}
</script>
<template>
    <AdminLayout title="Reports">
        <PrimaryButton class="ml-4" @click="createReport">
            Create
        </PrimaryButton>
        <EventSelector :events="props.events" />
        <BodySection>
            <table class="border-solid">
                <ReportTableHeader
                    :fields="fieldsToRender"
                />
                <ReportItem
                    v-for="report in reports"
                    :report="report" />
            </table>
        </BodySection>
    </AdminLayout>
</template>
