<template>
  <div class="container-fluid">
    <div class="row">
      <!-- Panel de filtros (a la izquierda) -->
      <div
        :class="['filter-panel', 'col-md-3', 'p-4', 'shadow-sm', 'overflow-auto', 'vh-100']"
        style="background-color: #f0f8ff; margin-top: 0;"
        :style="{ transform: showFilters ? 'translateX(0)' : 'translateX(-100%)' }"
      >
        <transition name="fade">
          <div v-if="showFilters" class="filter-panel-content">
            <h2 class="h4 mb-4">Filtros</h2>
            <button @click="showFilters = false" class="btn-close position-absolute top-0 end-0 m-2"
              aria-label="Cerrar filtros"></button>

            <!-- Filtros -->
            <div class="filters">
                <!-- Filtro de Gravedad -->
                <div class="mb-3">
                    <button
                      class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center"
                      @click="toggleFilter('gravedad')"
                    >
                        <span>GRAVEDAD</span>
                        <i :class="['bi', openFilters.gravedad ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
                    </button>
                    <div class="mt-2" v-show="openFilters.gravedad">
                        <div v-for="option in filters.gravedad" :key="option.value" class="form-check">
                            <input :id="'gravedad-' + option.value" type="checkbox" :value="option.value"
                                v-model="selectedFilters.gravedad" class="form-check-input" />
                            <label :for="'gravedad-' + option.value" class="form-check-label">{{ option.label
                            }}</label>
                        </div>
                    </div>
                </div>

               <!-- Filtro de Prioridad -->
              <div class="mb-3">
                <button
                  class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center"
                  @click="toggleFilter('prioridad')"
                >
                  <span>PRIORIDAD</span>
                  <i :class="['bi', openFilters.prioridad ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
                </button>
                <div class="mt-2" v-show="openFilters.prioridad">
                  <div v-for="option in filters.prioridad" :key="option.value" class="form-check">
                    <input :id="'prioridad-' + option.value" type="checkbox" :value="option.value"
                      v-model="selectedFilters.prioridad" class="form-check-input" />
                    <label :for="'prioridad-' + option.value" class="form-check-label">{{ option.label
                    }}</label>
                  </div>
                </div>
              </div>

                          <!-- Filtro de Taller -->
                            <div class="mb-3">
                          <button
                            class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center"
                            @click="toggleFilter('taller')"
                          >
                            <span>TALLER</span>
                            <i :class="['bi', openFilters.taller ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
                          </button>
                          <div class="mt-2" v-show="openFilters.taller">
                            <div v-for="taller in talleres" :key="taller.id_taller" class="form-check">
                              <input
                                :id="'taller-' + taller.id_taller"
                                type="checkbox"
                                :value="taller.nombre"
                                v-model="selectedFilters.taller"
                                class="form-check-input"
                              />
                              <label :for="'taller-' + taller.id_taller" class="form-check-label">{{ taller.nombre }}</label>
                            </div>
                          </div>
                        </div>


                          <!-- Filtro de Estado -->
                          <div class="mb-3">
                              <button
                                  class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center"
                                  @click="toggleFilter('estado')">
                                  <span>ESTADO</span>
                                  <i :class="['bi', openFilters.estado ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
                              </button>
                              <div class="mt-2" v-show="openFilters.estado">
                                  <div v-for="option in filters.estado" :key="option.value" class="form-check">
                                      <input :id="'estado-' + option.value" type="checkbox" :value="option.value"
                                          v-model="selectedFilters.estado" class="form-check-input" />
                                      <label :for="'estado-' + option.value" class="form-check-label">{{ option.label
                                      }}</label>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </transition>
          </div>

          <!-- Contenido principal -->
          <div :class="['main-content', 'col', { 'col-md-9': showFilters }]">
              <button v-show="!showFilters" class="filter-toggle btn btn-primary mb-1"
                  :class="{ 'hidden': showFilters }" @click="showFilters = true">
                  <i class="bi bi-funnel"></i> Filtros
              </button>

              <h1 class="h2 mb-4">Incidencias</h1>

              <!-- Barra de búsqueda -->
              <div class="d-flex justify-content-between mb-4">
                  <div class="input-group w-75">
                      <span class="input-group-text">
                          <i class="bi bi-search"></i>
                      </span>
                      <input type="text" placeholder="Buscar..." v-model="searchQuery" class="form-control" />
                  </div>

                   <div class="d-flex justify-content-end">
                     <button class="btn btn-success ml-2" @click="openModal">
                        <i class="bi bi-plus-square"></i> Nueva Incidencia
                    </button>
                     <button class="btn btn-primary ml-2" @click="openMantenimientoModal">
                        <i class="bi bi-tools"></i> Crear Mantenimiento
                    </button>
                 </div>
              </div>

              <!-- Tabla -->
              <div class="table-responsive">
                  <table class="table table-hover">
                      <thead>
                          <tr>
                              <th>TÍTULO</th>
                              <th>ESTADO</th>
                              <th>PRIORIDAD</th>
                              <th>FECHA</th>
                              <th>MÁQUINA</th>
                              <th>TALLER</th>
                              <th>CREADOR</th>
                              <th>TÉCNICO</th>
                              <th>GRAVEDAD</th>
                          </tr>
                      </thead>
                      <tbody>
                          <tr v-for="(incidencia, i) in paginatedIncidencias" :key="i"
                              @click="goToIncidencia(incidencia.id_incidencia)" style="cursor: pointer;">
                              <td class="fw-medium">{{ incidencia.descripcion }}</td>
                              <td>
                                  <span :class="['badge', getEstadoClass(incidencia.estado)]">
                                      {{ incidencia.estado }}
                                  </span>
                              </td>
                              <td>
                                  <span :class="['badge', getPrioridadClass(incidencia.maquina.prioridad)]">
                                      {{ incidencia.maquina.prioridad }}
                                  </span>
                              </td>
                              <td>{{ formatDate(incidencia.fecha_reporte) }}</td>
                              <td>{{ incidencia.maquina.nombre }}</td>
                              <td>
                                  {{ incidencia.maquina?.taller?.nombre || 'Sin taller' }}
                              </td>
                              <td>{{ incidencia.creador?.nombre || 'Sin creador' }}</td>
                              <td>{{
                                      incidencia.tecnico ? incidencia.tecnico.nombre : 'Sin asignar'
                                  }}</td>
                              <td>
                                  <span :class="['badge', getGravedadClass(incidencia.gravedad)]">
                                      {{ incidencia.gravedad }}
                                  </span>
                              </td>
                          </tr>
                      </tbody>
                  </table>
              </div>

              <!-- Paginación -->
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                    <li class="page-item" :class="{ disabled: currentPage === 1 }">
                        <button class="page-link" @click="previousPage" aria-label="Previous" >
                            <span aria-hidden="true">«</span>
                        </button>
                    </li>
                    <li v-for="page in totalPages" :key="page"
                        class="page-item"
                    :class="{ active: page === currentPage }"
                    >
                        <button class="page-link" @click="goToPage(page)">{{ page }}</button>
                    </li>
                    <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                        <button class="page-link" @click="nextPage" aria-label="Next">
                            <span aria-hidden="true">»</span>
                        </button>
                    </li>
                    </ul>
                </nav>
          </div>
      </div>

        <!-- Modal para crear mantenimiento -->
        <div class="modal fade" :class="{ 'show d-block': showMantenimientoModal }" tabindex="-1" role="dialog">
           <div class="modal-dialog" role="document">
                <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title">Nuevo Mantenimiento</h5>
                         <button type="button" class="btn-close" @click="closeMantenimientoModal"></button>
                     </div>
                    <div class="modal-body">
                         <div class="mb-3">
                            <label for="mantenimiento-titulo" class="form-label">Título</label>
                             <input type="text" class="form-control" id="mantenimiento-titulo" v-model="newMantenimiento.descripcion"
                                required>
                         </div>
                          <div class="mb-3">
                            <label for="mantenimiento-taller" class="form-label">Taller</label>
                             <div class="mt-2" style="height: auto; overflow-y: auto; border: 1px solid #ced4da; padding: 5px; border-radius: 5px;">
                                <div v-for="taller in talleres" :key="taller.id_taller" class="form-check">
                                   <input
                                        :id="'taller-mantenimiento-' + taller.id_taller"
                                        type="checkbox"
                                        :value="taller"
                                        v-model="selectedMantenimientoTaller"
                                        class="form-check-input"
                                   />
                                  <label :for="'taller-mantenimiento-' + taller.id_taller" class="form-check-label">{{ taller.nombre }}</label>
                               </div>
                              </div>
                            </div>
                             <div class="mb-3">
                              <label for="mantenimiento-maquina" class="form-label">Máquina</label>
                               <div class="mt-2" style="max-height: 250px; overflow-y: auto; border: 1px solid #ced4da; padding: 5px; border-radius: 5px;">
                                    <div v-for="maquina in filteredMantenimientoMaquinas" :key="maquina.id_maquina" class="form-check">
                                        <input
                                          :id="'maquina-mantenimiento-' + maquina.id_maquina"
                                          type="checkbox"
                                          :value="maquina"
                                         v-model="selectedMantenimientoMachine"
                                          class="form-check-input"
                                        />
                                        <label :for="'maquina-mantenimiento-' + maquina.id_maquina" class="form-check-label">{{ maquina.nombre }}</label>
                                    </div>
                                </div>
                          </div>

                        <div class="mb-3">
                            <label for="mantenimiento-frecuencia" class="form-label">Frecuencia</label>
                            <select class="form-select" id="mantenimiento-frecuencia" v-model="newMantenimiento.frecuencia" required>
                                <option value="">Selecciona una frecuencia</option>
                                <option value="Diaria">Diaria</option>
                                <option value="Semanal">Semanal</option>
                                <option value="Mensual">Mensual</option>
                                <option value="Anual">Anual</option>
                            </select>
                        </div>
                          <div class="mb-3" v-if="newMantenimiento.frecuencia === 'Semanal'">
                            <label for="mantenimiento-fecha-semanal" class="form-label">Día de la semana</label>
                            <input type="date" class="form-control" id="mantenimiento-fecha-semanal" v-model="newMantenimiento.fechaSemanal" required>
                        </div>
                     </div>
                      <div class="modal-footer">
                           <button type="button" class="btn btn-secondary" @click="closeMantenimientoModal">Cancelar</button>
                           <button type="button" class="btn btn-primary" @click="createMantenimiento">Guardar</button>
                      </div>
                 </div>
             </div>
            </div>
           <div v-if="showMantenimientoModal" class="modal-backdrop fade show"></div>


      <!-- Modal para crear incidencia -->
      <div class="modal fade" :class="{ 'show d-block': showModal }" tabindex="-1" role="dialog">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title">Nueva Incidencia</h5>
                      <button type="button" class="btn-close" @click="closeModal"></button>
                  </div>
                  <div class="modal-body">
                      <div class="mb-3">
                          <label for="incidencia-titulo" class="form-label">Título</label>
                          <input type="text" class="form-control" id="incidencia-titulo" v-model="newIncidencia.descripcion"
                              required>
                      </div>
                      <div class="mb-3">
                         <label for="incidencia-taller" class="form-label">Taller</label>
                           <select class="form-select" id="incidencia-taller" v-model="selectedTaller" required>
                             <option value="">Selecciona un taller</option>
                                  <option v-for="taller in talleres" :key="taller.id_taller" :value="taller">
                                        {{ taller.nombre }}
                                  </option>
                            </select>
                      </div>
                      <div class="mb-3">
                          <label for="incidencia-maquina" class="form-label">Máquina</label>
                          <select class="form-select" id="incidencia-maquina" v-model="selectedMachine" required>
                           <option value="">Selecciona una máquina</option>
                              <option v-for="maquina in filteredMaquinas" :key="maquina.id_maquina" :value="maquina">
                                {{ maquina.nombre }} - {{ maquina.descripcion }}
                            </option>
                          </select>
                      </div>

                      <div class="mb-3">
                          <label for="incidencia-gravedad" class="form-label">Gravedad</label>
                          <select class="form-select" id="incidencia-gravedad" v-model="newIncidencia.gravedad"
                              required>
                              <option v-for="option in filters.gravedad" :key="option.value" :value="option.value">{{
                                  option.label }}</option>
                          </select>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" @click="closeModal">Cancelar</button>
                      <button type="button" class="btn btn-primary" @click="createIncidencia">Guardar</button>
                  </div>
              </div>
          </div>
      </div>
       <div v-if="showModal" class="modal-backdrop fade show"></div>
  </div>
