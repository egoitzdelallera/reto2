<template>
  <div class="container-fluid">
    <!-- Header -->
    <div class="row text-white py-3 mb-4">
      <div class="col">
        <h2 class="mb-0">Lista de Usuarios: {{ selectedRole }}</h2>
      </div>
    </div>

    <!-- Controls -->
    <div class="container mb-4">
      <div class="row align-items-center">
        <div class="col-md-3">
          <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#newUserModal">
            <i class="bi bi-plus-circle me-2"></i>Nuevo Usuario
          </button>
        </div>
       
          <div class="d-flex gap-2 mt-3">
            <input 
              type="text" 
              class="form-control" 
              placeholder="Buscar usuarios..." 
              v-model="searchQuery"
              @input="handleSearch"
            >
             <div class="col-md-9">
          <div class="btn-group" role="group" aria-label="Filtrar por rol">
            <button @click="changeRole('Todos')" class="btn" :class="selectedRole === 'Todos' ? 'btn-primary' : 'btn-outline-primary'">Todos</button>
            <button @click="changeRole('Administrador')" class="btn" :class="selectedRole === 'Administrador' ? 'btn-primary' : 'btn-outline-primary'">Administradores</button>
            <button @click="changeRole('Técnico')" class="btn" :class="selectedRole === 'Técnico' ? 'btn-primary' : 'btn-outline-primary'">Técnicos</button>
            <button @click="changeRole('Operario')" class="btn" :class="selectedRole === 'Operario' ? 'btn-primary' : 'btn-outline-primary'">Operarios</button>
          </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="container">
      <div class="table-responsive">
        <table class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>FOTO</th>
              <th>NOMBRE</th>
              <th>APELLIDO</th>
              <th>CORREO</th>
              <th>CAMPUS</th>
              <th>ROL</th>
              
              <th>ESTADO</th>
              <th>ACCIONES</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in displayedUsers" :key="user.id_usuario">
              <td>
                <i class="bi bi-person-circle fs-2"></i>
                
              </td>
              <td>{{ user.nombre }}</td>
              <td>{{ user.apellido }}</td>
              <td>{{ user.correo }}</td>
              <td>{{ user.campus.nombre }}</td>
              <td>
                <span class="badge" :class="getRoleBadgeClass(user.rol)">
                  {{ user.rol }}
                </span>
              </td>
              
              <td>
                <span class="badge" :class="user.estado === 'Habilitado' || user.estado === true ? 'bg-success' : 'bg-danger'">
                   {{ user.estado === 'Habilitado' || user.estado === true ? 'Habilitado' : 'Deshabilitado' }}
                </span>
              </td>
              <td>
                <div class="btn-group">
                  <button 
                    class="btn btn-sm" 
                    :class="user.estado ? 'btn-outline-danger' : 'btn-outline-success'"
                    @click="confirmDisableUser(user)"
                  >
                    <i class="bi" :class="user.estado ? 'bi-person-x' : 'bi-person-check'"></i>
                  </button>
                  <button 
                    class="btn btn-sm btn-outline-primary"
                    @click="editUser(user)"
                  >
                    <i class="bi bi-pencil"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Loading spinner -->
      <div v-if="isLoading" class="text-center my-4">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
      </div>

      <!-- Pagination -->
      <nav v-if="totalPages > 1" class="mt-4">
        <ul class="pagination justify-content-center">
          <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <button class="page-link" @click="changePage(currentPage - 1)" aria-label="Previous">
              <span aria-hidden="true">«</span>
            </button>
          </li>
          <li v-for="pageNumber in totalPages" :key="pageNumber" class="page-item" :class="{ active: currentPage === pageNumber }">
            <button class="page-link" @click="changePage(pageNumber)">{{ pageNumber }}</button>
          </li>
          <li class="page-item" :class="{ disabled: currentPage === totalPages }">
            <button class="page-link" @click="changePage(currentPage + 1)" aria-label="Next">
              <span aria-hidden="true">»</span>
            </button>
          </li>
        </ul>
      </nav>
    </div>

      <!-- Modal para Confirmar deshabilitar usuario -->
      <div class="modal fade" id="disableUserModal" tabindex="-1" aria-labelledby="disableUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="disableUserModalLabel">Cambiar estado</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>¿Estás seguro de que quieres cambiar el estado del usuario: <strong>{{ userToDisable?.nombre }} {{ userToDisable?.apellido }}</strong>?</p>
              <div v-if="disableAlertMessage" class="alert" :class="disableAlertClass">
               {{ disableAlertMessage }}
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-danger" @click="disableUser">Cambiar estado</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Modal para Editar usuario -->
      <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="submitEditForm">
                  <div class="mb-3">
                      <label for="editNombre" class="form-label">Nombre</label>
                      <input type="text" class="form-control" id="editNombre" v-model="userToEdit.nombre">
                  </div>
                  <div class="mb-3">
                      <label for="editApellido" class="form-label">Apellido</label>
                      <input type="text" class="form-control" id="editApellido" v-model="userToEdit.apellido">
                  </div>
                  <div class="mb-3">
                      <label for="editCorreo" class="form-label">Correo</label>
                      <input type="email" class="form-control" id="editCorreo" v-model="userToEdit.correo">
                  </div>
                  <div class="mb-3">
                     <label for="editRol" class="form-label">Rol</label>
                    <select class="form-select" id="editRol" v-model="userToEdit.rol">
                        <option value="Administrador">Administrador</option>
                        <option value="Técnico">Técnico</option>
                        <option value="Operario">Operario</option>
                    </select>
                  </div>
                  <div class="mb-3">
                      <label for="editPassword" class="form-label">Contraseña</label>
                      <input type="password" class="form-control" id="editPassword" v-model="editPassword">
                  </div>
                  <div v-if="editAlertMessage" class="alert" :class="editAlertClass">
                    {{ editAlertMessage }}
                  </div>
                  
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary" @click="submitEditForm">Guardar Cambios</button>
            </div>
          </div>
        </div>
      </div>
            <!-- Modal para nuevo usuario -->
      <div class="modal fade" id="newUserModal" tabindex="-1" aria-labelledby="newUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="newUserModalLabel">Nuevo Usuario</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="submitNewUserForm">
                  <div class="mb-3">
                      <label for="newNombre" class="form-label">Nombre</label>
                      <input type="text" class="form-control" id="newNombre" v-model="newUser.nombre">
                  </div>
                  <div class="mb-3">
                      <label for="newApellido" class="form-label">Apellido</label>
                      <input type="text" class="form-control" id="newApellido" v-model="newUser.apellido">
                  </div>
                  <div class="mb-3">
                      <label for="newCorreo" class="form-label">Correo</label>
                      <input type="email" class="form-control" id="newCorreo" v-model="newUser.correo">
                  </div>
                  <div class="mb-3">
                     <label for="newRol" class="form-label">Rol</label>
                    <select class="form-select" id="newRol" v-model="newUser.rol">
                        <option value="Administrador">Administrador</option>
                        <option value="Técnico">Técnico</option>
                        <option value="Operario">Operario</option>
                    </select>
                  </div>
                  <div class="mb-3">
                      <label for="newCampus" class="form-label">Campus</label>
                      <select class="form-select" id="newCampus" v-model="newUser.id_campus">
                        <option v-for="campusItem in campus" :key="campusItem.id_campus" :value="campusItem.id_campus">
                            {{ campusItem.nombre }}
                          </option>
                    </select>
                  </div>
                  <div class="mb-3">
                      <label for="newPassword" class="form-label">Contraseña</label>
                      <input type="password" class="form-control" id="newPassword" v-model="newUser.password">
                  </div>
                  <div v-if="newUserAlertMessage" class="alert" :class="newUserAlertClass">
                    {{ newUserAlertMessage }}
                  </div>
                  
                </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary" @click="submitNewUserForm">Crear Usuario</button>
            </div>
          </div>
        </div>
      </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import axios from 'axios'
