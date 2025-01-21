<template>
  <div class="container-fluid">
    <!-- Header -->
    <div class="row text-white py-3 mb-4">
      <div class="col">
        <h2 class="mb-0">Lista Usuarios</h2>
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
        <div class="col-md-9">
          <div class="d-flex gap-2">
            <input 
              type="text" 
              class="form-control" 
              placeholder="Buscar usuarios..." 
              v-model="searchQuery"
              @input="handleSearch"
            >
            <button class="btn btn-outline-secondary">
              <i class="bi bi-funnel me-2"></i>Filtrar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="container" ref="tableContainer">
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
              <th>ESPECIALIZACIÓN</th>
              <th>ESTADO</th>
              <th>ACCIONES</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in displayedUsers" :key="user.id_usuario">
              <td>
                <img 
                  :src="user.imagen_perfil || '/default-avatar.png'" 
                  class="rounded-circle"
                  width="40" 
                  height="40"
                  alt="Profile"
                >
              </td>
              <td>{{ user.nombre }}</td>
              <td>{{ user.apellido }}</td>
              <td>{{ user.correo }}</td>
              <td>{{ user.campus }}</td>
              <td>
                <span class="badge" :class="getRoleBadgeClass(user.rol)">
                  {{ user.rol }}
                </span>
              </td>
              <td>{{ user.especializacion || '-' }}</td>
              <td>
                <span class="badge" :class="user.estado ? 'bg-success' : 'bg-danger'">
                  {{ user.estado ? 'Habilitado' : 'Deshabilitado' }}
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

      <!-- Hidden load more button -->
      <button 
        ref="loadMoreTrigger"
        class="invisible"
        @click="loadMore"
      >
        Load More
      </button>
    </div>

      <!-- Modal para Confirmar deshabilitar usuario -->
      <div class="modal fade" id="disableUserModal" tabindex="-1" aria-labelledby="disableUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="disableUserModalLabel">Confirmar Deshabilitación</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>¿Estás seguro de que quieres deshabilitar al usuario: <strong>{{ userToDisable?.nombre }} {{ userToDisable?.apellido }}</strong>?</p>
              <div v-if="disableAlertMessage" class="alert" :class="disableAlertClass">
               {{ disableAlertMessage }}
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="button" class="btn btn-danger" @click="disableUser">Deshabilitar Usuario</button>
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
                        <option value="Admin">Admin</option>
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

  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import { useIntersectionObserver } from '@vueuse/core'
import { Modal } from 'bootstrap'; // Importa Modal de bootstrap

// Estado
const users = ref([])
const displayedUsers = ref([])
const page = ref(1)
const isLoading = ref(false)
const searchQuery = ref('')
const loadMoreTrigger = ref(null)
const tableContainer = ref(null)
// Estados modales
const userToDisable = ref(null);
const userToEdit = ref({});
const editPassword = ref('');

// Estados alertas
const disableAlertMessage = ref(null);
const disableAlertClass = ref('');
const editAlertMessage = ref(null);
const editAlertClass = ref('');

// Configuración
const PAGE_SIZE = 20
const API_URL = 'http://localhost:8000/api/users'

// Obtener usuarios
const fetchUsers = async () => {
  if (isLoading.value) return

  isLoading.value = true
  try {
    const response = await axios.get(`${API_URL}`, {
      params: {
        page: page.value,
        limit: PAGE_SIZE,
        search: searchQuery.value
      },
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    })
    
    // Añadir nuevos usuarios al array existente
    users.value = [...users.value, ...response.data]
    displayedUsers.value = users.value
    page.value++
  } catch (error) {
    console.error('Error fetching users:', error)
  } finally {
    isLoading.value = false
  }
}

// Configurar Intersection Observer para infinite scroll
const { stop } = useIntersectionObserver(
  loadMoreTrigger,
  ([{ isIntersecting }]) => {
    if (isIntersecting) {
      loadMore()
    }
  },
  { threshold: 0.5 }
)

// Cargar más usuarios
const loadMore = () => {
  fetchUsers()
}

// Búsqueda de usuarios
const handleSearch = () => {
  // Resetear la paginación
  page.value = 1
  users.value = []
  
  // Recargar usuarios con el término de búsqueda
  fetchUsers()
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
    await axios.patch(`${API_URL}/${userToDisable.value.id_usuario}/toggle-status`, {}, {
      headers: {
        'Authorization': `Bearer ${localStorage.getItem('token')}`
      }
    });
    userToDisable.value.estado = !userToDisable.value.estado;

    // Actualizar usuario en array de usuarios
    const userIndex = users.value.findIndex(user => user.id_usuario === userToDisable.value.id_usuario);
    if (userIndex !== -1) {
      users.value[userIndex].estado = userToDisable.value.estado;
      displayedUsers.value = [...users.value]; // Actualiza displayedUsers
    }
    
    disableAlertClass.value = 'alert-success';
    disableAlertMessage.value = 'Usuario deshabilitado con éxito';
  } catch (error) {
    console.error('Error toggling user status:', error);
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
        
        const response = await axios.patch(`${API_URL}/${userToEdit.value.id_usuario}`, dataToUpdate, {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        });
        
          // Actualizar el usuario en la lista users y displayedUsers
        const userIndex = users.value.findIndex(user => user.id_usuario === userToEdit.value.id_usuario);
        if (userIndex !== -1) {
            users.value[userIndex] = { ...userToEdit.value, ...response.data.user};
            displayedUsers.value = [...users.value];
        }
        
        editAlertClass.value = 'alert-success';
        editAlertMessage.value = 'Usuario actualizado con éxito';
    } catch (error) {
      console.error('Error updating user:', error);
      editAlertClass.value = 'alert-danger';
      editAlertMessage.value = 'Error al actualizar usuario';
    } finally{
        setTimeout(() => {
          editAlertMessage.value = null;
          editAlertClass.value = '';
          const modal = Modal.getInstance(document.getElementById('editUserModal'));
          modal.hide();
        }, 2000);
    }
};


// Obtener clase para el badge del rol
const getRoleBadgeClass = (rol) => {
  const classes = {
    'Admin': 'bg-danger',
    'Técnico': 'bg-primary',
    'Operario': 'bg-warning text-dark'
  }
  return classes[rol] || 'bg-secondary'
}

// Lifecycle hooks
onMounted(() => {
  fetchUsers()
})

onUnmounted(() => {
  stop()
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