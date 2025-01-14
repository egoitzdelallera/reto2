<template>
  <div class="app-container">
    <Sidebar v-if="showSidebar" />
    <main class="main-content" :class="{ 'with-sidebar': showSidebar }">
      <router-view></router-view>
    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { createRouter, createWebHistory, useRoute } from 'vue-router'
import Sidebar from './components/SideBar.vue'
import IncidentsTable from './components/IncidentesTabla.vue'
import Mantenimientos from './components/MantenimientosTabla.vue'
import Login from './components/InicioSesion.vue'

const routes = [
  { path: '/', component: IncidentsTable },
  { path: '/mantenimientos', component: Mantenimientos },
  { path: '/login', component: Login }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

const route = useRoute()
const showSidebar = computed(() => route.path !== '/login')
</script>

<style scoped>
.app-container {
  display: flex;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  padding: 20px;
}


</style>