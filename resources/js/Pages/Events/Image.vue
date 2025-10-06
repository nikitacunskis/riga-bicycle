<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
const props = defineProps({
    image: Object,
});
console.log(props.image);
</script>

<template>
    <AdminLayout title="Dashboard - Create Event">
        <img :src="props.image" />
    </AdminLayout>
</template>
