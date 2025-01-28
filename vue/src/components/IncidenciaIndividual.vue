<template>

  
    <div class="bg-primary min-vh-100 m-0">
      <!-- Header -->
      <header class="bg-primary shadow-sm py-3 mb-4 border-bottom">
        <div class="container">
          <div class="d-flex justify-content-start align-items-center">
            <button class="btn btn-link btn-sm p-0 text-info" style="text-decoration: none; width: auto;">
              <i class="bi bi-arrow-left me-2 btn btn-outline-info"></i>Atrás
            </button>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <h1 class="h3 text-white">{{ incidencia.descripcion }}</h1>
          </div>  
        </div>
      </header>
  
      <div class="container">
        <div class="row g-4">
          <!-- Main Content -->
          <div class="col-lg-8">
            <!-- Incident Card -->
            <div class="card mb-4 bg-primary">
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                  <div>
                    <div class="d-flex align-items-center gap-2">
                      <h2 class="h1 mb-0 ">{{ incidencia.nombre }}</h2>
                      <div><button class="btn btn-link p-0 text-info">
                        <i class="bi bi-bookmark"></i>
                      </button></div>
                    </div>
                    <div class="mt-2">
                      <span class="badge bg-danger me-2">{{ incidencia.maquina?.estado }}</span>
                      <span class="badge bg-success">{{ incidencia.estado }}</span>
                    </div>
                  </div>
                  <span class="badge bg-warning text-dark">Prioridad {{ incidencia.gravedad }}</span>
                </div>
  
                <div class="row g-3">
                  <div class="col-md-6">
                    <h6 class="text-muted ">Máquina</h6>
                    <p>{{ incidencia.maquina?.nombre }}</p>
                  </div>
                  <div class="col-md-6">
                    <h6 class="text-muted">Estado</h6>
                    <p>{{ incidencia.estado }}</p>
                  </div>
                  <div class="col-12">
                    <h6 class="text-muted">Descripción</h6>
                    <p>{{ incidencia.descripcion }}</p>
                  </div>
                  <div class="col-md-6">
                    <h6 class="text-muted">Fecha</h6>
                    <p>{{ incidencia.fecha_reporte }}</p>
                  </div>
                  <div class="col-md-6">
                    <h6 class="text-muted">Tipo de avería</h6>
                    <p>{{ incidencia.tipo_averia?.nombre }}</p>
                  </div>
                  <div class="col-md-6">
                    <h6 class="text-muted">Taller</h6>
                    <p>{{ incidencia.maquina?.taller?.nombre }}</p>
                  </div>
                  <div class="col-md-6">
                    <h6 class="text-muted">Campus</h6>
                    <p>{{ incidencia.maquina?.taller?.campus?.nombre }}</p>
                  </div>
                  <div v-if="incidencia.multimedia && incidencia.multimedia.includes('.jpg')">
                    <img :src="getMultimediaUrl(incidencia.multimedia)" alt="Imagen de la incidencia" />
                  </div>
                  <div v-else-if="incidencia.multimedia">
                      <p>Archivo multimedia disponible para descargar:</p>
                      <a :href="getMultimediaUrl(incidencia.multimedia)" target="_blank" download>{{incidencia.multimedia}}</a>
                    </div>
                  <div v-else>
                    <p>No hay multimedia asociada a esta incidencia.</p>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Tabs Card -->
            <div class="card bg-primary">
              <div class="card-body">
                <h3 class="card-title mb-4">Detalles de la incidencia</h3>
  
                <ul class="nav nav-tabs mb-4">
                  <li class="nav-item flex-fill" v-for="tab in tabs" :key="tab">
                    <button
                      @click="activeTab = tab"
                      :class="{ active: activeTab === tab }"
                      class="tab-button w-100"
                    >
                      {{ tab }}
                    </button>
                  </li>
                </ul>
  
                <!-- Operator Tab -->
                <div v-if="activeTab === 'Operario'" class="tab-content">
                  <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle bg-secondary" style="width: 64px; height: 64px;"></div>
                    <div class="ms-3">
                      <h4 class="h5 mb-1">{{ incidencia.creador?.nombre }} {{ incidencia.creador?.apellido }}</h4>
                      <p class="text-muted mb-0">{{ incidencia.creador?.rol }}</p>
                    </div>
                  </div>
                  <div class="row g-3">
                    <div class="col-md-6">
                      <h6 class="text-muted">Correo Electrónico</h6>
                      <p>{{ incidencia.creador?.correo }}</p>
                    </div>
                  </div>
                </div>
  
                <!-- Phases Tab -->
                <div v-else-if="activeTab === 'Fases'" class="tab-content">
                  <div v-for="(fase, index) in incidencia.fases_incidencias" :key="index" class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h4 class="h5 mb-0">Título Fase {{ index + 1 }} - {{ fase.nombre }}</h4>
                      <span :class="['status', `status-${fase.estado?.toLowerCase()}`]">
                        {{ fase.estado }}
                      </span>
                    </div>
  
                    <div class="row g-3 mb-3">
                      <div class="col-md-6">
                        <h6 class="text-muted">Fecha de Inicio</h6>
                        <p>{{ fase.fecha_inicio }}</p>
                      </div>
                      <div v-if="fase.fecha_fin" class="col-md-6">
                        <h6 class="text-muted">Fecha de Final</h6>
                        <p>{{ fase.fecha_fin }}</p>
                      </div>
                    </div>
  
                    <h6 class="text-muted">Descripción</h6>
                    <p>{{ fase.descripcion }}</p>
                    <button 
                      v-if="fase.estado !== 'Completada'"
                      @click="asignarme(fase.id_fase_incidencia)" 
                      class="btn btn-primary mb-3"
                    >
                      Asignarme fase {{ index + 1 }}
                    </button>
  
                    <h6 class="text-muted mt-3">Técnicos</h6>
                    <div class="row g-3">
                      <div class="col-12" v-for="(tecnicoFase, index) in fase.tecnicos_fases_incidencias" :key="index">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                              <div class="rounded-circle bg-secondary" style="width: 48px; height: 48px;"></div>
                              <div class="ms-3">
                                <!-- Mostramos el nombre completo del técnico -->
                                <h5>{{ tecnicoFase.tecnico.nombre }} {{ tecnicoFase.tecnico.apellido }}</h5>
                              </div>
                            </div>
                            <div class="row g-3">
                              <div class="col-md-6">
                                <h6 class="text-muted">Correo Electrónico</h6>
                                <!-- Correo del técnico -->
                                <p>{{ tecnicoFase.tecnico.correo }}</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
  
                <!-- Machine Tab -->
                <div v-else-if="activeTab === 'Máquina'" class="tab-content">
                  <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                      <h4 class="h5 mb-1">{{ incidencia.maquina?.nombre }}</h4>
                      <span class="badge bg-warning text-dark">{{ incidencia.maquina?.prioridad }}</span>
                    </div>
                    <span 
                      class="badge" 
                      :class="{
                        'bg-success': incidencia.maquina?.estado === 'Habilitado',
                        'bg-danger': incidencia.maquina?.estado === 'Deshabilitado'
                      }"
                    >
                      {{ incidencia.maquina?.estado }}
                    </span>

                  </div>
  
                  <h6 class="text-muted">Ubicación</h6>
                  <p class="mb-1">{{ incidencia.maquina?.taller?.campus?.nombre }} → {{ incidencia.maquina?.taller?.campus?.ubicacion }}</p>
  
                  <h6 class="text-muted">Descripción</h6>
                  <p class="mb-3">{{ incidencia.maquina?.descripcion }}</p>
  
                  <div class="row g-3">
                    <div class="col-md-6">
                      <h6 class="text-muted">Taller</h6>
                      <p>{{ incidencia.maquina?.taller?.nombre }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Phases Sidebar -->
          <div class="col-lg-4">
            <div class="card bg-primary">
              <div class="card-body">
                <div v-for="(fase, index) in incidencia.fases_incidencias" :key="index" class="mb-4">
                  <div class="d-flex align-items-center mb-2">
                    <div
                      class="rounded-circle me-2"
                      :class="fase.estado?.toLowerCase()"
                      style="width: 12px; height: 12px;"
                    ></div>
                    <h5 class="mb-0">Fase {{ index + 1 }}</h5>
                    <span
                      class="badge ms-2"
                      :class="['status', `status-${fase.estado?.toLowerCase()}`]"
                    >
                      {{ fase.estado }}
                    </span>
                  </div>
                  <p class="text-muted small mb-2">{{ fase.fecha_inicio }} / {{ fase.fecha_fin }}</p>
                  <p class="mb-2">{{ fase.descripcion }}</p>
                  <div class="d-flex flex-wrap gap-2">
                    <span class="tag">{{ fase.tecnicos_fases_incidencias.map(tecnico => tecnico.tecnico.nombre + ' ' + tecnico.tecnico.apellido).join(', ') }}</span>
                  </div>

                  <div class="d-grid gap-2" v-if="fase.estado !== 'Completada'">
                    <button class="btn btn-success" @click="openPopup(fase)">Finalizar la Fase {{index + 1}}</button>
                    <button class="btn btn-secondary" @click="openFinalizarIncidenciaPopup(fase)">Finalizar Incidencia</button>
                  </div>
                </div>

                <div v-if="showPopup" class="popup-backdrop">
                  <div class="popup">
                    <h5>Finalizar Fase</h5>
                    <p>Escribe una descripción de lo que has hecho para completar esta fase:</p>
                    <textarea v-model="descripcion" class="form-control" rows="4" placeholder="Descripción..."></textarea>
                    <div class="d-flex justify-content-end mt-3 gap-2">
                      <button class="btn btn-secondary" @click="closePopup">Cancelar</button>
                      <button class="btn btn-success" @click="confirmFinalizarFase">Confirmar</button>
                    </div>
                  </div>
                </div>

                <div v-if="showFinalizarIncidenciaPopup" class="popup-backdrop">
                  <div class="popup">
                    <h5>Finalizar Incidencia</h5>
                    <p>Escribe una descripción de lo que has hecho para cerrar esta incidencia:</p>
                    <textarea v-model="descripcionFinalizarIncidencia" class="form-control" rows="4" placeholder="Descripción..."></textarea>
                    <div class="d-flex justify-content-end mt-3 gap-2">
                      <button class="btn btn-secondary" @click="closeFinalizarIncidenciaPopup">Cancelar</button>
                      <button class="btn btn-danger" @click="confirmFinalizarIncidencia">Confirmar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </template>
  
<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import useIncidencias from '../composables/useIncidencias'; // Importa el composable

// Rutas
const route = useRoute();
const router = useRouter();
const { getIncidenciaById, finalizarYCrearFase, asignarTecnicoAFase, finalizarFaseYIncidencia } = useIncidencias();

const activeTab = ref('Operario');
const tabs = ['Operario', 'Fases', 'Máquina'];
const incidencia = ref({});

// Popup modal
const showPopup = ref(false);
const showFinalizarIncidenciaPopup = ref(false); // Estado del popup de finalizar incidencia
const descripcion = ref('');
const descripcionFinalizarIncidencia = ref(''); // Descripción para finalizar incidencia
const faseSeleccionada = ref(null);
const getMultimediaUrl = (path) => {
  // Asegúrate de que esta ruta apunte a tu carpeta de almacenamiento público
  return `/public/storage/${path}`; 
};

onMounted(async () => {
const incidenciaId = route.params.id;
try {
  const foundIncidencia = await getIncidenciaById(incidenciaId);
  if (foundIncidencia) {
    incidencia.value = foundIncidencia;
  } else {
    console.error('Incidencia no encontrada');
    router.push('/incidencias');
  }
} catch (error) {
  console.error('Error al cargar la incidencia:', error);
  router.push('/incidencias');
}
});

const openPopup = (fase) => {
  showPopup.value = true;
  descripcion.value = '';
  faseSeleccionada.value = fase;
}

const closePopup = () => {
  showPopup.value = false;
  descripcion.value = '';
  faseSeleccionada.value = null;
};

const confirmFinalizarFase = async () => {
  if (!descripcion.value) {
    alert('Por favor, escribe la descripcion antes de confirmar.');
    return;
  }

  if (faseSeleccionada.value) {
    try {
      await finalizarYCrearFase(
        faseSeleccionada.value.id_fase_incidencia, 
        descripcion.value,
        incidencia.value.id_incidencia
      );
      // Actualiza la incidencia despues de finalizar la fase
      const updatedIncidencia = await getIncidenciaById(route.params.id);
      incidencia.value = updatedIncidencia;

      closePopup();
    } catch (error) {
      console.error('Error al finalizar la fase:', error);
    }
  }
}

const openFinalizarIncidenciaPopup = (fase) => {
  showFinalizarIncidenciaPopup.value = true;
  descripcionFinalizarIncidencia.value = '';
  faseSeleccionada.value = fase;
};

const closeFinalizarIncidenciaPopup = () => {
  showFinalizarIncidenciaPopup.value = false;
  descripcionFinalizarIncidencia.value = '';
  faseSeleccionada.value = null;
};

const confirmFinalizarIncidencia = async () => {
  if (!descripcionFinalizarIncidencia.value) {
    alert('Por favor, escribe la descripción antes de confirmar.');
    return;
  }

  if (faseSeleccionada.value) {
    try {
      await finalizarFaseYIncidencia(
        faseSeleccionada.value.id_fase_incidencia,
        descripcionFinalizarIncidencia.value, 
        incidencia.value.id_incidencia
      );

      const updatedIncidencia = await getIncidenciaById(route.params.id);
      incidencia.value = updatedIncidencia;

      // Cerrar el popup de confirmación
      closePopup();
    } catch (error) {
      console.error('Error al finalizar la incidencia:', error);
    }
  }
};

const asignarme = async (faseId) => {
  try {
    await asignarTecnicoAFase(faseId);
  } catch (error) {
    console.error('Error al asignar la fase:', error);
  }
}
</script>
  <style scoped>  
  </style>