<template>
    <div class="modal-overlay">
      <div class="modal-container bg-primary">
        <div class="modal-header border-b mb-4 pb-2">
          <h5 class="text-xl font-bold">Cambiar estado</h5>

        </div>
        <div class="modal-body">
          <p>¿Estás seguro de que quieres cambiar el estado de la máquina: <strong>{{ maquina.nombre }}</strong>?</p>
          <div v-if="alertMessage" class="alert" :class="alertClass">
              {{ alertMessage }}
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" @click="closeModal"
            class="btn btn-secondary">Cancelar</button>
          <button type="button" class="btn btn-primary" @click="toggleStatus">Cambiar estado</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { defineProps, defineEmits, ref } from 'vue';
  import axios from 'axios';
  const API_URL = 'http://localhost:8000/api/maquinas'
  const props = defineProps({
      maquina: {
          type: Object,
          required: true
      }
  });
  const emit = defineEmits(['cerrar-modal', 'estado-cambiado']);
  
  const alertMessage = ref(null);
  const alertClass = ref('');
  
  const closeModal = () => {
    emit('cerrar-modal');
      alertMessage.value = null;
    alertClass.value = '';
  };
  
  const toggleStatus = async () => {
      try {
          let token = localStorage.getItem('jwt_token');
  
          if (token === null || token === undefined || token === '') {
              console.error('Error: Token JWT is null, undefined, or empty.');
              alertClass.value = 'alert-danger';
              alertMessage.value = 'Error de autenticación. Token inválido';
              setTimeout(() => {
                alertMessage.value = null;
                alertClass.value = '';
                  closeModal();
              }, 2000);
              return; // Salir de la función si el token no es válido
          }
  
          const headers = {
              'Authorization': `Bearer ${token}`,
              'Content-Type': 'application/json'
          };
          const response = await axios.patch(`${API_URL}/${props.maquina.id_maquina}/toggle-status`, {}, {
              headers: headers
          });
          console.log("response toggle:", response.data);
           alertClass.value = 'bg-green-200 text-green-800 border-2 border-green-800 p-2 rounded';
          alertMessage.value = 'Estado de la maquina cambiado con exito';
          setTimeout(() => {
              closeModal();
              emit('estado-cambiado');
          }, 2000);
      } catch (error) {
          console.error('Error al cambiar estado de la maquina:', error);
           console.error('Response Data (toggleStatus):', error.response?.data);
          alertClass.value = 'bg-red-200 text-red-800 border-2 border-red-800 p-2 rounded';
            alertMessage.value = 'Error al cambiar el estado de la maquina';
           setTimeout(() => {
             alertMessage.value = null;
             alertClass.value = '';
             closeModal();
           }, 2000);
      }
  };
  </script>
  <style scoped>
  .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    /* Ensure modal is on top of other content */
  }
  
  .modal-container {
    background-color: white;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    max-width: 600px;
    padding: 1.5rem;
    position: relative;
    width: 90%;
  }
  </style>