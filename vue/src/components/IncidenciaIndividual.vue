<template>
  <div class="bg-primary min-vh-100 m-0">
    <!-- Header -->
    <header class="bg-primary shadow-sm py-3 mb-4 border-bottom">
      <div class="container">
        <div class="d-flex justify-content-start align-items-center">
          <button @click="goBack" class="btn btn-link btn-sm p-0 text-info" style="text-decoration: none; width: auto;">
            <i class="bi bi-arrow-left me-2 btn btn-outline-info"></i>Atrás
          </button>
        </div>
      </div>
    </header>

    <div class="container">
      <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
          <!-- Incident Card -->
          <div class="mb-4 bg-primary" style="border-top: 1.3px solid grey; border-bottom: 1.3px solid grey;">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <div class="d-flex align-items-center gap-2">
                    <h1 class="mb-0 text-black mt-3" style="line-height: 1.2; letter-spacing: -0.05em; font-weight: bold;">{{ incidencia.descripcion }}</h1>
                  </div>
                    <div class="mt-2">
                    <span class="badge bg-fondoRojo text-danger border border-danger me-2" title="gravedad de la incidencia">{{ incidencia.gravedad }}</span>
                    <span
                      :class="{
                        'badge bg-info text-primary': incidencia.maquina?.prioridad === 'Alta',
                        'badge bg-secondary text-info': incidencia.maquina?.prioridad === 'Media',
                        'badge bg-primary text-info border border-info': incidencia.maquina?.prioridad === 'Baja'
                      }"
                      title="prioridad de la máquina"
                      >
                      {{ incidencia.maquina?.prioridad }}
                      </span>
                    </div>
                  </div>
                  <span class="badge bg-black text-white mt-3" style="font-size: 1em;" title="estado de la incidencia">{{ incidencia.estado }}</span>
                  </div>
                <div class="d-flex justify-content-between align-items-start" style="border-top: 1px solid grey;">
                <div class="row g-3 flex-grow-1 my-2">
                  <div class="col-md-6">
                    <h4 class="text-muted">Máquina</h4>
                    <p class="text-info">{{ incidencia.maquina?.nombre }}</p>
                  </div>
                  <div class="col-md-6">
                    <h4 class="text-muted">Estado</h4>
                    <p class="text-info">{{ incidencia.estado }}</p>
                  </div>
                  <div class="col-md-6">
                    <h4 class="text-muted">Fecha</h4>
                    <p class="text-info">{{ incidencia.fecha_reporte }}</p>
                  </div>
                    <div class="col-md-6">
                    <h4 class="text-muted">{{ incidencia.tipo_averia ? 'Tipo de avería' : 'Tipo de mantenimiento' }}</h4>
                    <p class="text-info">{{ incidencia.tipo_averia?.nombre || incidencia.tipo_mantenimiento?.nombre }}</p>
                    </div>
                  <div class="col-md-6">
                    <h4 class="text-muted">Taller</h4>
                    <p class="text-info">{{ incidencia.maquina?.taller?.nombre }}</p>
                  </div>
                  <div class="col-md-6">
                    <h4 class="text-muted">Campus</h4>
                    <p class="text-info">{{ incidencia.maquina?.taller?.campus?.nombre }}</p>
                  </div>
                </div>
                <div class="ms-3 my-4">
                    <div v-if="incidencia.multimedia && (incidencia.multimedia.includes('.jpg') || incidencia.multimedia.includes('.png') || incidencia.multimedia.includes('.jpeg'))">
                        <img
                            :src="getMultimediaUrl(incidencia.multimedia)"
                            alt="Imagen de la incidencia"
                            style="max-width: 370px; height: auto; border: 1.3px solid grey; border-radius: 6px; cursor: pointer;"
                            @click="openImageModal(getMultimediaUrl(incidencia.multimedia))"
                        />
                    </div>
                    <div v-else-if="incidencia.multimedia && incidencia.multimedia.includes('.mp4')">
                    <video :src="getMultimediaUrl(incidencia.multimedia)" controls style="max-width: 370px; height: auto; border: 1.3px solid grey; border-radius: 6px;"></video>
                    </div>
                    <div v-else-if="incidencia.multimedia">
                    <p>Archivo multimedia disponible para visualizar:</p>
                    <a :href="getMultimediaUrl(incidencia.multimedia)" target="_blank" download class="text-info">
                      <i class="bi bi-file-earmark-pdf-fill fs-3 text-info">PDF</i>
                    </a>
                    </div>
                  <div v-else>
                    <p>No hay multimedia asociada a esta incidencia.</p>
                  </div>
                </div>
              </div>

              <div v-if="showImageModal" class="image-modal-backdrop" @click="closeImageModal">
                <div class="image-modal" @click.stop>
                    <img :src="imageModalSrc" alt="Imagen de la incidencia en grande" style="max-width: 100%; height: auto;" />
                </div>
            </div>
            </div>
          </div>

          <!-- Tabs Card -->
          <div class="card bg-primary mb-5">
            <div class="card-body">
              <h3 class="card-title mb-4 text-info">Detalles de la incidencia</h3>

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
              <div v-if="activeTab === 'Operario'" class="tab-content ms-2">
                <div class="d-flex align-items-center mb-4">
                  <div class="rounded-circle bg-primary">
                    <i class="bi bi-person-circle fs-2"></i>
                  </div>
                  <div class="ms-3">
                    <h4 class="h5 mb-1 text-info">{{ incidencia.creador?.nombre }} {{ incidencia.creador?.apellido }}</h4>
                    <p class="text-muted mb-0 text-info">{{ incidencia.creador?.rol }}</p>
                  </div>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <h6 class="text-muted">Correo Electrónico</h6>
                    <p class="text-info">{{ incidencia.creador?.correo }}</p>
                  </div>
                </div>
              </div>

              <!-- Phases Tab -->
                <div v-else-if="activeTab === 'Fases'" class="tab-content">
                  <div v-for="(fase, index) in incidencia.fases_incidencias" :key="index" class="mb-4" style="border-bottom: 1px solid grey; padding-bottom: 1rem;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                      <h2 class="mb-0 text-info">Fase {{ index + 1 }} - {{ fase.descripcion }}</h2>
                       <span
                          :class="['badge', 'ms-2', 'status', `status-${fase.estado?.toLowerCase()}`, { 'bg-warning': fase.estado?.toLowerCase() === 'en proceso'}]"
                      >
                        {{ fase.estado }}
                      </span>
                    </div>

                    <div class="row g-3 mb-3">
                      <div class="col-md-6">
                        <h6 class="text-muted">Fecha de Inicio</h6>
                        <p class="text-info">{{ fase.fecha_inicio }}</p>
                      </div>
                      <div v-if="fase.fecha_fin" class="col-md-6">
                        <h6 class="text-muted">Fecha de Final</h6>
                        <p class="text-info">{{ fase.fecha_fin }}</p>
                      </div>
                    </div>
                    <button
                      v-if="fase.estado !== 'Completada'"
                      @click="asignarme(fase.id_fase_incidencia)"
                      class="btn btn-secondary"
                    >
                      Asignarme fase {{ index + 1 }}
                    </button>

                    <h6 class="text-muted mt-3">Técnicos</h6>
                    <div class="row g-3">
                      <div class="col-12" v-for="(tecnicoFase, index) in fase.tecnicos_fases_incidencias" :key="index">
                        <div class="card bg-primary">
                          <div class="card-body bg-secondary">
                            <div class="d-flex align-items-center mb-3">
                              <div class="rounded-circle text-info">
                                <i class="bi bi-person-circle fs-2"></i>
                              </div>
                              <div class="ms-3">
                                <!-- Mostramos el nombre completo del técnico -->
                                <h4 class="text-info">{{ tecnicoFase.tecnico.nombre }} {{ tecnicoFase.tecnico.apellido }}</h4>
                              </div>
                            </div>
                            <div class="row g-3">
                              <div class="col-md-6">
                                <h6 class="text-muted">Correo Electrónico</h6>
                                <!-- Correo del técnico -->
                                <p class="text-info">{{ tecnicoFase.tecnico.correo }}</p>
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
                    <h4 class="h5 mb-1 text-info">{{ incidencia.maquina?.nombre }}</h4>
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
                <p class="mb-1 text-info">{{ incidencia.maquina?.taller?.campus?.nombre }} → {{ incidencia.maquina?.taller?.campus?.ubicacion }}</p>

                <h6 class="text-muted">Descripción</h6>
                <p class="mb-3 text-info">{{ incidencia.maquina?.descripcion }}</p>

                <div class="row g-3">
                  <div class="col-md-6">
                    <h6 class="text-muted">Taller</h6>
                    <p class="text-info">{{ incidencia.maquina?.taller?.nombre }}</p>
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

                  <h5 class="mb-0 mx-1 text-info">Fase {{ index + 1 }}</h5>
                  <span
                          :class="['badge', 'ms-2', 'status', `status-${fase.estado?.toLowerCase()}`, { 'bg-warning': fase.estado?.toLowerCase() === 'en proceso'}]"
                      >
                        {{ fase.estado }}
                      </span>
                </div>
                <p class="text-muted small mb-2 text-info">{{ fase.fecha_inicio }} / {{ fase.fecha_fin }}</p>
                <p class="mb-2 text-info">{{ fase.descripcion }}</p>
                <div class="d-flex flex-wrap gap-2">
                  <span class="tag text-info">{{ fase.tecnicos_fases_incidencias.map(tecnico => tecnico.tecnico.nombre + ' ' + tecnico.tecnico.apellido).join(', ') }}</span>
                </div>

                <div class="d-grid gap-2 mt-3" v-if="fase.estado !== 'Completada'">
                  <button
                    v-if="fase.estado !== 'Completada'"
                    @click="asignarme(fase.id_fase_incidencia)"
                    class="btn btn-secondary text-info"
                  >
                    Asignarme fase {{ index + 1 }}
                  </button>
                    <button class="btn btn-info text-primary" @click="openPopup(fase)">Finalizar la Fase {{index + 1}}</button>
                    <button class="btn btn-primary" @click="openFinalizarIncidenciaPopup(fase)">Finalizar Incidencia</button>
                </div>
              </div>

              <div v-if="showPopup" class="popup-backdrop">
                <div class="popup">
                  <h5>Finalizar Fase</h5>
                  <p>Escribe una descripción de lo que has hecho para completar esta fase:</p>
                  <textarea v-model="descripcion" class="form-control" rows="4" placeholder="Descripción..."></textarea>
                  <div class="d-flex justify-content-end mt-3 gap-2">
                    <button class="btn btn-secondary" @click="closePopup">Cancelar</button>
                    <button class="btn btn-primary bg-fondoRojo" @click="confirmFinalizarFase">Confirmar</button>
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

