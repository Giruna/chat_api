import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

// Importing Vuetify CSS
import 'vuetify/styles'

// Create Vuetify
const vuetify = createVuetify({
  components,
  directives,
})

const app = createApp(App)

app.use(vuetify)

app.use(router)

app.mount('#app')
