<template>
    <div class="incident-detail">
      <!-- Header -->
      <header class="header">
        <div class="container">
          <h1 class="header-title">Detalle Incidencia</h1>
        </div>
      </header>
  
      <main class="container main-content">
        <!-- Back button -->
        <button class="back-button">Atrás</button>
  
        <div class="content-grid">
          <!-- Left column -->
          <div class="main-info">
            <!-- Title section -->
            <div class="card">
              <div class="card-header">
                <div class="title-container">
                  <h2 class="incident-title">Título incidencia</h2>
                  <button class="bookmark-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m19 21-7-4-7 4V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16z"/></svg>
                  </button>
                </div>
                <div class="status-container">
                  <span class="priority">Prioridad 01</span>
                  <span class="status status-red">Máquina parada</span>
                  <span class="status status-green">Habilitado</span>
                </div>
              </div>
  
              <div class="card-content">
                <!-- Machine details -->
                <div class="info-grid">
                  <div class="info-item">
                    <h3 class="info-label">Máquina</h3>
                    <p class="info-value">Torno PM74</p>
                  </div>
                  <div class="info-item">
                    <h3 class="info-label">Estado</h3>
                    <p class="info-value">En Proceso</p>
                  </div>
                </div>
  
                <!-- Description -->
                <div class="info-item">
                  <h3 class="info-label">Descripción</h3>
                  <p class="info-value">
                    He tenido un problema con el torno y e tenido un problema
                    con el torno y e tenido un problema con el torno y e tenido un
                    problema con el torno y problema con el torno.
                  </p>
                </div>
  
                <!-- Date and Type -->
                <div class="info-grid">
                  <div class="info-item">
                    <h3 class="info-label">Fecha</h3>
                    <p class="info-value">Hace 3 días</p>
                  </div>
                  <div class="info-item">
                    <h3 class="info-label">Tipo de avería</h3>
                    <p class="info-value">Neumática</p>
                  </div>
                </div>
  
                <!-- Workshop and Campus -->
                <div class="info-grid">
                  <div class="info-item">
                    <h3 class="info-label">Taller</h3>
                    <p class="info-value">Taller Neumática</p>
                  </div>
                  <div class="info-item">
                    <h3 class="info-label">Campus</h3>
                    <p class="info-value">Arriaga</p>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Details section -->
            <div class="card">
              <h2 class="card-title">Detalles de la incidencia</h2>
              <div class="info-item">
                <h3 class="info-label">Operario</h3>
                <!-- Add operator details here -->
              </div>
            </div>
          </div>
  
          <!-- Right column - Phases -->
          <div class="phases">
            <div class="card">
              <!-- Phase 1 -->
              <div class="phase">
                <div class="phase-header">
                  <div class="phase-title">
                    <div class="phase-dot"></div>
                    <h3>Fase 1</h3>
                    <span class="status status-green">Completada</span>
                  </div>
                  <span class="phase-date">2 de Agosto / 14 de Agosto</span>
                </div>
                <p class="phase-description">Título fase 1 - He cambiado el ac...</p>
                <div class="phase-tags">
                  <span class="tag">Alfredo Fernandez</span>
                </div>
              </div>
  
              <!-- Phase 2 -->
              <div class="phase">
                <div class="phase-header">
                  <div class="phase-title">
                    <div class="phase-dot"></div>
                    <h3>Fase 2</h3>
                    <span class="status status-green">Completada</span>
                  </div>
                  <span class="phase-date">14 de Agosto / 20 de Agosto</span>
                </div>
                <p class="phase-description">Título fase 2 - He cambiado el ac...</p>
                <div class="phase-tags">
                  <span class="tag">Alfredo Fernandez</span>
                  <span class="tag">Vicente Abad</span>
                  <span class="tag">Maite Lezama</span>
                </div>
              </div>
  
              <!-- Phase 3 -->
              <div class="phase">
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
</script>
  
  <style >
  </style>