</template>

<script setup>
import { useRouter, useRoute } from 'vue-router';
import useIncidencias from '@/composables/useIncidencias';
import useMaquinas from '@/composables/useMaquinas';
import useTalleres from '@/composables/useTalleres';
import { ref, reactive, computed, watch, onMounted } from 'vue';

const router = useRouter();
const route = useRoute();
const showFilters = ref(false);
const searchQuery = ref('');
const showModal = ref(false);
const showMantenimientoModal = ref(false);
const itemsPerPage = 30;
const currentPage = ref(1);

const { maquinas,  } = useMaquinas();
const { incidencias, createIncidencia: apiCreateIncidencia } = useIncidencias();
const { talleres, fetchTalleres } = useTalleres();

const newIncidencia = reactive({
descripcion: '',
gravedad: '',
});

const newMantenimiento = reactive({
    descripcion: '',
    frecuencia: null,
    fechaSemanal:null
});

onMounted(async () => {
  await fetchTalleres();
});
const selectedMachine = ref(null);
const selectedTaller = ref(null);
const selectedMantenimientoTaller = ref([]);
const selectedMantenimientoMachine = ref([]);
const filters = reactive({
  gravedad: [
      { value: 'Maquina parada', label: 'Maquina parada' },
      { value: 'Aviso', label: 'Aviso' },
      { value: 'Maquina en Marcha', label: 'Maquina en Marcha' },
         { value: 'Mantenimiento', label: 'Mantenimiento' },
  ],
  prioridad: [
      { value: 'Alta', label: 'Alta' },
      { value: 'Media', label: 'Media' },
      { value: 'Baja', label: 'Baja' },
  ],
  estado: [
     { value: 'Abierta', label: 'Abierta' },
    { value: 'En progreso', label: 'En progreso' },
  ],
});

