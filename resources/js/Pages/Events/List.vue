<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import EventItem from '@/Components/Events/EventItem.vue';
import BodySection from '@/Components/BodySection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import route from '../../../../vendor/tightenco/ziggy/src/js';

const props = defineProps({
    events: Array,
});


const createEvent = () => {
    window.open(route("dashboard.events.create"),"_self");
}
</script>
<template>
    <AdminLayout title="Events">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Events
            </h2>
            <PrimaryButton class="ml-4" @click="createEvent">
                Create
            </PrimaryButton>
        </template>

        <BodySection>
            
            <div class="grid grid-cols-3 gap-3">
                <div>Date</div>
                <div>Weather</div>
                <div>Action</div>
                <EventItem 
                    v-for="event in events"
                    :event="event" />
            </div>
        </BodySection>
    </AdminLayout>
</template>