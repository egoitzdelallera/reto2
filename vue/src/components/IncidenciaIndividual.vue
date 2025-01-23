<template>
    <link href="../assets/bootstrap5_3/dist/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  
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
                    <button @click="asignarme" class="btn btn-primary mb-3">Asignarme fase {{ index + 1 }}</button>
  
                    <h6 class="text-muted mt-3">Técnicos</h6>
                    <div class="row g-3">
                      <div class="col-12">
                        <div class="card">
                          <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                              <div class="rounded-circle bg-secondary" style="width: 48px; height: 48px;"></div>
                              <div class="ms-3">
                                <h5 v-html="fase.tecnicos_fases_incidencias.map(tecnico => tecnico.tecnico.nombre + ' ' + tecnico.tecnico.apellido).join('<br>')"></h5>
                              </div>
                              <div class="contact-info">
                                <p>{{ fase.tecnicos_fases_incidencias[0]?.tecnico?.correo }}</p>
                              </div>
                            </div>
                            <span class="badge bg-success mt-2">Habilitado</span>
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
                </div>
  
                <!-- Phase 3 with Assign Button -->
                <div v-if="showAssignButton" class="mb-4">
                  <button class="btn btn-secondary w-100">Ver Fases</button>
                </div>
  
                <!-- Action Buttons -->
                <div class="d-grid gap-2">
                  <button class="btn btn-success">Finalizar la Fase 3</button>
                  <button class="btn btn-secondary">Finalizar Incidencia</button>
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
const route = useRoute();
const router = useRouter();
const { getIncidenciaById } = useIncidencias();

const activeTab = ref('Operario');
const tabs = ['Operario', 'Fases', 'Máquina'];
const incidencia = ref({});



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
</script>
  <style scoped>
  main{
    padding: 0px;
  }
  </style>