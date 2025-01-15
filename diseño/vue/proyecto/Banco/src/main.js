import { createApp } from 'vue'
import App from './App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import IncidentsTable from './views/IncidentesView.vue'
import Mantenimientos from './views/MantenimientosView.vue'
import Usuarios from './views/UsuarioView.vue'
import Perfil from './views/PerfilView.vue'
import iniciosesion from './views/InicioSesionView.vue'

const routes = [
  { path: '/', component: iniciosesion },
  { path: '/mantenimientos', component: Mantenimientos },
  { path: '/incidencias', component: IncidentsTable },
  { path: '/usuarios', component: Usuarios},
  { path: '/perfil', component: Perfil},

 
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

const app = createApp(App)
app.use(router)
app.mount('#app')

