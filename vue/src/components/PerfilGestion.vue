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
            <!-- User Card -->
            <div class="card mb-4 bg-primary">
              <div class="card-body">
                  <div class="d-flex justify-content-between align-items-start mb-3">
                      <div>
                          <h2 class="h1 mb-0 ">{{ user.nombre }} {{ user.apellido }}</h2>
                           <div class="mt-2">
                              <span class="badge bg-secondary me-2">{{ user.rol }}</span>
                                <span class="badge bg-secondary">{{ user.specialization }}</span>
                           </div>
                      </div>
  
                        <span class="badge" :class="user.estado === 'Habilitado' ? 'bg-success' : 'bg-danger'">
                             {{ user.estado }}
                          </span>
                  </div>
  
                <div class="row g-3">
                  <div class="col-md-6">
                    <h6 class="text-muted ">Correo</h6>
                    <p>{{ user.correo }}</p>
                  </div>
                  <div class="col-md-6">
                    <h6 class="text-muted">Campus</h6>
                    <p>{{ user.campus.nombre }}</p>
                  </div>
                    <div class="col-12 d-flex justify-content-start">
                     <button class="btn btn-outline-info btn-sm"
                       @click="openEditUserModal()">
                        <i class="bi bi-pencil me-2"></i>Editar
                    </button>
                </div>
                </div>
              </div>
            </div>
  
              <div class="card bg-primary">
              <div class="card-body">
                <h3 class="card-title mb-4">Incidencias</h3>
                <div class="row g-3">
                  <div class="col-md-6">
                    <h6 class="text-muted ">Creadas</h6>
                    <p>-</p>
                  </div>
                  <div class="col-md-6">
                     <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-muted">Intervenciones</h6>
                            <p>87</p>
                        </div>
                          <div>
                            <h6 class="text-muted">Resueltas</h6>
                           <p>71</p>
                        </div>
                      </div>
                  </div>
              </div>
              </div>
            </div>
          </div>
  
           <!-- Phases Sidebar -->
          <div class="col-lg-4">
            <div class="card bg-primary">
              <div class="card-body">
                  <h3 class="card-title mb-4">Incidencias en curso</h3>
                <div v-for="(incident, index) in incidents" :key="index" class="mb-4">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                     <h5 class="mb-0">{{incident.nombre}}</h5>
                  </div>
                  <div class="d-flex justify-content-between">
                        <p class="text-muted small mb-2">Máquina: {{incident.maquina_nombre}}</p>
                       <span :class="['badge', getPriorityBadgeClass(incident.prioridad)]">
                        {{ incident.prioridad }}
                       </span>
                   </div>
                </div>
              </div>
            </div>
          </div>
        </div>
            <!-- Modal para Editar usuario -->
       <div v-if="isEditUserModalOpen" class="modal-overlay">
            <div class="modal-container">
                <div class="modal-header border-b mb-4 pb-2">
                    <h5 class="text-xl font-bold">Editar Usuario</h5>
                    <button @click="closeEditUserModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-800">
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
                     <button type="button" @click="closeEditUserModal" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded mr-2">Cancelar</button>
                     <button type="submit" @click="submitEditForm" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar Cambios</button>
                 </div>
            </div>
        </div>
      </div>
    </div>
</template>
  
  <script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
  
  
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
  const getPriorityBadgeClass = (priority) => {
      switch(priority){
          case 'Alta':
             return 'bg-danger';
          case 'Media':
             return 'bg-warning text-dark';
          case 'Baja':
             return 'bg-info';
          default:
            return 'bg-secondary';
      }
  }
 const openEditUserModal = () => {
        console.log("openEditUserModal called");
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
      const submitEditForm = async () => {
        try {
          const dataToUpdate = {
            nombre: userToEdit.value.nombre,
             apellido: userToEdit.value.apellido,
            password: editPassword.value
        };
        console.log("Data to update:", dataToUpdate)
        const token = localStorage.getItem('jwt_token');

        const headers = {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
        };
         console.log("url for patch: " , `http://localhost:8000/api/users/${userToEdit.value.id_usuario}`);
      const response = await axios.patch(`http://localhost:8000/api/users/${userToEdit.value.id_usuario}`, dataToUpdate, {
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
.table th {
  white-space: nowrap;
}

.table td {
  vertical-align: middle;
}
/* Animación para nuevos elementos */
.table tr {
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
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
    z-index: 1000; /* Ensure modal is on top of other content */
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