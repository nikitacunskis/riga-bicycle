import './bootstrap'
import '../css/app.css'

// Leaflet CSS (global)
import 'leaflet/dist/leaflet.css'
import 'leaflet.markercluster/dist/MarkerCluster.css'
import 'leaflet.markercluster/dist/MarkerCluster.Default.css'

// Fix Leaflet icon URLs under Vite (so default markers render)
import L from 'leaflet'
import marker2x from 'leaflet/dist/images/marker-icon-2x.png'
import marker from 'leaflet/dist/images/marker-icon.png'
import shadow from 'leaflet/dist/images/marker-shadow.png'
delete L.Icon.Default.prototype._getIconUrl
L.Icon.Default.mergeOptions({ iconRetinaUrl: marker2x, iconUrl: marker, shadowUrl: shadow })

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { InertiaProgress } from '@inertiajs/progress'
import { installInertiaGA } from './plugins/inertia-ga';

const appName = document.title || 'Laravel'

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vue = createApp({ render: () => h(App, props) })
        installInertiaGA(); // <— one line
        vue.use(plugin)
        vue.mount(el)
    },
})

InertiaProgress.init({ color: '#10b981' })
