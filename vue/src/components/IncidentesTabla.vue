<template>
  <div class="container-fluid bg-primary p-5">
 
      <!-- Panel de filtros (a la izquierda) -->
      <div
        :class="['filter-panel bg-secondary', 'col-md-3', 'p-4', 'shadow-sm', 'overflow-auto', 'vh-100']"
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
                  class="btn btn-outline-info w-100 text-start d-flex justify-content-between align-items-center"
                  @click="toggleFilter('gravedad')"
                >
                  <span>GRAVEDAD</span>
                  <i :class="['bi', openFilters.gravedad ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
                </button>
                <div class="mt-2" v-show="openFilters.gravedad">
                  <div v-for="option in filters.gravedad" :key="option.value" class="form-check">
                    <input
                      :id="'gravedad-' + option.value"
                      type="checkbox"
                      :value="option.value"
                      v-model="selectedFilters.gravedad"
                      class="form-check-input bg-secondary"
                    />
                    <label :for="'gravedad-' + option.value" class="form-check-label  px-2 text-black">{{
                      option.label
                    }}</label>
                  </div>
                </div>
              </div>

              <!-- Filtro de Prioridad -->
              <div class="mb-3">
                <button
                  class="btn btn-outline-info w-100 text-start d-flex justify-content-between align-items-center"
                  @click="toggleFilter('prioridad')"
                >
                  <span>PRIORIDAD</span>
                  <i :class="['bi', openFilters.prioridad ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
                </button>
                <div class="mt-2" v-show="openFilters.prioridad">
                  <div v-for="option in filters.prioridad" :key="option.value" class="form-check">
                    <input
                      :id="'prioridad-' + option.value"
                      type="checkbox"
                      :value="option.value"
                      v-model="selectedFilters.prioridad"
                      class="form-check-input bg-secondary"
                    />
                    <label :for="'prioridad-' + option.value" class="form-check-label  px-2 text-black">{{
                      option.label
                    }}</label>
                  </div>
                </div>
              </div>

              <!-- Filtro de Taller -->
              <div class="mb-3">
                <button
                  class="btn btn-outline-info w-100 text-start d-flex justify-content-between align-items-center"
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
                      class="form-check-input bg-secondary"
                    />
                    <label :for="'taller-' + taller.id_taller" class="form-check-label  px-2 text-black">{{
                      taller.nombre
                    }}</label>
                  </div>
                </div>
              </div>

              <!-- Filtro de Estado -->
              <div class="mb-3">
                <button
                  class="btn btn-outline-info w-100 text-start d-flex justify-content-between align-items-center"
                  @click="toggleFilter('estado')"
                >
                  <span>ESTADO</span>
                  <i :class="['bi', openFilters.estado ? 'bi-chevron-up' : 'bi-chevron-down']"></i>
                </button>
                <div class="mt-2" v-show="openFilters.estado">
                  <div v-for="option in filters.estado" :key="option.value" class="form-check">
                    <input
                      :id="'estado-' + option.value"
                      type="checkbox"
                      :value="option.value"
                      v-model="selectedFilters.estado"
                      class="form-check-input bg-secondary"
                    />
                    <label :for="'estado-' + option.value" class="form-check-label text-black px-2">{{
                      option.label
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
              

              <h1 class="text-2xl font-bold mx-5 px-3 mb-6">Incidencias</h1>

              <!-- Barra de búsqueda -->
              <div class="d-flex justify-content-around mb-4">
                  <div class="input-group w-50">
                      <span class="input-group-text">
                          <i class="bi bi-search"></i>
                      </span>
                      <input type="text" placeholder="Buscar..." v-model="searchQuery" class="form-control" />
                  </div>

                   <div class="d-flex justify-content-between">
                    <button v-show="!showFilters" class=" btn btn-primary mb-1"
                      :class="{ 'hidden': showFilters }" @click="showFilters = true">
                      <i class="bi bi-funnel"></i> Filtros
                    </button>
                     <button class="btn btn-secondary bg-black text-primary mx-3 ml-2" @click="openModal">
                        <i class="bi bi-plus-square"></i> Nueva Incidencia
                    </button>
                     <button class="btn btn-primary border border-secondary ml-2" @click="openMantenimientoModal">
                        <i class="bi bi-tools"></i> Crear Mantenimiento
                    </button>
                 </div>
              </div>

        <!-- Tabla -->
        <div class="container bg-primary">
        <div class="table-responsive">
          <table class="table table-hover">
            <thead class="table -light">
              <tr>
                <th class="bg-secondary text-info">TÍTULO</th>
                <th class="bg-secondary text-info">ESTADO</th>
                <th class="bg-secondary text-info">PRIORIDAD</th>
                <th class="bg-secondary text-info">FECHA</th>
                <th class="bg-secondary text-info">MÁQUINA</th>
                <th class="bg-secondary text-info">TALLER</th>
                <th class="bg-secondary text-info">CREADOR</th>
                <th class="bg-secondary text-info">TÉCNICO</th>
                <th class="bg-secondary text-info">GRAVEDAD</th>
              </tr>
            </thead>
            <tbody  class="table -light">
              <tr
                v-for="(incidencia, i) in paginatedIncidencias"
                :key="i"
                @click="goToIncidencia(incidencia.id_incidencia)"
                style="cursor: pointer;"
              >
                <td class="font-medium bg-primary">{{ incidencia.descripcion }}</td>
                <td class="font-medium bg-primary">   
                    {{ incidencia.estado }}
                </td>
                <td class="font-medium bg-primary">
                  <span :class="['badge', getPrioridadClass(incidencia.maquina.prioridad)]">
                    {{ incidencia.maquina.prioridad }}
                  </span>
                </td>
                <td class="font-medium bg-primary">{{ formatDate(incidencia.fecha_reporte) }}</td>
                <td class="font-medium bg-primary">{{ incidencia.maquina.nombre }}</td>
                <td class="font-medium bg-primary">
                  {{ incidencia.maquina?.taller?.nombre || 'Sin taller' }}
                </td>
                <td class="font-medium bg-primary">{{ incidencia.creador?.nombre || 'Sin creador' }}</td>
                <td class="font-medium bg-primary">{{ incidencia.tecnico ? incidencia.tecnico.nombre : 'Sin asignar' }}</td>
                <td class="font-medium bg-primary">
                  <span :class="['badge', getGravedadClass(incidencia.gravedad)]">
                    {{ incidencia.gravedad }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

        <!-- Paginación -->
        <nav aria-label="Page navigation" class="mt-4">
          <ul class="pagination justify-content-center">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
              <button class="page-link text-black" @click="previousPage" aria-label="Previous">
                <span aria-hidden="true">«</span>
              </button>
            </li>
            <li
              v-for="page in totalPages"
              :key="page"
              class="page-item"
              :class="{ active: page === currentPage }"
            >
              <button class="page-link text-black" @click="goToPage(page)">{{ page }}</button>
            </li>
            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
              <button class="page-link text-black" @click="nextPage" aria-label="Next">
                <span aria-hidden="true">»</span>
              </button>
            </li>
          </ul>
        </nav>
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
              <input
                type="text"
                class="form-control"
                id="mantenimiento-titulo"
                v-model="newMantenimiento.descripcion"
                required
              />
            </div>
            <div class="mb-3">
              <label for="mantenimiento-taller" class="form-label">Taller</label>
              <div
                class="mt-2"
                style="
                                max-height: 150px;
                                overflow-y: auto;
                                border: 1px solid #ced4da;
                                padding: 5px;
                                border-radius: 5px;
                              "
              >
                <div v-for="taller in talleres" :key="taller.id_taller" class="form-check">
                  <input
                    :id="'taller-mantenimiento-' + taller.id_taller"
                    type="checkbox"
                    :value="taller"
                    v-model="selectedMantenimientoTaller"
                    class="form-check-input"
                  />
                  <label :for="'taller-mantenimiento-' + taller.id_taller" class="form-check-label">{{
                    taller.nombre
                  }}</label>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="mantenimiento-maquina" class="form-label">Máquina</label>
              <div
                class="mt-2"
                style="
                                max-height: 250px;
                                overflow-y: auto;
                                border: 1px solid #ced4da;
                                padding: 5px;
                                border-radius: 5px;
                              "
              >
                <div
                  v-for="maquina in filteredMantenimientoMaquinas"
                  :key="maquina.id_maquina"
                  class="form-check"
                >
                  <input
                    :id="'maquina-mantenimiento-' + maquina.id_maquina"
                    type="checkbox"
                    :value="maquina"
                    v-model="selectedMantenimientoMachine"
                    class="form-check-input"
                  />
                  <label :for="'maquina-mantenimiento-' + maquina.id_maquina" class="form-check-label">{{
                    maquina.nombre
                  }}</label>
                </div>
              </div>
            </div>
            <div class="mb-3">
              <label for="mantenimiento-frecuencia-tipo" class="form-label">Frecuencia</label>
              <select
                class="form-select"
                id="mantenimiento-frecuencia-tipo"
                v-model="newMantenimiento.frecuenciaTipo"
                required
              >
                <option value="">Selecciona una frecuencia</option>
                <option value="Dias">Día(s)</option>
                <option value="Semanas">Semana(s)</option>
                <option value="Meses">Mes(es)</option>
                <option value="Años">Año(s)</option>
              </select>
            </div>
            <div class="mb-3" v-if="newMantenimiento.frecuenciaTipo">
              <label for="mantenimiento-frecuencia-cantidad" class="form-label">Cantidad</label>
              <input
                type="number"
                class="form-control"
                id="mantenimiento-frecuencia-cantidad"
                v-model.number="newMantenimiento.frecuenciaCantidad"
                required
              />
            </div>
            <div class="mb-3">
              <label for="mantenimiento-tipo" class="form-label">Tipo de Mantenimiento</label>
              <select
      class="form-select"
      id="mantenimiento-tipo"
      v-model="selectedTipoMantenimiento"
      required
    >
      <option value="">Selecciona un tipo de mantenimiento</option>
      <option v-for="tipo in tiposMantenimiento" :key="tipo.id_tipo_mantenimiento" :value="tipo.id_tipo_mantenimiento">
        {{ tipo.nombre }}
      </option>
    </select>
            </div>
            <div class="mb-3" v-if="newMantenimiento.frecuencia === 'Semanal'">
              <label for="mantenimiento-fecha-semanal" class="form-label">Día de la semana</label>
              <input
                type="date"
                class="form-control"
                id="mantenimiento-fecha-semanal"
                v-model="newMantenimiento.fechaSemanal"
                required
              />
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="closeMantenimientoModal">
              Cancelar
            </button>
            <button type="button" class="btn btn-primary" @click="createMantenimiento">Guardar</button>
          </div>
        </div>
      </div>
    </div>
    <div v-if="showMantenimientoModal" class="modal-backdrop fade show"></div>

    <!-- Modal para crear incidencia -->
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
        <input
          type="text"
          class="form-control"
          id="incidencia-titulo"
          v-model="newIncidencia.descripcion"
          required
        />
      </div>
      <div class="mb-3">
        <label for="incidencia-taller" class="form-label">Taller</label>
        <select class="form-select" id="incidencia-taller" v-model="selectedTaller" required @change="updateFilteredMachines">
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
            {{ maquina.nombre }}
          </option>
        </select>
      </div>


      <div class="mb-3">
        <label for="incidencia-tipo-averia" class="form-label">Tipo de Avería</label>
        <select
          class="form-select"
          id="incidencia-tipo-averia"
          v-model="selectedTipoAveria"
          required
        >
          <option value="">Selecciona un tipo de avería</option>
          <option v-for="tipoAveria in tipoAverias" :key="tipoAveria.id_tipo_averia" :value="tipoAveria">
            {{ tipoAveria.nombre }}
          </option>
        </select>
      </div>

      <div class="mb-3">
        <label for="incidencia-gravedad" class="form-label">Gravedad</label>
        <select
          class="form-select"
          id="incidencia-gravedad"
          v-model="newIncidencia.gravedad"
          required
        >
          <option v-for="option in filters.gravedad" :key="option.value" :value="option.value">{{
            option.label
          }}</option>
        </select>
      </div>

      <div class="mb-3">
        <label for="incidencia-multimedia" class="form-label">Subir Multimedia</label>
        <input 
          type="file" 
          class="form-control" 
          id="incidencia-multimedia" 
          @change="handleFileChange"
        />
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" @click="closeModal">Cancelar</button>
      <button type="button" class="btn btn-primary" @click="nuevaIncidencia">Guardar</button>
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
import useTipoAveria from '@/composables/useTipoAveria';
import useTipoMantenimiento from '@/composables/useTiposMantenimiento';
import { ref, reactive, computed, watch, onMounted } from 'vue';

const getPrioridadClass = (prioridad) => {
    console.log('Prioridad:', prioridad);
    switch (prioridad) {
      case 'Alta':
        return 'badge bg-info text-primary fw-normal';
      case 'Media':
        return 'badge bg-secondary text-info fw-normal';
      case 'Baja':
        return 'badge bg-primary text-info fw-normal border border-secondary';
      default:
        return 'badge bg-info text-white fw-normal';
    }
  };

const getGravedadClass = (gravedad) => {
  switch (gravedad) {
    case 'Maquina parada':
    return 'badge bg-fondoRojo text-danger border border-naranja';
    case 'Aviso':
    return 'badge bg-fondoNaranja text-naranja border border-naranja';
    case 'Maquina en Marcha':
    return 'badge bg-primary text-info bg-opacity-10 border border-info';
    case 'Mantenimiento':
      return 'badge bg-secondary text-info border border-info'; // Gris
    default:
      return 'badge bg-secondary text-white'; // Gris
  }
};


const router = useRouter();
const route = useRoute();
const showFilters = ref(false);
const searchQuery = ref('');
const showModal = ref(false);
const showMantenimientoModal = ref(false);
const itemsPerPage = 30;
const currentPage = ref(1);

const { maquinas } = useMaquinas();
const { incidencias, createIncidencia } = useIncidencias();
const { talleres, fetchTalleres } = useTalleres();
const { tipoAverias, fetchTipoAverias } = useTipoAveria();
const { tiposMantenimiento, fetchTiposMantenimiento } = useTipoMantenimiento();
const selectedTipoAveria = ref(null);
const selectedTipoMantenimiento = ref(null);

const newIncidencia = reactive({
  descripcion: '',
  gravedad: '',
});
const multimediaFile = ref(null);

// Manejar la selección de archivos
const handleFileChange = (event) => {
  const file = event.target.files[0];
  if (file) {
    multimediaFile.value = file;
  }
};
const selectedMaquinas = ref([]);
const newMantenimiento = reactive({
  descripcion: '',
  frecuenciaTipo: null,
  frecuenciaCantidad: null,
  frecuencia: null,
  fechaSemanal: null,
  multimedia: null,
});
watch(selectedMaquinas, (newValue) => {
console.log('selectedMaquinas changed:', newValue);
});
onMounted(async () => {
  await fetchTalleres();
  await fetchTipoAverias();
  await fetchTiposMantenimiento();
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
    { value: 'Resuelta', label: 'Resuelta' },
    { value: 'Cancelada', label: 'Cancelada' },
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
  taller: false,
  estado: false,
});

const toggleFilter = (filterName) => {
  openFilters[filterName] = !openFilters[filterName];
};

const filteredMaquinas = computed(() => {
  if (!selectedTaller.value) {
    return maquinas.value;
  }
  return maquinas.value.filter((maquina) => maquina.id_taller === selectedTaller.value.id_taller);
});
const filteredMantenimientoMaquinas = computed(() => {
  if (selectedMantenimientoTaller.value.length === 0) {
    return maquinas.value;
  }
  const selectedTallerIds = selectedMantenimientoTaller.value.map((taller) => taller.id_taller);
  return maquinas.value.filter((maquina) => selectedTallerIds.includes(maquina.id_taller));
});

const filteredIncidencias = computed(() => {
  const tallerFilter = route.query.taller;

  return incidencias.value.filter((incidencia) => {
    const matchesSearch = Object.values(incidencia).some(
      (value) => value && value.toString().toLowerCase().includes(searchQuery.value.toLowerCase())
    );
    const matchesTaller =
      !tallerFilter || incidencia.maquina?.taller?.nombre === tallerFilter;

    const matchesSelectTaller =
      selectedFilters.taller.length === 0 ||
      selectedFilters.taller.includes(incidencia.maquina?.taller?.nombre);

    const matchesFilters =
      (selectedFilters.gravedad.length === 0 ||
        selectedFilters.gravedad.includes(incidencia.gravedad)) &&
      (selectedFilters.prioridad.length === 0 ||
        selectedFilters.prioridad.includes(incidencia.maquina.prioridad)) &&
      (selectedFilters.estado.length === 0 || selectedFilters.estado.includes(incidencia.estado));

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
    currentPage.value = 1;
  },
  { deep: true }
);




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

const nuevaIncidencia = async () => {
  try {
    if (!selectedMachine.value || !selectedTaller.value) {
      throw new Error('No Machine or Taller Selected');
    }
    if (!selectedTipoAveria.value) {
      throw new Error('No tipo Averia Selected');
    }

    const userData = JSON.parse(localStorage.getItem('user_data'));
    
    const formData = new FormData();
    formData.append("descripcion", newIncidencia.descripcion);
    formData.append("gravedad", newIncidencia.gravedad);
    formData.append("id_maquina", selectedMachine.value.id_maquina);
    formData.append("id_tipo_averia", selectedTipoAveria.value.id_tipo_averia);
    formData.append("estado", "Abierta");
    formData.append("id_creador", userData.id);

    if (multimediaFile.value) {
      formData.append("multimedia", multimediaFile.value);
    }

    console.log('multimedia: ', multimediaFile.value);
    console.log('formData: ', formData);
    
    await createIncidencia(formData);
    closeModal();
  } catch (error) {
    console.error('Error creating incidencia:', error);
  }
};

const createMantenimiento = async () => {
try {
   if (selectedMantenimientoMachine.value.length === 0) {
       throw new Error('Debe seleccionar al menos una máquina.');
   }

   if (!newMantenimiento.descripcion) {
       throw new Error('Debe indicar una descripción para el mantenimiento.');
   }
   if (!selectedTipoMantenimiento.value) {
       throw new Error('Debe seleccionar un tipo de mantenimiento.');
   }
   if (!newMantenimiento.frecuenciaTipo || !newMantenimiento.frecuenciaCantidad) {
       throw new Error('Frecuencia o cantidad no introducida');
   }

   const token = localStorage.getItem('jwt_token');
   if (!token) {
       throw new Error('Usuario no autenticado');
   }

   const frecuenciaEnDias = calculateTotalDays();

   const newMantenimientoToSend = {
       maquinas: selectedMantenimientoMachine.value.map(maquina => ({ id_maquina: maquina.id_maquina })),
       id_tipo_mantenimiento: selectedTipoMantenimiento.value,
       descripcion: newMantenimiento.descripcion,
       gravedad: 'Mantenimiento',
       estado: 'Abierta',
       frecuencia: frecuenciaEnDias
   };

   const response = await fetch('http://localhost:8000/api/mantenimientos', {
       method: 'POST',
       headers: {
           'Content-Type': 'application/json',
           'Authorization': `Bearer ${token}`
       },
       body: JSON.stringify(newMantenimientoToSend)
   });

   if (!response.ok) {
       const responseText = await response.text();
       console.error("Error response text:", responseText);
       try {
           const errorData = JSON.parse(responseText);
           console.error("Parsed error data:", errorData);
           throw new Error(`Error al crear el mantenimiento: ${response.status} - ${errorData.message || response.statusText}`);
       } catch (jsonError) {
           throw new Error(`Error al crear el mantenimiento: ${response.status} - ${jsonError || response.statusText}`);
       }
   }

   const createdMantenimiento = await response.json();
   console.log("Mantenimiento creado:", createdMantenimiento);

   closeMantenimientoModal();

} catch (error) {
   console.error('Error al crear el mantenimiento:', error);
   alert(error.message);
}
};
const calculateTotalDays = () => {
let days = 0;
switch (newMantenimiento.frecuenciaTipo) {
  case 'Dias':
    days = newMantenimiento.frecuenciaCantidad;
    break;
  case 'Semanas':
    days = newMantenimiento.frecuenciaCantidad * 7;
    break;
  case 'Meses':
    days = newMantenimiento.frecuenciaCantidad * 30;
    break;
  case 'Años':
    days = newMantenimiento.frecuenciaCantidad * 365;
    break;
  default:
    days = 0;
}
return days;
};


const resetForm = () => {
  newIncidencia.descripcion = '';
  selectedTaller.value = null;
  selectedMachine.value = null;
  newIncidencia.gravedad = '';
  selectedTipoAveria.value = null;
};
const resetMantenimientoForm = () => {
  newMantenimiento.descripcion = '';
  newMantenimiento.frecuenciaTipo = null;
  newMantenimiento.frecuenciaCantidad = null;
  newMantenimiento.frecuencia = null;
  newMantenimiento.fechaSemanal = null;
  selectedMantenimientoTaller.value = [];
  selectedMantenimientoMachine.value = [];
  selectedTipoMantenimiento.value = null;
};

const goToPage = (page) => {
  currentPage.value = page;
};
const previousPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};
const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
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
  width: 10%;
  position: fixed;
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

.badge {
  display: inline-block;
  padding: 0.25em 0.5em;
}
  .fontbadge {
  display: inline-block;
  padding: 0.25em 0.5em;
  font-size: 0.75em;
  font-weight: 700;
  line-height: 1;
  text-align: center;
      vertical-align: baseline;
  border-radius: 0.25rem;
}
.badge:hover {
    color: inherit;
    background-color: inherit;
    border-color: inherit;
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
opacity: 0;
}
.btn-outline-info:hover{
  background-color: #828282;
  color: #d1d1d1;
}
.btn-outline-info:focus{
box-shadow: 0 0 5px #828282;
outline: none;
}
</style>