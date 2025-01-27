<template>
    <div class="modal-overlay">
      <div class="modal-container">
        <div class="modal-header border-b mb-4 pb-2">
          <h5 class="text-xl font-bold">Editar Maquina</h5>
          <button @click="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submitEditMaquina">
            <div class="mb-3">
              <label for="editNombre" class="form-label block text-sm font-medium text-gray-700">Nombre</label>
              <input type="text" id="editNombre" v-model="maquinaToEdit.nombre"
                class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
            </div>
            <div class="mb-3">
              <label for="editDescripcion" class="form-label block text-sm font-medium text-gray-700">Descripción</label>
              <textarea id="editDescripcion" v-model="maquinaToEdit.descripcion"
                class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
            </div>
            <div class="mb-3">
              <label for="editTaller" class="form-label block text-sm font-medium text-gray-700">Taller</label>
              <select id="editTaller" v-model="maquinaToEdit.id_taller"
                class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option v-for="taller in talleres" :key="taller.id_taller" :value="taller.id_taller">
                  {{ taller.nombre }}
                </option>
              </select>
            </div>
            <div class="mb-3">
              <label for="editPrioridad" class="form-label block text-sm font-medium text-gray-700">Prioridad</label>
              <select id="editPrioridad" v-model="maquinaToEdit.prioridad"
                class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="Alta">Alta</option>
                <option value="Media">Media</option>
                <option value="Baja">Baja</option>
              </select>
            </div>
            <div v-if="alertMessage" class="mt-4 text-center" :class="alertClass">
              {{ alertMessage }}
            </div>
          </form>
        </div>
        <div class="modal-footer border-t pt-4 flex justify-end">
          <button type="button" @click="closeModal"
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancelar</button>
          <button type="submit" class="btn btn-primary" @click="submitEditMaquina">Guardar Cambios</button>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import useMaquinas from '@/composables/useMaquinas';
  import axios from 'axios'
  const API_URL = 'http://localhost:8000/api/maquinas'
  const {  } = useMaquinas();
  const props = defineProps({
    maquina: {
      type: Object,
      required: true
    },
     talleres: {
      type: Array,
      required: true
     }
  });
  
  const emit = defineEmits(['cerrar-modal', 'maquina-editada']);
  const maquinaToEdit = ref({ ...props.maquina });
  const alertMessage = ref(null);
  const alertClass = ref('');
  const closeModal = () => {
      alertMessage.value = null;
      alertClass.value = '';
    emit('cerrar-modal');
  };
  
  const submitEditMaquina = async () => {
      try {
          const token = localStorage.getItem('jwt_token');
          const headers = {
              'Authorization': `Bearer ${token}`,
              'Content-Type': 'application/json'
          };
          const dataToUpdate = {
            nombre: maquinaToEdit.value.nombre,
            descripcion: maquinaToEdit.value.descripcion,
            id_taller: maquinaToEdit.value.id_taller,
            prioridad: maquinaToEdit.value.prioridad,
          };
        const response = await axios.patch(`${API_URL}/${maquinaToEdit.value.id_maquina}`, dataToUpdate, {
          headers: headers
        });
        console.log("response from patch:", response.data);
        alertClass.value = 'bg-green-200 text-green-800 border-2 border-green-800 p-2 rounded'
          alertMessage.value = 'Maquina actualizada con éxito';
        setTimeout(() => {
            closeModal();
            emit('maquina-editada');
        }, 2000);
      } catch (error) {
           console.error('Error al actualizar la máquina:', error);
           console.error('Response Data (submitEditMaquina):', error.response?.data);
          alertClass.value = 'bg-red-200 text-red-800 border-2 border-red-800 p-2 rounded'
          alertMessage.value = 'Error al actualizar la máquina';
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