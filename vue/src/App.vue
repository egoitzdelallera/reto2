<template>
  <div class="app-container">
    <Sidebar v-if="showSidebar" />
    <main class="main-content p-0" :class="{ 'with-sidebar': showSidebar }">
      <router-view></router-view>
    </main>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { createRouter, createWebHistory, useRoute } from 'vue-router'

//import Login from './views/InicioSesionView.vue'
import incidencias from './views/IncidentesView.vue'
const routes = [

  { path: '/', component: incidencias },

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