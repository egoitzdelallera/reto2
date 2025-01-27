<template>
    <div class="container bg-primary">
       <div class="table-responsive">
         <table class="table table-hover">
           <thead class="table -light">
             <tr>
               <th class="bg-secondary text-info">TÍTULO</th>
               <th class="bg-secondary text-info">ESTADO</th>
               <th class="bg-secondary text-info">PRIORIDAD</th>
               <th class="bg-secondary text-info">FECHA</th>
               <th class="bg-secondary text-info">MÁQUINA</th>
               <th class="bg-secondary text-info">TALLER</th>
               <th class="bg-secondary text-info">CREADOR</th>
               <th class="bg-secondary text-info">TÉCNICO</th>
               <th class="bg-secondary text-info">GRAVEDAD</th>
             </tr>
           </thead>
           <tbody  class="table -light">
             <tr
               v-for="(incidencia, i) in paginatedIncidencias"
               :key="i"
               @click="goToIncidencia(incidencia.id_incidencia)"
               style="cursor: pointer;"
             >
               <td class="font-medium bg-primary">{{ incidencia.descripcion }}</td>
               <td class="font-medium bg-primary">   
                   {{ incidencia.estado }}
               </td>
               <td class="font-medium bg-primary">
                 <span :class="['badge', getPrioridadClass(incidencia.maquina.prioridad)]">
                   {{ incidencia.maquina.prioridad }}
                 </span>
               </td>
               <td class="font-medium bg-primary">{{ formatDate(incidencia.fecha_reporte) }}</td>
               <td class="font-medium bg-primary">{{ incidencia.maquina.nombre }}</td>
               <td class="font-medium bg-primary">
                 {{ incidencia.maquina?.taller?.nombre || 'Sin taller' }}
               </td>
               <td class="font-medium bg-primary">{{ incidencia.creador?.nombre || 'Sin creador' }}</td>
               <td class="font-medium bg-primary">{{ incidencia.tecnico ? incidencia.tecnico.nombre : 'Sin asignar' }}</td>
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
             <button class="page-link text-black" @click="previousPage" aria-label="Previous">
               <span aria-hidden="true">«</span>
             </button>
           </li>
           <li
             v-for="page in totalPages"
             :key="page"
             class="page-item"
             :class="{ active: page === currentPage }"
           >
             <button class="page-link text-black" @click="goToPage(page)">{{ page }}</button>
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
import { computed } from 'vue';
import { useRouter } from 'vue-router';


const props = defineProps({
   incidencias: Array,
   currentPage: Number,
   itemsPerPage: Number,
   searchQuery: String,
 });
const emit = defineEmits(['page-change']);
const router = useRouter();

const getPrioridadClass = (prioridad) => {
   console.log('Prioridad:', prioridad);
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
     return 'badge bg-secondary text-info border border-info'; // Gris
   default:
     return 'badge bg-secondary text-white'; // Gris
 }
};


const paginatedIncidencias = computed(() => {
   const start = (props.currentPage - 1) * props.itemsPerPage;
   const end = start + props.itemsPerPage;
   return props.incidencias.slice(start, end);
});

const totalPages = computed(() => {
   return Math.ceil(props.incidencias.length / props.itemsPerPage);
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
       emit('page-change', props.currentPage - 1)
 }
};
const nextPage = () => {
  if (props.currentPage < totalPages.value) emit('page-change', props.currentPage + 1)
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