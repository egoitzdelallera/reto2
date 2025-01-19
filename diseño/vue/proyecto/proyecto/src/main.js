import { createApp } from 'vue'
import App from './App.vue'
import { createRouter, createWebHistory } from 'vue-router'
import IncidentsTable from './views/IncidentesView.vue'
import Mantenimientos from './views/MantenimientosView.vue'
import Usuarios from './views/UsuarioView.vue'
import Perfil from './views/PerfilView.vue'
import iniciosesion from './views/InicioSesionView.vue'
import IncidenciaIndividual from './views/IncidenciaIndividualView.vue'
import Maquinas from './views/MaquinasView.vue'
import MaquinaIndividual from './views/MaquinaIndividualView.vue'

import './assets/css/global.css';

const routes = [
  { path: '/', component: iniciosesion },
  { path: '/mantenimientos', component: Mantenimientos },
  { path: '/incidencias', component: IncidentsTable },
  { path: '/usuarios', component: Usuarios},
  { path: '/perfil', component: Perfil},
  { path: '/IncidenciaIndividual/:id', name: 'IncidenciaIndividual', component: IncidenciaIndividual },
  { path: '/maquinas', component: Maquinas },
  { path: '/MaquinaIndividual/:id',name:'MaquinaIndividual', component: MaquinaIndividual },
 
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

const app = createApp(App)
app.use(router)
app.mount('#app')

