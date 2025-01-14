<script setup>
  // Función: Mostrar las cuentas del cliente identificado usando el componente MostrarCuentas.
  import Incidentes from '../components/IncidentesTabla.vue';
  import SideBar from '../views/SideBar.vue';
</script>

<template>
  <div>
    <SideBar> </SideBar>
    <Incidentes> </Incidentes>
  </div>
</template>