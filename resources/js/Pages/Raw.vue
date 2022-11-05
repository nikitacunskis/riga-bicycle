<script setup>
import FrontLayout from '@/Layouts/FrontLayout.vue';
import RawTable from '@/Components/RawTable.vue';

const props = defineProps({
    reports: Object,
});

const breadcrumbs = [
    {
        text: 'Neapstrādāti (RAW) dati',
        href: '/raw'
    }
];


const donate = () => {
    window.open("https://www.pilsetacilvekiem.lv/ziedot/", '_blank');
    showTable();
}

const showTable = () => {
    document.getElementById('donation-box').classList.add('hidden');
    document.getElementById('rawTable').classList.remove('hidden');
}
</script>
<template>
    <FrontLayout title="Neapstrādāti (RAW) dati" :breadcrumbs="breadcrumbs">
        <div id="rawTable" class="hidden">
            <RawTable :reports="props.reports"/>

        </div>
        <div id="donation-box" tabindex="-1" class="overflow-y-auto overflow-x-hidden0 z-50 md:inset-0 h-modal md:h-full">

            <table class="w-full">
                <tr>
                    <td rowspan="2" class="px-6">
                        <i class="fa-solid fa-heart text-[200pt] text-red-500"></i>
                    </td>
                    <td>
                        <h3 class="mb-5 text-[20pt] font-normal text-gray-700 dark:text-gray-400">"<b>Pilsēta Cilvēkiem</b>" piedāvā pieeju visiem datiem <b>BEZMAKSAS</b>. Vai vēlaties atbalstīt projektu ar ziedojumu?</h3>
                        <p class="text-sm text-gray-400">Pieprasītie dati paliks tepat. Mēs atvērsim ziedojumu lapu blakus cilnē.</p>
                        <p class="text-sm text-gray-400">Apvienība “Pilsēta cilvēkiem” ir sabiedriskā labuma organizācija, tādēļ ziedotāji (gan fiziskās personas, gan uzņēmumi), var saņemt nodokļu atvieglojumu.</p>
                    </td>
                </tr>
                <tr> 
                    <td>
                        <button @click="donate" type="button" class="text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-lg inline-flex items-center px-5 py-2.5 text-center mr-2">
                            ZIEDOT un saņemt datus
                        </button>   
                        <button @click="showTable" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                            Nē, vēlos tikai saņemt datus!
                        </button>

                    </td>
                </tr>
            </table>
        </div>
    </FrontLayout>
</template>