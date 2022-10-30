
<script setup>
    import { useForm } from '@inertiajs/inertia-vue3';
    import PrimaryButton from '@/Components/PrimaryButton.vue';

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
        props.fields.fields.forEach(e =>
        {
            if(document.getElementById('checkbox_'+e).checked)
            {
                form.selected.push(e);
            }
        });
        form.post(route('page.report.post'));
    };
</script>
<template>
    <div>
        <div>
                <table>
                    <tr>
                        <td v-for="field in fields.options">
                            <div class="dropdown-check-list" tabindex="100">
                                <span class="anchor">{{field.label}}</span>
                                <ul class="items">
                                    <li v-for="o in field.options">    
                                        <input type="checkbox" :id="'checkbox_'+o.id" :name="o.id"/>{{ o.label }} 
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </table>
            <form @submit.prevent="submit">
                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Create
                </PrimaryButton>
            </form>
        </div>
    </div>
</template>