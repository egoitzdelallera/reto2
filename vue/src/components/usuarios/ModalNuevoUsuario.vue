<template>
  <div class="modal-overlay">
    <div class="modal-container bg-primary">
      <div class="modal-header border-b mb-4 pb-2">
        <h5 class="text-xl font-bold">Nuevo Usuario</h5>
        
      </div>
      <div class="modal-body">
        <form @submit.prevent="submitNewUserForm">
          <div class="mb-3">
            <label for="newNombre" class="form-label block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" id="newNombre" v-model="newUser.nombre"
              class="form-control mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
          </div>
          <div class="mb-3">
            <label for="newApellido" class="form-label block text-sm font-medium text-gray-700">Apellido</label>
            <input type="text" id="newApellido" v-model="newUser.apellido"
              class="form-control mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
          </div>
          <div class="mb-3">
            <label for="newCorreo" class="form-label block text-sm font-medium text-gray-700">Correo</label>
            <input type="email" id="newCorreo" v-model="newUser.correo"
              class="form-control mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
          </div>
          <div class="mb-3">
            <label for="newRol" class="form-label block text-sm font-medium text-gray-700">Rol</label>
            <select id="newRol" v-model="newUser.rol"
              class="form-select mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
              <option value="" disabled selected>Seleccione un rol</option>
              <option value="Administrador">Administrador</option>
              <option value="Tecnico">Técnico</option>
              <option value="Operario">Operario</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="newCampus" class="form-label block text-sm font-medium text-gray-700">Campus</label>
            <select id="newCampus"
                class="form-select mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                v-model="newUser.id_campus"
                >
                <option value="" disabled selected>Seleccione un campus</option>
                <option
                v-for="campusItem in campus"
                :key="campusItem.id_campus"
                :value="campusItem.id_campus"
                 >
                {{ campusItem.nombre }}
                 </option>
               </select>
          </div>
          <div class="mb-3">
            <label for="newPassword" class="form-label block text-sm font-medium text-gray-700">Contraseña</label>
            <input type="password" id="newPassword" v-model="newUser.password"
              class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
          </div>
          <div v-if="alertMessage" class="mt-4 text-center" :class="alertClass">
            {{ alertMessage }}
          </div>
        </form>
      </div>
      <div class="modal-footer border-t pt-4 flex justify-end">
        <button type="button" @click="closeModal"
          class="btn btn-secondary">Cancelar</button>
        <button type="submit" class="btn btn-primary" @click="submitNewUserForm">Crear Usuario</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import useCampus from '@/composables/useCampus';

const API_URL = 'http://localhost:8000/api/users';

const {
  campus,
  loading,
  error,
  fetchCampus
} = useCampus();

const emit = defineEmits(['cerrar-modal', 'usuario-creado']);
const newUser = ref({
    nombre: '',
      apellido: '',
    correo: '',
    rol: 'Operario',
    password: '',
    id_campus: null,
    estado: 'Habilitado' // Set default estado here
});
const alertMessage = ref(null);
const alertClass = ref('');

onMounted(async () => {
  await fetchCampus();
});


const closeModal = () => {
  resetForm()
  alertMessage.value = null;
  emit('cerrar-modal');
};
const resetForm = () => {
  newUser.value = {
    nombre: '',
    apellido: '',
    correo: '',
    rol: 'Operario',
    password: '',
    id_campus: null,
        estado: 'Habilitado' // Reset estado to 'habilitado'
  }
}

const submitNewUserForm = async () => {
  try {
      console.log('Data to send:', JSON.stringify(newUser.value, null, 2));
        const token = localStorage.getItem('jwt_token');
    if (!token) {
      console.error('No token found.');
      return
    }
    const headers = {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    };

    const response = await axios.post(`${API_URL}`, newUser.value, {
      headers: headers
    });

    console.log("Response from create user:", response)
    alertClass.value = 'bg-green-200 text-green-800 border-2 border-green-800 p-2 rounded';
    alertMessage.value = 'Usuario creado con éxito';
    setTimeout(() => {
      closeModal()
      emit('usuario-creado')
    }, 2000);
  } catch (error) {
       console.error('Error creating user:', error);
      console.log("Response Data:", error.response?.data);
    alertClass.value = 'bg-red-200 text-red-800 border-2 border-red-800 p-2 rounded';
    alertMessage.value = 'Error al crear usuario';
    setTimeout(() => {
      alertMessage.value = null;
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