const selectedFilters = reactive({
  gravedad: [],
  prioridad: [],
  taller: [],
  estado: [],
});

const openFilters = reactive({
  gravedad: false,
  prioridad: false,
  taller:false,
  estado: false,
});

const toggleFilter = (filterName) => {
  openFilters[filterName] = !openFilters[filterName];
};

const filteredMaquinas = computed(() => {
    if (!selectedTaller.value) {
      return maquinas.value;
    }
   return maquinas.value.filter(maquina => maquina.id_taller === selectedTaller.value.id_taller);
});
const filteredMantenimientoMaquinas = computed(() => {
    if (selectedMantenimientoTaller.value.length === 0) {
        return maquinas.value;
    }
     const selectedTallerIds = selectedMantenimientoTaller.value.map(taller => taller.id_taller);
     return maquinas.value.filter(maquina => selectedTallerIds.includes(maquina.id_taller));
});

const filteredIncidencias = computed(() => {
    const tallerFilter = route.query.taller;

    return incidencias.value.filter((incidencia) => {
        const matchesSearch = Object.values(incidencia).some(
            (value) =>
                value &&
                value.toString().toLowerCase().includes(searchQuery.value.toLowerCase())
        );
        const matchesTaller = !tallerFilter || (incidencia.maquina?.taller?.nombre === tallerFilter);

        const matchesSelectTaller = selectedFilters.taller.length === 0 || selectedFilters.taller.includes(incidencia.maquina?.taller?.nombre);

        const matchesFilters =
            (selectedFilters.gravedad.length === 0 ||
                selectedFilters.gravedad.includes(incidencia.gravedad)) &&
            (selectedFilters.prioridad.length === 0 ||
                selectedFilters.prioridad.includes(incidencia.maquina.prioridad)) &&
            (selectedFilters.estado.length === 0 ||
                selectedFilters.estado.includes(incidencia.estado));

      return matchesSearch && matchesFilters && matchesTaller && matchesSelectTaller;
    });
});

