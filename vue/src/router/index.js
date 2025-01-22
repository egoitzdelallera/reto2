import { createRouter, createWebHistory } from 'vue-router';
import IncidentsTable from '../views/IncidentesView.vue';
import Usuarios from '../views/UsuarioView.vue';
import Perfil from '../views/PerfilView.vue';
import iniciosesion from '../views/InicioSesionView.vue';
import IncidenciaIndividual from '../views/IncidenciaIndividualView.vue';
import MaquinasView from '../views/MaquinasView.vue';



const routes = [
  { path: '/', component: iniciosesion },
  { path: '/incidencias', component: IncidentsTable, meta: { requiresAuth: true } },
  { path: '/usuarios', component: Usuarios, meta: { requiresAuth: true } },
  { path: '/maquinas', component: MaquinasView, meta: { requiresAuth: true } },
  { path: '/perfil', component: Perfil, meta: { requiresAuth: true } },
  { path: '/incidencias/:id', name: 'IncidenciaIndividual', component: IncidenciaIndividual, meta: { requiresAuth: true } },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Verificamos si el usuario esta autenticado
router.beforeEach((to, from, next) => {
  const isAuthenticated = localStorage.getItem('jwt_token');

  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isAuthenticated) {
       // Si no esta autenticado, ir al login
      next('/');
    } else {
      // Si esta autenticado, continuar
      next();
  }
  } else {
    next();  
  } 
});

export default router;
