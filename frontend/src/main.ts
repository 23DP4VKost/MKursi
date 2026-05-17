import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { fetchCurrentUser } from './services/auth'
import './styles/view-pages.css'
import { createVuetify } from 'vuetify'
import 'vuetify/styles'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'


const vuetify = createVuetify({
  components,
  directives,
})

createApp(App)
  .use(router)
  .use(vuetify)
  .mount('#app')

fetchCurrentUser()

  