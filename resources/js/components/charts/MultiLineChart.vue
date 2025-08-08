<script setup>
import { computed } from 'vue';
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement, Filler);

const props = defineProps({
  chartData: { type: Array, default: () => [] }, // array of arrays
  chartLabels: { type: Array, default: () => [] },
  colors: { type: Array, default: () => [] }
});

const data = computed(() => ({
  labels: props.chartLabels,
  datasets: props.chartData.map((set, index) => ({
    label: `Series ${index + 1}`,
    data: set,
    borderColor: props.colors[index] || '#16a34a',
    backgroundColor: (props.colors[index] || '#16a34a') + '33',
    fill: true,
    tension: 0.4
  }))
}));

const options = { responsive: true, maintainAspectRatio: false };
</script>

<template>
  <Line :data="data" :options="options" />
</template>
