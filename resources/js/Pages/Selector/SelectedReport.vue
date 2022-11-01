<script setup>
import LineChart from '@/Components/Graphs/LineChart.vue';
import FrontLayout from '@/Layouts/FrontLayout.vue';

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
    labels: ['Janvāris', 'Februāris', 'Marts', 'Aprīlis', 'Maijs', 'Jūnis', 'Jūlijs', 'Augusts', 'Septembirs', 'Oktobris', 'Novembris', 'Decembris'],
    datasets: generateDataset(),
}
let chartOptions = {
    responsive : true,
}


</script>
<template>

<FrontLayout>
        <LineChart 
            :chartData = "chartData"
            :chartOptions = "chartOptions"
        />
</FrontLayout>
</template>