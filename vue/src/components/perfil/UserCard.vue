<template>
    <div class="card mb-4 bg-primary">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h2 class="h1 mb-0 ">{{ user.nombre }} {{ user.apellido }}</h2>
                     <div class="mt-2">
                        <span class="badge bg-secondary me-2">{{ user.rol }}</span>
                        <span class="badge bg-secondary">{{ user.specialization }}</span>
                     </div>
                </div>
                <span class="badge" :class="user.estado === 'Habilitado' ? 'bg-black text-primary' : 'bg-secondary text-black'">
                    {{ user.estado }}
                    </span>
            </div>

            <div class="row g-3">
              <div class="col-md-6">
                  <h6 class="text-muted ">Correo</h6>
                  <p>{{ user.correo }}</p>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted">Campus</h6>
                    <p>{{ user.campus.nombre }}</p>
                </div>
                <div class="col-12 d-flex justify-content-start">
                    <button class="btn btn-outline-info btn-sm"
                      @click="$emit('open-edit-modal')">
                     <i class="bi bi-pencil me-2"></i>Editar
                   </button>
                 </div>
           </div>
        </div>
   </div>
</template>

<script setup>
const props = defineProps({
    user: {
    type: Object,
    required: true,
    },
});
 const emit = defineEmits(['open-edit-modal']);
</script>

<style scoped>

</style>