<script setup>
    import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div>
        <div class="sm:px-20 border-b border-gray-200">
            <ApplicationLogo class="block h-12 w-auto" />
        </div>

        <div class="bg-gray-200 bg-opacity-25">
            <slot />
        </div>
    </div>
</template>
