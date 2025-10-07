<script setup>
import AdminLayout from '../Layouts/AdminLayout.vue'
import FancyMenu from '../Components/FancyMenu.vue'
import UserCard from '../Components/UserCard.vue'
import PlacesMap from '../Components/PlaceMap.vue'
import { usePage } from '@inertiajs/vue3'
import { computed, ref, onMounted, onBeforeUnmount, watch } from 'vue'
import Chart from 'chart.js/auto'

const page = usePage()
const user = computed(() => page.props.auth?.user ?? null)

const props = defineProps({
    kpi: Object,
    next_event: Object,
    reports_stat: Object,
    reports_trend: Object,
})

console.log(props.reports_trend)

const formatInt = (n) => (typeof n === 'number' ? n.toLocaleString('en-US') : n)

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

const topEvents = computed(() => props.kpi?.topEvents ?? [])
const hasTop = computed(() => (topEvents.value?.length ?? 0) > 0)

const bestDay = computed(() => {
    if (!hasTop.value) return null
    return [...topEvents.value].sort((a, b) => b.reports_count - a.reports_count)[0]
})

const statEntries = computed(() => {
    const s = props.reports_stat || {}
    return Object.entries(s)
        .map(([date, count]) => ({ date, count }))
        .sort((a, b) => a.date.localeCompare(b.date))
})

const labelsDates = computed(() => statEntries.value.map((e) => e.date))
const counts = computed(() => statEntries.value.map((e) => e.count))

// ---------- Main chart ----------
const lineRef = ref(null)
let lineChart = null

const buildLine = () => {
    if (!lineRef.value) return
    const ctx = lineRef.value.getContext('2d')
    if (lineChart) lineChart.destroy()
    lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labelsDates.value,
            datasets: [
                {
                    label: 'Reports per event date',
                    data: counts.value,
                    tension: 0.25,
                    pointRadius: 2,
                    borderWidth: 2,
                    fill: true,
                    backgroundColor: (context) => {
                        const { chart } = context
                        const { ctx, chartArea } = chart
                        if (!chartArea) return undefined
                        const gradient = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom)
                        gradient.addColorStop(0, 'rgba(16,185,129,0.75)')
                        gradient.addColorStop(1, 'rgba(16,185,129,0.02)')
                        return gradient
                    },
                },
            ],
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { mode: 'index', intersect: false },
            },
            interaction: { mode: 'nearest', intersect: false },
            scales: {
                x: { ticks: { maxRotation: 0, autoSkip: true, autoSkipPadding: 12 }, grid: { display: false } },
                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.06)' } },
            },
        },
    })
}

onMounted(() => buildLine())
onBeforeUnmount(() => { if (lineChart) lineChart.destroy() })
watch([labelsDates, counts], () => buildLine())

// ---------- Dynamic report collection block ----------
const direction = computed(() => props?.reports_trend?.streak?.direction ?? 'flat')
const lastStatus = computed(() => props?.reports_trend?.last_vs_previous?.status ?? 'flat')
const lastDelta = computed(() => props?.reports_trend?.last_vs_previous?.delta ?? 0)

const recentCounts = computed(() => {
    const arr = counts.value ?? []
    return arr.slice(-12)
})

const tinyRef = ref(null)
let tinyChart = null

const buildTiny = () => {
    if (!tinyRef.value) return
    const ctx = tinyRef.value.getContext('2d')
    if (!ctx) return
    if (tinyChart) tinyChart.destroy()

    tinyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: recentCounts.value.map((_, i) => i + 1),
            datasets: [{
                data: recentCounts.value,
                tension: 0.35,
                pointRadius: 0,
                borderWidth: 2,
                fill: true,
                borderColor:
                    direction.value === 'up'
                        ? 'rgb(16,185,129)'
                        : direction.value === 'down'
                            ? 'rgb(248,113,113)'
                            : 'rgb(161,161,170)',
                backgroundColor: (ctx2) => {
                    const { chart } = ctx2
                    const { ctx, chartArea } = chart
                    if (!chartArea) return undefined
                    const g = ctx.createLinearGradient(0, chartArea.top, 0, chartArea.bottom)
                    if (direction.value === 'up') {
                        g.addColorStop(0, 'rgba(16,185,129,0.35)')
                        g.addColorStop(1, 'rgba(16,185,129,0.03)')
                    } else if (direction.value === 'down') {
                        g.addColorStop(0, 'rgba(248,113,113,0.35)')
                        g.addColorStop(1, 'rgba(248,113,113,0.03)')
                    } else {
                        g.addColorStop(0, 'rgba(161,161,170,0.35)')
                        g.addColorStop(1, 'rgba(161,161,170,0.03)')
                    }
                    return g
                },
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { enabled: false } },
            scales: {
                x: { display: false },
                y: { display: false, beginAtZero: true },
            },
            elements: { line: { capBezierPoints: true } },
        },
    })
}

