<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PlaceItem from '@/Components/Places/PlaceItem.vue';
import BodySection from '@/Components/BodySection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    places: Array,
});

const route = (name) => name;

const createPlace = () => {
    window.open(route('dashboard.places.create'), '_self');
}
</script>
<template>
    <AdminLayout title="Places">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Places
            </h2>
            <PrimaryButton class="ml-4" @click="createPlace">
                Create
            </PrimaryButton>
        </template>

        <BodySection>
            
            <div class="grid grid-cols-3 gap-3">
                <div>Location</div>
                <div>Coordinates</div>
                <div>Functions</div>
                <PlaceItem 
                    v-for="place in places"
                    :place="place" />
            </div>
        </BodySection>
    </AdminLayout>
</template>