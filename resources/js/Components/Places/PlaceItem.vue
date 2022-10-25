<script setup>
import UpdateButton from './UpdateButton.vue';
import DeleteButton from './DeleteButton.vue';
import MapLink from './MapLink.vue';

const props = defineProps({
    place: Object,
});
</script>

<template>
    <div>{{ props.place.location }}</div>
    <div>
        <MapLink :place="props.place" />
        <!-- <a :href="'https://www.openstreetmap.org/#map=19/' + props.place.coordinates">Show map</a> -->
    </div>
    <div>
        <UpdateButton :place="props.place" />
        <DeleteButton :place="props.place" />
    </div>

</template>