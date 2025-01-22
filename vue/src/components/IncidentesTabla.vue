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
            <button
              @click="showFilters = false"
              class="btn-close position-absolute top-0 end-0 m-2"
              aria-label="Cerrar filtros"
            ></button>

            <!-- Filtros -->
            <div class="filters">
              <!-- Filtro de Gravedad -->
              <div class="mb-3">
                <button
                  class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center"
                  @click="toggleFilter('gravedad')"
                >
                  <span>GRAVEDAD</span>
                  <i
                    :class="['bi', openFilters.gravedad ? 'bi-chevron-up' : 'bi-chevron-down']"
                  ></i>
                </button>
                <div class="mt-2" v-show="openFilters.gravedad">
                  <div
                    v-for="option in filters.gravedad"
                    :key="option.value"
                    class="form-check"
                  >
                    <input
                      :id="'gravedad-' + option.value"
                      type="checkbox"
                      :value="option.value"
                      v-model="selectedFilters.gravedad"
                      class="form-check-input"
                    />
                    <label
                      :for="'gravedad-' + option.value"
                      class="form-check-label"
                      >{{ option.label }}</label
                    >
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
                  <i
                    :class="['bi', openFilters.prioridad ? 'bi-chevron-up' : 'bi-chevron-down']"
                  ></i>
                </button>
                <div class="mt-2" v-show="openFilters.prioridad">
                  <div
                    v-for="option in filters.prioridad"
                    :key="option.value"
                    class="form-check"
                  >
                    <input
                      :id="'prioridad-' + option.value"
                      type="checkbox"
                      :value="option.value"
                      v-model="selectedFilters.prioridad"
                      class="form-check-input"
                    />
                    <label
                      :for="'prioridad-' + option.value"
                      class="form-check-label"
                      >{{ option.label }}</label
                    >
                  </div>
                </div>
              </div>

              <!-- Filtro de Campus -->
              <div class="mb-3">
                <button
                  class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center"
                  @click="toggleFilter('campus')"
                >
                  <span>CAMPUS</span>
                  <i
                    :class="['bi', openFilters.campus ? 'bi-chevron-up' : 'bi-chevron-down']"
                  ></i>
                </button>
                <div class="mt-2" v-show="openFilters.campus">
                  <div
                    v-for="option in filters.campus"
                    :key="option.value"
                    class="form-check"
                  >
                    <input
                      :id="'campus-' + option.value"
                      type="checkbox"
                      :value="option.value"
                      v-model="selectedFilters.campus"
                      class="form-check-input"
                    />
                    <label :for="'campus-' + option.value" class="form-check-label"
                      >{{ option.label }}</label
                    >
                  </div>
                </div>
              </div>

              <!-- Filtro de Estado -->
              <div class="mb-3">
                <button
                  class="btn btn-outline-secondary w-100 text-start d-flex justify-content-between align-items-center"
                  @click="toggleFilter('estado')"
                >
                  <span>ESTADO</span>
                  <i
                    :class="['bi', openFilters.estado ? 'bi-chevron-up' : 'bi-chevron-down']"
                  ></i>
                </button>
                <div class="mt-2" v-show="openFilters.estado">
                  <div
                    v-for="option in filters.estado"
                    :key="option.value"
                    class="form-check"
                  >
                    <input
                      :id="'estado-' + option.value"
                      type="checkbox"
                      :value="option.value"
                      v-model="selectedFilters.estado"
                      class="form-check-input"
                    />
                    <label :for="'estado-' + option.value" class="form-check-label"
                      >{{ option.label }}</label
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
        </transition>
      </div>

      <!-- Contenido principal -->
      <div :class="['main-content', 'col', { 'col-md-9': showFilters }]">
        <h1 class="h2 mb-4">Incidencias</h1>

        <!-- Barra de búsqueda -->
        <div class="mb-4 d-flex justify-content-between align-items-center">
          <div class="input-group w-50">
            <span class="input-group-text">
              <i class="bi bi-search"></i>
            </span>
            <input
              type="text"
              placeholder="Buscar..."
              v-model="searchQuery"
              class="form-control"
            />
          </div>
           <div>
          <button
            v-show="!showFilters"
            class="filter-toggle btn btn-primary mb-1 me-2"
            :class="{ 'hidden': showFilters }"
            @click="showFilters = true"
          >
            <i class="bi bi-funnel"></i> Filtros
          </button>
           <button
              class="btn btn-success mb-1"
              @click="openModal = true"
            >
              <i class="bi bi-plus"></i> Crear Incidencia
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
                <th>CREADOR</th>
                <th>TÉCNICO</th>
                <th>GRAVEDAD</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(incidencia, i) in filteredIncidencias"
                :key="i"
                @click="goToIncidencia(incidencia.id_incidencia)"
                style="cursor: pointer;"
              >
                <td class="fw-medium">{{ incidencia.descripcion }}</td>
                <td>
                  <span :class="['badge', getEstadoClass(incidencia.estado)]">
                    {{ incidencia.estado }}
                  </span>
                </td>
                <td>
                  <span
                    :class="['badge', getPrioridadClass(incidencia.maquina.prioridad)]"
                  >
                    {{ incidencia.maquina.prioridad }}
                  </span>
                </td>
                <td>{{ incidencia.fecha_reporte }}</td>
                <td>{{ incidencia.maquina.nombre }}</td>
                <td>{{ incidencia.creador.nombre }}</td>
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
      </div>
    </div>

    <!-- Modal de creación de incidencia -->
    <div class="modal fade" :class="{ show: openModal }" style="display: none;" >
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Crear Incidencia</h5>
            <button type="button" class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
             <div class="mb-3">
                <label class="form-label">Descripción de la Incidencia</label>
                <textarea class="form-control" v-model="newIncidencia.descripcion"></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">Gravedad</label>
              <select class="form-select" v-model="newIncidencia.gravedad">
                <option value="Maquina parada">Maquina parada</option>
                <option value="Maquina en marcha">Maquina en marcha</option>
                <option value="Aviso">Aviso</option>
                <option value="Mantenimiento">Mantenimiento</option>
              </select>
            </div>
             <!-- Opciones adicionales para Mantenimiento -->
            <div v-if="newIncidencia.gravedad === 'Mantenimiento'">
                <div class="mb-3">
                    <label class="form-label">Tipo de Mantenimiento</label>
                    <select class="form-select" v-model="newIncidencia.tipo_mantenimiento">
                      <option v-for="tipo in tiposMantenimiento" :key="tipo.id_tipo_mantenimiento" :value="tipo.id_tipo_mantenimiento">{{tipo.nombre}}</option>
                    </select>
                </div>
                  <div class="mb-3">
                    <label class="form-label">Frecuencia de Mantenimiento</label>
                    <select class="form-select" v-model="newIncidencia.frecuencia">
                        <option value="diaria">Diaria</option>
                        <option value="semanal">Semanal</option>
                        <option value="mensual">Mensual</option>
                        <option value="anual">Anual</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Multimedia</label>
              <input type="file" class="form-control" @change="handleMultimediaUpload" accept="image/*, video/*">
               <div v-if="newIncidencia.multimedia">
                    <div v-if="newIncidencia.multimedia.startsWith('data:image')">
                    <img :src="newIncidencia.multimedia" alt="Imagen" style="max-width: 100px; max-height: 100px; margin-top: 5px;">
                    </div>
                    <div v-else-if="newIncidencia.multimedia.startsWith('data:video')">
                        <video :src="newIncidencia.multimedia" controls width="100" height="100" style="margin-top: 5px;"></video>
                    </div>
                    <p style="margin-top: 5px;">Multimedia seleccionado</p>
                </div>
            </div>
           <div class="mb-3">
              <label class="form-label">Taller</label>
              <select class="form-select" v-model="selectedTaller" @change="fetchMaquinas">
                <option v-for="taller in talleres" :key="taller.id_taller" :value="taller.id_taller">{{ taller.nombre }}</option>
              </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Máquina</label>
                  <select class="form-select" v-model="newIncidencia.id_maquina" :disabled="!maquinas.length">
                   <option v-for="maquina in maquinas" :key="maquina.id_maquina" :value="maquina.id_maquina">{{ maquina.nombre }}</option>
                  </select>
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeModal">Cancelar</button>
            <button type="button" class="btn btn-primary" @click="createIncidencia">Crear Incidencia</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router';
