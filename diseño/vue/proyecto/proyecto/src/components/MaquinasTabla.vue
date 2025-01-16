<template>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <div class="container-fluid py-4">
    <div class="row mb-4 align-items-center">
      <div class="col">
        <h2 class="mb-0">Máquinas</h2>
      </div>
      <div class="col-auto">
        <div class="d-flex align-items-center">
          <button class="btn btn-dark me-3">
            Nueva Máquina
          </button>
          <button class="btn btn-outline-secondary me-3">
            <span>Filtrar</span>
          </button>
          <div class="input-group">
            <input 
              type="search" 
              class="form-control"
              placeholder="Search..."
              v-model="searchQuery"
            >
          </div>
        </div>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="table-light">
          <tr>
            <th>NOMBRE ↑</th>
            <th>DESCRIPCIÓN</th>
            <th>CAMPUS ↑</th>
            <th>TALLER</th>
            <th>PRIORIDAD ↑</th>
            <th>RESPONSABLE DE TALLER</th>
            <th>ESTADO</th>
            <th>ACCIONES</th>
          </tr>
        </thead>
        <tbody>
          <tr 
            v-for="machine in machines" 
            :key="machine.id"
            @click="goToMachine(machine.id)"
            style="cursor: pointer;"
          >
            <td>{{ machine.name }}</td>
            <td class="text-truncate" style="max-width: 200px;">{{ machine.description }}</td>
            <td>{{ machine.campus }}</td>
            <td>{{ machine.workshop }}</td>
            <td>
              <span :class="getPriorityClass(machine.priority)">
                {{ machine.priority }}
              </span>
            </td>
            <td>{{ machine.manager }}</td>
            <td>
              <span :class="getStatusClass(machine.status)">
                {{ machine.status ? 'Habilitado' : 'Deshabilitado' }}
              </span>
            </td>
            <td>
              <div class="d-flex gap-2">
                <button 
                  class="btn btn-sm"
                  :class="machine.status ? 'btn-success' : 'btn-secondary'"
                  @click.stop="toggleStatus(machine)"
                >
                  Habilitado
                </button>
                <button 
                  class="btn btn-sm btn-outline-primary"
                  @click.stop="editMachine(machine.id)"
                >
                  Editar
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const searchQuery = ref('')

const machines = ref([
  {
    id: 1,
    name: 'PA74',
    description: 'Brazo robótico de alta precisión...',
    campus: 'Arteaga',
    workshop: 'A03',
    priority: 'Alta',
    manager: 'Juan López',
    status: true
  },
  {
    id: 2,
    name: 'RM52',
    description: 'Brazo robótico de alta precisión...',
    campus: 'Molinuevo',
    workshop: 'M02',
    priority: 'Media',
    manager: 'Antonio Martín',
    status: true
  },
  {
    id: 3,
    name: 'Robot Delta',
    description: 'Brazo robótico de alta precisión...',
    campus: 'Nieves Cano',
    workshop: 'NC03',
    priority: 'Media',
    manager: 'Ana Pérez',
    status: true
  }
])

const goToMachine = (id) => {
  router.push({ name: 'MaquinaIndividual', params: { id: id } })
}

const editMachine = (id) => {
  router.push({ name: 'MachineEdit', params: { id: id } })
}

const toggleStatus = (machine) => {
  machine.status = !machine.status
}

const getPriorityClass = (priority) => {
  return {
    'Alta': 'priority-high',
    'Media': 'priority-medium',
    'Baja': 'priority-low'
  }[priority]
}

const getStatusClass = (status) => {
  return status ? 'status-enabled' : 'status-disabled'
}
</script>

  <style scoped>
  
  </style>