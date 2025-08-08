<script setup>
import { computed } from 'vue';

const props = defineProps({
  label: { type: String, default: '' },
  link: { type: String, default: '#' },
  bgColor: { type: String, default: '#16a34a' },
  textColor: { type: String, default: '#ffffff' },
  position: { type: String, default: 'left' },
  width: { type: String, default: 'auto' },
  icon: { type: String, default: '' }
});

const positionClass = computed(() => {
  if (props.position === 'center') return 'flex justify-center';
  if (props.position === 'right') return 'flex justify-end';
  return 'flex justify-start';
});

const widthClass = computed(() => props.width === 'full' ? 'w-full' : '');
</script>

<template>
  <div :class="positionClass">
    <a :href="link" class="px-4 py-2 rounded-md inline-flex items-center" :class="widthClass" :style="{ backgroundColor: bgColor, color: textColor }">
      <i v-if="icon" :class="[icon, 'mr-2']"></i>{{ label }}
    </a>
  </div>
</template>
