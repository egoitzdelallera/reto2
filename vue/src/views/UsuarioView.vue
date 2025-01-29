<script setup>
  import UsuariosTabla from '../components/usuarios/UsuariosTabla.vue';
  import SideBar from '../views/SideBar.vue';
  import { useRoute } from 'vue-router';

const route = useRoute();
</script>

<template>
  <div>
    <SideBar />
    <UsuariosTabla :initialRole="route.query.role || 'Todos'" />
  </div>
</template>