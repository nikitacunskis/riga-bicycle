
<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import LineChart from '@/Components/Graphs/LineChart.vue';

const props = defineProps({
    dataset: Object,
});
const randomColorPalette = () => {
    let colorPalette = ["#086788","#07a0c3","#f0c808","#fff1d0","#dd1c1a"];
    var palette = [];
    while(true){
        var r = Math.floor(Math.random() * 5);
        if(palette.indexOf(colorPalette[r]) === -1) 
        {
            palette.push(colorPalette[r]);
        }
        if(palette.length >= colorPalette.length)
        {
            break;
        }
    }
    return palette;
}

const generateDataset = () => {
    let colorPalette = randomColorPalette();
    let generatedDataset = [];
    props.dataset.forEach( (e, index) => {
        generatedDataset.push({
            ...e,
            tension: 0.3,
            borderColor: colorPalette[index],
        })
    });
    return generatedDataset;
}

let chartData = {
    labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
    datasets: generateDataset(),
}
let chartOptions = {
    responsive : true,
}

const form = useForm({
    years: '',
});


</script>
<template>
<div>
    <div>
        <LineChart 
            :chartData = "chartData"
            :chartOptions = "chartOptions"
        />
    </div>
</div>

</template>