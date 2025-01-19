<script setup>
  // Función: Mostrar las cuentas del cliente identificado usando el componente MostrarCuentas.
  import Incidentes from '../components/IncidentesTabla.vue';
  import SideBar from '../views/SideBar.vue';
  import carta from '../views/carta.vue';
  
</script>

<template>
  <div>
    <SideBar> </SideBar>
    <Incidentes> </Incidentes>
    <carta> </carta>
  </div>
</template>