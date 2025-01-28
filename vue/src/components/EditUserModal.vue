<template>
    <div v-if="isEditUserModalOpen" class="modal-overlay">
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
                        <input type="text" id="editNombre" v-model="userToEdit.nombre" class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-3">
                        <label for="editApellido" class="form-label block text-sm font-medium text-gray-700">Apellido</label>
                        <input type="text" id="editApellido" v-model="userToEdit.apellido" class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-3">
                        <label for="editPassword" class="form-label block text-sm font-medium text-gray-700">Contraseña</label>
                       <input type="password" id="editPassword" v-model="editPassword" class="form-control mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                <div v-if="editAlertMessage" class="mt-4 text-center" :class="editAlertClass">
                    {{ editAlertMessage }}
                </div>
                </form>
            </div>
           <div class="modal-footer border-t pt-4 flex justify-end">
               <button type="button" @click="closeModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancelar</button>
               <button type="submit" @click="submitEditForm" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar Cambios</button>
            </div>
       </div>
    </div>
</template>
<script setup>
import { ref } from 'vue';
const props = defineProps({
    isEditUserModalOpen: Boolean,
     userToEdit: Object,
       editAlertMessage: String,
      editAlertClass: String
  });
 const emit = defineEmits(['close-edit-modal', 'submit-edit-form']);
    const editPassword = ref('');
   const closeModal = () => {
         emit('close-edit-modal');
    };

    const submitEditForm = () => {
          const dataToUpdate = {
            nombre: props.userToEdit.nombre,
             apellido: props.userToEdit.apellido,
              password: editPassword.value
          };
        emit('submit-edit-form',dataToUpdate);
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