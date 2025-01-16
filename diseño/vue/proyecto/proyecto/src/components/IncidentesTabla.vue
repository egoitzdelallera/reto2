<template>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <div class="container-fluid">
    <h1 class="display-4 mb-4">Incidencias</h1>

    <!-- Barra de búsqueda -->
    <div class="row mb-4">
      <div class="col-md-4 ml-auto">
        <div class="input-group">
          <input
            type="text"
            class="form-control"
            placeholder="Buscar..."
            v-model="searchQuery"
          />
          <div class="input-group-append">
            <span class="input-group-text">
              <SearchIcon class="text-muted" size="18" />
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabla -->
    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="thead-light">
          <tr>
            <th>TÍTULO</th>
            <th>ESTADO</th>
            <th>PRIORIDAD</th>
            <th>FECHA</th>
            <th>MÁQUINA</th>
            <th>OPERARIO</th>
            <th>TÉCNICO</th>
            <th>GRAVEDAD</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(incidencia, i) in incidencias"
            :key="i"
            class="cursor-pointer"
            @click="goToIncidencia(incidencia.id)"
          >
            <td class="font-weight-medium">{{ incidencia.titulo }}</td>
            <td>
              <span :class="['badge', getEstadoClass(incidencia.estado)]">
                {{ incidencia.estado }}
              </span>
            </td>
            <td>{{ incidencia.prioridad }}</td>
            <td>{{ incidencia.fecha }}</td>
            <td>{{ incidencia.maquina }}</td>
            <td>{{ incidencia.operario }}</td>
            <td>{{ incidencia.tecnico }}</td>
            <td>
              <span :class="['badge', getGravedadClass(incidencia.gravedad)]">
                {{ incidencia.gravedad }}
              </span>
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
import { SearchIcon } from 'lucide-vue-next'

const searchQuery = ref('')
const router = useRouter()

const incidencias = ref([
  {
    id: 1,
    titulo: 'Brazo robótico inactivo',
    estado: 'En Proceso',
    prioridad: '02',
    fecha: 'Hoy',
    maquina: 'PM74',
    operario: 'Iñigo',
    tecnico: 'Vicente Abad',
    gravedad: 'Máquina parada'
  },
  {
    id: 2,
    titulo: 'Brazo robótico inactivo',
    estado: 'En Proceso',
    prioridad: '01',
    fecha: 'Hace 22 horas',
    maquina: 'SM52',
    operario: 'Ander',
    tecnico: 'Alfredo Fernandez',
    gravedad: 'Máquina parada'
  },
  {
    id: 3,
    titulo: 'Brazo robótico inactivo',
    estado: 'Abierta',
    prioridad: '03',
    fecha: 'Hace 2 minutos',
    maquina: 'Impresora 3D',
    operario: 'Egoitz',
    tecnico: 'No Asignado',
    gravedad: 'Máquina en marcha'
  },
  {
    id: 4,
    titulo: 'Brazo robótico inactivo',
    estado: 'Abierta',
    prioridad: '03',
    fecha: 'Ayer',
    maquina: 'Torno CDC',
    operario: 'Egoitz',
    tecnico: 'No Asignado',
    gravedad: 'Aviso'
  },
  {
    id: 5,
    titulo: 'Brazo robótico inactivo',
    estado: 'Abierta',
    prioridad: '01',
    fecha: 'Hace 3 días',
    maquina: 'Soldadura TIG',
    operario: 'Iñigo',
    tecnico: 'No Asignado',
    gravedad: 'Mantenimiento'
  }
])

const goToIncidencia = (id) => {
  router.push({ name: 'IncidenciaIndividual', params: { id: id } })
}

const getEstadoClass = (estado) => {
  switch (estado) {
    case 'En Proceso':
      return 'badge-primary'
    case 'Abierta':
      return 'badge-secondary'
    default:
      return 'badge-secondary'
  }
}

const getGravedadClass = (gravedad) => {
  switch (gravedad) {
    case 'Máquina parada':
      return 'badge-danger'
    case 'Máquina en marcha':
      return 'badge-warning'
    case 'Aviso':
      return 'badge-info'
    case 'Mantenimiento':
      return 'badge-secondary'
    default:
      return 'badge-secondary'
  }
}
</script>

<style scoped>
.cursor-pointer {
  cursor: pointer;
}
</style>

