<template>
  <div class="modal-overlay">
    <div class="modal-container">
      <div class="modal-header border-b mb-4 pb-2">
        <h5 class="text-xl font-bold">Editar Usuario</h5>
        <button @click="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">
        <form @submit.prevent="submitEditForm">
          <div class="mb-3">
            <label for="editNombre" class="form-label block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" id="editNombre" v-model="userToEdit.nombre"
              class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
          </div>
          <div class="mb-3">
            <label for="editApellido" class="form-label block text-sm font-medium text-gray-700">Apellido</label>
            <input type="text" id="editApellido" v-model="userToEdit.apellido"
              class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
          </div>
          <div class="mb-3">
            <label for="editRol" class="form-label block text-sm font-medium text-gray-700">Rol</label>
            <select id="editRol" v-model="userToEdit.rol"
              class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
              <option value="Administrador">Administrador</option>
              <option value="Técnico">Técnico</option>
              <option value="Operario">Operario</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="editPassword" class="form-label block text-sm font-medium text-gray-700">Contraseña</label>
            <input type="password" id="editPassword" v-model="editPassword"
              class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
          </div>
          <div v-if="alertMessage" class="mt-4 text-center" :class="alertClass">
            {{ alertMessage }}
          </div>
        </form>
      </div>
      <div class="modal-footer border-t pt-4 flex justify-end">
        <button type="button" @click="closeModal"
          class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancelar</button>
        <button type="submit" class="btn btn-primary" @click="submitEditForm">Guardar Cambios</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
const API_URL = 'http://localhost:8000/api/users'

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
});
const emit = defineEmits(['cerrar-modal', 'usuario-editado']);
const userToEdit = ref({ ...props.user });
const editPassword = ref('');
const alertMessage = ref(null);
const alertClass = ref('');

const closeModal = () => {
  emit('cerrar-modal');
  alertMessage.value = null;
  alertClass.value = '';
};

const submitEditForm = async () => {
  try {
    const dataToUpdate = {
      nombre: userToEdit.value.nombre,
      apellido: userToEdit.value.apellido,
      rol: userToEdit.value.rol,
    };
    if (editPassword.value) {
      dataToUpdate.password = editPassword.value;
    }

    const token = localStorage.getItem('jwt_token');

    const headers = {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    };
    const response = await axios.patch(`${API_URL}/${userToEdit.value.id_usuario}`, dataToUpdate, {
      headers: headers
    });
    console.log("Response from edit user:", response);
    alertClass.value = 'bg-green-200 text-green-800 border-2 border-green-800 p-2 rounded';
    alertMessage.value = 'Usuario actualizado con éxito';
    setTimeout(() => {
      closeModal();
      emit('usuario-editado');
    }, 2000);
  } catch (error) {
    console.error('Error updating user:', error);
    console.log('Response Data (editUser):', error.response?.data);
    alertClass.value = 'bg-red-200 text-red-800 border-2 border-red-800 p-2 rounded';
    alertMessage.value = 'Error al actualizar usuario';
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