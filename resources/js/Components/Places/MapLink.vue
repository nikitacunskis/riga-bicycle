<script setup>
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const emit = defineEmits(['close']);

const props = defineProps({
    place: Object,
});

const show = ref(false);
const close = () => {
    emit('close');
};

const showMap = () => {
    show.value = !show.value;
}

const openMap = () => {
    window.open("https://www.openstreetmap.org/#map=19/" + props.place.coordinates);
}

const getCoordinate = function(){
    let coordinates = props.place.coordinates.split("/");
    return {
        latitude : coordinates[0],
        longitude : coordinates[1],
    }
}
const getMapLink = function(){
    let pos = {
        x1: Number(getCoordinate().longitude) - 0.00257605737,
        y1: Number(getCoordinate().latitude) - 0.00066748764,
        x2: Number(getCoordinate().longitude) + 0.00190323167,
        y2: Number(getCoordinate().latitude) + 0.00066755987,
        mx: Number(getCoordinate().longitude) + 0.00008863277435,
        my: Number(getCoordinate().latitude) + 0.00000004209515,
    } 
    return "https://www.openstreetmap.org/export/embed.html?bbox="+pos.x1+"%2C"+pos.y1+"%2C"+pos.x2+"%2C"+pos.y2+"&amp;layer=mapquest&amp;marker="+pos.mx+"%2C"+pos.my;
}
</script>

<template>
    <Modal
        :show="show"
        :max-width="'2xl'"
        :closeable="true"
        @close="close"  
        >
        <PrimaryButton class="ml-4" @click="showMap">
            Close Map
        </PrimaryButton>
        <PrimaryButton class="ml-4" @click="openMap">
            openstreetmap link
        </PrimaryButton>
        <iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" :src="getMapLink()" style="border: 1px solid black"></iframe>
    </Modal>

    <PrimaryButton class="ml-4" @click="showMap">
        Show Map
    </PrimaryButton>
</template>