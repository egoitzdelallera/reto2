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
        <div>CREADOR</div>
        <div>TÉCNICO</div>
        <div>GRAVEDAD</div>
      </div>

      <div class="table-body">
        <div
          v-for="(incidencia, i) in filteredIncidencias"
          :key="i"
          class="table-row cursor-pointer"
          @click="goToIncidencia(incidencia.id_incidencia)"
        >
          <div class="font-medium">{{ incidencia.descripcion }}</div>
          <div>
            <span :class="['status-badge', getEstadoClass(incidencia.estado)]">
              {{ incidencia.estado }}
            </span>
          </div>
          <div>
            <span :class="['status-badge', getPrioridadClass(incidencia.maquina.prioridad)]">
              {{ incidencia.maquina.prioridad }}
            </span>
          </div>
          <div>{{ incidencia.fecha_reporte }}</div>
          <div>{{ incidencia.maquina.nombre }}</div>
          <div>{{ incidencia.creador.nombre }}</div>
          <div>{{ incidencia.tecnico }}</div>
          <div>
            <span :class="[getGravedadClass(incidencia.gravedad)]">
              {{ incidencia.gravedad }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import { SearchIcon } from 'lucide-vue-next';
import useIncidencias from '@/composables/useIncidencias';

const {
  filteredIncidencias,
  searchQuery,
  getEstadoClass,
  getGravedadClass,
  getPrioridadClass,
} = useIncidencias();

const router = useRouter();

// Redirigir a la incidencia individual
const goToIncidencia = (id) => {
  router.push({name: 'IncidenciaIndividual', params: { id: id}});
};
</script>

<style scoped>

</style>

