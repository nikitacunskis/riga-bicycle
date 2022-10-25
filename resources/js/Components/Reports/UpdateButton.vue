<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    report: Object,
});
const form = useForm({
    id: props.report.id,
});


const submit = () => {
    form.get(route('dashboard.reports.edit', {'id':props.report.id}));
};

</script>
<template>

    <form @submit.prevent="submit">
        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Edit
        </PrimaryButton>
    </form>
</template>
<script>
</script>