const paginatedIncidencias = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
   return filteredIncidencias.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredIncidencias.value.length / itemsPerPage);
});

watch(
  () => selectedFilters,
  () => {
      currentPage.value = 1;
  },
 { deep: true }
);
watch(
  () => route.query,
  () => {
    currentPage.value = 1
  },
  { deep: true }
);

// ***************************************************************************************
// Aquí están los cambios en las funciones getEstadoClass, getPrioridadClass y getGravedadClass
// ***************************************************************************************

const getEstadoClass = (estado) => {
  switch (estado) {
    case 'Cancelada':
      return 'badge bg-primary text-info fw-normal';  
    case 'En progreso':
    return 'badge bg-primary text-black fw-normal border border-black border-dashed';
    case 'Resuelta':
      return 'badge bg-black text-primary fw-normal';
    case 'Abierta':
      return 'badge bg-info text-white fw-normal';
    default:
      return 'badge bg-info text-white fw-normal';
  }
};

const getPrioridadClass = (prioridad) => {
    console.log('Prioridad:', prioridad);
    switch (prioridad) {
      case 'Alta':
        return 'badge bg-fondoRojo text-danger';
      case 'Media':
        return 'badge bg-fondoNaranja text-naranja';
      case 'Baja':
        return 'badge bg-info text-white fw-normal';
      default:
        return 'badge bg-info text-white fw-normal';
    }
  };

