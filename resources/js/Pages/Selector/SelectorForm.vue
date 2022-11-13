<script setup>
    import { useForm } from '@inertiajs/inertia-vue3';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import FrontLayout from '@/Layouts/FrontLayout.vue';

    const props = defineProps({
        fields: Object,
    });

    let formFields = {};
    props.fields.fields.forEach(e =>
    {
        formFields[e] = false;  
    });
    const form = useForm({
        selected : [],
    });

    const submit = () => {
        document.getElementById('form-table').classList.add('hidden');
        document.getElementById('donation-box').classList.remove('hidden');
    };

    const donate = () => {
        window.open("https://www.pilsetacilvekiem.lv/ziedot/", '_blank');
        realSubmit();
    }

    const realSubmit = () => {
        form.selected = []; //in some undetected cases form.selected saves it's state. 
        props.fields.fields.forEach(e =>
        {
            if(document.getElementById('checkbox_'+e).checked)
            {
                form.selected.push(e);
            }
        });
        form.post(route('page.report.post'));
    }

    const checkAll = (field) => {
        field.options.forEach(e => {
            document.getElementById('checkbox_'+e.id).checked = !document.getElementById('checkbox_'+e.id).checked;
        });
    }

    const breadcrumbs = [
        {
            text: 'Atskaites',
            href: '/report'
        },
    ];

</script>
<template>
    <FrontLayout title="Atskaites" :breadcrumbs="breadcrumbs">
        <table id="form-table" class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th v-for="field in fields.options" class="border border-slate-300 py-3 px-6" scope="col" >
                        <span class="anchor">{{field.label}}</span><br>
                        (<button type="button" @click="checkAll(field)"><i class="fa-solid fa-square-check"></i>visus</button>)
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border border-slate-300 align-top">
                        <ul class="items align-top">
                            <li v-for="o in fields.options.years.options">    
                                <input type="checkbox" :id="'checkbox_'+o.id" :name="o.id" checked/>{{ o.label }} 
                            </li>
                        </ul>
                    </td>

                    <td class="border border-slate-300 align-top">
                        <ul class="items align-top">
                            <li v-for="o in fields.options.objects.options">    
                                <input type="checkbox" :id="'checkbox_'+o.id" :name="o.id" checked/>{{ o.label }} 
                            </li>
                        </ul>
                    </td>

                    <!-- <td class="border border-slate-300 align-top">
                        <ul class="items align-top">
                            <li v-for="o in fields.options.direction.options">    
                                <input type="checkbox" :id="'checkbox_'+o.id" :name="o.id" checked/>{{ o.label }} 
                            </li>
                        </ul>
                    </td>

                    <td class="border border-slate-300 align-top">
                        <ul class="items align-top">
                            <li v-for="o in fields.options.roadType.options">    
                                <input type="checkbox" :id="'checkbox_'+o.id" :name="o.id" checked/>{{ o.label }} 
                            </li>
                        </ul>
                    </td>

                    <td class="border border-slate-300 align-top">
                        <ul class="items align-top">
                            <li v-for="o in fields.options.attributes.options">    
                                <input type="checkbox" :id="'checkbox_'+o.id" :name="o.id" checked/>{{ o.label }} 
                            </li>
                        </ul>
                    </td> -->

                    <td class="border border-slate-300 align-top">
                        <ul class="items align-top">
                            <li v-for="o in fields.options.places.options">    
                                <input type="checkbox" :id="'checkbox_'+o.id" :name="o.id" checked/>{{ o.label }} 
                            </li>
                        </ul>
                    </td>

                    <td class="border border-slate-300 align-top">
                        <ul class="items align-top">
                            <li v-for="o in fields.options.method.options">    
                                <div class="flex items-center">
                                    <input :id="'checkbox_'+o.id" type="radio" :value="o.id" name="place" class="text-blue-600 bg-gray-100 border-gray-300 focus:ring-2">
                                    <label :for="'checkbox_'+o.id" class="ml-2 text-sm font-medium">{{ o.label }} </label>
                                </div>
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
            <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="submit">
                Rādīt grafiku
            </PrimaryButton>
        </table>
 
        <div id="donation-box" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden0 z-50 md:inset-0 h-modal md:h-full">

            <table class="w-full">
                <tr>
                    <td rowspan="2" class="px-6">
                        <i class="fa-solid fa-heart text-[200pt] text-red-500"></i>
                    </td>
                    <td>
                        <h3 class="mb-5 text-[20pt] font-normal text-gray-700 dark:text-gray-400">"<b>Pilsēta cilvēkiem</b>" piedāvā pieeju visiem datiem <b>BEZMAKSAS</b>. Vai vēlaties atbalstīt projektu ar ziedojumu?</h3>
                        <p class="text-sm text-gray-400">Pieprasītie dati paliks tepat. Mēs atvērsim ziedojumu lapu blakus cilnē.</p>
                        <p class="text-sm text-gray-400">Apvienība “Pilsēta cilvēkiem” ir sabiedriskā labuma organizācija, tādēļ ziedotāji (gan fiziskās personas, gan uzņēmumi), var saņemt <a href="https://www.vid.gov.lv/lv/nodoklu-atvieglojumi" class="text-green-500">nodokļu atvieglojumu</a>.</p>
                    </td>
                </tr>
                <tr> 
                    <td>
                        <form @submit.prevent="realSubmit">
                            <button @click="donate" type="button" class="text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-lg inline-flex items-center px-5 py-2.5 text-center mr-2">
                                ZIEDOT un saņemt datus
                            </button>   
                            <button type="submit" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">
                                Nē, vēlos tikai saņemt datus!
                            </button>
                        </form>

                    </td>
                </tr>
            </table>
        </div>
    </FrontLayout>       
</template>