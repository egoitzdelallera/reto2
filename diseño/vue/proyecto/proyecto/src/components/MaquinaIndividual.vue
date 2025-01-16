<template>
    <div class="machine-detail">
      <!-- Back button -->
      <div class="back-link">
        <a href="#" class="back-button">Atrás</a>
      </div>
  
      <!-- Machine header -->
      <div class="machine-header">
        <div class="header-content">
          <div class="title-section">
            <h1>Nombre Máquina</h1>
            <span class="priority-badge">Prioridad alta</span>
          </div>
          <button class="status-button enabled">
            Habilitado
          </button>
        </div>
        
        <!-- Machine info grid -->
        <div class="machine-info">
          <div class="info-grid">
            <div class="info-item">
              <label>Campus</label>
              <span>Arteaga</span>
            </div>
            <div class="info-item">
              <label>Taller</label>
              <span>A03</span>
            </div>
            <div class="info-item">
              <label>Responsable de taller</label>
              <span>Juan López</span>
            </div>
            <div class="info-item">
              <label>Nombre Taller</label>
              <span>Taller Neumática</span>
            </div>
            <button class="edit-button">
              Editar
            </button>
          </div>
        </div>
  
        <!-- Description -->
        <div class="description-section">
          <label>Descripción</label>
          <p>Brazo robótico de alta precisión diseñado para tareas de ensamblaje y manipulación rápida en líneas de producción. Brazo robótico de alta precisión diseñado para tareas de ensamblaje y manipulación rápida.</p>
        </div>
      </div>
  
      <!-- Stats sections -->
      <div class="stats-container">
        <!-- Maintenance stats -->
        <div class="stats-section">
          <h2>Mantenimientos</h2>
          <div class="stats-grid">
            <div class="stat-item">
              <span class="stat-value">5</span>
              <span class="stat-label">Diarios</span>
            </div>
            <div class="stat-item">
              <span class="stat-value">3</span>
              <span class="stat-label">Semanales</span>
            </div>
            <div class="stat-item">
              <span class="stat-value">4</span>
              <span class="stat-label">Mensuales</span>
            </div>
            <div class="stat-item">
              <span class="stat-value">1</span>
              <span class="stat-label">Anuales</span>
            </div>
          </div>
        </div>
  
        <!-- Incidents stats -->
        <div class="stats-section">
          <h2>Incidencias</h2>
          <div class="stats-grid">
            <div class="stat-item">
              <span class="stat-value">9</span>
              <span class="stat-label">Núm. de incidencias</span>
            </div>
          </div>
        </div>
      </div>
  
      <!-- Maintenance panel -->
      <div class="maintenance-panel">
        <h3>Mantenimientos de máquina</h3>
        <div class="maintenance-list">
          <div class="maintenance-item">
            <span>Limpieza</span>
            <span class="frequency">Diaria</span>
          </div>
          <div class="maintenance-item">
            <span>Pruebas técnicas</span>
            <span class="frequency">Mensual</span>
          </div>
          <div class="maintenance-item">
            <span>Listar materiales</span>
            <span class="frequency">Semanal</span>
          </div>
        </div>
        <button class="add-maintenance">
          Agregar Mantenimiento
        </button>
      </div>
  
      <!-- Footer -->
      <footer class="site-footer">
        <div class="footer-content">
          <div class="company-info">
            <span class="company-name">NovaByte.</span>
            <span class="company-description">Gestión avanzada de incidencias.</span>
          </div>
          <div class="contact-info">
            <div class="contact-section">
              <h4>Información de Contacto</h4>
              <p>Teléfono: +34 123 456 789</p>
            </div>
            <div class="address-section">
              <p>Calle 42, 28001 Vitoria, España</p>
              <p>gestion@metatronica.com</p>
            </div>
          </div>
          <div class="social-links">
            <a href="#" class="social-link">Facebook</a>
            <a href="#" class="social-link">Twitter</a>
          </div>
        </div>
      </footer>
    </div>
  </template>
  
  <style scoped>
  .machine-detail {
    padding: 20px;
    background: white;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
  
  .back-link {
    margin-bottom: 20px;
  }
  
  .back-button {
    color: #666;
    text-decoration: none;
    font-size: 14px;
  }
  
  .machine-header {
    margin-bottom: 30px;
  }
  
  .header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }
  
  .title-section {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  h1 {
    margin: 0;
    font-size: 24px;
    font-weight: 600;
  }
  
  .priority-badge {
    background: #fff0f0;
    color: #dc3545;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
  }
  
  .status-button {
    padding: 6px 12px;
    border-radius: 4px;
    border: none;
    font-size: 13px;
  }
  
  .status-button.enabled {
    background: #d4edda;
    color: #198754;
  }
  
  .machine-info {
    background: #f8f9fa;
    border-radius: 4px;
    padding: 20px;
  }
  
  .info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    position: relative;
  }
  
  .info-item {
    display: flex;
    flex-direction: column;
  }
  
  .info-item label {
    color: #666;
    font-size: 12px;
    margin-bottom: 4px;
  }
  
  .info-item span {
    font-size: 14px;
  }
  
  .edit-button {
    position: absolute;
    right: 0;
    top: 0;
    padding: 4px 12px;
    border: 1px solid #0d6efd;
    background: white;
    color: #0d6efd;
    border-radius: 4px;
    font-size: 13px;
    cursor: pointer;
  }
  
  .description-section {
    margin-top: 20px;
  }
  
  .description-section label {
    display: block;
    color: #666;
    font-size: 12px;
    margin-bottom: 4px;
  }
  
  .description-section p {
    font-size: 14px;
    line-height: 1.5;
    margin: 0;
  }
  
  .stats-container {
    display: flex;
    gap: 30px;
    margin: 30px 0;
  }
  
  .stats-section {
    flex: 1;
  }
  
  .stats-section h2 {
    font-size: 18px;
    margin-bottom: 15px;
  }
  
  .stats-grid {
    display: flex;
    gap: 20px;
  }
  
  .stat-item {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 4px;
    text-align: center;
    min-width: 100px;
  }
  
  .stat-value {
    display: block;
    font-size: 24px;
    font-weight: 600;
    color: #212529;
  }
  
  .stat-label {
    display: block;
    font-size: 12px;
    color: #666;
    margin-top: 4px;
  }
  
  .maintenance-panel {
    background: #f8f9fa;
    border-radius: 4px;
    padding: 20px;
    max-width: 300px;
    position: fixed;
    right: 20px;
    top: 20px;
  }
  
  .maintenance-panel h3 {
    font-size: 16px;
    margin-bottom: 15px;
  }
  
  .maintenance-list {
    margin-bottom: 15px;
  }
  
  .maintenance-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #dee2e6;
    font-size: 14px;
  }
  
  .frequency {
    color: #666;
  }
  
  .add-maintenance {
    width: 100%;
    padding: 8px;
    background: #212529;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
  }
  
  .site-footer {
    margin-top: auto;
    padding: 20px;
    background: #f8f9fa;
    margin: 40px -20px -20px;
  }
  
  .footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1200px;
    margin: 0 auto;
  }
  
  .company-info {
    display: flex;
    flex-direction: column;
  }
  
  .company-name {
    font-weight: 600;
    font-size: 18px;
  }
  
  .company-description {
    color: #666;
    font-size: 14px;
  }
  
  .contact-info {
    display: flex;
    gap: 40px;
  }
  
  .contact-section h4 {
    font-size: 14px;
    margin: 0 0 5px;
  }
  
  .contact-section p,
  .address-section p {
    margin: 0;
    color: #666;
    font-size: 14px;
  }
  
  .social-links {
    display: flex;
    gap: 15px;
  }
  
  .social-link {
    color: #666;
    text-decoration: none;
    font-size: 14px;
  }
  
  @media (max-width: 768px) {
    .stats-container {
      flex-direction: column;
    }
    
    .maintenance-panel {
      position: static;
      max-width: none;
      margin-top: 20px;
    }
    
    .footer-content {
      flex-direction: column;
      gap: 20px;
      text-align: center;
    }
    
    .contact-info {
      flex-direction: column;
      gap: 20px;
    }
  }
  </style>