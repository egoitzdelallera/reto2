/*
HomeView es la vista por defecto y muestra información sobre los productos de la entidad bancaria.
ConsultasView es la vista que muestra todas las cuentas de un cliente. Al hacer click en una se verán sus movimientos.
TransaccionesView es la vista que pide un número de cuenta y una cantidad a transferir.
*/

import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/consultas',
      name: 'consultas',
      component: () => import('../views/ConsultasView.vue')
    },
    {
      path: '/transacciones',
      name: 'transacciones',
      component: () => import('../views/TransaccionesView.vue')
    }
  ]
})

export default router
