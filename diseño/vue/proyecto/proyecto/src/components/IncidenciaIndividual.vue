<template>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <div class="bg-light min-vh-100">
    <!-- Header -->
    <header class="bg-white shadow-sm py-3 mb-4">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center">
          <h1 class="h3 mb-0">Detalle Incidencia</h1>
          <button class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-2"></i>Atrás
          </button>
        </div>
      </div>
    </header>

    <div class="container">
      <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
          <!-- Incident Card -->
          <div class="card mb-4">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                  <div class="d-flex align-items-center gap-2">
                    <h2 class="h4 mb-0">{{ incident.title }}</h2>
                    <button class="btn btn-link p-0">
                      <i class="bi bi-bookmark"></i>
                    </button>
                  </div>
                  <div class="mt-2">
                    <span class="badge bg-danger me-2">{{ incident.machineStatus }}</span>
                    <span class="badge bg-success">{{ incident.enabledStatus }}</span>
                  </div>
                </div>
                <span class="badge bg-warning text-dark">Prioridad {{ incident.priority }}</span>
              </div>

              <div class="row g-3">
                <div class="col-md-6">
                  <h6 class="text-muted">Máquina</h6>
                  <p>{{ incident.machine }}</p>
                </div>
                <div class="col-md-6">
                  <h6 class="text-muted">Estado</h6>
                  <p>{{ incident.status }}</p>
                </div>
                <div class="col-12">
                  <h6 class="text-muted">Descripción</h6>
                  <p>{{ incident.description }}</p>
                </div>
                <div class="col-md-6">
                  <h6 class="text-muted">Fecha</h6>
                  <p>{{ incident.date }}</p>
                </div>
                <div class="col-md-6">
                  <h6 class="text-muted">Tipo de avería</h6>
                  <p>{{ incident.failureType }}</p>
                </div>
                <div class="col-md-6">
                  <h6 class="text-muted">Taller</h6>
                  <p>{{ incident.workshop }}</p>
                </div>
                <div class="col-md-6">
                  <h6 class="text-muted">Campus</h6>
                  <p>{{ incident.campus }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Tabs Card -->
          <div class="card">
            <div class="card-body">
              <h3 class="card-title mb-4">Detalles de la incidencia</h3>
              
              <ul class="nav nav-tabs mb-4">
                <li class="nav-item" v-for="tab in tabs" :key="tab">
                  <button 
                    class="nav-link" 
                    :class="{ active: activeTab === tab }"
                    @click="activeTab = tab"
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
                    <h4 class="h5 mb-1">{{ operator.name }}</h4>
                    <p class="text-muted mb-0">{{ operator.role }}</p>
                  </div>
                </div>
                <div class="row g-3">
                  <div class="col-md-6">
                    <h6 class="text-muted">Correo Electrónico</h6>
                    <p>{{ operator.email }}</p>
                  </div>
                  <div class="col-md-6">
                    <h6 class="text-muted">Teléfono</h6>
                    <p>{{ operator.phone }}</p>
                  </div>
                </div>
              </div>

              <!-- Phases Tab -->
              <div v-else-if="activeTab === 'Fases'" class="tab-content">
                <div v-for="(phase, index) in phasesDetails" :key="index" class="mb-4">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="h5 mb-0">Título Fase {{ index + 1 }} - {{ phase.title }}</h4>
                    <span :class="['badge', phase.status === 'Completada' ? 'bg-success' : 'bg-warning']">
                      {{ phase.status }}
                    </span>
                  </div>

                  <div class="row g-3 mb-3">
                    <div class="col-md-6">
                      <h6 class="text-muted">Fecha de Inicio</h6>
                      <p>{{ phase.startDate }}</p>
                    </div>
                    <div class="col-md-6">
                      <h6 class="text-muted">Fecha de Final</h6>
                      <p>{{ phase.endDate }}</p>
                    </div>

                  </div>

                  <h6 class="text-muted">Descripción</h6>
                  <p>{{ phase.description }}</p>
                  <button @click="asignarme">Asignarme fase {{ index + 1 }} </button>

                  <h6 class="text-muted mt-3">Técnicos</h6>
                  <div class="row g-3">
                    <div v-for="(tech, techIndex) in phase.technicians" :key="techIndex" class="col-12">
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle bg-secondary" style="width: 48px; height: 48px;"></div>
                            <div class="ms-3">
                              <h5 class="card-title mb-1">{{ tech.name }}</h5>
                              <p class="text-muted mb-0">{{ tech.role }}</p>
                            </div>
                          </div>
                          <div class="row g-2">
                            <div class="col-md-6">
                              <p class="mb-1"><small class="text-muted">Email:</small> {{ tech.email }}</p>
                              <p class="mb-1"><small class="text-muted">Teléfono:</small> {{ tech.phone }}</p>
                            </div>
                            <div class="col-md-6">
                              <p class="mb-1"><small class="text-muted">Ubicación:</small> {{ tech.location }}</p>
                              <p class="mb-1"><small class="text-muted">Dirección:</small> {{ tech.address }}</p>
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
                    <h4 class="h5 mb-1">{{ machine.name }}</h4>
                    <span class="badge bg-warning text-dark">{{ machine.priority }}</span>
                  </div>
                  <span class="badge bg-success">Habilitado</span>
                </div>

                <h6 class="text-muted">Ubicación</h6>
                <p class="mb-1">Campus de Arriaga - Egibide → Vitoria - Gasteiz, Álava</p>
                <p class="mb-3">{{ machine.address }}</p>

                <h6 class="text-muted">Descripción</h6>
                <p class="mb-3">{{ machine.description }}</p>

                <div class="row g-3">
                  <div class="col-md-6">
                    <h6 class="text-muted">Taller</h6>
                    <p>{{ machine.workshop }}</p>
                  </div>
                  <div class="col-md-6">
                    <h6 class="text-muted">Teléfono</h6>
                    <p>{{ machine.phone }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Phases Sidebar -->
        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <div v-for="(phase, index) in phases" :key="index" class="mb-4">
                <div class="d-flex align-items-center mb-2">
                  <div 
                    class="rounded-circle me-2" 
                    :class="phase.status === 'Completada' ? 'bg-success' : 'bg-warning'"
                    style="width: 12px; height: 12px;"
                  ></div>
                  <h5 class="mb-0">Fase {{ index + 1 }}</h5>
                  <span 
                    class="badge ms-2"
                    :class="phase.status === 'Completada' ? 'bg-success' : 'bg-warning'"
                  >
                    {{ phase.status }}
                  </span>
                </div>
                <p class="text-muted small mb-2">{{ phase.dateRange }}</p>
                <p class="mb-2">{{ phase.description }}</p>
                <div class="d-flex flex-wrap gap-2">
                  <span 
                    v-for="(tag, tagIndex) in phase.tags" 
                    :key="tagIndex"
                    class="badge bg-light text-dark"
                  >
                    {{ tag }}
                  </span>
                </div>
              </div>

              <!-- Phase 3 with Assign Button -->
              <div v-if="showAssignButton" class="mb-4">
                <button class="btn btn-dark w-100">Ver Fases</button>
              </div>

              <!-- Action Buttons -->
              <div class="d-grid gap-2">
                <button class="btn btn-success">Finalizar la Fase 3</button>
                <button class="btn btn-dark">Finalizar Incidencia</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

// Reusing your existing data structure
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
  },
  {
    status: 'En Proceso',
    dateRange: '20 de Agosto ',
    description: 'Título fase 3 - He cambiado el ac...',
    
  },
  
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
  },
  {
    status: 'En Proceso', 
    dateRange: '20 de Agosto ',
    
   
  },
  
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