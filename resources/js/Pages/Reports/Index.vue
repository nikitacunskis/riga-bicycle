<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import BodySection from "../../Components/BodySection.vue";
import EventSelector from "../../Components/Reports/EventSelector.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from "vue";

const props = defineProps({
    events: {
        type: Array,
        required: true,
    },
});

const createReport = () => {
    window.open(route("dashboard.reports.create"),"_self");
}
</script>

<template>
    <AdminLayout title="Select event">
        <PrimaryButton class="ml-4" @click="createReport">
            Create
        </PrimaryButton>
        <BodySection>
            <EventSelector :events="props.events" />
        </BodySection>
    </AdminLayout>
</template>
