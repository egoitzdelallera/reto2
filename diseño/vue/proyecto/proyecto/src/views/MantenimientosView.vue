
<script setup>
  // Función: Mostrar las cuentas del cliente identificado usando el componente MostrarCuentas.
  import mantenimientos from '../components/MantenimientosTabla.vue';
  import SideBar from '../views/SideBar.vue';
  import '../assets/css/Mantenimientos.css' // Asegúrate de que la ruta sea correcta

</script>
<template>
  <div>
    <SideBar> </SideBar>
    <mantenimientos> </mantenimientos>
  </div>
</template>