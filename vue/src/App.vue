<template>
  <div id="app">
    <h1>Listado de Máquinas</h1>
    
    <!-- Mostrar lista de máquinas -->
    <ul>
      <li v-for="maquina in maquinas" :key="maquina.id">
        <strong>{{ maquina.nombre }}</strong> - {{ maquina.modelo }}
      </li>
    </ul>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'App',
  
  data() {
    return {
      maquinas: [], // Aquí almacenaremos las máquinas obtenidas desde el backend
    };
  },
  
  mounted() {
    // Llamada a la API de Laravel para obtener las máquinas
    axios.get('http://localhost:8000/api/maquinas') // Ajusta la URL si es necesario
      .then(response => {
        this.maquinas = response.data; // Asignar los datos de las máquinas a la propiedad 'maquinas'
      })
      .catch(error => {
        console.error('Error al obtener las máquinas:', error);
      });
  }
};
</script>

<style>
/* Aquí puedes agregar estilos */
</style>
