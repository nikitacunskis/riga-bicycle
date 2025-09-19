<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ReportItem from '@/Components/Reports/ReportItem.vue';
import BodySection from '@/Components/BodySection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ReportTableHeader from '@/Components/Reports/ReportTableHeader.vue';
import ReportSetup from '@/Components/Reports/ReportSetup.js';
import { ZiggyVue } from 'ziggy-js';
import { Ziggy }    from '@/ziggy';

let reportSetup = new ReportSetup();
const fieldsToRender = reportSetup.getItemsShow;

const props = defineProps({
    reports: Array,
});


const createReport = () => {
    window.open(route("dashboard.reports.create"),"_self");
}
</script>
<template>
    <AdminLayout title="Reports">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reports
            </h2>
            <PrimaryButton class="ml-4" @click="createReport">
                Create
            </PrimaryButton>
        </template>

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
