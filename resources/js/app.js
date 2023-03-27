import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(name, import.meta.glob('./Pages/**/*.vue')),
    setup({
        el, App, props, plugin,
    }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el)
    },
    progress: {
        color: '#4B5563',
    },
})

function resolvePageComponent(name, pages) {
    const componentPath = `./Pages/${name}`
    let page = pages[`${componentPath}.vue`]

    if (typeof page === 'undefined') {
        page = pages[`${componentPath}/index.vue`]

        if (typeof page === 'undefined') {
            throw new Error(`Page not found: ${componentPath}.vue or ${componentPath}/index.vue`)
        }
    }

    return typeof page === 'function' ? page() : page
}
