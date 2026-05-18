import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { fetchCurrentUser, getAuthToken, setAuthToken } from './services/auth'
import './styles/view-pages.css'
import { createVuetify } from 'vuetify'
import 'vuetify/styles'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'

const savedToken = getAuthToken()
if (savedToken) {
  setAuthToken(savedToken)
}

const vuetify = createVuetify({
  components,
  directives,
})

createApp(App)
  .use(router)
  .use(vuetify)
  .mount('#app')

fetchCurrentUser()

  