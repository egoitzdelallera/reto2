<template>
  <div class="incident-detail">
    <header class="header">
      <div class="container">
        <h1 class="header-title">Detalle Incidencia</h1>
      </div>
    </header>

    <main class="container main-content">
      <button class="back-button" @click="$router.go(-1)">Atrás</button>

      <div class="content-grid">
        <div class="main-info">
          <div class="card">
            <div class="card-header">
              <div class="title-container">
                <h2 class="incident-title">{{ incidencia.descripcion }}</h2>
                <button class="bookmark-button">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                </button>
              </div>
              <div class="status-container">
                <span class="priority">Prioridad {{ incidencia.gravedad }}</span>
                <span :class="['status', 'status-red', {'status-red': incidencia.maquina?.estado == 'Deshabilitado'}, {'status-green': incidencia.maquina?.estado == 'Habilitado'}]">{{ incidencia.maquina?.estado }}</span>
                <span :class="['status', 'status-green', {'status-green': incidencia.estado == 'Habilitado'}, {'status-red': incidencia.estado == 'Deshabilitado'}]">{{ incidencia.estado }}</span>
              </div>
            </div>

            <div class="card-content">
              <div class="info-grid">
                <div class="info-item">
                  <h3 class="info-label">Máquina</h3>
                  <p class="info-value">{{ incidencia.maquina?.nombre }}</p>
                </div>
                <div class="info-item">
                  <h3 class="info-label">Estado</h3>
                  <p class="info-value">{{ incidencia.estado }}</p>
                </div>
              </div>

              <div class="info-item">
                <h3 class="info-label">Descripción</h3>
                <p class="info-value">{{ incidencia.descripcion }}</p>
              </div>

              <div class="info-grid">
                <div class="info-item">
                  <h3 class="info-label">Fecha de Reporte</h3>
                  <p class="info-value">{{ incidencia.fecha_reporte }}</p>
                </div>
                <div class="info-item">
                  <h3 class="info-label">Tipo de avería</h3>
                  <p class="info-value">{{ incidencia.tipo_averia?.nombre }}</p>
                </div>
              </div>

              <div class="info-grid">
                <div class="info-item">
                  <h3 class="info-label">Taller</h3>
                  <p class="info-value">{{ incidencia.maquina?.taller?.nombre }}</p>
                </div>
                <div class="info-item">
                  <h3 class="info-label">Campus</h3>
                  <p class="info-value">{{ incidencia.maquina?.taller?.campus?.nombre }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <h2 class="card-title">Detalles de la incidencia</h2>
            <div class="tabs">
              <button
                v-for="tab in tabs"
                :key="tab"
                @click="activeTab = tab"
                :class="{ active: activeTab === tab }"
                class="tab-button"
              >
                {{ tab }}
              </button>
            </div>
            <div class="tab-content">
              <div v-if="activeTab === 'Operario'" class="operator-info">
                <div class="operator-header">
                  <div class="operator-avatar"></div>
                  <div class="operator-details">
                    <h3>{{ incidencia.creador?.nombre }} {{ incidencia.creador?.apellido }}</h3>
                    <p>{{ incidencia.creador?.rol }}</p>
                  </div>
                </div>
                <div class="operator-contact">
                  <div class="contact-item">
                    <h4>Correo Electrónico</h4>
                    <p>{{ incidencia.creador?.correo }}</p>
                  </div>
                  <!-- ... (Otros datos del operario si es necesario) ... -->
                </div>
              </div>
              <div v-else-if="activeTab === 'Fases'" class="phases-tab">
                <div v-for="(fase, index) in incidencia.fases_incidencias" :key="index" class="phase-detail">
                  <div class="phase-detail-header">
                    <h3>Título Fase {{ index + 1 }} - {{ fase.nombre }}</h3>
                     <span :class="['status', `status-${fase.estado?.toLowerCase()}`]">{{ fase.estado }}</span>
                  </div>

                  <div class="phase-info">
                    <div class="phase-dates">
                      <div class="date-item">
                        <h4>Fecha de Inicio de Fase {{ index + 1 }}</h4>
                        <p>{{ fase.fecha_inicio }}</p>
                      </div>
                      <div v-if="fase.fecha_fin" class="date-item">
                        <h4>Fecha de Final de Fase {{ index + 1 }}</h4>
                        <p>{{ fase.fecha_fin }}</p>
                      </div>
                    </div>

                    <div class="phase-description">
                      <h4>Descripción de la fase {{ index + 1 }}</h4>
                      <p>{{ fase.descripcion }}</p>
                    </div>

                    <div class="technicians">
                      <h4>Técnicos de la Fase {{ index + 1 }}</h4>
                      <div class="technician-list">
                         <div class="technician-card">
                            <div class="technician-avatar"></div>
                            <div class="technician-info">
                                <h5 v-html="fase.tecnicos_fases_incidencias.map(tecnico => tecnico.tecnico.nombre + ' ' + tecnico.tecnico.apellido).join('<br>')"></h5>
                              <div class="contact-info">
                                <p>{{ fase.tecnicos_fases_incidencias[0]?.tecnico?.correo }}</p>
                              </div>
                                <span class="status status-green">Habilitado</span>
                            </div>
                         </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else-if="activeTab === 'Máquina'" class="machine-tab">
                <div class="machine-header">
                  <div class="machine-title">
                    <h3>{{ incidencia.maquina?.nombre }}</h3>
                    <span class="priority">{{ incidencia.maquina?.prioridad }}</span>
                  </div>
                  <span :class="['status', 'status-green', {'status-green': incidencia.maquina?.estado == 'Habilitado'}, {'status-red': incidencia.maquina?.estado == 'Deshabilitado'}]">Habilitado</span>
                </div>

                <div class="machine-info">
                  <div class="location-info">
                     <h4>{{ incidencia.maquina?.campus?.nombre }} → {{ incidencia.maquina?.ubicacion }}</h4>
                   <!--   <p>{{ incidencia.maquina.address }}</p>-->
                  </div>

                  <div class="machine-description">
                    <h4>Descripción de la máquina</h4>
                    <p>{{ incidencia.maquina?.descripcion }}</p>
                  </div>

                  <div class="contact-grid">
                    <div class="contact-item">
                      <h4>Taller</h4>
                      <p>{{ incidencia.maquina?.taller?.nombre }}</p>
                    </div>
                   <!-- <div class="contact-item">
                      <h4>Teléfono</h4>
                      <p>{{ incidencia.maquina?.telefono }}</p>
                    </div>-->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

       <div class="phases">
          <div class="card">
                <div v-for="(fase, index) in incidencia.fases_incidencias" :key="index" class="phase">
                  <div class="phase-header">
                    <div class="phase-title">
                      <div class="phase-dot" :class="fase.estado?.toLowerCase()"></div>
                      <h3>Fase {{ index + 1 }}</h3>
                      <span :class="['status', `status-${fase.estado?.toLowerCase()}`]">{{ fase.estado }}</span>
                    </div>
                    <span class="phase-date">{{ fase.fecha_inicio }} / {{ fase.fecha_fin }}</span>
                  </div>
                    <p class="phase-description">{{ fase.descripcion }}</p>
                    <div class="phase-tags">
                        <span class="tag">{{ fase.tecnicos_fases_incidencias.map(tecnico => tecnico.tecnico.nombre + ' ' + tecnico.tecnico.apellido).join(', ') }}</span>
                      </div>
                </div>
             <!--   <div v-if="showAssignButton" class="phase">
              <div class="phase-header">
                <div class="phase-title">
                  <div class="phase-dot"></div>
                  <h3>Fase 3</h3>
                  <span class="status status-orange">En Progreso</span>
                </div>
                <span class="phase-date">2 de Agosto / 14 de Agosto</span>
              </div>
              <p class="phase-description">No asignado</p>
              <button class="assign-button">Asignarme en la Fase 3</button>
             </div>-->
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
/* ... ( Tus estilos CSS ) ... */
</style>