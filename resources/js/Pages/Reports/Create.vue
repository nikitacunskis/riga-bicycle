<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ReportSetup from '@/Components/Reports/ReportSetup.js';
import InputField from '@/Components/Reports/InputField.vue';

let reportSetup = new ReportSetup();
const fieldsToRender = reportSetup.getItemsShow; 

const props = defineProps({
    places: Object,
    events: Object,
    prev_event_id: {
        type: String,
        default: '',
    }
});

console.log(props.places);

const form = useForm({
   place_id: '',
   event_id: props.prev_event_id,
   womens: '',
   man: '',
   radway: '',
   pavement: '',
   biekpath: '',
   child_chairs: '',
   supermobility: '',
   to_center: '',
   from_center: '',
   children_self: '',
   children_passanger: '',
});

//fill data with 0 for user friendly 
let report = {};
fieldsToRender.forEach(e => {
    report[e.id] = 0; 
});

const submit = () => {
    let data = {};
    fieldsToRender.forEach(element => {
        form[element.id] = document.getElementById(element.id).value;
    });

    form['place_id'] = document.getElementById('places').value;
    form['event_id'] = document.getElementById('events').value;
    form.post(route('dashboard.reports.store'))
        .then(response => {
            console.log(response);
        });
}
const isNumeric = (str) => {
  if (typeof str != "string") return false // we only process strings!  
  return !isNaN(str) && // use type coercion to parse the _entirety_ of the string (`parseFloat` alone does not do this)...
         !isNaN(parseFloat(str)) // ...and ensure strings of whitespace fail
}
const excelString = () => {
    //	Akmens tilts	185	72	113	39%	0	185	-	7	32	98	87	1	2	187											
    let data = document.getElementById('excel-string').value.split('\t');
    console.log('data', data);
    form['place_id'] = data[1];
    form['womens'] = data[3];
    form['man'] = data[4];
    form['radway'] = data[6];
    form['pavement'] = data[7];
    form['biekpath'] = data[8];
    form['child_chairs'] = data[9];
    form['supermobility'] = data[10];
    form['to_center'] = data[11];
    form['from_center'] = data[12];
    form['children_self'] = data[13];
    form['children_passanger'] = data[14];
    let keys = Object.keys(form);
    console.log('keys', keys);
    fieldsToRender.forEach((e,key) => {
        if(e.id !== 'event_id')
        {
            console.log('e', e.id, typeof(e));
            if(isNumeric(form[e.id]))
            {
                document.getElementById(e.id).value = form[e.id];
            }
            else
            {
                document.getElementById(e.id).value = 0;
            }
        }
    });
    console.log(data);
}
</script>

<template>
    <AdminLayout title="Dashboard - Create Report">
        <AuthenticationCard>
            <h2>Create Report</h2>
            <form @submit.prevent="submit">
                <div class="flex items-center justify-end mt-4">
                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Create
                    </PrimaryButton>
                </div>
                <div>
                    <label for="places" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Place</label>
                    <select id="places"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option v-for="place in props.places" :value="place.id">
                            {{ place.location }}
                        </option>
                    </select>
                </div>
                <div>
                    <label for="events" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-400">Events</label>
                    <select id="events"  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option v-for="event in props.events" :value="event.id">
                            {{ event.date }}
                        </option>
                    </select>
                </div>
                <TextInput :value="''" id="excel-string" />

                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="excelString" type="button">  
                    Import
                </PrimaryButton>    
                <InputField
                    v-for="field in fieldsToRender"
                    :field = "field"
                    :report = "report"
                />
            </form>
        </AuthenticationCard>
    </AdminLayout>
</template>
