<script setup>
  // Función: Mostrar las cuentas del cliente identificado usando el componente MostrarCuentas.
  import IncidenciaIndividual from '../components/IncidenciaIndividual.vue';
  import SideBar from '../views/SideBar.vue';
  import '../assets/css/IncidenciasIndividual.css' // Asegúrate de que la ruta sea correcta

</script>

<template>
  <div>
    <SideBar> </SideBar>
    <IncidenciaIndividual> </IncidenciaIndividual>
  </div>
</template>
<style ></style>