import useIncidencias from '@/composables/useIncidencias';
import useTalleres from '@/composables/useTalleres';
import useMaquinas from '@/composables/useMaquinas';
import useTiposMantenimiento from '@/composables/useTiposMantenimiento';
import { ref, reactive, computed } from 'vue';

const router = useRouter();
const showFilters = ref(false);
const searchQuery = ref('');

// Obtener datos
const { incidencias, createIncidencia: createIncidenciaApi } = useIncidencias();
const { talleres } = useTalleres();
const { maquinas, fetchMaquinasByTallerId} = useMaquinas();
const { tiposMantenimiento } = useTiposMantenimiento();


const filters = reactive({
  gravedad: [
    { value: 'Maquina parada', label: 'Maquina parada' },
    { value: 'Aviso', label: 'Aviso' },
    { value: 'Mantenimiento', label: 'Mantenimiento' },
  ],
  prioridad: [
    { value: 'Alta', label: 'Alta' },
    { value: 'Media', label: 'Media' },
    { value: 'Baja', label: 'Baja' },
  ],
  campus: [
    { value: 'Taller A', label: 'Taller A' },
    { value: 'Taller B', label: 'Taller B' },
    { value: 'Taller C', label: 'Taller C' },
  ],
  estado: [
    { value: 'Abierta', label: 'Abierta' },
    { value: 'En progreso', label: 'En progreso' },
    { value: 'Resuelta', label: 'Resuelta' },
    { value: 'Cancelada', label: 'Cancelada' },
  ],
});

const selectedFilters = reactive({
  gravedad: [],
  prioridad: [],
  campus: [],
  estado: [],
});

const openFilters = reactive({
  gravedad: false,
  prioridad: false,
  campus: false,
  estado: false,
});

