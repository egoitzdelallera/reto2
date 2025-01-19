<template>
  <div class="container-fluid mt-4">
    <!-- Botón para mostrar/ocultar los filtros -->
    <button class="btn btn-primary mb-3 col-md-1" @click="showFilters = !showFilters">
      <i class="bi bi-funnel"></i> Filtros
    </button>

    <div class="row">
      <!-- Panel de filtros (desplegable) -->
      <div :class="['col-md-3', 'filter-sidebar', { 'show': showFilters }]">
        <div v-show="showFilters" class="filters-container p-3">
          <!-- Barra de búsqueda -->
          <div class="search-container mb-3">
            <input
              type="text"
              class="form-control search-input"
              placeholder="Buscar..."
              v-model="searchQuery"
            />
          </div>
          
          <!-- Filtros -->
          <div class="filters">
            <!-- Filtro de Gravedad -->
            <div class="filter-section mb-3">
              <button 
                class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center"
                @click="toggleFilter('gravedad')"
              >
                <span>GRAVEDAD</span>
                <i :class="['bi', openFilters.gravedad ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
              </button>
              <div class="filter-content mt-2" v-show="openFilters.gravedad">
                <div v-for="option in filters.gravedad" :key="option.value" class="form-check">
                  <input
                    :id="'gravedad-' + option.value"
                    type="checkbox"
                    :value="option.value"
                    v-model="selectedFilters.gravedad"
                    class="form-check-input"
                  />
                  <label :for="'gravedad-' + option.value" class="form-check-label">{{ option.label }}</label>
                </div>
              </div>
            </div>

            <!-- Filtro de Prioridad -->
            <div class="filter-section mb-3">
              <button 
                class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center"
                @click="toggleFilter('prioridad')"
              >
                <span>PRIORIDAD</span>
                <i :class="['bi', openFilters.prioridad ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
              </button>
              <div class="filter-content mt-2" v-show="openFilters.prioridad">
                <div v-for="option in filters.prioridad" :key="option.value" class="form-check">
                  <input
                    :id="'prioridad-' + option.value"
                    type="checkbox"
                    :value="option.value"
                    v-model="selectedFilters.prioridad"
                    class="form-check-input"
                  />
                  <label :for="'prioridad-' + option.value" class="form-check-label">{{ option.label }}</label>
                </div>
              </div>
            </div>

            <!-- Filtro de Campus -->
            <div class="filter-section mb-3">
              <button 
                class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center"
                @click="toggleFilter('campus')"
              >
                <span>CAMPUS</span>
                <i :class="['bi', openFilters.campus ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
              </button>
              <div class="filter-content mt-2" v-show="openFilters.campus">
                <div v-for="option in filters.campus" :key="option.value" class="form-check">
                  <input
                    :id="'campus-' + option.value"
                    type="checkbox"
                    :value="option.value"
                    v-model="selectedFilters.campus"
                    class="form-check-input"
                  />
                  <label :for="'campus-' + option.value" class="form-check-label">{{ option.label }}</label>
                </div>
              </div>
            </div>

            <!-- Filtro de Estado -->
            <div class="filter-section mb-3">
              <button 
                class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center"
                @click="toggleFilter('estado')"
              >
                <span>ESTADO</span>
                <i :class="['bi', openFilters.estado ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
              </button>
              <div class="filter-content mt-2" v-show="openFilters.estado">
                <div v-for="option in filters.estado" :key="option.value" class="form-check">
                  <input
                    :id="'estado-' + option.value"
                    type="checkbox"
                    :value="option.value"
                    v-model="selectedFilters.estado"
                    class="form-check-input"
                  />
                  <label :for="'estado-' + option.value" class="form-check-label">{{ option.label }}</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!-- Columna de la tabla -->
      <div :class="['col-md', { 'col-md-10': showFilters }]">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="thead-light">
              <tr>
                <th>TALLER</th>
                <th>ESTADO</th>
                <th>PRIORIDAD</th>
                <th>FECHA INICIO</th>
                <th>FECHA FIN</th>
                <th>RESPONSABLE</th>
                <th>EQUIPOS</th>
                <th>TIPO</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(maintenance, i) in filteredMaintenances"
                :key="i"
                @click="goToMaintenance(i)"
                style="cursor: pointer;"
              >
                <td>{{ maintenance.taller }}</td>
                <td>
                  <span
                    :class="[
                      'badge',
                      getMaintenanceStateClass(maintenance.estado),
                    ]"
                  >
                    {{ maintenance.estado }}
                  </span>
                </td>
                <td>{{ maintenance.prioridad }}</td>
                <td>{{ maintenance.fechaInicio }}</td>
                <td>{{ maintenance.fechaFin }}</td>
                <td>{{ maintenance.responsable }}</td>
                <td>{{ maintenance.equipos }}</td>
                <td>
                  <span
                    :class="[
                      'badge',
                      getMaintenanceTypeClass(maintenance.tipo),
                    ]"
                  >
                    {{ maintenance.tipo }}
                  </span>
                </td>
              </tr>
              <tr v-if="filteredMaintenances.length === 0">
                <td colspan="8" class="text-center">
                  No se encontraron mantenimientos.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed } from 'vue';
