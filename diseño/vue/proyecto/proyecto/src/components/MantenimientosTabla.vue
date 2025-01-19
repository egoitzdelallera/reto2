<template>
  <div class="container-fluid">
    <h1 class="display-4 mb-4">Mantenimientos</h1>
    <div class="row mb-4">
      <div class="col-md-6 offset-md-6">
        <input
          type="text"
          placeholder="Search..."
          class="form-control"
          v-model="searchQuery"
        />
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="thead-light">
          <tr>
            <th>TALLER</th>
            <th>ESTADO</th>
            <th>PRIORIDAD</th>
            <th>FECHA INICIO</th>
            <th>FECHA FIN</th>
            <th>RESPONSABLE</th>
            <th>EQUIPOS</th>
            <th>TIPO</th>
          </tr>
        </thead>
        <tbody>
          <tr 
            v-for="(maintenance, i) in maintenances" 
            :key="i" 
            @click="goToMaintenance(i)"
            style="cursor: pointer;"
          >
            <td>{{ maintenance.taller }}</td>
            <td>
              <span
                :class="[
                  'badge',
                  maintenance.estado === 'En Curso'
                    ? 'bg-primary'
                    : 'bg-secondary',
                ]"
              >
                {{ maintenance.estado }}
              </span>
            </td>
            <td>{{ maintenance.prioridad }}</td>
            <td>{{ maintenance.fechaInicio }}</td>
            <td>{{ maintenance.fechaFin }}</td>
            <td>{{ maintenance.responsable }}</td>
            <td>{{ maintenance.equipos }}</td>
            <td>
              <span
                :class="[
                  'badge',
                  {
                    'bg-success': maintenance.tipo === 'Preventivo',
                    'bg-danger': maintenance.tipo === 'Correctivo',
                  },
                ]"
              >
                {{ maintenance.tipo }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const searchQuery = ref('')
const maintenances = ref([
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
  // ... (rest of the maintenance objects)
])

const goToMaintenance = (id) => {
  router.push({ name: 'IncidenciaIndividual', params: { id: id } })
}
</script>

<style scoped>
/* Your existing styles here */
</style>