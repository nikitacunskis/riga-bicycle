import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'   /** #### CHANGED (EXPLANATION): modern adapter */
import { InertiaProgress } from '@inertiajs/progress'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

const appName = document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

createInertiaApp({
    title: title => title ? `${title} - ${appName}` : appName,
    resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vue = createApp({ render: () => h(App, props) })
        vue.use(plugin) /** #### ADDED (EXPLANATION): registers Inertia on the client (mandatory). */
        vue.mount(el)
    },
})

InertiaProgress.init({ color: '#10b981' })