import { useRouter } from 'vue-router';

const router = useRouter();
const searchQuery = ref('');
const showFilters = ref(false);

// Estado para controlar qué filtros están abiertos
const openFilters = reactive({
  gravedad: false,
  prioridad: false,
  campus: false,
  estado: false
});

// Opciones de filtros disponibles
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

// Estado para los filtros seleccionados
const selectedFilters = reactive({
  gravedad: [],
  prioridad: [],
  campus: [],
  estado: []
});

// Función para alternar la visibilidad de las secciones de filtro
const toggleFilter = (filterName) => {
  openFilters[filterName] = !openFilters[filterName];
};

// Función para mostrar/ocultar el panel de filtros
const toggleFilters = () => {
  showFilters.value = !showFilters.value;
};

const maintenances = ref([
  {
    taller: 'Taller A',
    estado: 'En Curso',
    prioridad: '02',
    fechaInicio: 'Hoy',
    fechaFin: 'Próxima semana',
    responsable: 'Vicente Abad',
    equipos: 'PM74, SM52',
    tipo: 'Preventivo',
  },
  {
    taller: 'Taller B',
    estado: 'En Curso',
    prioridad: '01',
    fechaInicio: 'Ayer',
    fechaFin: 'Mañana',
    responsable: 'Alfredo Fernandez',
    equipos: 'Torno CDC',
    tipo: 'Correctivo',
  },
  {
    taller: 'Taller C',
    estado: 'Pendiente',
    prioridad: '03',
    fechaInicio: '2024-08-20',
    fechaFin: '2024-08-24',
    responsable: 'Elena García',
    equipos: 'Impresora 3D',
    tipo: 'Preventivo',
  },
  // ... (resto de los datos de mantenimiento)
]);

const filteredMaintenances = computed(() => {
  return maintenances.value.filter((maintenance) => {
    const matchesSearch = Object.values(maintenance).some(value =>
      value.toString().toLowerCase().includes(searchQuery.value.toLowerCase())
    );

    const matchesFilters = 
      (selectedFilters.gravedad.length === 0 || selectedFilters.gravedad.includes(maintenance.gravedad)) &&
      (selectedFilters.prioridad.length === 0 || selectedFilters.prioridad.includes(maintenance.prioridad)) &&
      (selectedFilters.campus.length === 0 || selectedFilters.campus.includes(maintenance.taller)) &&
      (selectedFilters.estado.length === 0 || selectedFilters.estado.includes(maintenance.estado));

    return matchesSearch && matchesFilters;
  });
});

const goToMaintenance = (id) => {
  router.push({ name: 'IncidenciaIndividual', params: { id: id } });
};

const getMaintenanceStateClass = (estado) => {
  switch (estado) {
    case 'En Curso':
      return 'bg-primary text-white';
    case 'Pendiente':
      return 'bg-warning text-dark';
    case 'Completado':
      return 'bg-success text-white';
    default:
      return 'bg-secondary text-white';
  }
};

const getMaintenanceTypeClass = (tipo) => {
  switch (tipo) {
    case 'Preventivo':
      return 'bg-success text-white';
    case 'Correctivo':
      return 'bg-danger text-white';
    default:
      return 'bg-secondary text-white';
  }
};
</script>

<style scoped>
.filter-sidebar {
  position: fixed;
  left: -100%;
  top: 0;
  bottom: 0;
  width: 300px;
  background-color: #f8f9fa;
  transition: left 0.3s ease-in-out;
  z-index: 1000;
  overflow-y: auto;
}

.filter-sidebar.show {
  left: 0;
}

.filters-container {
  height: 100%;
}

@media (min-width: 768px) {
  .filter-sidebar {
    position: static;
    width: auto;
    background-color: transparent;
    overflow-y: visible;
    height: auto;
  }

  .filter-sidebar.show {
    display: block;
  }
}

/* Estilos adicionales para mejorar la apariencia */
.table th {
  font-size: 0.9rem;
  font-weight: 600;
}

.table td {
  font-size: 0.9rem;
}

.badge {
  font-size: 0.8rem;
}

.filter-content {
  max-height: 200px;
  overflow-y: auto;
}
</style>