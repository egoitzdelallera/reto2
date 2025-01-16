<template>
  <div class="incidencias-container">
    <h1 class="text-2xl font-bold mb-6">Incidencias</h1>

    <!-- Barra de búsqueda -->
    <div class="search-container mb-6">
      <div class="relative">
        <input
          type="text"
          placeholder="Buscar..."
          v-model="searchQuery"
          class="search-input"
        />
        <SearchIcon class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" />
      </div>
    </div>

    <!-- Tabla -->
    <div class="table-container">
      <div class="table-header">
        <div>TÍTULO</div>
        <div>ESTADO</div>
        <div>PRIORIDAD</div>
        <div>FECHA</div>
        <div>MÁQUINA</div>
        <div>OPERARIO</div>
        <div>TÉCNICO</div>
        <div>GRAVEDAD</div>
      </div>

      <div class="table-body">
        <div
          v-for="(incidencia, i) in incidencias"
          :key="i"
          class="table-row cursor-pointer"
          @click="goToIncidencia(incidencia.id)"
        >
          <div class="font-medium">{{ incidencia.titulo }}</div>
          <div>
            <span :class="['status-badge', getEstadoClass(incidencia.estado)]">
              {{ incidencia.estado }}
            </span>
          </div>
          <div>{{ incidencia.prioridad }}</div>
          <div>{{ incidencia.fecha }}</div>
          <div>{{ incidencia.maquina }}</div>
          <div>{{ incidencia.operario }}</div>
          <div>{{ incidencia.tecnico }}</div>
          <div>
            <span :class="['status-badge', getGravedadClass(incidencia.gravedad)]">
              {{ incidencia.gravedad }}
            </span>
          </div>
        </div>
      </div>
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
      return 'bg-blue-100 text-blue-800'
    case 'Abierta':
      return 'bg-gray-100 text-gray-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const getGravedadClass = (gravedad) => {
  switch (gravedad) {
    case 'Máquina parada':
      return 'bg-red-100 text-red-800'
    case 'Máquina en marcha':
      return 'bg-orange-100 text-orange-800'
    case 'Aviso':
      return 'bg-yellow-100 text-yellow-800'
    case 'Mantenimiento':
      return 'bg-gray-100 text-gray-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

</script>

<style scoped>

</style>

