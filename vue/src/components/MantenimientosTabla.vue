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


</style>