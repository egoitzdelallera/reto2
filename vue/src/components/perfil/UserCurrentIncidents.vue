<template>
    <div class="card bg-primary">
       <div class="card-body">
           <h3 class="card-title mb-4">Incidencias en curso</h3>
           <div v-for="(incident, index) in incidents" :key="index" class="mb-4">
             <div class="d-flex justify-content-between align-items-center mb-2">
               <h5 class="mb-0">{{incident.nombre}}</h5>
            </div>
            <div class="d-flex justify-content-between">
                <p class="text-muted small mb-2">Máquina: {{incident.maquina_nombre}}</p>
                   <span :class="['badge', getPriorityBadgeClass(incident.prioridad)]">
                      {{ incident.prioridad }}
                    </span>
             </div>
         </div>
       </div>
    </div>
</template>

<script setup>
const props = defineProps({
 incidents: {
     type: Array,
     required: true,
 },
});

const getPriorityBadgeClass = (priority) => {
     switch(priority){
       case 'Alta':
          return 'bg-danger';
       case 'Media':
          return 'bg-warning text-dark';
       case 'Baja':
          return 'bg-info';
       default:
         return 'bg-secondary';
     }
 }
</script>

<style scoped>

</style>