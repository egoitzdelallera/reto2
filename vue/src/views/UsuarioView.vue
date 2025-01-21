<script setup>
import { useRoute } from 'vue-router';
import Usuario from '../components/UsuariosGestion.vue';
import SideBar from '../views/SideBar.vue';

const route = useRoute();
</script>

<template>
  <div>
    <SideBar />
    <Usuario :initialRole="route.query.role || 'Todos'" />
  </div>
</template>

