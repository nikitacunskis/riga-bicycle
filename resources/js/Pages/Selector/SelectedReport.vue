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

/* Emerald palette with depth */
const emeralds = ['#047857','#059669','#10B981','#34D399','#6EE7B7','#A7F3D0','#D1FAE5']

/* Chart dataset using brand colors */
const chartData = computed(() => ({
    labels: [
        'Janvāris','Februāris','Marts','Aprīlis','Maijs','Jūnijs',
        'Jūlijs','Augusts','Septembris','Oktobris','Novembris','Decembris'
    ],
    datasets: (props.dataset || []).map((d, i) => ({
        ...d,
        tension: 0.35,
        borderWidth: 3,
        pointRadius: 0,
        borderColor: emeralds[i % emeralds.length],
        backgroundColor: emeralds[i % emeralds.length] + '33'
    }))
}))

const chartOptions = {
    responsive: true,
    plugins: {
        legend: { position: 'bottom', labels: { color: '#064e3b', usePointStyle: true, padding: 20 } },
        tooltip: { mode: 'index', intersect: false }
    },
    scales: {
        x: { ticks: { color: '#065f46' }, grid: { color: 'rgba(16,185,129,0.08)' } },
        y: { ticks: { color: '#065f46' }, grid: { color: 'rgba(16,185,129,0.08)' } }
    }
}

/* Paginated raw table */
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

            <!-- Radiant emerald “heavenly beams” -->
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

                <!-- Line chart -->
                <div class="mt-10 rounded-3xl bg-white ring-1 ring-emerald-100 p-4 shadow-lg">
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
