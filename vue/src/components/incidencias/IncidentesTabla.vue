<template>
  <div class="container-fluid bg-primary p-5">
    <div class="row">
      <!-- Panel de filtros (a la izquierda) -->
      <FilterPanel
        :showFilters="showFilters"
        @toggle="showFilters = !showFilters"
        :filters="filters"
        :talleres="talleres"
        :selectedFilters="selectedFilters"
      />

      <!-- Contenido principal -->
      <div :class="['main-content', 'col', { 'col-md-9': showFilters }]">
        <h1 class="h2 mb-4 px-4">Incidencias</h1>

        <div class="d-flex justify-content-around mb-4">
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

          <div class="d-flex justify-content-between">
            <button
              v-show="!showFilters"
              class="btn btn-primary mb-1"
              :class="{ 'hidden': showFilters }"
              @click="showFilters = true"
            >
              <i class="bi bi-funnel"></i> Filtros
            </button>
            <button class="btn btn-secondary bg-black text-primary mx-3 ml-2" @click="openModal">
              <i class="bi bi-plus-square"></i> Nueva Incidencia
            </button>
            <button
              v-if="isAdmin"
              class="btn btn-primary border border-secondary ml-2"
              @click="openMantenimientoModal"
            >
              <i class="bi bi-tools"></i> Crear Mantenimiento
            </button>
          </div>
        </div>
        <IncidenciaTable
          :incidencias="filteredIncidencias"
          :searchQuery="searchQuery"
          :currentPage="currentPage"
          :itemsPerPage="itemsPerPage"
          @page-change="currentPage = $event"
        />
      </div>
    </div>
    <!-- Modal para crear incidencia -->
    <NewIncidenciaModal
      :showModal="showModal"
      :talleres="talleres"
      :maquinas="maquinas"
      :filteredMaquinas="filteredMaquinas"
      :tipoAverias="tipoAverias"
      :filters="filters"
      :selectedTaller="selectedTaller"
      :selectedMachine="selectedMachine"
      :selectedTipoAveria="selectedTipoAveria"
      @update:selectedTaller="selectedTaller = $event"
      @update:selectedMachine="selectedMachine = $event"
      @update:selectedTipoAveria="selectedTipoAveria = $event"
      @close-modal="closeModal"
      @create-incidencia="createIncidencia"
    />
    <!-- Modal para crear mantenimiento (solo visible para administradores) -->
    <NewMantenimientoModal
      :showModal="showMantenimientoModal"
      :talleres="talleres"
      :maquinas="maquinas"
      :filteredMaquinas="filteredMantenimientoMaquinas"
      :tiposMantenimiento="tiposMantenimiento"
      :selectedMantenimientoTaller="selectedMantenimientoTaller"
      :selectedMantenimientoMachine="selectedMantenimientoMachine"
      :selectedTipoMantenimiento="selectedTipoMantenimiento"
      @update:selectedMantenimientoTaller="selectedMantenimientoTaller = $event"
      @update:selectedMantenimientoMachine="selectedMantenimientoMachine = $event"
      @update:selectedTipoMantenimiento="selectedTipoMantenimiento = $event"
      @close-modal="closeMantenimientoModal"
      @create-mantenimiento="createMantenimiento"
    />
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import FilterPanel from './FilterPanel.vue';
import IncidenciaTable from './IncidenciaTable.vue';
import NewIncidenciaModal from './NewIncidenciaModal.vue';
import NewMantenimientoModal from './NewMantenimientoModal.vue';
import useIncidencias from '@/composables/useIncidencias';
import useMaquinas from '@/composables/useMaquinas';
import useTalleres from '@/composables/useTalleres';
import useTipoAveria from '@/composables/useTipoAveria';
import useTipoMantenimiento from '@/composables/useTiposMantenimiento';

const router = useRouter();
const route = useRoute();
const showFilters = ref(false);
const searchQuery = ref('');
const showModal = ref(false);
const showMantenimientoModal = ref(false);
const itemsPerPage = 30;
const currentPage = ref(1);

