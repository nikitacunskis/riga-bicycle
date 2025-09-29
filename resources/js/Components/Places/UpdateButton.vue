<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    place: Object,
});
const form = useForm({
    id: props.place.id,
});


const submit = () => {
    form.get(route('dashboard.places.edit', {'id':props.place.id}));
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
