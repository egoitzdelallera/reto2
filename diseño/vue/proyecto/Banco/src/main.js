import { createApp } from 'vue'
import App from './App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import IncidentsTable from './components/IncidentesTabla.vue'
import Mantenimientos from './components/MantenimientosTabla.vue'
import Usuarios from './components/UsuariosGestion.vue'
import Perfil from './components/PerfilGestion.vue'
import iniciosesion from './components/InicioSesion.vue'

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

