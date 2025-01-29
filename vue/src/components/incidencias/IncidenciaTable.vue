<template>
  <div class="container-fluid bg-primary">
    <div class="table-responsive">
      <table class="table table-hover">
        <thead class="table -light">
          <tr>
            <th class="bg-secondary text-info">TÍTULO</th>
            <th class="bg-secondary text-info">ESTADO</th>
            <th
              class="bg-secondary text-info mx-3"
              @click="sortBy('prioridad')"
              style="cursor: pointer; white-space: nowrap;"
            >
              PRIORIDAD
              <i
                v-if="sortColumn === 'prioridad'"
                :class="['bi', sortOrder === 'asc' ? 'bi-caret-up bg-secondary text-info  p-0 m-0' : 'bi-caret-down bg-secondary text-info p-0 m-0']"
                style="white-space: nowrap;"
              >
          </i>
              <span
                class="bg-secondary text-info bi bi-caret-right p-0 m-0"
                v-else
                style="white-space: nowrap;"
              ></span>
            </th>
            <th
              class="bg-secondary text-info"
              @click="sortBy('fecha_reporte')"
              style="cursor: pointer; white-space: nowrap;"
            >
              FECHA
              <span
                v-if="sortColumn === 'fecha_reporte'"
                class="bg-secondary text-info"
                style="white-space: nowrap;"
              >
                <i
                  :class="['bi', sortOrder === 'asc' ? 'bi-caret-up p-0 m-0' : 'bi-caret-down p-0 m-0']"
                ></i>
              </span>
              <span
                class="bg-secondary text-info bi bi-caret-right p-0 m-0"
                v-else
                style="white-space: nowrap;"
              ></span>
            </th>
            <th class="bg-secondary text-info">MÁQUINA</th>
            <th class="bg-secondary text-info">TALLER</th>
            <th class="bg-secondary text-info">CREADOR</th>
            <th class="bg-secondary text-info">TÉCNICO</th>
            <th
              class="bg-secondary text-info"
              @click="sortBy('gravedad')"
              style="cursor: pointer; white-space: nowrap;"
            >
              GRAVEDAD
              <span
                v-if="sortColumn === 'gravedad'"
                class="bg-secondary text-info"
                style="white-space: nowrap;"
              >
                <i
                  :class="['bi', sortOrder === 'asc' ? 'bi-caret-up p-0 m-0' : 'bi-caret-down p-0 m-0']"
                ></i>
              </span>
              <span
                class="bg-secondary text-info bi bi-caret-right p-0 m-0"
                v-else
                style="white-space: nowrap;"
              ></span>
            </th>
          </tr>
        </thead>
        <tbody class="table -light">
          <tr
            v-for="(incidencia, i) in paginatedIncidencias"
            :key="i"
            @click="goToIncidencia(incidencia.id_incidencia)"
            style="cursor: pointer;"
            :class="{ 'bg-secondary': isOwnTechnicianRow(incidencia) }"
          >
            <td class="font-medium bg-primary">
              {{ incidencia.descripcion }}
            </td>
            <td class="font-medium bg-primary">
              {{ incidencia.estado }}
            </td>
            <td class="font-medium bg-primary">
              <span
                :class="['badge', getPrioridadClass(incidencia.maquina.prioridad)]"
              >
                {{ incidencia.maquina.prioridad }}
              </span>
            </td>
            <td class="font-medium bg-primary">
              {{ formatDate(incidencia.fecha_reporte) }}
            </td>
            <td class="font-medium bg-primary">
              {{ incidencia.maquina.nombre }}
            </td>
            <td class="font-medium bg-primary">
              {{ incidencia.maquina?.taller?.nombre || 'Sin taller' }}
            </td>
            <td class="font-medium bg-primary">
              {{ incidencia.creador?.nombre || 'Sin creador' }}
            </td>
            <td class="font-medium bg-primary">
              {{
                incidencia.fases_incidencias && incidencia.fases_incidencias.length > 0
                  ? incidencia.fases_incidencias[
                      incidencia.fases_incidencias.length - 1
                    ].tecnicos_fases_incidencias
                      .map((tecnico) => tecnico.tecnico.nombre)
                      .join(', ')
                  : 'No asignado'
              }}
            </td>
            <td class="font-medium bg-primary">
              <span :class="['badge', getGravedadClass(incidencia.gravedad)]">
                {{ incidencia.gravedad }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <!-- Paginación -->
  <nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
      <li class="page-item" :class="{ disabled: currentPage === 1 }">
        <button
          class="page-link text-black"
          @click="previousPage"
          aria-label="Previous"
        >
          <span aria-hidden="true">«</span>
        </button>
      </li>
      <li
        v-for="page in totalPages"
        :key="page"
        class="page-item"
        :class="{ active: page === currentPage }"
      >
        <button class="page-link text-black" @click="goToPage(page)">
          {{ page }}
        </button>
      </li>
      <li class="page-item" :class="{ disabled: currentPage === totalPages }">
        <button class="page-link text-black" @click="nextPage" aria-label="Next">
          <span aria-hidden="true">»</span>
        </button>
      </li>
    </ul>
  </nav>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useRouter } from 'vue-router';

