<script setup>
  // Función: Mostrar las cuentas del cliente identificado usando el componente MostrarCuentas.
  import maquinas from '../components/MaquinasTabla.vue';
  import SideBar from '../views/SideBar.vue';
  import '../assets/css/Maquinas.css' /// Asegúrate de que la ruta sea correcta

</script>
<template>
  <div>
    <SideBar> </SideBar>
    <maquinas> </maquinas>
  </div>
</template>