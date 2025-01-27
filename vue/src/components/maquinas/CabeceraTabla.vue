<template>
    <div class="d-flex justify-content-between m-3 mb-6">
      <h1 class="text-2xl font-bold mx-5 px-2 mb-6">Máquinas</h1>
      <div class="d-flex justify-content-center w-25">
          <button class="btn btn-dark m-3" @click="abrirModal">
              <i class="bi bi-plus-circle me-2"></i>Nueva Máquina
          </button>
          <div class="input-group m-3">
               <span class="input-group-text bg-white border-end-0">
                <i class="bi bi-search"></i>
              </span>
              <input type="text" class="form-control border-start-0" placeholder="Buscar..." v-model="searchQuery"
                @input="handleSearch" />
            </div>
      </div>
    </div>
  </template>
  <script setup>
  import { ref } from 'vue';
  
  const searchQuery = ref('');
  const emit = defineEmits(['abrir-modal', 'buscar']);
  
  const abrirModal = () => {
    emit('abrir-modal');
  };
  const handleSearch = () =>{
    emit('buscar', searchQuery.value)
  }
  </script>