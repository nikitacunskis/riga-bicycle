<script setup>
import { Bar } from 'vue-chartjs'
import { Chart, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from 'chart.js'
Chart.register(BarElement, CategoryScale, LinearScale, Tooltip, Legend)

const props = defineProps({ items: Array, height: { default: 220 }, title: { default: '' } })
const labels = (props.items ?? []).map(i => i.label)
const values = (props.items ?? []).map(i => i.value)
const data = { labels, datasets: [{ data: values, borderWidth: 0 }] }
const options = { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, title: { display: !!props.title, text: props.title } }, scales:{ x:{ grid:{ display:false } }, y:{ grid:{ drawBorder:false } } } }
</script>
<template>
    <div :style="{height: height + 'px'}">
        <Bar :data="data" :options="options" />
    </div>
</template>
