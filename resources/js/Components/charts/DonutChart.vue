<script setup>
import { Doughnut } from 'vue-chartjs'
import { Chart, ArcElement, Tooltip, Legend } from 'chart.js'
Chart.register(ArcElement, Tooltip, Legend)

const props = defineProps({ items: Array, height: { default: 220 }, title: { default: '' } })
const labels = (props.items ?? []).map(i => i.label)
const values = (props.items ?? []).map(i => i.value)
const data = { labels, datasets: [{ data: values }] }
const options = { responsive: true, maintainAspectRatio: false, plugins:{ legend:{ position:'bottom' }, title:{ display: !!props.title, text: props.title } } }
</script>
<template>
    <div :style="{height: height + 'px'}">
        <Doughnut :data="data" :options="options" />
    </div>
</template>
