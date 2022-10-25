<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    report: Object,
});
const form = useForm({
    id: props.report.id,
});


const submit = () => {
    form.delete(route('dashboard.reports.destroy', {'id':props.report.id}), {
        onFinish: () => console.log('report destroyed'),
    });
};
</script>
<template>
    <form @submit.prevent="submit">
        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Delete
        </PrimaryButton>
    </form>
</template>