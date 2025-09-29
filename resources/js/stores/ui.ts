import { defineStore } from 'pinia'

export const useUiStore = defineStore('ui', {
    state: () => ({
        dark: false,
        commandOpen: false,
    }),
    actions: {
        toggleDark() { this.dark = !this.dark; document.documentElement.classList.toggle('dark', this.dark) },
        openCommand() { this.commandOpen = true },
        closeCommand() { this.commandOpen = false },
    },
})
