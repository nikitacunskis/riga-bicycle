<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    place: Object,
});
const form = useForm({
    id: props.place.id,
});


const submit = () => {
    form.delete(route('dashboard.places.destroy', {'id':props.place.id}), {
        onFinish: () => console.log('place destroyed'),
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