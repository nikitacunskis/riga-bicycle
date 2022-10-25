<script setup>
import UpdateButton from './UpdateButton.vue';
import DeleteButton from './DeleteButton.vue';

const props = defineProps({
    event: Object,
});
</script>

<template>
    <div>{{ props.event.date }}</div>
    <div>{{ props.event.weather }}</div>
    <div>
        <UpdateButton :event="props.event" />
        <DeleteButton :event="props.event" />
    </div>

</template>