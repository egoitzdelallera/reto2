<template>
    <nav v-if="totalPaginas > 1" class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: paginaActual === 1 }">
          <button class="page-link text-black" @click="cambiarPagina(paginaActual - 1)" aria-label="Previous">
            <span aria-hidden="true">«</span>
          </button>
        </li>
        <li v-for="pageNumber in totalPaginas" :key="pageNumber" class="page-item"
          :class="{ active: paginaActual === pageNumber }">
          <button class="page-link text-black" @click="cambiarPagina(pageNumber)">{{ pageNumber }}</button>
        </li>
        <li class="page-item" :class="{ disabled: paginaActual === totalPaginas }">
          <button class="page-link text-black" @click="cambiarPagina(paginaActual + 1)" aria-label="Next">
            <span aria-hidden="true">»</span>
          </button>
        </li>
      </ul>
    </nav>
  </template>
  
  <script setup>
  import { defineProps, defineEmits } from 'vue';
  const props = defineProps({
    totalPaginas: {
      type: Number,
      required: true
    },
    paginaActual: {
      type: Number,
      required: true
    }
  });
  const emit = defineEmits(['cambiar-pagina']);
  const cambiarPagina = (pagina) => {
      emit('cambiar-pagina', pagina);
  };
  </script>