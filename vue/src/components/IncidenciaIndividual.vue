<template>
  <div class="incidencias-container flex">
    <!-- Panel de filtros (a la izquierda) -->
    <transition name="slide">
      <div v-if="showFilters" class="filter-sidebar w-52 bg-white p-3 shadow-lg overflow-y-auto h-screen fixed left-0 top-0 z-10">
        <h2 class="text-lg font-bold mb-3">Filtros</h2>
        <button @click="showFilters = false" class="absolute top-1 right-1 text-gray-500 hover:text-gray-700">
          <span class="sr-only">Cerrar filtros</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
        
        <!-- Filtros -->
        <div class="filters">
          <div v-for="(options, filterType) in filters" :key="filterType" class="filter-section mb-2">
            <button 
              class="btn btn-outline-secondary w-full text-left flex justify-between items-center text-sm"
              @click="toggleFilter(filterType)"
            >
              <span>{{ filterType.toUpperCase() }}</span>
              <i :class="['bi', openFilters[filterType] ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
            </button>
            <div class="filter-content mt-2" v-show="openFilters[filterType]">
              <div v-for="option in options" :key="option.value" class="form-check">
                <input
                  :id="${filterType}-${option.value}"
                  type="checkbox"
                  :value="option.value"
                  v-model="selectedFilters[filterType]"
                  class="form-check-input"
                />
                <label :for="${filterType}-${option.value}" class="form-check-label">{{ option.label }}</label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- Contenido principal -->
    <div class="flex-grow ml-0 transition-all duration-300" :class="{ 'ml-52': showFilters }">
      <!-- Botón para mostrar/ocultar los filtros -->
      <button class="btn btn-primary mb-3" @click="showFilters = !showFilters">
        <i class="bi bi-funnel"></i> Filtros
      </button>

      <h1 class="text-2xl font-bold mb-6">Incidencias</h1>

      <!-- Barra de búsqueda -->
      <div class="search-container mb-6">
        <div class="relative">
          <input
            type="text"
            placeholder="Buscar..."
            v-model="searchQuery"
            class="search-input w-full p-2 pl-10 rounded border"
          />
          <SearchIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 h-5 w-5" />
        </div>
      </div>

      <!-- Tabla -->
      <div class="table-container">
        <div class="table-header grid grid-cols-8 gap-4 font-bold bg-gray-100 p-2">
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
            v-for="(incidencia, i) in filteredIncidents"
            :key="i"
            class="table-row grid grid-cols-8 gap-4 cursor-pointer hover:bg-gray-50 p-2"
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
            <div>{{ incidencia.tecnico ? incidencia.tecnico.nombre : 'Sin asignar' }}</div>
            <div>
              <span :class="[getGravedadClass(incidencia.gravedad)]">
                {{ incidencia.gravedad }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';
import { SearchIcon } from 'lucide-vue-next';

const router = useRouter();
const showFilters = ref(false);
const searchQuery = ref('');

const filters = reactive({
  gravedad: [
    { value: 'alta', label: 'Alta' },
    { value: 'media', label: 'Media' },
    { value: 'baja', label: 'Baja' }
  ],
  prioridad: [
    { value: '01', label: 'Alta' },
    { value: '02', label: 'Media' },
    { value: '03', label: 'Baja' }
  ],
  campus: [
    { value: 'Taller A', label: 'Taller A' },
    { value: 'Taller B', label: 'Taller B' },
    { value: 'Taller C', label: 'Taller C' }
  ],
  estado: [
    { value: 'En Curso', label: 'En Curso' },
    { value: 'Pendiente', label: 'Pendiente' },
    { value: 'Completado', label: 'Completado' }
  ]
});

const selectedFilters = reactive({
  gravedad: [],
  prioridad: [],
  campus: [],
  estado: []
});

const openFilters = reactive({
  gravedad: false,
  prioridad: false,
  campus: false,
  estado: false
});

const toggleFilter = (filterName) => {
  openFilters[filterName] = !openFilters[filterName];
};

// Mock data for incidents (replace this with actual data fetching logic)
const incidents = ref([
  {
    id_incidencia: 1,
    descripcion: 'Fallo en máquina A',
    estado: 'En Curso',
    maquina: { prioridad: '01', nombre: 'Máquina A' },
    fecha_reporte: '2023-05-20',
    creador: { nombre: 'Juan Pérez' },
    tecnico: { nombre: 'Ana García' },
    gravedad: 'alta'
  },
  // Add more mock incidents as needed
]);

const filteredIncidents = computed(() => {
  return incidents.value.filter((incidencia) => {
    const matchesSearch = Object.values(incidencia).some(value =>
      value && value.toString().toLowerCase().includes(searchQuery.value.toLowerCase())
    );

    const matchesFilters = 
      (selectedFilters.gravedad.length === 0 || selectedFilters.gravedad.includes(incidencia.gravedad)) &&
      (selectedFilters.prioridad.length === 0 || selectedFilters.prioridad.includes(incidencia.maquina.prioridad)) &&
      (selectedFilters.campus.length === 0 || selectedFilters.campus.includes(incidencia.maquina.nombre)) &&
      (selectedFilters.estado.length === 0 || selectedFilters.estado.includes(incidencia.estado));

    return matchesSearch && matchesFilters;
  });
});

const getEstadoClass = (estado) => {
  // Implement this function based on your requirements
  switch (estado) {
    case 'En Curso': return 'bg-yellow-200 text-yellow-800';
    case 'Pendiente': return 'bg-red-200 text-red-800';
    case 'Completado': return 'bg-green-200 text-green-800';
    default: return '';
  }
};

const getPrioridadClass = (prioridad) => {
  // Implement this function based on your requirements
  switch (prioridad) {
    case '01': return 'bg-red-200 text-red-800';
    case '02': return 'bg-yellow-200 text-yellow-800';
    case '03': return 'bg-green-200 text-green-800';
    default: return '';
  }
};

const getGravedadClass = (gravedad) => {
  // Implement this function based on your requirements
  switch (gravedad) {
    case 'alta': return 'text-red-600 font-bold';
    case 'media': return 'text-yellow-600 font-bold';
    case 'baja': return 'text-green-600 font-bold';
    default: return '';
  }
};

const goToIncidencia = (id) => {
  router.push({ name: 'IncidenciaIndividual', params: { id: id } });
};
</script>

<style scoped>
.slide-enter-active,
.slide-leave-active {
  transition: transform 0.3s ease;
}

.slide-enter-from,
.slide-leave-to {
  transform: translateX(-100%);
}

.filter-sidebar {
  transition: transform 0.3s ease;
}

.table-container {
  width: 100%;
  overflow-x: auto;
}

.status-badge {
  padding: 0.25rem 0.5rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 500;
}

</style>