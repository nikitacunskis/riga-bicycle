<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PlaceItem from '@/Components/Places/PlaceItem.vue';
import BodySection from '@/Components/BodySection.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ZiggyVue } from 'ziggy-js';
import { Ziggy }    from '@/ziggy';

const props = defineProps({
    places: Array,
});

const createPlace = () => {
    window.open(route("dashboard.places.create"),"_self");
}
const columns = [
    { key: 'id', label: 'ID' },
    { key: 'key', label: 'Key' },
    { key: 'owner', label: 'Owner' },
    { key: 'type', label: 'Type', format: v => v || '—' },
    { key: 'reg_number', label: 'Reg #' , format: v => v || '—' },
    { key: 'email', label: 'Email', format: v => v || '—' },
    { key: 'phone', label: 'Phone', format: v => v || '—' },
    { key: 'valid_until', label: 'Valid Until', format: v => v ? new Date(v).toLocaleDateString() : '—' },
]
</script>
<template>
    <AdminLayout title="Places">
        <PrimaryButton class="ml-4" @click="createPlace">
            Create
        </PrimaryButton>
        <BodySection>

            <div class="grid grid-cols-3 gap-3 p-3">
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
