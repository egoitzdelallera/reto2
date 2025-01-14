<template>
  <div class="maintenances-container">
    <h1 class="text-2xl font-bold mb-6">Mantenimientos</h1>
    <div class="mb-6">
      <div class="search-container">
        <input
          type="text"
          placeholder="Search..."
          class="search-input"
          v-model="searchQuery"
        />
      </div>
    </div>

    <div class="table-container">
      <div class="table-header">
        <div>TALLER</div>
        <div>ESTADO</div>
        <div>PRIORIDAD</div>
        <div>FECHA INICIO</div>
        <div>FECHA FIN</div>
        <div>RESPONSABLE</div>
        <div>EQUIPOS</div>
        <div>TIPO</div>
      </div>

      <div class="table-body">
        <div v-for="(maintenance, i) in maintenances" :key="i" class="table-row">
          <div>{{ maintenance.taller }}</div>
          <div>
            <span
              :class="[
                'status-badge',
                maintenance.estado === 'En Curso'
                  ? 'status-in-progress'
                  : 'status-scheduled',
              ]"
            >
              {{ maintenance.estado }}
            </span>
          </div>
          <div>{{ maintenance.prioridad }}</div>
          <div>{{ maintenance.fechaInicio }}</div>
          <div>{{ maintenance.fechaFin }}</div>
          <div>{{ maintenance.responsable }}</div>
          <div>{{ maintenance.equipos }}</div>
          <div>
            <span
              :class="[
                'status-badge',
                {
                  'type-preventive': maintenance.tipo === 'Preventivo',
                  'type-corrective': maintenance.tipo === 'Correctivo',
                },
              ]"
            >
              {{ maintenance.tipo }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "MaintenancesTable",
  data() {
    return {
      searchQuery: "",
      maintenances: [
        {
          taller: "Taller A",
          estado: "En Curso",
          prioridad: "02",
          fechaInicio: "Hoy",
          fechaFin: "Próxima semana",
          responsable: "Vicente Abad",
          equipos: "PM74, SM52",
          tipo: "Preventivo",
        },
        {
          taller: "Taller B",
          estado: "En Curso",
          prioridad: "01",
          fechaInicio: "Ayer",
          fechaFin: "Mañana",
          responsable: "Alfredo Fernandez",
          equipos: "Torno CDC",
          tipo: "Correctivo",
        },
          {
          taller: "Taller C",
          estado: "En Curso",
          prioridad: "02",
          fechaInicio: "Hoy",
          fechaFin: "Próxima semana",
          responsable: "Vicente Abad",
          equipos: "PM74, SM52",
          tipo: "Preventivo",
        },
        {
          taller: "Taller B",
          estado: "En Curso",
          prioridad: "01",
          fechaInicio: "Ayer",
          fechaFin: "Mañana",
          responsable: "Alfredo Fernandez",
          equipos: "Torno CDC",
          tipo: "Correctivo",
        },
           {
          taller: "Taller C",
          estado: "En Curso",
          prioridad: "02",
          fechaInicio: "Hoy",
          fechaFin: "Próxima semana",
          responsable: "Vicente Abad",
          equipos: "PM74, SM52",
          tipo: "Preventivo",
        },
        {
          taller: "Taller B",
          estado: "En Curso",
          prioridad: "01",
          fechaInicio: "Ayer",
          fechaFin: "Mañana",
          responsable: "Alfredo Fernandez",
          equipos: "Torno CDC",
          tipo: "Correctivo",
        },
        {
          taller: "Taller D",
          estado: "Planificado",
          prioridad: "03",
          fechaInicio: "La semana que viene",
          fechaFin: "En 2 semanas",
          responsable: "No Asignado",
          equipos: "Impresora 3D",
          tipo: "Preventivo",
        },
           {
          taller: "Taller C",
          estado: "En Curso",
          prioridad: "02",
          fechaInicio: "Hoy",
          fechaFin: "Próxima semana",
          responsable: "Vicente Abad",
          equipos: "PM74, SM52",
          tipo: "Preventivo",
        },
        {
          taller: "Taller B",
          estado: "En Curso",
          prioridad: "01",
          fechaInicio: "Ayer",
          fechaFin: "Mañana",
          responsable: "Alfredo Fernandez",
          equipos: "Torno CDC",
          tipo: "Correctivo",
        },
           {
          taller: "Taller C",
          estado: "En Curso",
          prioridad: "02",
          fechaInicio: "Hoy",
          fechaFin: "Próxima semana",
          responsable: "Vicente Abad",
          equipos: "PM74, SM52",
          tipo: "Preventivo",
        },
        {
          taller: "Taller B",
          estado: "En Curso",
          prioridad: "01",
          fechaInicio: "Ayer",
          fechaFin: "Mañana",
          responsable: "Alfredo Fernandez",
          equipos: "Torno CDC",
          tipo: "Correctivo",
        },
        {
          taller: "Taller E",
          estado: "Planificado",
          prioridad: "01",
          fechaInicio: "En 1 mes",
          fechaFin: "En 2 meses",
          responsable: "No Asignado",
          equipos: "Cognex",
          tipo: "Correctivo",
        },
      ],
    };
  },
};
</script>

<style scoped>
*{
  font-family:
    'Inter',
    system-ui,
    -apple-system,
    'Segoe UI',
    sans-serif;
  font-size: 15px;
  text-rendering: optimizeLegibility;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  
}
.maintenances-container {
  padding-left: 5%;
  background: white;
  height: 100vh;
}

.search-container {
  display: flex;
  justify-content: flex-end;
  margin-bottom: 1rem;
}

.search-input {
  width: 300px;
  padding: 0.5rem 1rem;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  background-color: #f5f5f5;
  font-size: 0.875rem;
}

.search-input:focus {
  outline: none;
  border-color: #d1d5db;
  background-color: white;
}

.table-container {
  background: white;
  border-radius: 0.5rem;
  overflow: hidden;
}

.table-header {
  display: grid;
  grid-template-columns: repeat(8, 1fr);
  gap: 1rem;
  padding: 1rem;
  background-color: #f5f5f5;
  font-size: 0.875rem;
  font-weight: 500;
  color: #161616;
  border-bottom: 1px solid #e5e7eb;
}

.table-body {
  display: flex;
  flex-direction: column;
}

.table-row {
  display: grid;
  grid-template-columns: repeat(8, 1fr);
  gap: 1rem;
  padding: 1rem;
  align-items: center;
    transition: 0.2s;
    margin-bottom: 3px;
    border-radius: 10px;
}

.table-row:hover {
  background-color: #dfe0e0;
      transform: translatex(3px);
  }

.status-badge {
  display: inline-flex;
  padding: 0.25rem 0.75rem;
  border-radius: 1rem;
  font-size: 0.75rem;
  font-weight: 500;
}

.status-in-progress {
  background-color: #dbeafe;
  color: #1e40af;
}

.status-scheduled {
    background-color: #f3f4f6;
    color: #4b5563;
}


.type-preventive {
    background-color: #f0fdf4;
    color: #166534;
}

.type-corrective {
    background-color: #fee2e2;
    color: #991b1b;
}

</style>