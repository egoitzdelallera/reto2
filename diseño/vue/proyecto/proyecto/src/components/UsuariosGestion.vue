<template>
    <div class="container mt-5">
      <div class="text-center mb-4">
        <h1 class="display-4">Gestión de Usuarios</h1>
        <p class="lead">En esta sección podrás gestionar los usuarios de la aplicación.</p>
      </div>
  
      <div class="card shadow">
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="table-light">
                <tr>
                  <th>Nombre</th>
                  <th>Email</th>
                  <th>Rol</th>
                  <th>Tipo de Usuario</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="user in users" :key="user.id">
                  <td>{{ user.name }}</td>
                  <td>{{ user.email }}</td>
                  <td>{{ user.role }}</td>
                  <td>
                    <span :class="getUserTypeClass(user.type)">{{ user.type }}</span>
                  </td>
                  <td>
                    <button @click="openEditModal(user)" class="btn btn-outline-primary btn-sm me-1">
                      <i class="bi bi-pencil-square"></i>
                    </button>
                    <button @click="openDeleteModal(user)" class="btn btn-outline-danger btn-sm me-1">
                      <i class="bi bi-trash"></i>
                    </button>
                    <button @click="openInfoModal(user)" class="btn btn-outline-info btn-sm">
                      <i class="bi bi-info-circle"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
  
      <!-- Edit Modal -->
      <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="editModalLabel">Editar Usuario</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="updateUser">
                <div class="mb-3">
                  <label for="editName" class="form-label">Nombre</label>
                  <input type="text" class="form-control" id="editName" v-model="editingUser.name" required>
                </div>
                <div class="mb-3">
                  <label for="editEmail" class="form-label">Email</label>
                  <input type="email" class="form-control" id="editEmail" v-model="editingUser.email" required>
                </div>
                <div class="mb-3">
                  <label for="editRole" class="form-label">Rol</label>
                  <input type="text" class="form-control" id="editRole" v-model="editingUser.role" required>
                </div>
                <div class="mb-3">
                  <label for="editType" class="form-label">Tipo de Usuario</label>
                  <select class="form-select" id="editType" v-model="editingUser.type" required>
                    <option value="Admin">Admin</option>
                    <option value="Usuario">Usuario</option>
                    <option value="Invitado">Invitado</option>
                  </select>
                </div>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
              </form>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Delete Modal -->
      <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>¿Estás seguro de que deseas eliminar al usuario {{ deletingUser.name }}?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button @click="deleteUser" type="button" class="btn btn-danger">Eliminar</button>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Info Modal -->
      <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="infoModalLabel">Información del Usuario</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <h6>Estadísticas de {{ infoUser.name }}</h6>
              <div class="row">
                <div class="col-md-6">
                  <div class="card mb-3">
                    <div class="card-body">
                      <h6 class="card-title">Problemas Reportados</h6>
                      <canvas ref="problemsChart"></canvas>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="card mb-3">
                    <div class="card-body">
                      <h6 class="card-title">Actividad Mensual</h6>
                      <canvas ref="activityChart"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-body">
                  <h6 class="card-title">Información Adicional</h6>
                  <p><strong>Fecha de Registro:</strong> {{ infoUser.registrationDate }}</p>
                  <p><strong>Último Acceso:</strong> {{ infoUser.lastLogin }}</p>
                  <p><strong>Proyectos Activos:</strong> {{ infoUser.activeProjects }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  
  import Chart from 'chart.js/auto';
  
  const users = ref([
    { id: 1, name: 'Juan Pérez', email: 'juan@example.com', role: 'Desarrollador', type: 'Usuario', registrationDate: '2023-01-15', lastLogin: '2023-05-20', activeProjects: 3 },
    { id: 2, name: 'María García', email: 'maria@example.com', role: 'Diseñadora', type: 'Admin', registrationDate: '2022-11-30', lastLogin: '2023-05-21', activeProjects: 5 },
    { id: 3, name: 'Carlos López', email: 'carlos@example.com', role: 'Gerente', type: 'Invitado', registrationDate: '2023-03-10', lastLogin: '2023-05-19', activeProjects: 1 },
  ]);
  
  const editingUser = ref({});
  const deletingUser = ref({});
  const infoUser = ref({});
  let editModal, deleteModal, infoModal;
  
  onMounted(() => {
    editModal = new Modal(document.getElementById('editModal'));
    deleteModal = new Modal(document.getElementById('deleteModal'));
    infoModal = new Modal(document.getElementById('infoModal'));
  });
  
  const openEditModal = (user) => {
    editingUser.value = { ...user };
    editModal.show();
  };
  
  const openDeleteModal = (user) => {
    deletingUser.value = user;
    deleteModal.show();
  };
  
  const openInfoModal = (user) => {
    infoUser.value = { ...user };
    infoModal.show();
    setTimeout(() => {
      createCharts();
    }, 100);
  };
  
  const updateUser = () => {
    const index = users.value.findIndex(u => u.id === editingUser.value.id);
    if (index !== -1) {
      users.value[index] = { ...editingUser.value };
    }
    editModal.hide();
  };
  
  const deleteUser = () => {
    users.value = users.value.filter(u => u.id !== deletingUser.value.id);
    deleteModal.hide();
  };
  
  const getUserTypeClass = (type) => {
    switch (type) {
      case 'Admin':
        return 'badge bg-danger';
      case 'Usuario':
        return 'badge bg-primary';
      case 'Invitado':
        return 'badge bg-secondary';
      default:
        return 'badge bg-light text-dark';
    }
  };
  
  const createCharts = () => {
    const problemsCtx = document.querySelector('#infoModal .modal-body canvas:first-child');
    const activityCtx = document.querySelector('#infoModal .modal-body canvas:last-child');
  
    new Chart(problemsCtx, {
      type: 'pie',
      data: {
        labels: ['Resueltos', 'Pendientes', 'En Progreso'],
        datasets: [{
          data: [65, 15, 20],
          backgroundColor: ['#28a745', '#dc3545', '#ffc107']
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: 'bottom'
          }
        }
      }
    });
  
    new Chart(activityCtx, {
      type: 'bar',
      data: {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May'],
        datasets: [{
          label: 'Actividades Completadas',
          data: [12, 19, 3, 5, 2],
          backgroundColor: '#007bff'
        }]
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
  };
  </script>
  
  <style scoped>
  .table th, .table td {
    vertical-align: middle;
  }
  .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
  }
  </style>
  
  