const props = defineProps({
  incidencias: Array,
  currentPage: Number,
  itemsPerPage: Number,
  searchQuery: String,
});
const emit = defineEmits(['page-change']);
const router = useRouter();

const sortColumn = ref(null);
const sortOrder = ref(null);

const getPrioridadClass = (prioridad) => {
  switch (prioridad) {
    case 'Alta':
      return 'badge bg-info text-primary fw-normal';
    case 'Media':
      return 'badge bg-secondary text-info fw-normal';
    case 'Baja':
      return 'badge bg-primary text-info fw-normal border border-secondary';
    default:
      return 'badge bg-info text-white fw-normal';
  }
};

const getGravedadClass = (gravedad) => {
  switch (gravedad) {
    case 'Maquina parada':
      return 'badge bg-fondoRojo text-danger border border-naranja';
    case 'Aviso':
      return 'badge bg-fondoNaranja text-naranja border border-naranja';
    case 'Maquina en Marcha':
      return 'badge bg-success text-success bg-opacity-10 border border-success';
    case 'Mantenimiento':
      return 'badge bg-secondary text-info border border-info';
    default:
      return 'badge bg-secondary text-white';
  }
};
const isOwnTechnicianRow = (incidencia) => {
    const userData = localStorage.getItem('user_Data');

    if(!userData) return false;

    try {
        const user = JSON.parse(userData);
        if (incidencia.fases_incidencias && incidencia.fases_incidencias.length > 0) {
          const lastFase = incidencia.fases_incidencias[incidencia.fases_incidencias.length - 1];
            if (lastFase.tecnicos_fases_incidencias && lastFase.tecnicos_fases_incidencias.length > 0) {
            return lastFase.tecnicos_fases_incidencias.some(tecnico => tecnico.tecnico.id === user.id)
             }
        }
        return false
    } catch (error) {
        console.error("Error parsing user data from localStorage:", error);
        return false
    }
};

const filteredIncidencias = computed(() => {
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Establecer la hora a 00:00:00 para la comparación de fechas
    return props.incidencias.filter(incidencia => {
      const reportDate = new Date(incidencia.fecha_reporte);
        reportDate.setHours(0, 0, 0, 0); // Establecer la hora a 00:00:00 también para la fecha de la incidencia
      return reportDate <= today;
    });
});

