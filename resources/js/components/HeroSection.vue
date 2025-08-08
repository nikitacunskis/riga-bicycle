<script setup>
import BaseButton from './BaseButton.vue';

const props = defineProps({
  backgroundSrc: { type: String, default: '' },
  heroHeight: { type: String, default: '400px' },
  heroText: { type: String, default: '' },
  heroTextColor: { type: String, default: '#ffffff' },
  heroSubText: { type: String, default: '' },
  heroButtons: { type: Array, default: () => [] }
});
</script>

<template>
  <section class="w-full bg-center bg-cover flex items-center justify-center" :style="{ backgroundImage: `url(${backgroundSrc})`, height: heroHeight }">
    <div class="bg-black bg-opacity-40 p-4 text-center">
      <h1 class="text-3xl font-bold mb-2" :style="{ color: heroTextColor }">{{ heroText }}</h1>
      <p class="mb-4" :style="{ color: heroTextColor }">{{ heroSubText }}</p>
      <div class="flex flex-col sm:flex-row sm:justify-center gap-2">
        <BaseButton v-for="(btn, index) in heroButtons" :key="index" :label="btn.label" :link="btn.link" :bg-color="btn.bgColor" :text-color="btn.textColor" position="center" />
      </div>
    </div>
  </section>
</template>
