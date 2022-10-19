require('./bootstrap')

import { createApp } from 'vue'
import MainPage from './components/MainPage/index'

const app = createApp({})

app.component('Main Page', MainPage)

app.mount('#app')