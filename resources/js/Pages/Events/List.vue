<script setup>
import { ref, computed } from 'vue'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import EventItem from '@/Components/Events/EventItem.vue'
import BodySection from '@/Components/BodySection.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { ZiggyVue } from 'ziggy-js'
import { Ziggy }    from '@/ziggy'

const props = defineProps({
    events: {
        type: Array,
        default: () => [],
    },
})

const createEvent = () => {
    window.open(route('dashboard.events.create'), '_self')
}

// --- Pagination state ---
const pageSizeOptions = [5, 10, 20, 50]
const pageSize = ref(10)
const currentPage = ref(1)

const totalItems = computed(() => props.events.length)
const totalPages = computed(() => Math.max(1, Math.ceil(totalItems.value / pageSize.value)))

const pagedEvents = computed(() => {
    const start = (currentPage.value - 1) * pageSize.value
    return props.events.slice(start, start + pageSize.value)
})

function goToPage(p) {
    const safe = Math.min(Math.max(1, p), totalPages.value)
    currentPage.value = safe
    // Optional: scroll the table into view for better UX on long pages
    document?.getElementById('events-table')?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

function changePageSize(size) {
    pageSize.value = size
    currentPage.value = 1
}
</script>

<template>
    <AdminLayout title="Events">
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Events</h2>
                    <PrimaryButton @click="createEvent">Create</PrimaryButton>
                </div>

                <!-- Controls -->
                <div class="flex items-center gap-3">
                    <label class="text-sm text-gray-500">Rows</label>
                    <select
                        class="rounded-lg border-gray-300 text-sm shadow-sm focus:border-black focus:ring-black"
                        :value="pageSize"
                        @change="changePageSize(parseInt($event.target.value, 10))"
                    >
                        <option v-for="opt in pageSizeOptions" :key="opt" :value="opt">{{ opt }}</option>
                    </select>
                    <span class="text-sm text-gray-500">Total: {{ totalItems }}</span>
                </div>
            </div>
        </template>

        <BodySection>
            <!-- Responsive scroll container -->
            <div class="overflow-x-auto shadow-sm">
                <!-- Subtle top gradient highlight -->
                <div class="h-1 w-full bg-gradient-to-r from-gray-100 via-gray-50 to-gray-100"></div>

                <table
                    id="events-table"
                    class="min-w-full table-auto text-left"
                    aria-describedby="events-caption"
                >
                    <caption id="events-caption" class="sr-only">
                        Events table with pagination
                    </caption>
                    <thead class="sticky top-0 backdrop-blur z-10">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Date
                        </th>
                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Weather
                        </th>
                        <th scope="col" class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            Actions
                        </th>
                    </tr>
                    </thead>

                    <tbody class="[&_tr:nth-child(odd)]:bg-gray-50/40">
                        <template v-if="pagedEvents.length">
                            <EventItem
                                v-for="(event, idx) in pagedEvents"
                                :key="event.id ?? `${event.date}-${idx}`"
                                :event="event"
                            />
                        </template>

                        <tr v-else>
                            <td colspan="3" class="px-4 py-8 text-center text-sm text-gray-500">
                                No events yet.
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Bottom border echo -->
                <div class="h-1 w-full bg-gradient-to-r from-gray-100 via-gray-50 to-gray-100"></div>
            </div>

            <!-- Pagination controls -->
            <div class="mt-4 flex items-center justify-between">
                <div class="text-sm text-gray-500">
                    Page <span class="font-medium text-gray-700">{{ currentPage }}</span>
                    of <span class="font-medium text-gray-700">{{ totalPages }}</span>
                </div>

                <nav class="inline-flex items-center gap-1" role="navigation" aria-label="Pagination">
                    <button
                        class="rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm disabled:opacity-40 hover:bg-gray-50"
                        :disabled="currentPage === 1"
                        @click="goToPage(1)"
                    >
                        « First
                    </button>
                    <button
                        class="rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm disabled:opacity-40 hover:bg-gray-50"
                        :disabled="currentPage === 1"
                        @click="goToPage(currentPage - 1)"
                    >
                        ‹ Prev
                    </button>

                    <!-- Simple number window -->
                    <button
                        v-for="p in Math.min(7, totalPages)"
                        :key="p"
                        class="rounded-xl border px-3 py-2 text-sm shadow-sm hover:bg-gray-50"
                        :class="p === currentPage ? 'border-black bg-black text-white' : 'border-gray-300'"
                        @click="goToPage(p)"
                    >
                        {{ p }}
                    </button>

                    <button
                        class="rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm disabled:opacity-40 hover:bg-gray-50"
                        :disabled="currentPage === totalPages"
                        @click="goToPage(currentPage + 1)"
                    >
                        Next ›
                    </button>
                    <button
                        class="rounded-xl border border-gray-300 px-3 py-2 text-sm shadow-sm disabled:opacity-40 hover:bg-gray-50"
                        :disabled="currentPage === totalPages"
                        @click="goToPage(totalPages)"
                    >
                        Last »
                    </button>
                </nav>
            </div>
        </BodySection>
    </AdminLayout>
</template>
