<script setup>
  // Función: Mostrar las cuentas del cliente identificado usando el componente MostrarCuentas.
  import Incidentes from '../components/incidencias/IncidentesTabla.vue';
  import SideBar from '../views/SideBar.vue';
  import '../assets/css/Incidencias.css' // Asegúrate de que la ruta sea correcta

</script>

<template>
  <div>
    <SideBar> </SideBar>
    <Incidentes> </Incidentes>
  </div>
</template>