onMounted(() => buildTiny())
onBeforeUnmount(() => { if (tinyChart) tinyChart.destroy() })
watch([recentCounts, direction], () => buildTiny())
</script>

<template>
    <AdminLayout title="Dashboard">
        <section class="border border-zinc-800 bg-zinc-900 p-6 shadow-xl shadow-black/30 mb-6">
            <header class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold">Welcome</h3>
                <span class="text-xs text-zinc-400">"Pilsēta cilvēkiem veloskaitīšana" Control Center</span>
            </header>
            <p class="text-sm text-zinc-300">
                Use the left sidebar to manage members, programs, events, and reports. Keep it lean: fast approvals,
                clean records, transparent updates.
            </p>
        </section>

        <section class="border border-zinc-800 bg-zinc-900 p-6 shadow-xl shadow-black/30 mb-6">
            <header class="flex items-center justify-between mb-5">
                <h3 class="text-lg font-semibold">KPI</h3>
                <span v-if="next_event?.read" class="text-xs text-emerald-400 inline-flex items-center gap-2">
          <span class="inline-block h-2 w-2 bg-emerald-400 animate-pulse"></span>
          Next count: {{ next_event.read }} ({{ formatDate(next_event.date) }})
        </span>
            </header>

            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="border border-zinc-800 bg-zinc-950/50 p-5">
                    <div class="text-xs text-zinc-400 mb-2">Total bikes counted</div>
                    <div class="text-3xl font-bold tracking-tight">{{ formatInt(kpi?.bikesOverall) }}</div>
                    <div class="mt-3 text-[15px] text-zinc-500">Cumulative across all approved reports.</div>
                </div>
                <div class="border border-zinc-800 bg-zinc-950/50 p-5">
                    <div class="text-xs text-zinc-400 mb-2">Reports</div>
                    <div class="text-3xl font-bold tracking-tight">{{ formatInt(kpi?.reports) }}</div>
                    <div class="mt-3 text-[15px] text-zinc-500">Submitted & stored in the archive.</div>
                </div>
                <div class="border border-zinc-800 bg-zinc-950/50 p-5">
                    <div class="text-xs text-zinc-400 mb-2">Events</div>
                    <div class="text-3xl font-bold tracking-tight">{{ formatInt(kpi?.events) }}</div>
                    <div class="mt-3 text-[15px] text-zinc-500">Unique counting days/locations.</div>
                </div>
                <div
                    class="relative overflow-hidden border border-zinc-800 bg-zinc-950/50 p-5"
                    :class="{
                        'ring-1 ring-emerald-500/20': direction === 'up',
                        'ring-1 ring-red-500/20': direction === 'down',
                        'ring-1 ring-zinc-600/10': direction === 'flat',
                    }"
                >
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <div class="text-xs text-zinc-400 mb-2">Report collection — dynamics</div>
                            <div class="text-3xl font-bold tracking-tight text-white">
                                {{ formatInt(props?.reports_trend?.streak?.length) }}
                                <span class="text-zinc-400 font-normal text-[20px]">days</span>
                                <span class="mx-1 text-zinc-400 font-normal text-[20px]">of</span>
                                <span
                                    class="inline-flex items-center gap-1.5 text-[20px]"
                                    :class="{
                                        'text-emerald-400': direction === 'up',
                                        'text-red-400': direction === 'down',
                                        'text-zinc-300': direction === 'flat',
                                    }"
                                >
                                    <span v-if="direction === 'up'">▲</span>
                                    <span v-else-if="direction === 'down'">▼</span>
                                </span>
                            </div>

                            <p v-if="lastStatus === 'up'" class="mt-2 text-sm text-emerald-400">
                                +{{ formatInt(lastDelta) }} vs last event
                            </p>
                            <p v-else-if="lastStatus === 'down'" class="mt-2 text-sm text-red-400">
                                −{{ formatInt(lastDelta) }} vs last event
                            </p>
                            <p v-else class="mt-2 text-sm text-zinc-400">
                                Same number of reports as last event
                            </p>

                            <div class="mt-3 inline-flex items-center gap-2">
                                <span
                                    class="px-2 py-0.5 text-[11px] font-medium"
                                    :class="{
                                        'bg-emerald-500/10 text-emerald-300 border border-emerald-500/20': direction === 'up',
                                        'bg-red-500/10 text-red-300 border border-red-500/20': direction === 'down',
                                        'bg-zinc-800 text-zinc-300 border border-zinc-700': direction === 'flat',
                                    }"
                                >
                                    Live trend
                                </span>
                                <span class="text-[11px] text-zinc-500">
                                    Updated {{ new Date().toLocaleDateString('en-GB') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="border border-zinc-800 bg-zinc-900 p-6 shadow-xl shadow-black/30 mb-6">
            <header class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Reports — Trends</h3>
                <div class="text-xs text-zinc-400">Charted with Chart.js for quick pattern recognition.</div>
            </header>
            <div class="border border-zinc-800 bg-zinc-950/50 p-5">
                <div class="text-xs text-zinc-400 mb-2">Per-event submissions over time</div>
                <div class="h-64">
                    <canvas ref="lineRef"></canvas>
                </div>
                <div class="mt-3 text-[11px] text-zinc-500">Each point = one counting event date ({{ labelsDates.length }} total).</div>
            </div>
        </section>

        <section class="border border-zinc-800 bg-zinc-900 p-6 shadow-xl shadow-black/30">
            <header class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Reports — Highlights</h3>
                <div class="text-xs text-zinc-400">Data quality first: completeness, clarity, and weather context.</div>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-1 border border-zinc-800 bg-zinc-950/50 p-5">
                    <div class="text-xs text-zinc-400 mb-2">Top event by report count</div>
                    <div v-if="bestDay" class="space-y-2">
                        <div class="text-sm text-zinc-300">Date: <a :href="'/dashboard/reports/list/' + bestDay.id"><span class="font-medium">{{ formatDate(bestDay.date) }}</span></a></div>
                        <div class="text-sm text-zinc-300">Reports: <span class="font-semibold">{{ formatInt(bestDay.reports_count) }}</span></div>
                        <div class="text-xs text-zinc-400">Weather:</div>
                        <p class="text-xs leading-relaxed text-zinc-300">{{ bestDay.weather }}</p>
                        <div class="mt-3 flex items-center gap-2">
                            <span class="inline-block h-1.5 w-1.5 bg-emerald-500"></span>
                            <span class="text-[11px] text-zinc-500">Created {{ new Date(bestDay.created_at).toLocaleDateString('en-GB') }}</span>
                        </div>
                    </div>
                    <div v-else class="text-sm text-zinc-400">No top events yet.</div>
                </div>

                <div class="lg:col-span-2 border border-zinc-800 bg-zinc-950/50 p-0">
                    <div class="px-5 py-4 border-b border-zinc-800 flex items-center justify-between">
                        <div class="text-sm font-medium">Top events</div>
                        <div class="text-[11px] text-zinc-500">Sorted by reports count (desc)</div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="text-left text-zinc-400">
                            <tr class="border-b border-zinc-800">
                                <th class="py-3 px-5">ID</th>
                                <th class="py-3 px-5">Date</th>
                                <th class="py-3 px-5">Reports</th>
                                <th class="py-3 px-5">Weather</th>
                                <th class="py-3 px-5">Created</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="e in [...topEvents].sort((a,b)=>b.reports_count-a.reports_count)" :key="e.id" class="border-b border-zinc-900/70 hover:bg-zinc-900/60">
                                <td class="py-3 px-5 font-mono text-zinc-300"><a :href="'/dashboard/reports/list/' + e.id">#{{ e.id }}</a></td>
                                <td class="py-3 px-5 text-zinc-200">{{ formatDate(e.date) }}</td>
                                <td class="py-3 px-5 font-semibold">{{ formatInt(e.reports_count) }}</td>
                                <td class="py-3 px-5 text-zinc-300 max-w-[28rem] truncate" :title="e.weather">{{ e.weather }}</td>
                                <td class="py-3 px-5 text-zinc-400">{{ new Date(e.created_at).toLocaleDateString('en-GB') }}</td>
                            </tr>
                            <tr v-if="!hasTop" class="border-t border-zinc-900/70">
                                <td colspan="5" class="py-6 px-5 text-center text-zinc-500">No events available.</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section class="border border-zinc-800 bg-zinc-900 p-6 shadow-xl shadow-black/30">

            <PlacesMap
                :api-url="'/api/map/places'"
                :events-url="'/api/map/events'"

                title="Bike hotspots"
                :show-legend="true"
                legend-text="Grey = 0 reports; Green = more"
                wrapper-class="pb-3 flex items-center gap-4 bg-neutral-900/50 "
                title-class="text-sm font-semibold text-white"
                select-class="text-sm bg-neutral-800 border border-neutral-700 px-3 py-2"
                legend-class="ml-auto text-xs text-neutral-400"

                marker-metric="reports_total"
                heat-metric="bikes_total"
                :size-fn="(v,max)=> 4 + Math.round(14*(v/max))"
                :color-fn="(v,max)=> v===0 ? '#737373' : `hsl(${120-120*(v/max)} 90% 45%)`"

                :popup-template="p => `
                    <div style='min-width:260px'>
                      <strong>${p.name ?? 'Unknown'} (#${p.id})</strong><br/>
                      <small>${p.city ?? ''}</small><hr/>
                      <div>🚲 Bikes: <b>${p.bikes_total ?? 0}</b></div>
                      <div>📝 Reports: <b>${p.reports_total ?? 0}</b></div>
                    </div>
                `"
            />

        </section>
    </AdminLayout>
</template>
