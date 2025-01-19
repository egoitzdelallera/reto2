<script setup>
  // Función: Mostrar las cuentas del cliente identificado usando el componente MostrarCuentas.
  import maquinas from '../components/MaquinasTabla.vue';
  import SideBar from '../views/SideBar.vue';

</script>
<template>
  <div>
    <SideBar> </SideBar>
    <maquinas> </maquinas>
  </div>
</template>