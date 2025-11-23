<script setup>
import { ref, computed } from 'vue'
import LineChart from '../../Components/Graphs/LineChart.vue'
import FrontLayout from '../../Layouts/FrontLayout.vue'
import RawTable from '../../Components/RawTable.vue'

const props = defineProps({
    dataset: { type: Array,  default: () => [] },
    report:  { type: Object, default: () => ({ places: [], objects: [], method: '' }) },
    raw:     { type: Array,  default: () => [] },
    e:       { type: Array,  default: () => [] }
})

/* ===========================================================
   🎨 COLOR MODE
   emerald mode (default)  ||  high-contrast mode (toggle)
=========================================================== */
const colorful = ref(false)

/* Emerald palette with depth */
const emeralds = ['#047857','#059669','#10B981','#34D399','#6EE7B7','#A7F3D0','#D1FAE5']

/* High-contrast preset palette (colorblind-safe) */
const highContrasts = [
    '#0072B2','#D55E00','#009E73','#CC79A7','#F0E442',
    '#56B4E9','#E69F00','#000000','#0099A8','#9C179E'
]

/* Pick colors based on mode */
function pickColor(i) {
    return colorful.value
        ? highContrasts[i % highContrasts.length]
        : emeralds[i % emeralds.length]
}

/* Toggle button */
function toggleColors() {
    colorful.value = !colorful.value
}

/* ===========================================================
   📈 CHART DATA (opacity + hover behavior)
=========================================================== */
const chartData = computed(() => ({
    labels: [
        'Janvāris','Februāris','Marts','Aprīlis','Maijs','Jūnijs',
        'Jūlijs','Augusts','Septembris','Oktobris','Novembris','Decembris'
    ],
    datasets: (props.dataset || []).map((d, i) => {
        const c = pickColor(i)
        const base = c
        return {
            ...d,
            tension: 0.35,
            borderWidth: 3,
            pointRadius: 0,

            // ✨ DEFAULT — transparent
            borderColor: base,
            backgroundColor: base,

            // ✨ HOVER — full color
            hoverBorderColor: c,
            hoverBackgroundColor: c,
            pointHoverRadius: 6
        }
    })
}))

/* ===========================================================
   ⚙️ CHART OPTIONS (enable highlight hover)
=========================================================== */
const chartOptions = {
    responsive: true,
    interaction: { mode: 'nearest', intersect: false },
    elements: {
        line: { hoverBorderWidth: 4 },
        point: { hoverRadius: 8 }
    },
    plugins: {
        legend: { position: 'bottom', labels: { color: '#064e3b', usePointStyle: true, padding: 20 } },
        tooltip: { mode: 'index', intersect: false }
    },
    scales: {
        x: { ticks: { color: '#065f46' }, grid: { color: 'rgba(16,185,129,0.08)' } },
        y: { ticks: { color: '#065f46' }, grid: { color: 'rgba(16,185,129,0.08)' } }
    }
}

/* ===========================================================
   🧾 RAW TABLE PAGINATION
=========================================================== */
const pageSize = 25
const visibleCount = ref(pageSize)
const canLoadMore = computed(() => visibleCount.value < props.raw.length)
const visibleRows = computed(() => props.raw.slice(0, visibleCount.value))
function loadMore() { visibleCount.value = Math.min(visibleCount.value + pageSize, props.raw.length) }

const breadcrumbs = [{ text: 'Atskaites', href: '/report' }]
</script>

<template>
    <FrontLayout title="Atskaites" :breadcrumbs="breadcrumbs">
        <section
            class="relative mx-auto max-w-7xl overflow-hidden rounded-3xl bg-white/60
             backdrop-blur-2xl ring-1 ring-emerald-200 shadow-2xl p-6 md:p-10">

            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div
                    class="absolute -top-40 -left-1/2 w-[200%] h-[200%] bg-gradient-to-tr
                 from-emerald-300/20 via-emerald-500/10 to-emerald-300/20
                 blur-3xl animate-pulse-slow">
                </div>
            </div>

            <div class="relative">
                <h1 class="text-center text-4xl md:text-5xl font-extrabold tracking-tight text-emerald-900 drop-shadow-sm">
                    Rīgas Veloskaitīšanas Atskaites
                </h1>
                <p class="mt-3 text-center max-w-2xl mx-auto text-emerald-800/80 text-lg">
                    Mēs skaitām katra mēneša 15. datumam tuvākajā piektdienā, lai parādītu pilsētas kustības spēku.
                </p>

                <!-- Info card -->
                <div class="mt-10 bg-white/80 backdrop-blur ring-1 ring-emerald-100 rounded-2xl shadow-md p-6 md:p-8">
                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-xl font-semibold text-emerald-900 mb-2">Iekļautie punkti</h3>
                            <ul class="list-disc list-inside space-y-1 text-emerald-900/90">
                                <li v-for="place in props.report.places" :key="place">{{ place }}</li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-xl font-semibold text-emerald-900 mb-2">Iekļautās grupas</h3>
                            <ul class="list-disc list-inside space-y-1 text-emerald-900/90">
                                <li v-for="object in props.report.objects" :key="object">{{ object }}</li>
                            </ul>
                            <p class="mt-3 text-emerald-900/90">
                                Metode: <b>{{ props.report.method }}</b>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Chart + button -->
                <div class="mt-10 rounded-3xl bg-white ring-1 ring-emerald-100 p-4 shadow-lg">

                    <div class="flex justify-end mb-3">
                        <button
                            @click="toggleColors"
                            class="px-4 py-1.5 text-sm rounded-md font-semibold
                                   bg-emerald-700 text-white hover:bg-emerald-800 transition"
                        >
                            Krāsains grafiks
                        </button>
                    </div>

                    <LineChart :chartData="chartData" :chartOptions="chartOptions" />
                </div>

                <div class="pt-4">
                    <RawTable :reports="props.raw" />
                </div>
            </div>
        </section>
    </FrontLayout>
</template>

<style scoped>
@keyframes pulse-slow {
    0%, 100% { opacity: 0.6; transform: scale(1); }
    50% { opacity: 0.9; transform: scale(1.05); }
}
.animate-pulse-slow { animation: pulse-slow 12s ease-in-out infinite; }
</style>
