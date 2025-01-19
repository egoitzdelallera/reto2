<template>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container-fluid py-4">
  <!-- Back button -->
  <div class="mb-4">
    <a href="#" class="btn btn-outline-secondary">
      <i class="bi bi-arrow-left me-2"></i>Atrás
    </a>
  </div>

  <!-- Machine header -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
          <h1 class="h3 mb-0">Nombre Máquina</h1>
          <span class="badge bg-danger">Prioridad alta</span>
        </div>
        <button class="btn btn-success">
          Habilitado
        </button>
      </div>

      <!-- Machine info grid -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <label class="form-label text-muted">Campus</label>
          <div>Arteaga</div>
        </div>
        <div class="col-md-3">
          <label class="form-label text-muted">Taller</label>
          <div>A03</div>
        </div>
        <div class="col-md-3">
          <label class="form-label text-muted">Responsable de taller</label>
          <div>Juan López</div>
        </div>
        <div class="col-md-3">
          <label class="form-label text-muted">Nombre Taller</label>
          <div>Taller Neumática</div>
        </div>
      </div>

      <button class="btn btn-primary mb-4">
        <i class="bi bi-pencil me-2"></i>Editar
      </button>

      <!-- Description -->
      <div>
        <label class="form-label text-muted">Descripción</label>
        <p>Brazo robótico de alta precisión diseñado para tareas de ensamblaje y manipulación rápida en líneas de producción. Brazo robótico de alta precisión diseñado para tareas de ensamblaje y manipulación rápida.</p>
      </div>
    </div>
  </div>

  <!-- Stats sections -->
  <div class="row g-4 mb-4">
    <!-- Maintenance stats -->
    <div class="col-md-8">
      <div class="card h-100">
        <div class="card-body">
          <h2 class="h5 mb-4">Mantenimientos</h2>
          <div class="row g-4 text-center">
            <div class="col-3">
              <h3 class="h2 mb-0">5</h3>
              <small class="text-muted">Diarios</small>
            </div>
            <div class="col-3">
              <h3 class="h2 mb-0">3</h3>
              <small class="text-muted">Semanales</small>
            </div>
            <div class="col-3">
              <h3 class="h2 mb-0">4</h3>
              <small class="text-muted">Mensuales</small>
            </div>
            <div class="col-3">
              <h3 class="h2 mb-0">1</h3>
              <small class="text-muted">Anuales</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Incidents stats -->
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <h2 class="h5 mb-4">Incidencias</h2>
          <div class="text-center">
            <h3 class="h2 mb-0">9</h3>
            <small class="text-muted">Núm. de incidencias</small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Maintenance panel -->
  <div class="card mb-4">
    <div class="card-body">
      <h3 class="h5 mb-4">Mantenimientos de máquina</h3>
      <div class="list-group mb-4">
        <div class="list-group-item d-flex justify-content-between align-items-center">
          <span>Limpieza</span>
          <span class="badge bg-secondary">Diaria</span>
        </div>
        <div class="list-group-item d-flex justify-content-between align-items-center">
          <span>Pruebas técnicas</span>
          <span class="badge bg-secondary">Mensual</span>
        </div>
        <div class="list-group-item d-flex justify-content-between align-items-center">
          <span>Listar materiales</span>
          <span class="badge bg-secondary">Semanal</span>
        </div>
      </div>
      <button class="btn btn-primary">
        <i class="bi bi-plus me-2"></i>Agregar Mantenimiento
      </button>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-light py-4 mt-auto">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4">
          <h4 class="h6 mb-2">NovaByte.</h4>
          <p class="text-muted small mb-0">Gestión avanzada de incidencias.</p>
        </div>
        <div class="col-md-4">
          <h4 class="h6 mb-2">Información de Contacto</h4>
          <p class="text-muted small mb-0">Teléfono: +34 123 456 789</p>
          <p class="text-muted small mb-0">Calle 42, 28001 Vitoria, España</p>
          <p class="text-muted small mb-0">gestion@metatronica.com</p>
        </div>
        <div class="col-md-4">
          <div class="d-flex gap-3">
            <a href="#" class="text-muted text-decoration-none">Facebook</a>
            <a href="#" class="text-muted text-decoration-none">Twitter</a>
          </div>
        </div>
      </div>
    </div>
  </footer>
</div>
</template>

<script setup>
// Add any required component logic here
</script>

<style scoped>
/* Add any custom styles here */

</style>