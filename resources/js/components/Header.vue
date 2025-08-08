<script setup>
import { ref } from 'vue';

const props = defineProps({
  menuItems: { type: Array, default: () => [] },
  logoSrc: { type: String, default: '' }
});

const open = ref(false);
</script>

<template>
  <header class="flex items-center justify-between p-4 bg-white">
    <div>
      <img :src="logoSrc" alt="Logo" class="h-10 w-auto" />
    </div>
    <nav>
      <button class="md:hidden" @click="open = !open">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
      </button>
      <ul :class="[open ? 'block' : 'hidden', 'md:flex md:space-x-4']" class="mt-2 md:mt-0">
        <li v-for="(item, index) in menuItems" :key="index" class="my-2 md:my-0">
          <a :href="item.link" class="px-3 py-2 rounded-md flex items-center" :style="{ backgroundColor: item.bgColor, color: item.textColor }">
            <i v-if="item.icon" :class="[item.icon, 'mr-1']"></i>{{ item.label }}
          </a>
        </li>
      </ul>
    </nav>
  </header>
</template>