// Modal de imagen
const showImageModal = ref(false);
const imageModalSrc = ref('');


const getMultimediaUrl = (path) => {
    const baseURL = import.meta.env.VITE_API_URL || "http://localhost:8000";
    return path.startsWith("/storage") ? `${baseURL}${path}` : path;
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

const goBack = () => {
  router.go(-1);
}

// Funciones para el modal de imagen
const openImageModal = (imageSrc) => {
    imageModalSrc.value = imageSrc;
    showImageModal.value = true;
};

const closeImageModal = () => {
  showImageModal.value = false;
  imageModalSrc.value = '';
};
</script>

<style scoped>
.popup-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1000;
}

.popup {
  background-color: white;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  width: 500px
}

.tab-button {
  background-color: #ededed;
  border: 1px solid #dedede;
  color: #828282;
  padding: 10px 20px;
  cursor: pointer;
  border-radius: 5px 5px 0 0;
  transition: background-color 0.3s ease;
  outline: none;
}

.tab-button:focus {
  box-shadow: none;
}

.tab-button.active {
  background-color: #e6e6e6;
  color: black;
  font-weight: 700;
}

.status {
  display: inline-block;
  padding: 0.25em 0.5em;
  border-radius: 0.25rem;
  font-size: 0.8rem;
  font-weight: 600;
  color: white;
}
.status-abierta {
  background-color: #34a36f;
}

.status-enproceso {
  background-color: #ffc107;
  color: black;
}

.status-pendiente {
  background-color: #dc3545;
}
.status-completada {
background-color: #198754;
}
/* Estilos para el modal de imagen */
.image-modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.8);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1001;
}

.image-modal {
  background-color: white;
  padding: 10px;
  border-radius: 8px;
  max-width: 90%;
  max-height: 90%;
  overflow: auto; /* Permite scroll si la imagen es muy grande */
}

.image-modal img {
  display: block;
  max-width: 100%;
  height: auto;
}
</style>