import { Modal } from 'bootstrap'; // Importa Modal de bootstrap

const props = defineProps({
  initialRole: {
    type: String,
    default: 'Todos'
  }
})

// Estado
const users = ref([])
const displayedUsers = ref([])
const currentPage = ref(1)
const isLoading = ref(false)
const searchQuery = ref('')
// Estados modales
const userToDisable = ref(null);
const userToEdit = ref({});
const editPassword = ref('');
const newUser = ref({
    nombre: '',
     apellido:'',
    correo: '',
    rol: 'Operario',
    password: '',
     id_campus: null,
});
const campus = ref([])
// Estados alertas
const disableAlertMessage = ref(null);
const disableAlertClass = ref('');
const editAlertMessage = ref(null);
const editAlertClass = ref('');
const newUserAlertMessage = ref(null);
const newUserAlertClass = ref('');

// Nueva propiedad reactiva para el rol seleccionado
const selectedRole = ref(props.initialRole)

// Configuración
const PAGE_SIZE = 10
const API_URL = 'http://localhost:8000/api/users'

// Calcular total de páginas
const totalPages = computed(() => {
  return Math.ceil(users.value.length / PAGE_SIZE);
});

// Obtener usuarios
const fetchUsers = async () => {
  if (isLoading.value) return;

  isLoading.value = true;
  try {
    const response = await axios.get(`${API_URL}`, {
      params: {
        page: 1, // siempre cargar la primera página de la API, la paginación se maneja en el cliente
        limit: 1000,  // Carga muchos datos para la paginación
        search: searchQuery.value,
        role: selectedRole.value !== 'Todos' ? selectedRole.value : undefined
      },
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`
      }
    });

    // Filtrar usuarios por rol si es necesario
    const filteredUsers = selectedRole.value !== 'Todos'
      ? response.data.filter(user => user.rol === selectedRole.value)
      : response.data;

    // Actualizar usuarios
    users.value = filteredUsers;
    updateDisplayedUsers();

  } catch (error) {
    console.error('Error fetching users:', error);
  } finally {
    isLoading.value = false;
  }
};

const updateDisplayedUsers = () => {
  const start = (currentPage.value - 1) * PAGE_SIZE;
  const end = start + PAGE_SIZE;
  displayedUsers.value = users.value.slice(start, end);
};
// Búsqueda de usuarios
const handleSearch = () => {
  currentPage.value = 1;
  fetchUsers();
};

const changePage = (page) => {
  currentPage.value = page;
  updateDisplayedUsers();
}


// Confirmar deshabilitar usuario
const confirmDisableUser = (user) => {
    userToDisable.value = user;
    const modal = new Modal(document.getElementById('disableUserModal'));
    modal.show();
};
  
// Deshabilitar usuario
const disableUser = async () => {
  try {
    let token = localStorage.getItem('jwt_token');
    console.log('Token JWT (disableUser - raw):', token);

        if (token === null || token === undefined || token === '') {
            console.error('Error: Token JWT is null, undefined, or empty.');
             disableAlertClass.value = 'alert-danger';
             disableAlertMessage.value = 'Error de autenticación. Token inválido';
              setTimeout(() => {
                disableAlertMessage.value = null;
                disableAlertClass.value = '';
                const modal = Modal.getInstance(document.getElementById('disableUserModal'));
                modal.hide();
                }, 2000);

            return; // Salir de la función si el token no es válido
        }


    const parts = token.split('.');
    console.log('Token JWT (disableUser - segments):', parts);

    const headers = {
      'Authorization': `Bearer ${token}`,
      'Content-Type': 'application/json'
    };
    console.log('Headers (disableUser):', headers);
    console.log('URL (disableUser):',`${API_URL}/${userToDisable.value.id_usuario}/toggle-status`)


    await axios.patch(`${API_URL}/${userToDisable.value.id_usuario}/toggle-status`, {}, {
      headers: headers
    });

    userToDisable.value.estado = !userToDisable.value.estado;
    
    const userIndex = users.value.findIndex(user => user.id_usuario === userToDisable.value.id_usuario);
    if (userIndex !== -1) {
      users.value[userIndex].estado = userToDisable.value.estado;
       updateDisplayedUsers();
    }

    disableAlertClass.value = 'alert-success';
    disableAlertMessage.value = 'Usuario deshabilitado con éxito';

  } catch (error) {
    console.error('Error toggling user status:', error);
    console.error('Response Data (disableUser):', error.response?.data);
    disableAlertClass.value = 'alert-danger';
    disableAlertMessage.value = 'Error al deshabilitar usuario';
  } finally {
    setTimeout(() => {
      disableAlertMessage.value = null;
      disableAlertClass.value = '';
      const modal = Modal.getInstance(document.getElementById('disableUserModal'));
      modal.hide();

    }, 2000);
  }
};
// Editar usuario
const editUser = (user) => {
    userToEdit.value = { ...user };
    editPassword.value = ''; // Resetear la contraseña al abrir el modal
    editAlertMessage.value = null; // Resetear mensaje de alerta
    editAlertClass.value = '';
    const modal = new Modal(document.getElementById('editUserModal'));
    modal.show();
}

// Función para enviar formulario de edición
// Función para enviar formulario de edición
// Función para enviar formulario de edición
const submitEditForm = async () => {
    try {
        const dataToUpdate = {
            nombre: userToEdit.value.nombre,
             apellido: userToEdit.value.apellido,
            correo: userToEdit.value.correo,
            rol: userToEdit.value.rol,
        };

        if (editPassword.value) {
          dataToUpdate.password = editPassword.value;
        }
      
        console.log('Valor del Rol (editUser):', JSON.stringify(dataToUpdate.rol));

        const token = localStorage.getItem('jwt_token');
        console.log('Token JWT:', token);

        const headers = {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json'
        };
        console.log('Headers:', headers);

        const response = await axios.post(`${API_URL}/${userToEdit.value.id_usuario}`, dataToUpdate, {
            headers: headers
        });

        const userIndex = users.value.findIndex(user => user.id_usuario === userToEdit.value.id_usuario);
        if (userIndex !== -1) {
            users.value[userIndex] = { ...userToEdit.value, ...response.data.user};
            updateDisplayedUsers();
        }

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
            const modal = Modal.getInstance(document.getElementById('editUserModal'));
            modal.hide();
        }, 2000);
    }
};
// Función para enviar formulario de nuevo usuario
const submitNewUserForm = async () => {
  try {
      console.log('Datos del nuevo usuario:', newUser.value);
       console.log('Valor del Rol (newUser):', JSON.stringify(newUser.value.rol));
       const response = await axios.post(`${API_URL}`, newUser.value, {
         headers: {
          'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`
          }
        });
      
        // Añadir nuevo usuario a la lista de usuarios
        users.value.push(response.data.user);
        updateDisplayedUsers(); // Actualizar la lista de usuarios mostrados

      // Limpiar el formulario
      newUser.value = {
        nombre: '',
        apellido: '',
        correo: '',
        rol: '',
        password: '',
        id_campus: '',
      };

        newUserAlertClass.value = 'alert-success';
        newUserAlertMessage.value = 'Usuario creado con éxito';

     } catch (error) {
      console.error('Error creating user:', error);
      console.error('Response Data (newUser):', error.response?.data);
        newUserAlertClass.value = 'alert-danger';
        newUserAlertMessage.value = 'Error al crear usuario';
    } finally{
      setTimeout(() => {
          newUserAlertMessage.value = null;
          newUserAlertClass.value = '';
          const modal = Modal.getInstance(document.getElementById('newUserModal'));
           modal.hide();

       }, 2000);
    }
};
 // Obtener campus
 // Obtener campus
const fetchCampus = async () => {
   try {
      const response = await axios.get(`${API_URL.replace('/users', '').concat('/campus')}`,{
           headers: {
                'Authorization': `Bearer ${localStorage.getItem('jwt_token')}`
           }
        });
      campus.value = response.data
    } catch (error) {
      console.error('Error fetching campus:', error);
    }
};

// Obtener clase para el badge del rol
const getRoleBadgeClass = (rol) => {
  const classes = {
    'Administrador': 'bg-danger',
    'Técnico': 'bg-primary',
    'Operario': 'bg-warning text-dark'
  }
  return classes[rol] || 'bg-secondary'
}

// Nueva función para cambiar el rol seleccionado
const changeRole = (role) => {
  selectedRole.value = role;
  currentPage.value = 1;
  fetchUsers();
}

// Lifecycle hooks
onMounted(() => {
  fetchUsers()
  fetchCampus()
})
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
</style>