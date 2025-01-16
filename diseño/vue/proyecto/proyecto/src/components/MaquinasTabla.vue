<template>
  <div class="machine-list">
    <div class="header">
      <h2>Máquinas</h2>
      <div class="header-actions">
        <button class="btn-new">
          Nueva Máquina
        </button>
        <div class="search-controls">
          <button class="btn-filter">
            <span>Filtrar</span>
          </button>
          <div class="search-container">
            <input 
              type="search" 
              placeholder="Search..."
              v-model="searchQuery"
            >
          </div>
        </div>
      </div>
    </div>

    <div class="table-container">
      <table>
        <thead>
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
            class="machine-row"
          >
            <td>{{ machine.name }}</td>
            <td class="description-cell">{{ machine.description }}</td>
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
              <div class="action-buttons">
                <button 
                  class="btn-status" 
                  :class="{'enabled': machine.status}"
                  @click.stop="toggleStatus(machine)"
                >
                  Habilitado
                </button>
                <button 
                  class="btn-edit"
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