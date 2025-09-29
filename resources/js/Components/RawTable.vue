<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
    reports: { type: Array, default: () => [] },
})

/* Weather reveal (unchanged) */
function showWeather(id) {
    document.getElementById(id)?.classList.remove('hidden')
    document.getElementById('button_' + id)?.classList.add('hidden')
}
function showAllWeather() {
    document.querySelectorAll('.hidden_weather').forEach(el => el.classList.remove('hidden'))
    document.querySelectorAll('.showhide_button').forEach(el => el.classList.add('hidden'))
}

/* Pagination */
const pageSize = 25
const visibleCount = ref(pageSize)
const visibleRows = computed(() => props.reports.slice(0, visibleCount.value))
const canLoadMore = computed(() => visibleCount.value < props.reports.length)
function loadMore() {
    visibleCount.value = Math.min(visibleCount.value + pageSize, props.reports.length)
}

/* --- NEW: place truncation / expansion --- */
const expandedPlaces = ref(new Set())

function keyFor(r) {
    return `${r.event}_${r.place_id}`
}
function isLongPlace(place) {
    return place && place.length > 16
}
function shortPlace(place) {
    return place.slice(0, 10) + '...' + place.slice(-3)
}
function togglePlace(key) {
    if (expandedPlaces.value.has(key)) expandedPlaces.value.delete(key)
    else expandedPlaces.value.add(key)
}
</script>

<template>
    <div class="overflow-x-auto relative rounded-2xl ring-1 ring-emerald-200 bg-white/80 backdrop-blur shadow">
        <table class="w-full text-xs text-left text-emerald-900">
            <thead class="text-[10px] uppercase bg-emerald-50 text-emerald-800">
            <tr>
                <th class="p-1">Datums</th>
                <th class="p-1">
                    Laika apstākļi<br>
                    <button type="button" @click="showAllWeather" class="text-emerald-600 hover:underline text-[10px]">
                        Rādīt visu
                    </button>
                </th>
                <th class="p-1">Vieta</th>
                <th class="p-1">Sievietes</th>
                <th class="p-1">Vīrieši</th>
                <th class="p-1">Bērni (paši)</th>
                <th class="p-1">Bērni (pasažieri)</th>
                <th class="p-1">Ceļš</th>
                <th class="p-1">Ietve</th>
                <th class="p-1">Veloceļš/josla</th>
                <th class="p-1">Uz Centru</th>
                <th class="p-1">No Centra</th>
                <th class="p-1">Bērnu krēsli</th>
                <th class="p-1">Supermobilitāte</th>
            </tr>
            </thead>

            <tbody class="text-[11px]">
            <tr v-for="r in visibleRows" :key="r.event + '_' + r.place_id"
                class="border-b last:border-0 hover:bg-emerald-50/50">
                <td class="p-1">{{ r.event }}</td>
                <td class="p-1">
                    <button type="button"
                            :id="'button_' + r.event + '_' + r.place_id"
                            class="showhide_button text-emerald-600 hover:underline"
                            @click="showWeather(r.event + '_' + r.place_id)">
                        Rādīt
                    </button>
                    <span class="hidden hidden_weather" :id="r.event + '_' + r.place_id">{{ r.weather }}</span>
                </td>

                <!-- UPDATED PLACE CELL -->
                <td class="p-1">
                    <template v-if="isLongPlace(r.place)">
                        <button
                            :title="r.place"
                            class="text-emerald-600 hover:underline"
                            @click="togglePlace(keyFor(r))"
                        >
                            {{ expandedPlaces.has(keyFor(r)) ? r.place : shortPlace(r.place) }}
                        </button>
                    </template>
                    <template v-else>
                        {{ r.place }}
                    </template>
                </td>

                <td class="p-1">{{ r.womens }}</td>
                <td class="p-1">{{ r.man }}</td>
                <td class="p-1">{{ r.children_self }}</td>
                <td class="p-1">{{ r.children_passanger }}</td>
                <td class="p-1">{{ r.radway }}</td>
                <td class="p-1">{{ r.pavement }}</td>
                <td class="p-1">{{ r.biekpath }}</td>
                <td class="p-1">{{ r.to_center }}</td>
                <td class="p-1">{{ r.from_center }}</td>
                <td class="p-1">{{ r.child_chairs }}</td>
                <td class="p-1">{{ r.supermobility }}</td>
            </tr>
            </tbody>
        </table>

        <!-- Load More -->
        <div class="flex justify-center py-4">
            <button v-if="canLoadMore" @click="loadMore"
                    class="px-5 py-2.5 rounded-xl bg-emerald-600 text-white font-medium shadow
                     hover:bg-emerald-700 focus:outline-none focus:ring-4 focus:ring-emerald-300
                     transition transform hover:-translate-y-0.5">
                Ielādēt vēl ({{ Math.min(pageSize, props.reports.length - visibleCount) }})
            </button>
            <p v-else class="text-sm text-gray-500">Viss ielādēts.</p>
        </div>
    </div>
</template>
