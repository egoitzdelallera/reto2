<template>
  <div class="bg-primary min-vh-100 m-0">
    <!-- Header -->
    <header class="bg-primary shadow-sm py-3 mb-4 border-bottom">
      <div class="container">
        <div class="d-flex justify-content-start align-items-center">
          <button class="btn btn-link btn-sm p-0 text-info" style="text-decoration: none; width: auto;">
            <i class="bi bi-arrow-left me-2 btn btn-outline-info"></i>Atrás
          </button>
        </div>
      </div>
    </header>

    <div class="container">
      <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
             <UserCard :user="user" @open-edit-modal="openEditUserModal" />

        </div>
         <!-- Phases Sidebar -->
         <div class="col-lg-4">
          <UserCurrentIncidents :incidents="incidents"/>
        </div>
      </div>
    </div>
      <EditUserModal
          :isEditUserModalOpen="isEditUserModalOpen"
           :userToEdit="userToEdit"
            @close-edit-modal="closeEditUserModal"
            @submit-edit-form="submitEditForm"
         :editAlertMessage="editAlertMessage"
           :editAlertClass="editAlertClass"
       />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import UserCard from './UserCard.vue';
import UserCurrentIncidents from './UserCurrentIncidents.vue';
import EditUserModal from './EditUserModal.vue'


const user = ref({
  nombre: '',
    apellido:'',
  rol: '',
    specialization: '',
  correo: '',
    campus: '',
   estado: ''
});

const incidents = ref([]);
  //Modal states
  const isEditUserModalOpen = ref(false);
  const userToEdit = ref({});
      const editPassword = ref('');
    const editAlertMessage = ref(null);
  const editAlertClass = ref('');
const openEditUserModal = () => {
  isEditUserModalOpen.value = true;
   userToEdit.value = { ...user.value };
   editPassword.value = '';
  editAlertMessage.value = null;
  editAlertClass.value = '';

  };
  const closeEditUserModal = () => {
      isEditUserModalOpen.value = false;
      editAlertMessage.value = null;
       editAlertClass.value = '';
  };
  const submitEditForm = async (dataToUpdate) => {
      try {
      console.log("Data to update:", dataToUpdate)
       const token = localStorage.getItem('jwt_token');

      const headers = {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
      };
      const response = await axios.post(`http://localhost:8000/api/users/${userToEdit.value.id_usuario}`, dataToUpdate, {
            headers: headers
        });

      console.log("response from patch:",response.data);
       user.value = {...user.value, ...response.data.user};
       editAlertClass.value = 'alert-success';
        editAlertMessage.value = 'Usuario actualizado con éxito';
     } catch (error) {
      console.error('Error updating user:', error);
      console.error('Response Data (editUser):', error.response?.data);
      editAlertClass.value = 'alert-danger';
      editAlertMessage.value = 'Error al actualizar usuario';
      } finally {
        setTimeout(() => {
          editAlertMessage.value = null;
           editAlertClass.value = '';
             closeEditUserModal();
        }, 2000);
   }
}
onMounted(async () => {
  try{
    const token = localStorage.getItem('jwt_token');
      const response = await axios.get('http://localhost:8000/api/perfil', {
          headers: {
           Authorization: `Bearer ${token}`,
          },
      });
      console.log("API Response:",response.data)
       user.value = response.data;
       userToEdit.value = {...response.data};
       // Fetch incidents for the current technician
       const incidentsResponse = await axios.get('http://localhost:8000/api/incidenciasPerfil', {
          headers: {
               Authorization: `Bearer ${token}`,
         },
          params: {
             id_tecnico: response.data.id_usuario,
         }
     });
       console.log("Incidents Response:",incidentsResponse.data)
      incidents.value = incidentsResponse.data;
  }catch (error){
    console.error('Error fetching user data:', error);
  }
});

</script>
<style scoped>
</style>