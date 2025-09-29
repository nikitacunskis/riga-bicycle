<script setup>
import { Line } from 'vue-chartjs'
import { Chart, LineElement, PointElement, LinearScale, CategoryScale, Filler, Tooltip, Legend } from 'chart.js'
Chart.register(LineElement, PointElement, LinearScale, CategoryScale, Filler, Tooltip, Legend)

const props = defineProps({
    labels: Array,
    values: Array,
    height: { type: Number, default: 220 },
    title: { type: String, default: '' },
})

const data = {
    labels: props.labels ?? [],
    datasets: [{
        data: props.values ?? [],
        fill: true,
        tension: 0.35,
        pointRadius: 0,
        borderWidth: 2,
    }],
}
const options = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: { legend: { display: false }, tooltip: { intersect: false, mode: 'index' }, title: { display: !!props.title, text: props.title } },
    scales: { x: { grid: { display: false } }, y: { grid: { drawBorder: false } } },
}
</script>

<template>
    <div :style="{height: height + 'px'}">
        <Line :data="data" :options="options" />
    </div>
</template>
