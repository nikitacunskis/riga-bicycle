<script setup>
import { computed } from 'vue';
import { Pie } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, ArcElement);

const props = defineProps({
  chartData: { type: Array, default: () => [] },
  chartLabels: { type: Array, default: () => [] }
});

const data = computed(() => ({
  labels: props.chartLabels,
  datasets: [
    {
      backgroundColor: ['#16a34a', '#ffffff'],
      borderColor: '#16a34a',
      data: props.chartData
    }
  ]
}));

const options = { responsive: true, maintainAspectRatio: false };
</script>

<template>
  <Pie :data="data" :options="options" />
</template>
