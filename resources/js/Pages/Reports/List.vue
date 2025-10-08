<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ReportItem from '@/Components/Reports/ReportItem.vue';
import BodySection from '@/Components/BodySection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ReportTableHeader from '@/Components/Reports/ReportTableHeader.vue';
import ReportSetup from '@/Components/Reports/ReportSetup.js';
import EventSelector from '@/Components/Reports/EventSelector.vue';

const reportSetup = new ReportSetup();
const fieldsToRender = reportSetup.getItemsShow;

const props = defineProps({ reports: Array, events: Array });
const createReport = () => window.open(route('dashboard.reports.create'), '_self');
</script>

<template>
    <AdminLayout title="Reports">
        <div class="mb-4 flex items-center justify-between">
            <h1 class="text-xl font-semibold text-slate-100">Reports</h1>
            <div class="flex items-center gap-3">
                <EventSelector :events="props.events" />
                <PrimaryButton @click="createReport">CREATE</PrimaryButton>
            </div>
        </div>

        <BodySection>
            <div class="overflow-hidden border border-slate-800 shadow-lg bg-slate-900">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-fixed text-sm">
                        <ReportTableHeader :fields="fieldsToRender" />
                        <tbody class="divide-y divide-slate-800">
                        <ReportItem
                            v-for="r in props.reports"
                            :key="r.id ?? r.uuid"
                            :report="r"
                        />
                        </tbody>
                    </table>
                </div>
            </div>
        </BodySection>
    </AdminLayout>
</template>