const getGravedadClass = (gravedad) => {
  switch (gravedad) {
    case 'Maquina parada':
      return 'badge bg-danger text-white';  // Rojo
    case 'Aviso':
      return 'badge bg-warning text-dark'; // Amarillo (texto oscuro para contraste)
    case 'Maquina en Marcha':
      return 'badge bg-info text-white'; // Celeste
    case 'Mantenimiento':
      return 'badge bg-secondary text-white'; // Gris
    default:
      return 'badge bg-secondary text-white'; // Gris
  }
};
// ***************************************************************************************

const goToIncidencia = (id) => {
  router.push({ name: 'IncidenciaIndividual', params: { id: id } });
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');

    return `${day}/${month}/${year} ${hours}:${minutes}`;
};

const openModal = () => {
  showModal.value = true;
};
const openMantenimientoModal = () => {
    showMantenimientoModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  resetForm();
};
const closeMantenimientoModal = () => {
    showMantenimientoModal.value = false;
  resetMantenimientoForm();
};

const createIncidencia = async () => {
    try {
      if (!selectedMachine.value || !selectedTaller.value) {
        throw new Error('No Machine or Taller Selected');
      }
        const newIncidenciaToSend = {
             ...newIncidencia,
             id_maquina: selectedMachine.value.id_maquina,
        };
      await apiCreateIncidencia(newIncidenciaToSend, selectedMachine.value);
      closeModal();
    } catch (error) {
      console.error("Error creating incidencia:", error);
    }
  };
    const createMantenimiento = async () => {
    try {
        if (!selectedMantenimientoTaller.value || selectedMantenimientoTaller.value.length === 0 || selectedMantenimientoMachine.value.length === 0 ) {
            throw new Error('No Machine or Taller Selected');
        }

        const newIncidencias = selectedMantenimientoMachine.value.map(machine => ({
           ...newMantenimiento,
          id_maquina: machine.id_maquina,
           gravedad: 'Mantenimiento',
           frecuencia: newMantenimiento.frecuencia,
           fecha_ini: newMantenimiento.frecuencia === 'Semanal' ? newMantenimiento.fechaSemanal : new Date().toISOString(),

        }));

          await Promise.all(newIncidencias.map(incidenciaData => apiCreateIncidencia(incidenciaData, null)));
          closeMantenimientoModal();
    } catch (error) {
        console.error("Error creating mantenimiento:", error);
    }
  };



const resetForm = () => {
  newIncidencia.descripcion = '';
  selectedTaller.value = null;
  selectedMachine.value = null;
  newIncidencia.gravedad = '';
};
const resetMantenimientoForm = () => {
  newMantenimiento.descripcion = '';
  newMantenimiento.frecuencia = null;
  newMantenimiento.fechaSemanal = null;
    selectedMantenimientoTaller.value = [];
    selectedMantenimientoMachine.value = [];
};

const goToPage = (page) => {
   currentPage.value = page;
};
const previousPage = () => {
 if (currentPage.value > 1){
      currentPage.value--;
 }
};
const nextPage = () => {
  if (currentPage.value < totalPages.value)
   currentPage.value++;
};
</script>

<style scoped>
.row {
  margin-left: 3% !important;
  position: relative;
  overflow-x: hidden;
}

.filter-panel {
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  z-index: 1001;
  transition: transform 0.3s ease-out;
  /* Transición solo para el translateX */
  transform: translateX(-100%);
}

.filter-panel-content {
  position: relative;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.fade-enter-to,
.fade-leave-from {
  opacity: 1;
}

.main-content {
  transition: margin-left 0.3s ease-out,
      width 0.3s ease-out;
  position: relative;
  z-index: 1;
}

.main-content.col-md-9 {
  margin-left: 25%;
}

.filter-toggle {
  width: 10%;
  position: fixed;
  top: 10px;
  left: 10px;
  z-index: 1002;
  transition: opacity 0.3s ease-out,
      transform 0.3s ease-out;
}

.filter-toggle:not(:hover) {
  opacity: 0.7;
}

.filter-toggle.hidden {
  transform: translateX(-100%);
}


.modal {
  display: none;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1050;
  width: 100%;
  height: 100%;
  overflow-x: hidden;
  overflow-y: auto;
  outline: 0;
}

.modal.show {
  display: block;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1040;
  width: 100vw;
  height: 100vh;
  background-color: #000;
}

.modal-backdrop.fade {
  opacity: 0;
}

.modal-backdrop.show {
  opacity: 0.5;
}
.border-dashed {

  border-style: dashed !important;
  border-width: 2px !important;
}
</style>