<script setup>
const props = defineProps({
  imageSrc: { type: String, default: '' },
  imageHeight: { type: String, default: '300px' }
});
</script>

<template>
  <div class="w-full overflow-hidden" :style="{ height: imageHeight }">
    <img :src="imageSrc" class="w-full h-full object-cover object-center" />
  </div>
</template>
