<script setup>
import BaseButton from './BaseButton.vue';

const props = defineProps({
  imageSrc: { type: String, default: '' },
  textContent: { type: String, default: '' },
  buttonLabel: { type: String, default: '' },
  buttonLink: { type: String, default: '' },
  buttonBgColor: { type: String, default: '#16a34a' },
  buttonTextColor: { type: String, default: '#ffffff' },
  reverse: { type: Boolean, default: false }
});
</script>

<template>
  <section class="flex flex-col md:flex-row items-center my-6" :class="{ 'md:flex-row-reverse': reverse }">
    <div v-if="imageSrc" class="w-full md:w-1/2">
      <img :src="imageSrc" class="w-full h-full object-cover" />
    </div>
    <div class="p-4 w-full" :class="imageSrc ? 'md:w-1/2' : ''">
      <p class="mb-4">{{ textContent }}</p>
      <BaseButton v-if="buttonLabel" :label="buttonLabel" :link="buttonLink" :bg-color="buttonBgColor" :text-color="buttonTextColor" />
    </div>
  </section>
</template>
