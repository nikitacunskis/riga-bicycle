<script setup>
import { onMounted, ref, watch } from 'vue'
import L from 'leaflet'
import 'leaflet.markercluster'
import 'leaflet.heat'

const props = defineProps({
    // Data endpoints
    apiUrl:    { type: String, default: '/api/map/places' },
    eventsUrl: { type: String, default: '/api/map/events' },

    // ===== UI / Styling controls =====
    title:         { type: String,  default: 'Places Map' },
    showLegend:    { type: Boolean, default: true },
    legendText:    { type: String,  default: 'Legend: small/grey = 0 reports; larger/green → more reports' },

    // Tailwind (or any) classes you want to override
    wrapperClass:  { type: String, default: 'p-3 flex items-center gap-3' },
    titleClass:    { type: String, default: 'text-sm font-medium text-neutral-200' },
    selectClass:   { type: String, default: 'text-sm bg-neutral-800 border border-neutral-700 rounded px-2 py-1' },
    legendClass:   { type: String, default: 'ml-auto text-xs text-neutral-400' },
    mapClass:      { type: String, default: '' },
    mapStyle:      { type: Object, default: () => ({ height: '540px' }) },

    // Leaflet base layer + controls
    tileUrl:       { type: String, default: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png' },
    tileAttribution:{ type: String, default: '&copy; OpenStreetMap contributors' },
    minZoom:       { type: Number, default: 3 },
    maxZoom:       { type: Number, default: 19 },
    preferCanvas:  { type: Boolean, default: true },
    showLayerToggle:{ type: Boolean, default: true },

    // Cluster options (pass any supported leaflet.markercluster options)
    clusterOptions: { type: Object, default: () => ({ showCoverageOnHover:false, maxClusterRadius:48 }) },

    // Heat options
    heatOptions: { type: Object, default: () => ({ radius: 28, blur: 18, maxZoom: 16, max: 1.0 }) },

    // ===== Metrics & appearance =====
    // Which feature property to use for marker size/color and heat intensity
    markerMetric: { type: String, default: 'reports_total' }, // e.g. 'reports_total' | 'bikes_total'
    heatMetric:   { type: String, default: 'bikes_total' },   // e.g. 'bikes_total' | 'reports_total'

    // Custom color/size functions (value, max) => result
    colorFn: { type: Function, default: (v, max) => v === 0 ? '#808080' : `hsl(${Math.round(120 - 120*(v/max))} 80% 45%)` },
    sizeFn:  { type: Function, default: (v, max) => 6 + (max ? Math.round(12*(v/max)) : 0) },

    // ===== Popup templating =====
    // Provide either popupTemplate (function -> string|HTMLElement) OR use the <template #popup> slot below.
    popupTemplate: { type: Function, default: null },
})

const emit = defineEmits(['marker-click'])

const mapEl   = ref(null)
const events  = ref([])
const eventId = ref(null)

let map, clusters, heat, layerCtrl

const fetchEvents = async () => {
    const res = await fetch(props.eventsUrl)
    events.value = await res.json()
}

const fetchPlaces = async () => {
    const u = new URL(props.apiUrl, window.location.origin)
    if (eventId.value) u.searchParams.set('event_id', eventId.value)
    const res = await fetch(u)
    return await res.json()
}

// Helper for default popup when no slot / template provided
const defaultPopupHtml = (p) => {
    return `
    <div style="min-width:230px">
      <strong>${p.name ?? 'Unknown'} (#${p.id})</strong><br/>
      Bikes: <b>${p.bikes_total ?? 0}</b><br/>
      Reports: <b>${p.reports_total ?? 0}</b>
    </div>`
}

// Render a popup using: 1) slot content if provided, 2) popupTemplate prop, 3) default
const renderPopup = (p) => {
    // 1) Try slot (runtime-mount a fragment)
    // We create a container div and, if there is a slot, mount a tiny app using the slot render.
    const slot = slots.popup
    if (slot) {
        const container = document.createElement('div')
        // Render the slot VNodes into the container
        // NOTE: In <script setup>, we need access to slots via useSlots()
        return container // will be filled in onMounted via a microtask below
    }
    // 2) popupTemplate function
    if (typeof props.popupTemplate === 'function') {
        const result = props.popupTemplate(p)
        if (result instanceof HTMLElement) return result
        return result ?? defaultPopupHtml(p)
    }
    // 3) Fallback to default
    return defaultPopupHtml(p)
}

import { useSlots, nextTick, createApp, h } from 'vue'
const slots = useSlots()

const render = (fc) => {
    if (!clusters) { clusters = L.markerClusterGroup(props.clusterOptions); map.addLayer(clusters) }

    clusters.clearLayers()
    if (heat) map.removeLayer(heat)

    // Gather metrics
    const metricValues = fc.features.map(f => (f.properties?.[props.markerMetric]) || 0)
    const maxMetric    = Math.max(...metricValues, 1)
    const heatValues   = fc.features.map(f => (f.properties?.[props.heatMetric]) || 0)
    const maxHeat      = Math.max(...heatValues, 1)

    const heatPts = []

    fc.features.forEach(f => {
        if (!f.geometry) return
        const [lng, lat] = f.geometry.coordinates
        const p = f.properties || {}

        // Circle marker styled by selected metric
        const metricVal = p[props.markerMetric] ?? 0

        const m = L.circleMarker([lat, lng], {
            radius: props.sizeFn(metricVal, maxMetric),
            color: '#262626',
            weight: 1,
            fillColor: props.colorFn(metricVal, maxMetric),
            fillOpacity: 0.85,
        })

        // Popup content
        const content = renderPopup(p)
        m.bindPopup(content)

        // Emit click if needed
        m.on('click', () => emit('marker-click', p))

        clusters.addLayer(m)

        // Heat point
        const hv = p[props.heatMetric] ?? 0
        const w = maxHeat ? (hv / maxHeat) : 0
        heatPts.push([lat, lng, Math.max(0.05, w)])
    })

    // Heat layer
    heat = L.heatLayer(heatPts, props.heatOptions)

    // Layers control
    if (layerCtrl) layerCtrl.remove()
    if (props.showLayerToggle) {
        layerCtrl = L.control.layers(null, { 'Clusters': clusters, 'Heatmap': heat }, { collapsed:true })
        layerCtrl.addTo(map)
    }

    // Fit bounds
    const group = L.featureGroup(clusters.getLayers())
    if (group.getLayers().length) map.fitBounds(group.getBounds().pad(0.2))
}

onMounted(async () => {
    map = L.map(mapEl.value, { preferCanvas: props.preferCanvas, minZoom: props.minZoom })
    L.tileLayer(props.tileUrl, { maxZoom: props.maxZoom, attribution: props.tileAttribution }).addTo(map)

    await fetchEvents()
    render(await fetchPlaces())
})

watch(eventId, async () => { render(await fetchPlaces()) })
</script>

<template>
    <div :class="wrapperClass">
        <h3 :class="titleClass">{{ title }}</h3>

        <select v-model="eventId" :class="selectClass">
            <option :value="null">All events</option>
            <option v-for="e in events" :key="e.id" :value="e.id">
                {{ e.date }} (id: {{ e.id }})
            </option>
        </select>

        <div v-if="showLegend" :class="legendClass">
            {{ legendText }}
        </div>

        <!-- Optional custom header slot -->
        <slot name="header" :events="events" :event-id="eventId" />
    </div>

    <div :class="mapClass" :style="mapStyle" ref="mapEl"></div>
</template>

<style scoped>
:deep(.leaflet-container){ background:#0a0a0a; }
</style>
