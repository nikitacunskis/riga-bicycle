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
        form.selected = []; //in some undetected cases form.selected saves it's state. 
        props.fields.fields.forEach(e =>
        {
            if(document.getElementById('checkbox_'+e).checked)
            {
                form.selected.push(e);
            }
        });
        form.post(route('page.report.post'));
    };

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
        <table class="w-full text-xs text-left text-gray-500 dark:text-gray-400">
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

                    <td class="border border-slate-300 align-top">
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
                    </td>

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
        </table>
        <form @submit.prevent="submit">
            <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Rādīt grafiku
            </PrimaryButton>
        </form>
    </FrontLayout>
</template>