const toggleFilter = (filterName) => {
  openFilters[filterName] = !openFilters[filterName];
};


const filteredIncidencias = computed(() => {
  return incidencias.value.filter((incidencia) => {
    const matchesSearch = Object.values(incidencia).some(
      (value) =>
        value &&
        value.toString().toLowerCase().includes(searchQuery.value.toLowerCase())
    );

    const matchesFilters =
      (selectedFilters.gravedad.length === 0 ||
        selectedFilters.gravedad.includes(incidencia.gravedad)) &&
      (selectedFilters.prioridad.length === 0 ||
        selectedFilters.prioridad.includes(incidencia.maquina.prioridad)) &&
      (selectedFilters.campus.length === 0 ||
        selectedFilters.campus.includes(incidencia.maquina.campus)) &&
      (selectedFilters.estado.length === 0 ||
        selectedFilters.estado.includes(incidencia.estado));

    return matchesSearch && matchesFilters;
  });
});


const getEstadoClass = (estado) => {
  switch (estado) {
    case 'Abierta':
      return 'badge-abierta';
    case 'En progreso':
      return 'badge-en-progreso';
    case 'Resuelta':
      return 'badge-resuelta';
    case 'Cancelada':
      return 'badge-cancelada';
    default:
      return '';
  }
};


const getPrioridadClass = (prioridad) => {
    switch (prioridad) {
      case 'Alta':
        return 'badge-alta';
      case 'Media':
        return 'badge-media';
      case 'Baja':
        return 'badge-baja';
      default:
        return '';
    }
  };


const getGravedadClass = (gravedad) => {
  switch (gravedad) {
    case 'Maquina parada':
      return 'badge-maquina-parada';
    case 'Aviso':
      return 'badge-aviso';
    case 'Mantenimiento':
      return 'badge-mantenimiento';
    default:
      return '';
  }
};

const goToIncidencia = (id) => {
  router.push({ name: 'IncidenciaIndividual', params: { id: id } });
};

//MODAL DE CREAR INCIDENCIA
const openModal = ref(false);
const newIncidencia = reactive({
  descripcion: '',
  gravedad: 'Maquina parada',
    tipo_mantenimiento: null,
    frecuencia: null,
    multimedia: null,
    id_maquina: null,

});

const selectedTaller = ref(null);

const closeModal = () => {
  openModal.value = false;
  resetNewIncidencia();
};


const resetNewIncidencia = () => {
    newIncidencia.descripcion = '';
    newIncidencia.gravedad = 'Maquina parada';
    newIncidencia.tipo_mantenimiento = null;
    newIncidencia.frecuencia = null;
    newIncidencia.multimedia = null;
    newIncidencia.id_maquina = null;
    selectedTaller.value = null;
};


const handleMultimediaUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
      const reader = new FileReader();
      reader.onload = (e) => {
          newIncidencia.multimedia = e.target.result;
      };
      reader.readAsDataURL(file);
    }
};


const createIncidencia = async () => {
   try {
    await createIncidenciaApi(newIncidencia);
    closeModal();
    } catch (error) {
    console.error('Error al crear la incidencia:', error);
    }
};

const fetchMaquinas = async () => {
    if (selectedTaller.value) {
        await fetchMaquinasByTallerId(selectedTaller.value);
    } else {
        maquinas.value = [];
    }
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
  transition: transform 0.3s ease-out; /* Transición solo para el translateX */
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
  transition: margin-left 0.3s ease-out, width 0.3s ease-out;
  position: relative;
  z-index: 1;
}

.main-content.col-md-9 {
  margin-left: 25%;
}

.filter-toggle {
  top: 10px;
  left: 10px;
  z-index: 1002;
  transition: opacity 0.3s ease-out, transform 0.3s ease-out;
}

.filter-toggle:not(:hover) {
  opacity: 0.7;
}

.filter-toggle.hidden {
  transform: translateX(-100%);
}

/* Clases para las insignias */
.badge {
    display: inline-block;
    padding: 0.25em 0.5em;
    font-size: 0.75em;
    font-weight: 700;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.25rem;
}

.badge-abierta {
  background-color: #dc3545;
  color: white;
}

.badge-en-progreso {
    background-color: #ffc107;
    color: black;
}

.badge-resuelta {
  background-color: #198754;
  color: white;
}

.badge-cancelada {
  background-color: #6c757d;
  color: white;
}

.badge-alta {
  background-color: #dc3545;
  color: white;
}

.badge-media {
  background-color: #ffc107;
  color: black;
}

.badge-baja {
  background-color: #28a745;
  color: white;
}

.badge-maquina-parada {
  background-color: #dc3545;
  color: white;
}

.badge-aviso {
  background-color: #ffc107;
  color: black;
}

.badge-mantenimiento {
  background-color: #17a2b8;
  color: white;
}


.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1050;
    transition: opacity 0.3s ease;
    opacity: 0;
    pointer-events: none;
  }

.modal.show {
    opacity: 1;
    pointer-events: auto;
  }
  .modal-dialog{
     max-width: 600px;
     margin: 1.75rem auto;
  }
</style>