<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { Dialog, Combobox, ComboboxInput, ComboboxOptions, ComboboxOption, TransitionRoot } from '@headlessui/vue'
import { useUiStore } from '../stores/ui'
const props = defineProps({ shortcuts: { type: Array, default: () => [] } })
const ui = useUiStore()
const query = ref('')
const filtered = computed(() => !query.value ? props.shortcuts : props.shortcuts.filter(s => s.label.toLowerCase().includes(query.value.toLowerCase())))

const keyHandler = (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') { e.preventDefault(); ui.openCommand() }
}
onMounted(()=>window.addEventListener('keydown', keyHandler))
onBeforeUnmount(()=>window.removeEventListener('keydown', keyHandler))

const choose = (item) => { ui.closeCommand(); item?.action?.() }
</script>

<template>
    <TransitionRoot appear :show="ui.commandOpen" as="template">
        <Dialog as="div" class="relative z-50" @close="ui.closeCommand">
            <div class="fixed inset-0 bg-black/30" />
            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-start justify-center p-4">
                    <div class="w-full max-w-xl transform overflow-hidden rounded-2xl bg-white dark:bg-zinc-900 shadow-xl transition-all">
                        <div class="p-4">
                            <Combobox @update:modelValue="choose">
                                <ComboboxInput class="w-full bg-transparent outline-none text-gray-900 dark:text-white text-sm" placeholder="Type a command… (Ctrl/⌘K)" v-model="query" />
                                <ComboboxOptions static class="mt-3 max-h-72 overflow-y-auto">
                                    <ComboboxOption v-for="item in filtered" :key="item.label" :value="item" as="template" v-slot="{ active }">
                                        <div :class="['px-3 py-2 rounded-md text-sm cursor-pointer', active ? 'bg-gray-100 dark:bg-zinc-800' : '']">{{ item.label }}</div>
                                    </ComboboxOption>
                                    <div v-if="!filtered.length" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400">No results.</div>
                                </ComboboxOptions>
                            </Combobox>
                        </div>
                    </div>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