const sortedIncidencias = computed(() => {
  let sorted = [...filteredIncidencias.value];

  // Orden por defecto (gravedad, prioridad, fecha)
  if (!sortColumn.value) {
    sorted.sort((a, b) => {
      const gravedadOrder = {
        'Maquina parada': 1,
        'Maquina en Marcha': 2,
        'Aviso': 3,
        'Mantenimiento': 4,
      };
      const prioridadOrder = {
        Alta: 1,
        Media: 2,
        Baja: 3,
      };

      const gravedadA = gravedadOrder[a.gravedad] || 99; // Usar 99 como valor predeterminado para casos no definidos
      const gravedadB = gravedadOrder[b.gravedad] || 99;
      const prioridadA = prioridadOrder[a.maquina.prioridad] || 99;
      const prioridadB = prioridadOrder[b.maquina.prioridad] || 99;
      const fechaA = new Date(a.fecha_reporte).getTime();
      const fechaB = new Date(b.fecha_reporte).getTime();

      if (gravedadA !== gravedadB) return gravedadA - gravedadB;
      if (prioridadA !== prioridadB) return prioridadA - prioridadB;
      return fechaB - fechaA;
    });
    return sorted;
  }

  // Orden por columna específica
  sorted.sort((a, b) => {
    let valueA, valueB;
    if (sortColumn.value === 'fecha_reporte') {
      valueA = new Date(a[sortColumn.value]).getTime();
      valueB = new Date(b[sortColumn.value]).getTime();
    } else if (sortColumn.value === 'prioridad') {
      const prioridadOrder = {
        Alta: 1,
        Media: 2,
        Baja: 3,
      };
      valueA = prioridadOrder[a.maquina.prioridad] || 99;
      valueB = prioridadOrder[b.maquina.prioridad] || 99;
    } else if (sortColumn.value === 'gravedad') {
      const gravedadOrder = {
        'Maquina parada': 1,
        'Maquina en Marcha': 2,
        Aviso: 3,
        Mantenimiento: 4,
      };
      valueA = gravedadOrder[a.gravedad] || 99;
      valueB = gravedadOrder[b.gravedad] || 99;
    } else {
      valueA = a[sortColumn.value];
      valueB = b[sortColumn.value];
    }

    if (valueA < valueB) {
      return sortOrder.value === 'asc' ? -1 : 1;
    }
    if (valueA > valueB) {
      return sortOrder.value === 'asc' ? 1 : -1;
    }
    return 0;
  });

  return sorted;
});

const paginatedIncidencias = computed(() => {
  const start = (props.currentPage - 1) * props.itemsPerPage;
  const end = start + props.itemsPerPage;
  return sortedIncidencias.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredIncidencias.value.length / props.itemsPerPage);
});

const goToIncidencia = (id) => {
  router.push({ name: 'IncidenciaIndividual', params: { id: id } });
};

const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const year = date.getFullYear();
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');

  return `${day}/${month}/${year} ${hours}:${minutes}`;
};

const goToPage = (page) => {
  emit('page-change', page);
};
const previousPage = () => {
  if (props.currentPage > 1) {
    emit('page-change', props.currentPage - 1);
  }
};
const nextPage = () => {
  if (props.currentPage < totalPages.value)
    emit('page-change', props.currentPage + 1);
};

const sortBy = (column) => {
  if (sortColumn.value === column) {
    // Si la columna ya está ordenada, cambiar entre ascendente, descendente y sin orden
    if (sortOrder.value === 'asc') {
      sortOrder.value = 'desc';
    } else if (sortOrder.value === 'desc') {
      sortOrder.value = null;
      sortColumn.value = null; // Para que vuelva a orden por defecto
    } else {
      sortOrder.value = 'asc';
    }
  } else {
    // Si se hace clic en una columna diferente, se establece el orden ascendente
    sortColumn.value = column;
    sortOrder.value = 'asc';
  }
};
</script>

<style scoped>
.badge {
  display: inline-block;
  padding: 0.25em 0.5em;
}

fontbadge {
  display: inline-block;
  padding: 0.25em 0.5em;
  font-size: 0.75em;
  font-weight: 700;
  line-height: 1;
  text-align: center;
  vertical-align: baseline;
  border-radius: 0.25rem;
}

.badge-pendiente {
  background-color: #dc3545;
  color: white;
}

.badge-en.badge-en-proceso {
  background-color: #ffc107;
  color: black;
}

.badge-resuelta {
  background-color: #198754;
  color: white;
}

.badge-Abierta {
  background-color: #34a36f;
  color: white;
}

.badge-alta {
  background-color: #dc3545;
  color: white;
}

.badge-media {
  background-color: #ffc107;
  color: black;
}

.badge-baja {
  background-color: #28a745;
  color: white;
}

.badge-maquina-parada {
  background-color: #dc3545;
  color: white;
}

.badge-aviso {
  background-color: #ffc107;
  color: black;
}

.badge-maquina-en-marcha {
  background-color: #17a2b8;
  color: white;
}

.badge-mantenimiento {
  background-color: #007bff;
  color: white;
}
</style>