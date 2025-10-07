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

const formatDate = (d) => {
    if (!d) return '—'
    if (d.includes('-')) {
        const parts = d.split('-')
        if (parts[0].length === 4) {
            const [y, m, day] = parts
            return new Date(`${y}-${m}-${day}T00:00:00Z`).toLocaleDateString('en-GB', {
                year: 'numeric', month: 'short', day: '2-digit',
            })
        } else {
            const [day, m, y] = parts
            return new Date(`${y}-${m}-${day}T00:00:00Z`).toLocaleDateString('en-GB', {
                year: 'numeric', month: 'short', day: '2-digit',
            })
        }
    }
    return d
}
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
                {{ formatDate(event.date) }}
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
