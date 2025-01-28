<template>
  <div class="modal-overlay">
    <div class="modal-container bg-primary">
      <div class="modal-header border-b mb-4 pb-2">
        <h5 class="text-xl font-bold">Nueva Máquina</h5>
      </div>
      <div class="modal-body">
        <form @submit.prevent="submitNewMaquina">
          <div class="mb-3">
            <label for="newNombre" class="form-label block text-sm font-medium text-gray-700">Nombre</label>
            <input type="text" id="newNombre" v-model="newMaquina.nombre"
              class="form-control mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
          </div>
          <div class="mb-3">
            <label for="newDescripcion" class="form-label block text-sm font-medium text-gray-700">Descripción</label>
            <textarea id="newDescripcion" v-model="newMaquina.descripcion"
              class="form-control mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
          </div>
          <div class="mb-3">
            <label for="newTaller" class="form-label block text-sm font-medium text-gray-700">Taller</label>
            <select id="newTaller" v-model="newMaquina.id_taller"
              class="form-select mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
               <option v-for="taller in filteredTalleres" :key="taller.id_taller" :value="taller.id_taller">
                {{ taller.nombre }}
              </option>
            </select>
          </div>
          <div class="mb-3">
            <label for="newPrioridad" class="form-label block text-sm font-medium text-gray-700">Prioridad</label>
            <select id="newPrioridad" v-model="newMaquina.prioridad"
              class="form-select mt-1 block w-full border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
              <option value="Alta">Alta</option>
              <option value="Media">Media</option>
              <option value="Baja">Baja</option>
            </select>
          </div>

          <div v-if="alertMessage" class="mt-4 text-center" :class="alertClass">
            {{ alertMessage }}
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" @click="closeModal"
          class="btn btn-secondary">Cancelar</button>
        <button type="submit" @click="submitNewMaquina"
          class="btn btn-primary">Guardar</button>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import useMaquinas from '@/composables/useMaquinas';

const { createMaquina } = useMaquinas();
const props = defineProps({
    talleres: {
      type: Array,
        required: true
    }
})
const emit = defineEmits(['cerrar-modal', 'maquina-creada']);

const newMaquina = ref({
  nombre: '',
  descripcion: '',
  id_taller: '',
  prioridad: 'Baja',
  estado: 'Habilitado'
});
const alertMessage = ref(null);
const alertClass = ref(null);

const closeModal = () => {
  resetForm();
    alertMessage.value = null;
  emit('cerrar-modal');
};
const resetForm = () => {
  newMaquina.value = {
    nombre: '',
    descripcion: '',
    id_taller: '',
    prioridad: 'Baja',
    estado: 'Habilitado'
  };
};
const submitNewMaquina = async () => {
  try {
    await createMaquina(newMaquina.value);
    alertClass.value = 'bg-green-200 text-green-800 border-2 border-green-800 p-2 rounded';
    alertMessage.value = 'Maquina creada correctamente';
    setTimeout(() => {
      closeModal();
      emit('maquina-creada');
    }, 1000);
  } catch (error) {
    console.error('Error creating machine', error);
    console.error('Response Data (newMachine):', error.response?.data);
    alertClass.value = 'bg-red-200 text-red-800 border-2 border-red-800 p-2 rounded';
      alertMessage.value = error.message;
      setTimeout(() => {
        alertMessage.value = null;
      }, 1000);
  }
};

const filteredTalleres = computed(() => {
      return props.talleres ? props.talleres.filter(taller => taller.estado === 'Habilitado') : [];
});
</script>
<style scoped>
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
  /* Ensure modal is on top of other content */
}

.modal-container {
  background-color: white;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
  max-width: 600px;
  padding: 1rem;
  position: relative;
  width: 30%;
}
</style>