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

</script>
<template>
    <FrontLayout title="Atskaites">
        <div>
            <div>
                <table class="table-auto border-collapse border border-slate-400">
                    <thead>
                        <tr>
                            <th v-for="field in fields.options" class="border border-slate-300">
                                <span class="anchor">{{field.label}}</span><br>
                                (<button type="button" @click="checkAll(field)"><i class="fa-solid fa-square-check"></i>visus</button>)
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td v-for="field in fields.options" class="border border-slate-300">
                                <div class="dropdown-check-list" tabindex="100">
                                    <ul class="items">
                                        <li v-for="o in field.options">    
                                            <input type="checkbox" :id="'checkbox_'+o.id" :name="o.id" checked/>{{ o.label }} 
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <form @submit.prevent="submit">
                    <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Rādīt grafiku
                    </PrimaryButton>
                </form>
            </div>
        </div>
    </FrontLayout>
</template>