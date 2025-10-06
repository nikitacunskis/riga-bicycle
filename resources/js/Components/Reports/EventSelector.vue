<script setup>
import { ref } from "vue";

const props = defineProps({
    events: {
        type: Array,
    },
});

const selectedEventId = ref(null);

const list = () => {
    if (selectedEventId.value) {
        window.location.href = '/dashboard/reports/list/' + selectedEventId.value;
    } else {
        alert('Please select an event first.');
    }
};
</script>
<template>
    <section>
        <select
            v-model="selectedEventId"
            class="bg-black text-white p-2 rounded m-3"
        >
            <option disabled value="">-- Select event --</option>
            <option
                v-for="event in props.events"
                :key="event.id"
                :value="event.id"
            >
                {{ event.date }}
            </option>
        </select>

        <button
            @click="list"
            class="bg-black text-white p-2 rounded hover:bg-gray-800"
        >
            Select
        </button>
    </section>
</template>
<script setup lang="ts">
</script>
