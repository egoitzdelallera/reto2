<script setup>
import { RouterLink, RouterView } from 'vue-router'
import HelloWorld from './components/HelloWorld.vue'
</script>

<script>
import api from './services/api.js';

export default {
  name: 'App',
  data() {
    return {
      datos: [], // Almacenamos los datos de la base de datos
    };
  },
  mounted() {
    this.cargarDatos();
  },
  methods: {
    async cargarDatos() {
      try {
        const response = await api.obtenerDatos(); // Llamar al api
        this.datos = response.data; // Guardar los datos recibidos
      } catch (error) {
        console.error('Error al cargar los datos:', error);
      }
    },
  },
};
</script>

<template>
  <div id="app">
    <h1>Datos desde la base de datos</h1>
    <ul v-if="datos.length > 0">
      <li v-for="dato in datos" :key="dato.id">
        {{ dato.nombre }} - {{ dato.descripcion }}
      </li>
    </ul>
    <p v-else>Cargando datos...</p>
  </div>
</template>

<style scoped>
header {
  line-height: 1.5;
  max-height: 100vh;
}

.logo {
  display: block;
  margin: 0 auto 2rem;
}

nav {
  width: 100%;
  font-size: 12px;
  text-align: center;
  margin-top: 2rem;
}

nav a.router-link-exact-active {
  color: var(--color-text);
}

nav a.router-link-exact-active:hover {
  background-color: transparent;
}

nav a {
  display: inline-block;
  padding: 0 1rem;
  border-left: 1px solid var(--color-border);
}

nav a:first-of-type {
  border: 0;
}

@media (min-width: 1024px) {
  header {
    display: flex;
    place-items: center;
    padding-right: calc(var(--section-gap) / 2);
  }

  .logo {
    margin: 0 2rem 0 0;
  }

  header .wrapper {
    display: flex;
    place-items: flex-start;
    flex-wrap: wrap;
  }

  nav {
    text-align: left;
    margin-left: -1rem;
    font-size: 1rem;

    padding: 1rem 0;
    margin-top: 1rem;
  }
}
</style>