const { maquinas } = useMaquinas();
const { incidencias, createIncidencia: apiCreateIncidencia } = useIncidencias();
const { talleres, fetchTalleres } = useTalleres();
const { tipoAverias, fetchTipoAverias } = useTipoAveria();
const { tiposMantenimiento, fetchTiposMantenimiento } = useTipoMantenimiento();
const selectedTipoAveria = ref(null);
const selectedTipoMantenimiento = ref(null);
const selectedMachine = ref(null);
const selectedTaller = ref(null);
const selectedMantenimientoTaller = ref([]);
const selectedMantenimientoMachine = ref([]);

const newIncidencia = reactive({
  descripcion: '',
  gravedad: '',
});

const newMantenimiento = reactive({
  descripcion: '',
  frecuenciaTipo: null,
  frecuenciaCantidad: null,
  frecuencia: null,
  fechaSemanal: null,
  multimedia: null,
});

// Determine if the user is an admin
const isAdmin = computed(() => {
  const userData = JSON.parse(localStorage.getItem('user_data') || '{}');
  return userData && userData.rol === 'Administrador';
});

onMounted(async () => {
  await fetchTalleres();
  await fetchTipoAverias();
  await fetchTiposMantenimiento();
  console.log('User role:', JSON.parse(localStorage.getItem('user_data') || '{}').rol);
  console.log('Is Admin:', isAdmin.value);
});

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

const filteredMaquinas = computed(() => {
  if (!selectedTaller.value) {
    return maquinas.value;
  }
  return maquinas.value.filter(
    (maquina) => maquina.id_taller === selectedTaller.value.id_taller
  );
});

const filteredMantenimientoMaquinas = computed(() => {
  if (selectedMantenimientoTaller.value.length === 0) {
    return maquinas.value;
  }
  const selectedTallerIds = selectedMantenimientoTaller.value.map(
    (taller) => taller.id_taller
  );
  return maquinas.value.filter((maquina) =>
    selectedTallerIds.includes(maquina.id_taller)
  );
});

const filteredIncidencias = computed(() => {
  const tallerFilter = route.query.taller;

  return incidencias.value.filter((incidencia) => {
    const matchesSearch = Object.values(incidencia).some(
      (value) =>
        value &&
        value.toString().toLowerCase().includes(searchQuery.value.toLowerCase())
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
      (selectedFilters.estado.length === 0 ||
        selectedFilters.estado.includes(incidencia.estado));

    return matchesSearch && matchesFilters && matchesTaller && matchesSelectTaller;
  });
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

const createIncidencia = async (newIncidenciaToSend) => {
  try {
    await apiCreateIncidencia(
      newIncidenciaToSend,
      selectedMachine.value,
      selectedTipoAveria.value
    );
    closeModal();
  } catch (error) {
    console.error('Error creating incidencia:', error);
  }
};
const createMantenimiento = async (mantenimientoData) => {
  const token = localStorage.getItem('jwt_token');
  if (!token) {
    throw new Error('Usuario no autenticado');
  }

  const newMantenimientoToSend = {
    maquinas: selectedMantenimientoMachine.value.map((maquina) => ({
      id_maquina: maquina.id_maquina,
    })),
    id_tipo_mantenimiento: selectedTipoMantenimiento.value,
    descripcion: mantenimientoData.descripcion,
    gravedad: 'Mantenimiento',
    estado: 'Abierta',
    frecuencia: mantenimientoData.frecuencia,
  };

  const response = await fetch('http://localhost:8000/api/mantenimientos', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify(newMantenimientoToSend),
  });
  if (!response.ok) {
    const responseText = await response.text();
    console.error('Error response text:', responseText);
    try {
      const errorData = JSON.parse(responseText);
      console.error('Parsed error data:', errorData);
      throw new Error(
        `Error al crear el mantenimiento: ${response.status} - ${
          errorData.message || response.statusText
        }`
      );
    } catch (jsonError) {
      throw new Error(
        `Error al crear el mantenimiento: ${response.status} - ${
          jsonError || response.statusText
        }`
      );
    }
  }

  const createdMantenimiento = await response.json();
  console.log('Mantenimiento creado:', createdMantenimiento);
  closeMantenimientoModal();
};

const calculateTotalDays = computed(() => {
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
});

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
</script>

<style scoped>
.row {
  margin-left: 3% !important;
  position: relative;
  overflow-x: hidden;
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
</style>