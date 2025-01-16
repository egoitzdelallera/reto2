<template>
  <div class="incident-detail">
    <header class="header">
      <div class="container">
        <h1 class="header-title">Detalle Incidencia</h1>
      </div>
    </header>

    <main class="container main-content">
      <button class="back-button">Atrás</button>

      <div class="content-grid">
        <div class="main-info">
          <div class="card">
            <div class="card-header">
              <div class="title-container">
                <h2 class="incident-title">{{ incident.title }}</h2>
                <button class="bookmark-button">
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                </button>
              </div>
              <div class="status-container">
                <span class="priority">Prioridad {{ incident.priority }}</span>
                <span class="status status-red">{{ incident.machineStatus }}</span>
                <span class="status status-green">{{ incident.enabledStatus }}</span>
              </div>
            </div>

            <div class="card-content">
              <div class="info-grid">
                <div class="info-item">
                  <h3 class="info-label">Máquina</h3>
                  <p class="info-value">{{ incident.machine }}</p>
                </div>
                <div class="info-item">
                  <h3 class="info-label">Estado</h3>
                  <p class="info-value">{{ incident.status }}</p>
                </div>
              </div>

              <div class="info-item">
                <h3 class="info-label">Descripción</h3>
                <p class="info-value">{{ incident.description }}</p>
              </div>

              <div class="info-grid">
                <div class="info-item">
                  <h3 class="info-label">Fecha</h3>
                  <p class="info-value">{{ incident.date }}</p>
                </div>
                <div class="info-item">
                  <h3 class="info-label">Tipo de avería</h3>
                  <p class="info-value">{{ incident.failureType }}</p>
                </div>
              </div>

              <div class="info-grid">
                <div class="info-item">
                  <h3 class="info-label">Taller</h3>
                  <p class="info-value">{{ incident.workshop }}</p>
                </div>
                <div class="info-item">
                  <h3 class="info-label">Campus</h3>
                  <p class="info-value">{{ incident.campus }}</p>
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
                    <h3>{{ operator.name }}</h3>
                    <p>{{ operator.role }}</p>
                  </div>
                </div>
                <div class="operator-contact">
                  <div class="contact-item">
                    <h4>Correo Electrónico</h4>
                    <p>{{ operator.email }}</p>
                  </div>
                  <div class="contact-item">
                    <h4>Teléfono</h4>
                    <p>{{ operator.phone }}</p>
                  </div>
                </div>
              </div>
              <div v-else-if="activeTab === 'Fases'" class="phases-tab">
                <div v-for="(phase, index) in phasesDetails" :key="index" class="phase-detail">
                  <div class="phase-detail-header">
                    <h3>Título Fase {{ index + 1 }} - {{ phase.title }}</h3>
                    <span :class="['status', `status-${phase.status.toLowerCase()}`]">{{ phase.status }}</span>
                  </div>

                  <div class="phase-info">
                    <div class="phase-dates">
                      <div class="date-item">
                        <h4>Fecha de Inicio de Fase {{ index + 1 }}</h4>
                        <p>{{ phase.startDate }}</p>
                      </div>
                      <div class="date-item">
                        <h4>Fecha de Final de Fase {{ index + 1 }}</h4>
                        <p>{{ phase.endDate }}</p>
                      </div>
                    </div>

                    <div class="phase-description">
                      <h4>Descripción de la fase {{ index + 1 }}</h4>
                      <p>{{ phase.description }}</p>
                    </div>

                    <div class="technicians">
                      <h4>Técnicos de la Fase {{ index + 1 }}</h4>
                      <div class="technician-list">
                        <div v-for="(tech, techIndex) in phase.technicians" :key="techIndex" class="technician-card">
                          <div class="technician-avatar"></div>
                          <div class="technician-info">
                            <h5>{{ tech.name }}</h5>
                            <p>{{ tech.role }}</p>
                            <div class="contact-info">
                              <p>{{ tech.email }}</p>
                              <p>{{ tech.phone }}</p>
                            </div>
                            <div class="location">
                              <p>{{ tech.location }}</p>
                              <p>{{ tech.address }}</p>
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
                    <h3>{{ machine.name }}</h3>
                    <span class="priority">{{ machine.priority }}</span>
                  </div>
                  <span class="status status-green">Habilitado</span>
                </div>

                <div class="machine-info">
                  <div class="location-info">
                    <h4>Campus de Arriaga - Egibide → Vitoria - Gasteiz, Álava</h4>
                    <p>{{ machine.address }}</p>
                  </div>

                  <div class="machine-description">
                    <h4>Descripción de la máquina</h4>
                    <p>{{ machine.description }}</p>
                  </div>

                  <div class="contact-grid">
                    <div class="contact-item">
                      <h4>Taller</h4>
                      <p>{{ machine.workshop }}</p>
                    </div>
                    <div class="contact-item">
                      <h4>Teléfono</h4>
                      <p>{{ machine.phone }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="phases">
          <div class="card">
            <div v-for="(phase, index) in phases" :key="index" class="phase">
              <div class="phase-header">
                <div class="phase-title">
                  <div class="phase-dot" :class="phase.status.toLowerCase()"></div>
                  <h3>Fase {{ index + 1 }}</h3>
                  <span :class="['status', `status-${phase.status.toLowerCase()}`]">{{ phase.status }}</span>
                </div>
                <span class="phase-date">{{ phase.dateRange }}</span>
              </div>
              <p class="phase-description">{{ phase.description }}</p>
              <div class="phase-tags">
                <span v-for="(tag, tagIndex) in phase.tags" :key="tagIndex" class="tag">{{ tag }}</span>
              </div>
            </div>
            <div v-if="showAssignButton" class="phase">
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
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const activeTab = ref('Operario');
const tabs = ['Operario', 'Fases', 'Máquina'];

const incident = {
  title: 'Título incidencia',
  priority: 'Alta',
  machineStatus: 'Máquina parada',
  enabledStatus: 'Habilitado',
  machine: 'Torno PM74',
  status: 'En Proceso',
  description: 'He tenido un problema con el torno y e tenido un problema con el torno y e tenido un problema con el torno y e tenido un problema con el torno y problema con el torno.',
  date: 'Hace 3 días',
  failureType: 'Neumática',
  workshop: 'Taller Neumática',
  campus: 'Arriaga'
};

const operator = {
  name: 'Pepe Lopez Gomez',
  role: 'Operario',
  email: 'pepe@gmail.com',
  phone: '630 63 06 30'
};

const phases = [
  {
    status: 'Completada',
    dateRange: '2 de Agosto / 14 de Agosto',
    description: 'Título fase 1 - He cambiado el ac...',
    tags: ['Alfredo Fernandez']
  },
  {
    status: 'Completada',
    dateRange: '14 de Agosto / 20 de Agosto',
    description: 'Título fase 2 - He cambiado el ac...',
    tags: ['Alfredo Fernandez', 'Vicente Abad', 'Maite Lezama']
  }
];

const showAssignButton = true;

const phasesDetails = [
  {
    title: 'He cambiado el ac...',
    status: 'Completada',
    startDate: '7 de Octubre del 2024',
    endDate: '9 de Octubre del 2024',
    description: 'Descripción de la fase 1 descripción de la fase 1, descripción de la fase 1 descripción de la fase 1',
    technicians: [
      {
        name: 'Alfredo Fernandez',
        role: 'Técnico',
        email: 'alfredo@gmail.com',
        phone: '630 63 06 30',
        location: 'Campus de Arriaga - Egibide → Vitoria - Gasteiz, Álava',
        address: 'Pozoa Kalea, s/n, 01013 Gasteiz, Araba'
      }
    ]
  },
  {
    title: 'He cambiado el ac...',
    status: 'Completada',
    startDate: '13 de Octubre del 2024',
    endDate: '20 de Octubre del 2024',
    description: 'Descripción de la fase 2 descripción de la fase 2, descripción de la fase 2',
    technicians: [
      {
        name: 'Alfredo Fernandez',
        role: 'Técnico',
        email: 'alfredo@gmail.com',
        phone: '630 63 06 30',
        location: 'Campus de Arriaga - Egibide → Vitoria - Gasteiz, Álava',
        address: 'Pozoa Kalea, s/n, 01013 Gasteiz, Araba'
      },
      {
        name: 'Vicente Abad',
        role: 'Técnico',
        email: 'vicente@gmail.com',
        phone: '630 63 06 30',
        location: 'Campus de Arriaga - Egibide → Vitoria - Gasteiz, Álava',
        address: 'Pozoa Kalea, s/n, 01013 Gasteiz, Araba'
      },
      {
        name: 'Maite Lezama',
        role: 'Técnico',
        email: 'maite@gmail.com',
        phone: '630 63 06 30',
        location: 'Campus de Arriaga - Egibide → Vitoria - Gasteiz, Álava',
        address: 'Pozoa Kalea, s/n, 01013 Gasteiz, Araba'
      }
    ]
  }
];

const machine = {
  name: 'Brazo Robótico PM74',
  priority: 'Prioridad Alta',
  address: 'Pozoa Kalea, s/n, 01013 Gasteiz, Araba',
  description: 'Descripción de la máquina descripción de la máquina, descripción de la máquina descripción de la máquina, máquina, descripción de la máquina descripción de la máquina descripción de la máquina.',
  workshop: 'Taller A',
  phone: '630 63 06 30'
};
</script>

<style scoped>

</style>

