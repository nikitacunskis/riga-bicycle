<script setup>
import { computed } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps({
  chartData: { type: Array, default: () => [] },
  chartLabels: { type: Array, default: () => [] }
});

const data = computed(() => ({
  labels: props.chartLabels,
  datasets: [
    {
      backgroundColor: '#16a34a',
      borderColor: '#16a34a',
      data: props.chartData
    }
  ]
}));

const options = { responsive: true, maintainAspectRatio: false };
</script>

<template>
  <Bar :data="data" :options="options" />
</template>
