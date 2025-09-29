<script setup>
import Dropdown from './Dropdown.vue'
import DropdownLink from './DropdownLink.vue'

const props = defineProps({
    user: { type: Object, default: null },
    jetstream: { type: Object, default: () => ({}) },
})
const emit = defineEmits(['switch'])

const currentTeam = computed(() => props.user?.current_team ?? null)
const allTeams = computed(() => props.user?.all_teams ?? [])
const canCreate = computed(() => !!props.jetstream?.canCreateTeams)
</script>

<template>
    <Dropdown v-if="currentTeam" align="right" width="60">
        <template #trigger>
      <span class="inline-flex rounded-md">
        <button
            type="button"
            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition"
        >
          {{ currentTeam?.name ?? 'Team' }}
          <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </button>
      </span>
        </template>

        <template #content>
            <div class="w-60">
                <div class="block px-4 py-2 text-xs text-gray-400">Manage Team</div>

                <DropdownLink v-if="currentTeam" :href="route('teams.show', currentTeam)">
                    Team Settings
                </DropdownLink>

                <DropdownLink v-if="canCreate" :href="route('teams.create')">
                    Create New Team
                </DropdownLink>

                <div class="border-t border-gray-100" />

                <div class="block px-4 py-2 text-xs text-gray-400">Switch Teams</div>

                <template v-for="team in allTeams" :key="team.id">
                    <form @submit.prevent="$emit('switch', team)">
                        <DropdownLink as="button">
                            <div class="flex items-center">
                                <svg
                                    v-if="team.id === (currentTeam?.id ?? null)"
                                    class="mr-2 h-5 w-5 text-green-400"
                                    fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor"
                                    viewBox="0 0 24 24"
                                ><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <div>{{ team.name }}</div>
                            </div>
                        </DropdownLink>
                    </form>
                </template>
            </div>
        </template>
    </Dropdown>
</template>
